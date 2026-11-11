<div class="top">
  <form class="search" method="get" action="jurnal.php"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
    <input name="q" type="search" value="<?= h($q) ?>" placeholder="Cari jurnal, kegiatan…" aria-label="Cari jurnal"></form>
  <div class="me">
    <a class="bell" href="pengumuman.php" aria-label="Pengumuman"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0"/></svg><?php if ($nb): ?><span><?= (int)$nb ?></span><?php endif; ?></a>
    <a class="me" href="profil.php" style="text-decoration:none;color:inherit"><div class="av"><?= h($ini) ?></div><div><b><?= h($nama) ?></b><small>NISN <?= h($nisn) ?></small></div></a>
  </div>
</div>
<?php if ($f): ?><div class="flash <?= h($f[0]) ?>" role="status"><?= h($f[1]) ?></div><?php endif; ?>
