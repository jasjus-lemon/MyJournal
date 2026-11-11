<?php
date_default_timezone_set('Asia/Jakarta');
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
// Tampilkan detail error hanya saat dibuka dari komputer sendiri / jaringan lokal
$lokal = (bool)preg_match('/^(localhost|127\.0\.0\.1|\[::1\]|192\.168\.|10\.|172\.(1[6-9]|2\d|3[01])\.)/', $_SERVER['HTTP_HOST'] ?? '');
ini_set('display_errors', $lokal ? '1' : '0');
header('X-Frame-Options: SAMEORIGIN'); header('X-Content-Type-Options: nosniff'); header('Referrer-Policy: same-origin');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS'])]);
session_start();
$k = require __DIR__.'/koneksi.php';
define('PIN_SISWA', !empty($k['pin_siswa']));
try {
  $db = new PDO("mysql:host={$k['host']};dbname={$k['nama']};charset=utf8mb4", $k['user'], $k['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
  $db->exec("SET time_zone='+07:00'");
} catch (PDOException $e) {
  http_response_code(500);
  exit($lokal ? 'Database error: '.h($e->getMessage()) : 'Layanan sedang bermasalah. Silakan coba lagi nanti.');
}
// Menyesuaikan database lama secara otomatis (sekali per sesi)
if (empty($_SESSION['skema'])) {
  if (!$db->query("SHOW COLUMNS FROM siswa LIKE 'pin_hash'")->fetch()) $db->exec('ALTER TABLE siswa ADD pin_hash VARCHAR(255) NULL');
  $db->exec("CREATE TABLE IF NOT EXISTS login_gagal (id INT AUTO_INCREMENT PRIMARY KEY, ip VARCHAR(45) NOT NULL, ident VARCHAR(60) NOT NULL, waktu DATETIME NOT NULL, KEY (ip,waktu))");
  $db->exec("CREATE TABLE IF NOT EXISTS guru (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) UNIQUE NOT NULL, nama VARCHAR(100) NOT NULL, password_hash VARCHAR(255) NOT NULL)");
  $_SESSION['skema'] = 1;
}
function need_login(){ if (empty($_SESSION['nisn'])) { header('Location: login.php'); exit; } }
function csrf(){ return $_SESSION['csrf'] ??= bin2hex(random_bytes(16)); }
function tgl($d){
  $b = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  [$y,$m,$t] = explode('-', substr($d,0,10));
  return (int)$t.' '.$b[(int)$m-1].' '.$y;
}
function buka($aktif, $judul){
  global $db;
  $nama = $_SESSION['nama']; $nisn = $_SESSION['nisn'];
  $ini = implode('', array_map(fn($w) => mb_substr($w, 0, 1), array_slice(explode(' ', $nama), 0, 2)));
  $nb = (int)$db->query("SELECT COUNT(*) FROM pengumuman WHERE tanggal>=DATE_SUB(CURDATE(),INTERVAL 7 DAY)")->fetchColumn();
  $q = trim($_GET['q'] ?? '');
  $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
  echo '<!DOCTYPE html><html lang="id"><head>'; include 'partials/head.php';
  echo '</head><body><div class="app">'; include 'partials/sidebar.php';
  echo '<main>'; include 'partials/top.php';
}
function tutup(){ echo '</main></div>'; include 'partials/dialog.php'; echo '</body></html>'; }
function baris($r, $aksi = false){
  $ok = $r['status'] === 'diverifikasi';
  $v = h(json_encode(['judul'=>$r['judul'],'tgl'=>tgl($r['tanggal']),'uraian'=>$r['uraian']], JSON_UNESCAPED_UNICODE));
  echo '<tr><td>'.h(tgl($r['tanggal'])).'</td><td>'.h($r['judul']).'</td><td><span class="pill '.($ok?'ok':'wait').'">'.($ok?'✔ Diverifikasi':'⏳ Menunggu Review').'</span></td>';
  echo '<td class="ak"><button class="eye" aria-label="Lihat" data-j=\''.$v.'\'>👁</button>';
  if ($aksi && !$ok) {
    $e = h(json_encode(['id'=>$r['id'],'tanggal'=>$r['tanggal'],'judul'=>$r['judul'],'uraian'=>$r['uraian']], JSON_UNESCAPED_UNICODE));
    echo '<button class="eye edit" aria-label="Ubah" data-j=\''.$e.'\'>✏️</button>';
    echo '<form class="i" method="post" action="hapus.php" onsubmit="return confirm(\'Hapus jurnal ini?\')"><input type="hidden" name="csrf" value="'.h(csrf()).'"><input type="hidden" name="id" value="'.(int)$r['id'].'"><button class="eye" aria-label="Hapus">🗑</button></form>';
  }
  echo '</td></tr>';
}
function spark($v, $c){
  static $n = 0; $n++; $w = 110; $h = 44; $k = count($v); $mn = min($v); $mx = max($v); $p = [];
  foreach ($v as $i => $x) $p[] = [round($i * $w / ($k - 1), 1), round($mx == $mn ? $h * .62 : ($h - 6) - ($x - $mn) / ($mx - $mn) * ($h - 14), 1)];
  $d = 'M'.$p[0][0].' '.$p[0][1];
  for ($i = 1; $i < $k; $i++) { $m = round(($p[$i-1][0] + $p[$i][0]) / 2, 1); $d .= " C$m {$p[$i-1][1]} $m {$p[$i][1]} {$p[$i][0]} {$p[$i][1]}"; }
  return '<svg class="sp" viewBox="0 0 110 44" aria-hidden="true"><defs><linearGradient id="sg'.$n.'" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="'.$c.'" stop-opacity=".35"/><stop offset="1" stop-color="'.$c.'" stop-opacity="0"/></linearGradient></defs><path d="'.$d." L$w $h L0 $h Z".'" fill="url(#sg'.$n.')"/><path d="'.$d.'" fill="none" stroke="'.$c.'" stroke-width="2" stroke-linecap="round"/></svg>';
}
