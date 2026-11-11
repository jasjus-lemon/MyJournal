<aside>
  <a class="logo" href="index.php" style="text-decoration:none;color:#fff"><b>T<i>J</i></b><div><h1>TKJ Journal</h1><small>Jurnal PKL Siswa TKJ</small></div></a>
  <nav>
    <a href="index.php"<?= $aktif==='dashboard'?' class="on"':'' ?>><svg viewBox="0 0 24 24"><path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>Dashboard</a>
    <a href="jurnal.php"<?= $aktif==='jurnal'?' class="on"':'' ?>><svg viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8zM14 3v5h5M9 13h6M9 17h6"/></svg>Jurnal Saya</a>
    <a href="riwayat.php"<?= $aktif==='riwayat'?' class="on"':'' ?>><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>Riwayat</a>
    <a href="profil.php"<?= $aktif==='profil'?' class="on"':'' ?>><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c1-4 4-6 8-6s7 2 8 6"/></svg>Profil</a>
    <a href="pengaturan.php"<?= $aktif==='pengaturan'?' class="on"':'' ?>><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1.2l2-1.5-2-3.4-2.3.9a7 7 0 0 0-2-1.2L14 3h-4l-.6 2.6a7 7 0 0 0-2 1.2l-2.3-.9-2 3.4 2 1.5A7 7 0 0 0 5 12c0 .4 0 .8.1 1.2l-2 1.5 2 3.4 2.3-.9c.6.5 1.3.9 2 1.2L10 21h4l.6-2.6c.7-.3 1.4-.7 2-1.2l2.3.9 2-3.4-2-1.5c.1-.4.1-.8.1-1.2z"/></svg>Pengaturan</a>
    <a href="logout.php"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>Keluar</a>
  </nav>
  <?php include 'partials/rack.php'; ?>
  <div class="tag">Teknologi Hari Ini,<br>Untuk Masa Depan<i></i></div>
</aside>