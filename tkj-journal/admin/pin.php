<?php
require 'inc.php'; need_guru();
$baru = []; $pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals(csrf(), $_POST['csrf'] ?? '')) { http_response_code(400); exit('Permintaan tidak valid.'); }
  if (($_POST['aksi'] ?? '') === 'buat') $sis = $db->query('SELECT nisn,nama FROM siswa WHERE pin_hash IS NULL ORDER BY nama')->fetchAll();
  else { $q = $db->prepare('SELECT nisn,nama FROM siswa WHERE nisn=?'); $q->execute([$_POST['nisn'] ?? '']); $sis = $q->fetchAll(); }
  $up = $db->prepare('UPDATE siswa SET pin_hash=? WHERE nisn=?');
  foreach ($sis as $s) { $pin = sprintf('%06d', random_int(0, 999999)); $up->execute([password_hash($pin, PASSWORD_DEFAULT), $s['nisn']]); $baru[] = [$s['nama'], $s['nisn'], $pin]; }
  $pesan = $baru ? count($baru).' PIN dibuat. PIN hanya ditampilkan sekali di bawah ini, cetak atau catat sekarang.' : 'Semua siswa sudah punya PIN.';
}
$rows = $db->query('SELECT nisn,nama,pin_hash IS NOT NULL ada FROM siswa ORDER BY nama')->fetchAll();
abuka('pin', 'PIN Siswa'); ?>
<div class="ph"><h2>PIN Siswa</h2>
  <form method="post"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>"><button class="btn noprint" name="aksi" value="buat">Buat PIN untuk yang belum punya</button></form></div>
<?php if ($pesan): ?><div class="flash <?= $baru ? 'ok' : 'err' ?>"><?= h($pesan) ?></div><?php endif; ?>
<?php if ($baru): ?><div class="card" style="margin-bottom:16px"><div class="h"><h3>PIN baru</h3><a href="#" class="noprint" onclick="window.print();return false">Cetak</a></div>
  <div class="tw"><table><thead><tr><th>Nama</th><th>NISN</th><th>PIN</th></tr></thead><tbody>
  <?php foreach ($baru as $b): ?><tr><td><?= h($b[0]) ?></td><td><?= h($b[1]) ?></td><td class="pin"><?= h($b[2]) ?></td></tr><?php endforeach; ?></tbody></table></div></div><?php endif; ?>
<div class="card noprint"><div class="tw"><table><thead><tr><th>Nama</th><th>NISN</th><th>Status PIN</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= h($r['nama']) ?></td><td><?= h($r['nisn']) ?></td>
  <td><span class="pill <?= $r['ada'] ? 'ok' : 'wait' ?>"><?= $r['ada'] ? '✔ Sudah ada' : 'Belum dibuat' ?></span></td>
  <td><form method="post" onsubmit="return confirm('Buat PIN baru untuk <?= h($r['nama']) ?>? PIN lama tidak berlaku lagi.')"><input type="hidden" name="csrf" value="<?= h(csrf()) ?>"><input type="hidden" name="nisn" value="<?= h($r['nisn']) ?>"><button class="btn alt sm" name="aksi" value="reset"><?= $r['ada'] ? 'Reset PIN' : 'Buat PIN' ?></button></form></td></tr>
<?php endforeach; ?></tbody></table></div></div>
<?php atutup();
