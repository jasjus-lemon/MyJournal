<?php
require 'inc.php'; need_guru();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals(csrf(), $_POST['csrf'] ?? '')) { http_response_code(400); exit('Permintaan tidak valid.'); }
  $a = $_POST['aksi'] ?? '';
  if ($a === 'verif' || $a === 'batal') {
    $db->prepare('UPDATE jurnal SET status=? WHERE id=?')->execute([$a === 'verif' ? 'diverifikasi' : 'menunggu', (int)($_POST['id'] ?? 0)]);
    $_SESSION['flash'] = ['ok', $a === 'verif' ? 'Jurnal diverifikasi.' : 'Verifikasi dibatalkan.'];
  } elseif ($a === 'semua') {
    $ids = array_values(array_filter(array_map('intval', $_POST['ids'] ?? [])));
    if ($ids) {
      $in = implode(',', array_fill(0, count($ids), '?'));
      $st = $db->prepare("UPDATE jurnal SET status='diverifikasi' WHERE status='menunggu' AND id IN ($in)"); $st->execute($ids);
      $_SESSION['flash'] = ['ok', $st->rowCount().' jurnal diverifikasi.'];
    }
  }
  $b = preg_replace('/[^A-Za-z0-9=&%+_.-]/', '', $_POST['balik'] ?? '');
  header('Location: verifikasi.php'.($b !== '' ? '?'.$b : '')); exit;
}
$status = $_GET['status'] ?? 'menunggu'; if (!in_array($status, ['menunggu','diverifikasi','semua'], true)) $status = 'menunggu';
$nisn = preg_replace('/\D/', '', $_GET['nisn'] ?? ''); $q = trim($_GET['q'] ?? '');
$sql = 'SELECT j.*,s.nama FROM jurnal j JOIN siswa s ON s.nisn=j.nisn WHERE 1=1'; $par = [];
if ($status !== 'semua') { $sql .= ' AND j.status=?'; $par[] = $status; }
if ($nisn !== '') { $sql .= ' AND j.nisn=?'; $par[] = $nisn; }
if ($q !== '') { $sql .= ' AND (j.judul LIKE ? OR j.uraian LIKE ?)'; $par[] = "%$q%"; $par[] = "%$q%"; }
$st = $db->prepare($sql.' ORDER BY j.tanggal DESC, s.nama LIMIT 300'); $st->execute($par); $rows = $st->fetchAll();
$sis = $db->query('SELECT nisn,nama FROM siswa ORDER BY nama')->fetchAll();
$balik = $_SERVER['QUERY_STRING'] ?? '';
$u = fn($s) => 'verifikasi.php?'.http_build_query(array_filter(['status'=>$s,'nisn'=>$nisn,'q'=>$q], fn($v) => $v !== ''));
$pend = array_column(array_filter($rows, fn($r) => $r['status'] === 'menunggu'), 'id');
abuka('verifikasi', 'Verifikasi Jurnal'); ?>
<div class="ph"><h2>Verifikasi Jurnal</h2>
<?php if ($pend && $status !== 'diverifikasi'): ?><form method="post" onsubmit="return confirm('Verifikasi <?= count($pend) ?> jurnal yang tampil?')">
  <input type="hidden" name="csrf" value="<?= h(csrf()) ?>"><input type="hidden" name="aksi" value="semua"><input type="hidden" name="balik" value="<?= h($balik) ?>">
  <?php foreach ($pend as $id): ?><input type="hidden" name="ids[]" value="<?= (int)$id ?>"><?php endforeach; ?>
  <button class="btn">Verifikasi semua yang tampil (<?= count($pend) ?>)</button></form><?php endif; ?></div>
<div class="tabs"><?php foreach (['menunggu'=>'Menunggu','diverifikasi'=>'Diverifikasi','semua'=>'Semua'] as $k => $t): ?><a href="<?= h($u($k)) ?>"<?= $k === $status ? ' class="on"' : '' ?>><?= $t ?></a><?php endforeach; ?></div>
<form class="fl" method="get"><input type="hidden" name="status" value="<?= h($status) ?>">
  <select name="nisn" onchange="this.form.submit()" aria-label="Siswa"><option value="">Semua siswa</option>
  <?php foreach ($sis as $s): ?><option value="<?= h($s['nisn']) ?>"<?= $s['nisn'] === $nisn ? ' selected' : '' ?>><?= h($s['nama']) ?></option><?php endforeach; ?></select>
  <input name="q" type="search" value="<?= h($q) ?>" placeholder="Cari kegiatan…" aria-label="Cari"><button class="btn sm">Cari</button></form>
<div class="card"><div class="tw"><table><thead><tr><th>Tanggal</th><th>Siswa</th><th>Kegiatan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($rows as $r): $ok = $r['status'] === 'diverifikasi'; ?>
  <tr><td><?= h(tgl($r['tanggal'])) ?></td><td><?= h($r['nama']) ?></td>
    <td><b><?= h($r['judul']) ?></b><?php if ($r['uraian']): ?><details><summary>Lihat uraian</summary><p><?= h($r['uraian']) ?></p></details><?php endif; ?></td>
    <td><span class="pill <?= $ok ? 'ok' : 'wait' ?>"><?= $ok ? '✔ Diverifikasi' : '⏳ Menunggu' ?></span></td>
    <td class="ak"><form class="i" method="post"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><input type="hidden" name="balik" value="<?= h($balik) ?>">
      <?php if ($ok): ?><button class="btn alt sm" name="aksi" value="batal">Batalkan</button><?php else: ?><button class="btn sm" name="aksi" value="verif">✔ Verifikasi</button><?php endif; ?></form></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php if (!$rows): ?><div class="empty">Tidak ada jurnal yang cocok.</div><?php endif; ?></div>
<?php atutup();
