<?php
require 'config.php'; need_login();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="jurnal-'.$_SESSION['nisn'].'.csv"');
echo "\xEF\xBB\xBF"; $o = fopen('php://output', 'w');
fputcsv($o, ['Tanggal','Judul kegiatan','Uraian','Status'], ',', '"', '\\');
$st = $db->prepare('SELECT * FROM jurnal WHERE nisn=? ORDER BY tanggal DESC'); $st->execute([$_SESSION['nisn']]);
foreach ($st as $r) fputcsv($o, [$r['tanggal'],$r['judul'],$r['uraian'],$r['status']], ',', '"', '\\');
