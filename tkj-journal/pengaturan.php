<?php
require 'config.php'; need_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals(csrf(), $_POST['csrf'] ?? '')) { http_response_code(400); exit('Permintaan tidak valid.'); }
  $st = $db->prepare('SELECT pin_hash FROM siswa WHERE nisn=?'); $st->execute([$_SESSION['nisn']]); $hs = $st->fetchColumn();
  $lama = $_POST['lama'] ?? ''; $baru = $_POST['baru'] ?? '';
  if (!$hs || !password_verify($lama, $hs)) $_SESSION['flash'] = ['err', 'PIN lama salah.'];
  elseif (!preg_match('/^\d{6}$/', $baru) || $baru === $lama) $_SESSION['flash'] = ['err', 'PIN baru harus 6 digit angka dan berbeda dari PIN lama.'];
  else { $db->prepare('UPDATE siswa SET pin_hash=? WHERE nisn=?')->execute([password_hash($baru, PASSWORD_DEFAULT), $_SESSION['nisn']]); $_SESSION['flash'] = ['ok', 'PIN berhasil diganti.']; }
  header('Location: pengaturan.php'); exit;
}
buka('pengaturan', 'Pengaturan'); ?>
<div class="ph"><h2>Pengaturan</h2></div>
<div class="card"><h3 style="margin:0">Tampilan</h3>
  <div class="opt"><button class="btn alt" data-tema="auto">Ikuti perangkat</button><button class="btn alt" data-tema="light">Terang</button><button class="btn alt" data-tema="dark">Gelap</button></div>
<?php if (PIN_SISWA): ?>
  <h3 style="margin:0">Ganti PIN</h3>
  <form method="post" class="opt" style="align-items:flex-end"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>">
    <div><label for="l" style="display:block;font-weight:700">PIN lama</label><input id="l" name="lama" type="password" inputmode="numeric" maxlength="6" required style="padding:10px;border:1px solid var(--line);border-radius:10px;background:var(--bg);color:var(--ink)"></div>
    <div><label for="b" style="display:block;font-weight:700">PIN baru (6 angka)</label><input id="b" name="baru" type="password" inputmode="numeric" maxlength="6" pattern="\d{6}" required style="padding:10px;border:1px solid var(--line);border-radius:10px;background:var(--bg);color:var(--ink)"></div>
    <button class="btn">Simpan PIN</button></form>
<?php endif; ?>
  <h3 style="margin:0">Data jurnal</h3>
  <div class="opt"><a class="btn alt" href="export.php">Unduh jurnal (CSV)</a></div>
  <h3 style="margin:0">Akun</h3>
  <div class="opt"><a class="btn" href="logout.php">Keluar</a></div></div>
<?php tutup();
