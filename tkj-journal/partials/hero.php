<?php
$kode = ['#4da3ff', '#34d399', '#a78bfa', '#fbbf24', '#7cc4ff'];
$lebar = [.55, .35, .7, .45, .6, .3, .65, .5, .4, .6, .35];
$node = [];
for ($k = 0; $k < 22; $k++) { $a = $k * 0.62; $r = 40 + (($k * 37) % 62); $node[] = [round(205 + $r * cos($a), 1), round(135 + $r * sin($a) * .95, 1)]; }
?>
<section class="hero">
  <svg class="bgart" viewBox="0 0 1000 300" preserveAspectRatio="none" aria-hidden="true">
    <path d="M0 230C250 120 470 330 1000 110L1000 300L0 300Z" fill="#fff" fill-opacity=".045"/>
    <path d="M0 110C300 270 620 30 1000 210L1000 0L0 0Z" fill="#5a9bff" fill-opacity=".08"/>
  </svg>
  <q>Belajar · Berkarya · Berinovasi</q>
  <div class="ht">
    <h2>Selamat Datang di <em>TKJ Journal!</em></h2>
    <p>Platform digital untuk mencatat dan mengumpulkan jurnal kegiatan PKL Teknik Komputer dan Jaringan.</p>
    <button class="btn" id="open"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>Isi Jurnal Sekarang<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
  </div>
  <svg class="art" viewBox="0 0 620 280" preserveAspectRatio="xMaxYMax meet" aria-hidden="true">
    <defs>
      <radialGradient id="gl" cx="50%" cy="50%" r="50%"><stop offset="0" stop-color="#3d8bff" stop-opacity=".7"/><stop offset="1" stop-color="#3d8bff" stop-opacity="0"/></radialGradient>
      <linearGradient id="lb" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#e3eaf7"/><stop offset="1" stop-color="#8fa4c6"/></linearGradient>
      <linearGradient id="cb" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#3b82f6"/><stop offset="1" stop-color="#0b2e7a"/></linearGradient>
    </defs>
    <ellipse cx="310" cy="150" rx="290" ry="125" fill="url(#gl)"/>
    <g fill="none" stroke="#6fb8ff" stroke-opacity=".55">
      <circle cx="205" cy="135" r="105"/><ellipse cx="205" cy="135" rx="42" ry="105"/><ellipse cx="205" cy="135" rx="78" ry="105"/>
      <ellipse cx="205" cy="135" rx="105" ry="30"/><ellipse cx="205" cy="100" rx="98" ry="14"/><ellipse cx="205" cy="172" rx="98" ry="14"/>
      <?php foreach ($node as $i => $n): $m = $node[($i + 3) % 22]; ?><line x1="<?= $n[0] ?>" y1="<?= $n[1] ?>" x2="<?= $m[0] ?>" y2="<?= $m[1] ?>" stroke-opacity=".35"/><?php endforeach; ?>
    </g>
    <?php foreach ($node as $n): ?><circle cx="<?= $n[0] ?>" cy="<?= $n[1] ?>" r="2.4" fill="#9bd5ff"/><?php endforeach; ?>
    <polygon points="255,50 445,32 455,160 268,184" fill="#081a3f" stroke="#b4c4e0" stroke-width="5" stroke-linejoin="round"/>
    <?php for ($r = 0; $r < 11; $r++):
      $t = ($r + .8) / 11.8; $lx = 255 + 13 * $t; $rx = 445 + 10 * $t; $ly = 50 + 134 * $t; $ry = 32 + 128 * $t;
      $u0 = .06; $u1 = .06 + $lebar[$r] * .8;
      $x0 = $lx + ($rx - $lx) * $u0; $y0 = $ly + ($ry - $ly) * $u0; $x1 = $lx + ($rx - $lx) * $u1; $y1 = $ly + ($ry - $ly) * $u1; ?>
      <line x1="<?= round($x0, 1) ?>" y1="<?= round($y0, 1) ?>" x2="<?= round($x1, 1) ?>" y2="<?= round($y1, 1) ?>" stroke="<?= $kode[$r % 5] ?>" stroke-width="3.2" stroke-linecap="round" opacity=".9"/>
    <?php endfor; ?>
    <polygon points="268,184 455,160 488,188 246,216" fill="url(#lb)"/>
    <polygon points="286,191 452,171 471,186 272,206" fill="#7d93b8" opacity=".55"/>
    <polygon points="338,200 392,194 398,200 342,206" fill="#c9d6ec"/>
    <g>
      <polygon points="470,214 540,192 604,214 534,238" fill="#5aa2ff"/>
      <polygon points="470,214 534,238 534,254 470,230" fill="url(#cb)"/><polygon points="534,238 604,214 604,230 534,254" fill="#0d3a96"/>
      <polygon points="508,206 540,196 572,206 540,218" fill="#1d5fe0" stroke="#9bd5ff" stroke-opacity=".8"/>
      <polygon points="508,206 540,218 540,230 508,218" fill="#0d3a96"/><polygon points="540,218 572,206 572,218 540,230" fill="#0a2a70"/>
    </g>
    <g fill="#9bd5ff"><circle cx="40" cy="60" r="2"/><circle cx="120" cy="30" r="1.6"/><circle cx="470" cy="70" r="2"/><circle cx="560" cy="120" r="1.8"/><circle cx="500" cy="40" r="1.4"/></g>
  </svg>
</section>
