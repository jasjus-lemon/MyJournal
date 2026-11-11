<?php
require 'inc.php'; need_guru();
header('Content-Type: text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename="rekap-jurnal-pkl.csv"');
echo "\xEF\xBB\xBF"; $o = fopen('php://output', 'w');
fputcsv($o, ['Nama','NISN','Tanggal','Judul kegiatan','Uraian','Status'], ',', '"', '\\');
foreach ($db->query('SELECT s.nama,s.nisn,j.tanggal,j.judul,j.uraian,j.status FROM jurnal j JOIN siswa s ON s.nisn=j.nisn ORDER BY s.nama,j.tanggal') as $r)
  fputcsv($o, [$r['nama'],"'".$r['nisn'],$r['tanggal'],$r['judul'],$r['uraian'],$r['status']], ',', '"', '\\');
