<?php
require 'config.php'; need_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals(csrf(), $_POST['csrf'] ?? '')) { http_response_code(400); exit('Permintaan tidak valid.'); }
$back = in_array($_POST['kembali'] ?? '', ['index.php','jurnal.php','riwayat.php'], true) ? $_POST['kembali'] : 'index.php';
$id = (int)($_POST['jid'] ?? 0);
$t = $_POST['tanggal'] ?? ''; $j = trim($_POST['judul'] ?? ''); $u = trim($_POST['uraian'] ?? '');
$d = DateTime::createFromFormat('Y-m-d', $t);
if (!$d || $d->format('Y-m-d') !== $t || $j === '' || mb_strlen($j) > 150) {
  $_SESSION['flash'] = ['err', 'Tanggal atau judul kegiatan belum benar.'];
} elseif ($t > date('Y-m-d')) {
  $_SESSION['flash'] = ['err', 'Tanggal tidak boleh di masa depan.'];
} else {
  try {
    if ($id) {
      $db->prepare("UPDATE jurnal SET tanggal=?,judul=?,uraian=? WHERE id=? AND nisn=? AND status='menunggu'")->execute([$t,$j,$u,$id,$_SESSION['nisn']]);
      $_SESSION['flash'] = ['ok', 'Jurnal diperbarui.'];
    } else {
      $db->prepare('INSERT INTO jurnal (nisn,tanggal,judul,uraian) VALUES (?,?,?,?)')->execute([$_SESSION['nisn'],$t,$j,$u]);
      $_SESSION['flash'] = ['ok', 'Jurnal terkirim.'];
    }
  } catch (PDOException $e) {
    if ($e->getCode() === '23000') $_SESSION['flash'] = ['err', 'Jurnal untuk tanggal itu sudah ada.']; else throw $e;
  }
}
header('Location: '.$back);
