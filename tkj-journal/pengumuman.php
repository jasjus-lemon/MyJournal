<?php
require 'config.php'; need_login();
$rows = $db->query('SELECT isi,tanggal FROM pengumuman ORDER BY id DESC LIMIT 50')->fetchAll();
buka('dashboard', 'Pengumuman'); ?>
<div class="ph"><h2>Pengumuman</h2></div>
<div class="card"><?php foreach ($rows as $r): ?><div class="ann"><?= h($r['isi']) ?><small><?= h(tgl($r['tanggal'])) ?></small></div><?php endforeach; ?>
<?php if (!$rows): ?><div class="empty">Belum ada pengumuman.</div><?php endif; ?></div>
<?php tutup();
