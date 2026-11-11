<?php
require 'config.php'; need_login();
$nisn = $_SESSION['nisn'];
$st = $db->prepare("SELECT COUNT(*) t,
  COALESCE(SUM(DATE_FORMAT(tanggal,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')),0) b,
  COALESCE(SUM(status='diverifikasi'),0) v, COALESCE(SUM(status='menunggu'),0) m FROM jurnal WHERE nisn=?");
$st->execute([$nisn]); $s = $st->fetch();
$rows = $db->prepare('SELECT * FROM jurnal WHERE nisn=? ORDER BY tanggal DESC LIMIT 5'); $rows->execute([$nisn]); $rows = $rows->fetchAll();
$act = $db->prepare('SELECT judul,created_at FROM jurnal WHERE nisn=? ORDER BY created_at DESC LIMIT 3'); $act->execute([$nisn]); $act = $act->fetchAll();
$ann = $db->query('SELECT isi,tanggal FROM pengumuman ORDER BY id DESC LIMIT 1')->fetch();

// data 14 hari terakhir untuk grafik mini
$hari = []; for ($i = 13; $i >= 0; $i--) $hari[] = date('Y-m-d', strtotime("-$i day"));
$g = $db->prepare("SELECT tanggal,COUNT(*) c,SUM(status='diverifikasi') v,SUM(status='menunggu') m FROM jurnal WHERE nisn=? AND tanggal>=? GROUP BY tanggal");
$g->execute([$nisn, $hari[0]]); $per = []; foreach ($g as $r) $per[$r['tanggal']] = $r;
$a = ['c' => $s['t'] - array_sum(array_column($per, 'c')), 'v' => $s['v'] - array_sum(array_column($per, 'v')), 'm' => $s['m'] - array_sum(array_column($per, 'm'))];
$sc = $sv = $sm = $sd = [];
foreach ($hari as $d) {
  $r = $per[$d] ?? ['c' => 0, 'v' => 0, 'm' => 0];
  foreach (['c', 'v', 'm'] as $k) $a[$k] += (int)$r[$k];
  $sc[] = $a['c']; $sv[] = $a['v']; $sm[] = $a['m']; $sd[] = (int)$r['c'];
}
$n7 = $v7 = 0; foreach (array_slice($hari, -7) as $d) { $n7 += (int)($per[$d]['c'] ?? 0); $v7 += (int)($per[$d]['v'] ?? 0); }
$hariini = !empty($per[date('Y-m-d')]);
$up = fn($n) => $n > 0 ? '<sup>↑ +'.$n.'</sup>' : '';
$cards = [
 ['Total Jurnal',$s['t'],'#0f2a66','M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8zM14 3v5h5M9 13h6M9 17h6','#3b82f6','jurnal.php',$sc,$up($n7)],
 ['Jurnal Bulan Ini',$s['b'],'#0f2a66','M8 3v4M16 3v4M3 10h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2z','#3b82f6','jurnal.php?bulan=1',$sd,$hariini ? '<sup>✔ hari ini</sup>' : '<sup class="no">belum hari ini</sup>'],
 ['Sudah Diverifikasi',$s['v'],'#16a34a','M5 12l5 5L20 7','#22c55e','jurnal.php?status=diverifikasi',$sv,$up($v7)],
 ['Menunggu Review',$s['m'],'#0f2a66','M12 7v5l3 2M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z','#f59e0b','jurnal.php?status=menunggu',$sm,''],
];
buka('dashboard', 'Dashboard');
include 'partials/hero.php'; ?>
<section class="stats">
<?php foreach ($cards as $c): ?>
  <a class="card stat" href="<?= $c[5] ?>"><div class="ic" style="background:<?= $c[2] ?>"><svg viewBox="0 0 24 24"><path d="<?= $c[3] ?>"/></svg></div>
    <div><small><?= $c[0] ?></small><div class="n"><?= (int)$c[1] ?><?= $c[7] ?></div></div><?= spark($c[6], $c[4]) ?></a>
<?php endforeach; ?>
</section>
<section class="grid">
  <div class="col">
    <div class="card"><div class="h"><h3><svg class="hi" viewBox="0 0 24 24"><path d="M3 10v4a1 1 0 0 0 1 1h2l5 4V5L6 9H4a1 1 0 0 0-1 1zM15 9a4 4 0 0 1 0 6M18 6a8 8 0 0 1 0 12"/></svg>Pengumuman</h3><a href="pengumuman.php">Lihat Semua →</a></div>
      <div class="note"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/></svg><div><?= $ann ? h($ann['isi']).'<small>'.h(tgl($ann['tanggal'])).'</small>' : 'Belum ada pengumuman.' ?></div></div></div>
    <div class="card"><div class="h"><h3><svg class="hi" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>Aktivitas Terbaru</h3><a href="riwayat.php">Lihat Semua →</a></div>
      <ul class="tl">
      <?php foreach ($act as $x): ?><li>Anda mengirim jurnal: <?= h($x['judul']) ?><small><?= h(tgl($x['created_at'])) ?>, <?= h(substr($x['created_at'], 11, 5)) ?></small></li>
      <?php endforeach; if (!$act): ?><li>Belum ada aktivitas<small>Kirim jurnal pertamamu hari ini</small></li><?php endif; ?>
      </ul></div>
  </div>
  <div class="card"><div class="h"><h3><svg class="hi" viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8zM14 3v5h5M9 13h6M9 17h6"/></svg>Jurnal Terbaru</h3><a href="jurnal.php">Lihat Semua →</a></div>
    <div class="tw"><table><thead><tr><th>Tanggal</th><th>Judul Kegiatan</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    <?php foreach ($rows as $r) baris($r); ?></tbody></table></div>
    <?php if (!$rows): ?><div class="empty">Belum ada jurnal. Klik Isi Jurnal Sekarang untuk mulai.</div><?php endif; ?>
  </div>
</section>
<?php tutup();
