<dialog id="dlg"><form method="post" action="simpan.php"><h3>Isi jurnal hari ini</h3>
  <input type="hidden" name="csrf" value="<?= h(csrf()) ?>"><input type="hidden" name="jid"><input type="hidden" name="kembali" value="<?= h(basename($_SERVER['SCRIPT_NAME'])) ?>">
  <label for="d">Tanggal</label><input id="d" name="tanggal" type="date" max="<?= date('Y-m-d') ?>" required>
  <label for="t">Judul kegiatan</label><input id="t" name="judul" maxlength="150" required placeholder="Contoh: Crimping kabel UTP">
  <label for="c">Uraian kegiatan</label><textarea id="c" name="uraian" rows="3" placeholder="Apa yang kamu kerjakan hari ini?"></textarea>
  <div class="row"><button type="button" class="btn alt" data-tutup>Batal</button><button class="btn go">Kirim jurnal</button></div></form></dialog>
<dialog id="det"><h3></h3><small></small><p></p><div class="row"><button type="button" class="btn alt" data-tutup>Tutup</button></div></dialog>
<script src="assets/app.js"></script>
