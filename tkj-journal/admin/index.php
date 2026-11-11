<?php
require 'inc.php'; need_guru();
$j = $db->query("SELECT COUNT(*) t, COALESCE(SUM(status='menunggu'),0) m, COALESCE(SUM(status='diverifikasi'),0) v FROM jurnal")->fetch();
$ns = (int)$db->query('SELECT COUNT(*) FROM siswa')->fetchColumn();
$belum = $db->query('SELECT nisn,nama FROM siswa WHERE nisn NOT IN (SELECT nisn FROM jurnal WHERE tanggal=CURDATE()) ORDER BY nama')->fetchAll();
$cards = [['👥','Total Siswa',$ns,'siswa.php'],['📓','Total Jurnal',$j['t'],'verifikasi.php?status=semua'],['⏳','Menunggu Verifikasi',$j['m'],'verifikasi.php'],['✅','Sudah Diverifikasi',$j['v'],'verifikasi.php?status=diverifikasi']];
abuka('index', 'Ringkasan'); ?>
<div class="ph"><h2>Ringkasan</h2><a class="btn alt" href="export.php">Unduh rekap (CSV)</a></div>
<section class="stats"><?php foreach ($cards as $c): ?>
  <a class="card stat" href="<?= $c[3] ?>"><div class="ic" style="background:#0f2a66;font-size:22px"><?= $c[0] ?></div><div><small><?= $c[1] ?></small><div class="n"><?= (int)$c[2] ?></div></div></a>
<?php endforeach; ?></section>
<div class="card"><div class="h"><h3>Belum mengisi jurnal hari ini (<?= count($belum) ?>)</h3><a href="siswa.php">Lihat semua siswa</a></div>
  <?php if ($belum): ?><div class="chips"><?php foreach ($belum as $b): ?><a href="verifikasi.php?status=semua&nisn=<?= h($b['nisn']) ?>"><?= h($b['nama']) ?></a><?php endforeach; ?></div>
  <?php else: ?><div class="empty">Semua siswa sudah mengisi jurnal hari ini.</div><?php endif; ?></div>
<?php atutup();
