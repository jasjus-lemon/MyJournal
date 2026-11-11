<?php
require 'inc.php'; need_guru();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals(csrf(), $_POST['csrf'] ?? '')) { http_response_code(400); exit('Permintaan tidak valid.'); }
  if (isset($_POST['hapus'])) { $db->prepare('DELETE FROM pengumuman WHERE id=?')->execute([(int)$_POST['hapus']]); $_SESSION['flash'] = ['ok', 'Pengumuman dihapus.']; }
  else { $i = trim($_POST['isi'] ?? ''); if ($i !== '') { $db->prepare('INSERT INTO pengumuman (isi,tanggal) VALUES (?,CURDATE())')->execute([$i]); $_SESSION['flash'] = ['ok', 'Pengumuman ditambahkan.']; } }
  header('Location: pengumuman.php'); exit;
}
$rows = $db->query('SELECT * FROM pengumuman ORDER BY id DESC LIMIT 50')->fetchAll();
abuka('pengumuman', 'Pengumuman'); ?>
<div class="ph"><h2>Pengumuman</h2></div>
<div class="card" style="margin-bottom:16px"><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>">
  <label for="i" style="font-weight:700">Pengumuman baru untuk semua siswa</label>
  <textarea id="i" name="isi" rows="3" required style="width:100%;margin:8px 0;padding:11px;border:1px solid var(--line);border-radius:10px;background:var(--bg);color:var(--ink);font:inherit"></textarea>
  <button class="btn">Kirim pengumuman</button></form></div>
<div class="card"><?php foreach ($rows as $r): ?><div class="ann"><?= h($r['isi']) ?><small><?= h(tgl($r['tanggal'])) ?></small>
  <form class="i" method="post" onsubmit="return confirm('Hapus pengumuman ini?')"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>"><button class="btn alt sm" name="hapus" value="<?= (int)$r['id'] ?>" style="margin-top:8px">Hapus</button></form></div>
<?php endforeach; if (!$rows): ?><div class="empty">Belum ada pengumuman.</div><?php endif; ?></div>
<?php atutup();
