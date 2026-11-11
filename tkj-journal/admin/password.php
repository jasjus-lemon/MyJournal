<?php
require 'inc.php'; need_guru();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals(csrf(), $_POST['csrf'] ?? '')) { http_response_code(400); exit('Permintaan tidak valid.'); }
  $st = $db->prepare('SELECT password_hash FROM guru WHERE id=?'); $st->execute([$_SESSION['guru']]); $hs = $st->fetchColumn();
  $l = $_POST['lama'] ?? ''; $b = $_POST['baru'] ?? ''; $k = $_POST['konfirmasi'] ?? '';
  if (!$hs || !password_verify($l, $hs)) $_SESSION['flash'] = ['err', 'Password lama salah.'];
  elseif (strlen($b) < 10 || $b === 'tkj123') $_SESSION['flash'] = ['err', 'Password baru minimal 10 karakter dan tidak boleh tkj123.'];
  elseif ($b !== $k) $_SESSION['flash'] = ['err', 'Konfirmasi password tidak sama.'];
  else { $db->prepare('UPDATE guru SET password_hash=? WHERE id=?')->execute([password_hash($b, PASSWORD_DEFAULT), $_SESSION['guru']]); unset($_SESSION['pw_default']); $_SESSION['flash'] = ['ok', 'Password berhasil diganti.']; }
  header('Location: password.php'); exit;
}
$in = 'style="width:100%;padding:11px;border:1px solid var(--line);border-radius:10px;background:var(--bg);color:var(--ink);font:inherit;margin:6px 0 12px"';
abuka('password', 'Ganti Password'); ?>
<div class="ph"><h2>Ganti Password</h2></div>
<div class="card" style="max-width:460px"><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>">
  <label for="a"><b>Password lama</b></label><input id="a" name="lama" type="password" required <?= $in ?>>
  <label for="b"><b>Password baru</b> (min. 10 karakter)</label><input id="b" name="baru" type="password" minlength="10" required <?= $in ?>>
  <label for="c"><b>Ulangi password baru</b></label><input id="c" name="konfirmasi" type="password" required <?= $in ?>>
  <button class="btn">Simpan password</button></form></div>
<?php atutup();
