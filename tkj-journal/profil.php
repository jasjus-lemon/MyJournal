<?php
require 'config.php'; need_login();
$st = $db->prepare("SELECT COUNT(*) t, MIN(tanggal) a, MAX(tanggal) z, COALESCE(SUM(status='diverifikasi'),0) v FROM jurnal WHERE nisn=?"); $st->execute([$_SESSION['nisn']]); $s = $st->fetch();
$ini = implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice(explode(' ', $_SESSION['nama']), 0, 2)));
buka('profil', 'Profil'); ?>
<div class="ph"><h2>Profil</h2><a class="btn alt" href="pengaturan.php">Pengaturan</a></div>
<div class="card"><div style="display:flex;gap:18px;align-items:center"><div class="big"><?= h($ini) ?></div>
  <div><h3 style="margin:0;font-size:22px"><?= h($_SESSION['nama']) ?></h3><div style="color:var(--mute)">Siswa PKL · NISN <?= h($_SESSION['nisn']) ?></div></div></div>
  <div class="pf">
    <div><small>Total jurnal</small><b><?= (int)$s['t'] ?></b></div>
    <div><small>Sudah diverifikasi</small><b><?= (int)$s['v'] ?></b></div>
    <div><small>Jurnal pertama</small><b><?= $s['a'] ? h(tgl($s['a'])) : '-' ?></b></div>
    <div><small>Jurnal terakhir</small><b><?= $s['z'] ? h(tgl($s['z'])) : '-' ?></b></div>
  </div></div>
<?php tutup();
