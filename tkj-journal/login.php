<?php
require 'config.php';
if (!empty($_SESSION['guru'])) { header('Location: admin/index.php'); exit; }
if (!empty($_SESSION['nisn'])) { header('Location: index.php'); exit; }
function batas($db, $ip, $id){   // pembatas percobaan gagal per IP (10 menit)
  $q = $db->prepare('SELECT COUNT(*) FROM login_gagal WHERE ip=? AND ident=? AND waktu>DATE_SUB(NOW(),INTERVAL 10 MINUTE)'); $q->execute([$ip, $id]); $a = (int)$q->fetchColumn();
  $q = $db->prepare('SELECT COUNT(*) FROM login_gagal WHERE ip=? AND waktu>DATE_SUB(NOW(),INTERVAL 10 MINUTE)'); $q->execute([$ip]);
  return $a >= 5 || (int)$q->fetchColumn() >= 40;
}
$err = ''; $id = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ip = $_SERVER['REMOTE_ADDR'] ?? '?'; $id = trim($_POST['id'] ?? ''); $pw = $_POST['password'] ?? ''; $gagal = false;
  if ($id === '') {
    $err = 'Isi NISN (siswa) atau username (guru).';
  } elseif (batas($db, $ip, strtolower($id))) {
    $err = 'Terlalu banyak percobaan gagal. Coba lagi dalam 10 menit.';
  } elseif (ctype_digit($id)) {                      // angka = siswa: NISN + PIN
    if (strlen($id) !== 10) { $err = 'NISN harus 10 digit angka.'; }
    else {
      $q = $db->prepare('SELECT nisn,nama,pin_hash FROM siswa WHERE nisn=?'); $q->execute([$id]); $s = $q->fetch();
      if (PIN_SISWA && $s && $s['pin_hash'] === null) { $err = 'PIN kamu belum dibuat. Minta PIN ke guru pembimbing.'; }
      elseif ($s && (!PIN_SISWA || password_verify($pw, $s['pin_hash']))) {
        session_regenerate_id(true); $_SESSION = ['nisn' => $s['nisn'], 'nama' => $s['nama'], 'skema' => 1];
        header('Location: index.php'); exit;
      } else { $err = PIN_SISWA ? 'NISN atau PIN salah.' : 'NISN tidak terdaftar. Periksa lagi atau hubungi guru pembimbing.'; $gagal = true; }
    }
  } else {                                           // ada huruf = guru: username + password
    $q = $db->prepare('SELECT * FROM guru WHERE username=?'); $q->execute([$id]); $g = $q->fetch();
    if ($g && password_verify($pw, $g['password_hash'])) {
      session_regenerate_id(true);
      $_SESSION = ['guru' => $g['id'], 'guru_nama' => $g['nama'], 'skema' => 1, 'pw_default' => $pw === 'tkj123'];
      header('Location: admin/index.php'); exit;
    }
    $err = 'Username atau password salah.'; $gagal = true;
  }
  if ($gagal) {
    $db->prepare('INSERT INTO login_gagal (ip,ident,waktu) VALUES (?,?,NOW())')->execute([$ip, strtolower($id)]);
    if (random_int(1, 20) === 1) $db->exec('DELETE FROM login_gagal WHERE waktu<DATE_SUB(NOW(),INTERVAL 1 DAY)');
  }
}
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>Masuk – TKJ Journal</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css"></head>
<body><section class="lg"><form class="lc" method="post" autocomplete="off">
  <div class="lb">TJ</div><h2>Masuk ke TKJ Journal</h2>
  <p><?= PIN_SISWA ? 'Siswa: NISN dan PIN dari guru. Guru: username dan password.' : 'Siswa: cukup masukkan NISN. Guru: username dan password.' ?></p>
  <label for="n">NISN atau username</label>
  <input id="n" name="id" value="<?= h($id) ?>" maxlength="50" required autofocus placeholder="NISN atau username" style="letter-spacing:0;font-size:16px">
  <div id="pw" hidden><label for="p" style="margin-top:12px"><?= PIN_SISWA ? 'PIN (siswa) atau password (guru)' : 'Password (guru)' ?></label>
  <input id="p" name="password" type="password" style="letter-spacing:0;font-size:16px"></div>
  <div id="err" role="alert"><?= h($err) ?></div><button class="btn">Masuk</button>
  <small>Tidak bisa masuk? Hubungi guru pembimbing.</small>
</form></section>
<script>
const i=document.getElementById('n'),w=document.getElementById('pw'),p=w.querySelector('input'),pinSiswa=<?= PIN_SISWA ? 'true' : 'false' ?>;
function t(){const g=pinSiswa||/\D/.test(i.value.trim());w.hidden=!g;p.required=g}
i.addEventListener('input',t);t();
</script></body></html>
