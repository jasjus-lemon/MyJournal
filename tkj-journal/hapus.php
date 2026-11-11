<?php
require 'config.php'; need_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals(csrf(), $_POST['csrf'] ?? '')) { http_response_code(400); exit('Permintaan tidak valid.'); }
$st = $db->prepare("DELETE FROM jurnal WHERE id=? AND nisn=? AND status='menunggu'");
$st->execute([(int)($_POST['id'] ?? 0), $_SESSION['nisn']]);
$_SESSION['flash'] = $st->rowCount() ? ['ok', 'Jurnal dihapus.'] : ['err', 'Jurnal tidak bisa dihapus (sudah diverifikasi atau tidak ditemukan).'];
header('Location: jurnal.php');
