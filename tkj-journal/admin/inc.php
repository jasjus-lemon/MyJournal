<?php
require __DIR__.'/../config.php';
function need_guru(){ if (empty($_SESSION['guru'])) { header('Location: ../login.php'); exit; } }
function abuka($aktif, $judul){
  $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
  $m = ['index'=>['Ringkasan','index.php'],'verifikasi'=>['Verifikasi Jurnal','verifikasi.php'],'siswa'=>['Data Siswa','siswa.php'],'pengumuman'=>['Pengumuman','pengumuman.php'],'pin'=>['PIN Siswa','pin.php'],'password'=>['Ganti Password','password.php']];
  if (!PIN_SISWA) unset($m['pin']);
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= h($judul) ?> – Guru TKJ Journal</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/style.css"></head>
<body><div class="app"><aside>
  <a class="logo" href="index.php" style="text-decoration:none;color:#fff"><b>TJ</b><div><h1>Panel Guru</h1><small>TKJ Journal</small></div></a>
  <nav><?php foreach ($m as $k => [$t, $u]): ?><a href="<?= $u ?>"<?= $aktif === $k ? ' class="on"' : '' ?>><?= $t ?></a><?php endforeach; ?><a href="logout.php">Keluar</a></nav>
  <div class="tag">Masuk sebagai<br><?= h($_SESSION['guru_nama']) ?><i></i></div>
</aside><main>
<?php if (!empty($_SESSION['pw_default'])): ?><div class="flash err">Password masih bawaan (tkj123). <a href="password.php">Ganti sekarang</a> sebelum situs dibuka ke internet.</div><?php endif; ?>
<?php if ($f): ?><div class="flash <?= h($f[0]) ?>" role="status"><?= h($f[1]) ?></div><?php endif;
}
function atutup(){ echo '</main></div></body></html>'; }
