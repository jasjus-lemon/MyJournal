<?php
require 'config.php'; need_login();
$st = $db->prepare('SELECT judul,tanggal,status,created_at FROM jurnal WHERE nisn=? ORDER BY created_at DESC LIMIT 100'); $st->execute([$_SESSION['nisn']]); $rows = $st->fetchAll();
buka('riwayat', 'Riwayat'); ?>
<div class="ph"><h2>Riwayat</h2><button class="btn open">Tambah jurnal</button></div>
<div class="card"><ul class="tl">
<?php foreach ($rows as $r): ?><li>Anda mengirim jurnal: <?= h($r['judul']) ?> (kegiatan <?= h(tgl($r['tanggal'])) ?>)<small><?= h(tgl($r['created_at'])) ?>, <?= h(substr($r['created_at'], 11, 5)) ?> · <?= $r['status'] === 'diverifikasi' ? 'Sudah diverifikasi' : 'Menunggu review' ?></small></li>
<?php endforeach; if (!$rows): ?><li>Belum ada riwayat<small>Kirim jurnal pertamamu untuk memulai</small></li><?php endif; ?>
</ul></div>
<?php tutup();
