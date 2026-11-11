<?php
require 'inc.php'; need_guru();
$rows = $db->query("SELECT s.nisn,s.nama,COUNT(j.id) t,COALESCE(SUM(j.status='diverifikasi'),0) v,COALESCE(SUM(j.status='menunggu'),0) m,MAX(j.tanggal) z
  FROM siswa s LEFT JOIN jurnal j ON j.nisn=s.nisn GROUP BY s.nisn,s.nama ORDER BY s.nama")->fetchAll();
abuka('siswa', 'Data Siswa'); ?>
<div class="ph"><h2>Data Siswa (<?= count($rows) ?>)</h2><a class="btn alt" href="export.php">Unduh rekap (CSV)</a></div>
<div class="card"><div class="tw"><table><thead><tr><th>Nama</th><th>NISN</th><th>Total</th><th>Diverifikasi</th><th>Menunggu</th><th>Terakhir mengisi</th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><a href="verifikasi.php?status=semua&nisn=<?= h($r['nisn']) ?>"><b><?= h($r['nama']) ?></b></a></td><td><?= h($r['nisn']) ?></td><td><?= (int)$r['t'] ?></td><td><?= (int)$r['v'] ?></td><td><?= (int)$r['m'] ?></td><td><?= $r['z'] ? h(tgl($r['z'])) : '-' ?></td></tr>
<?php endforeach; ?></tbody></table></div></div>
<?php atutup();
