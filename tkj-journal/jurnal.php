<?php
require 'config.php'; need_login();
$nisn = $_SESSION['nisn']; $q = trim($_GET['q'] ?? ''); $status = $_GET['status'] ?? ''; $bulan = isset($_GET['bulan']);
$sql = 'SELECT * FROM jurnal WHERE nisn=:n'; $par = [':n' => $nisn];
if (in_array($status, ['menunggu','diverifikasi'], true)) { $sql .= ' AND status=:s'; $par[':s'] = $status; }
if ($bulan) $sql .= " AND DATE_FORMAT(tanggal,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')";
if ($q !== '') { $sql .= ' AND (judul LIKE :a OR uraian LIKE :b)'; $par[':a'] = $par[':b'] = "%$q%"; }
$rows = $db->prepare($sql.' ORDER BY tanggal DESC'); $rows->execute($par); $rows = $rows->fetchAll();
$tabs = ['Semua'=>'jurnal.php','Bulan ini'=>'jurnal.php?bulan=1','Menunggu'=>'jurnal.php?status=menunggu','Diverifikasi'=>'jurnal.php?status=diverifikasi'];
$now = $bulan ? 'Bulan ini' : ($status === 'menunggu' ? 'Menunggu' : ($status === 'diverifikasi' ? 'Diverifikasi' : 'Semua'));
buka('jurnal', 'Jurnal Saya'); ?>
<div class="ph"><h2>Jurnal Saya</h2><button class="btn open">Tambah jurnal</button></div>
<div class="tabs"><?php foreach ($tabs as $k => $u): ?><a href="<?= $u ?>"<?= $k === $now ? ' class="on"' : '' ?>><?= $k ?></a><?php endforeach; ?></div>
<div class="card"><?php if ($q !== ''): ?><p>Hasil pencarian “<?= h($q) ?>” · <a href="jurnal.php">hapus pencarian</a></p><?php endif; ?>
  <div class="tw"><table><thead><tr><th>Tanggal</th><th>Judul Kegiatan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
  <?php foreach ($rows as $r) baris($r, true); ?></tbody></table></div>
  <?php if (!$rows): ?><div class="empty">Tidak ada jurnal di sini.</div><?php endif; ?>
  <p style="color:var(--mute);font-size:12.5px;margin:12px 0 0">Jurnal yang masih menunggu review bisa diubah atau dihapus. Setelah diverifikasi, jurnal dikunci.</p></div>
<?php tutup();
