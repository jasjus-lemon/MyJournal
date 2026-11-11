<svg class="rack" viewBox="0 0 240 160" aria-hidden="true">
  <defs><linearGradient id="rk" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#2a6bdc"/><stop offset="1" stop-color="#0a2a66"/></linearGradient>
  <radialGradient id="rg" cx="50%" cy="50%" r="50%"><stop offset="0" stop-color="#4da3ff" stop-opacity=".55"/><stop offset="1" stop-color="#4da3ff" stop-opacity="0"/></radialGradient></defs>
  <ellipse cx="120" cy="140" rx="115" ry="18" fill="url(#rg)"/>
  <?php for ($i = 0; $i < 6; $i++): ?><path d="M<?= 10 + $i * 4 ?> <?= 150 - $i * 3 ?> L<?= 230 - $i * 4 ?> <?= 150 - $i * 3 ?>" stroke="#4da3ff" stroke-opacity=".25" fill="none"/><?php endfor; ?>
  <?php foreach ([[14, 78, 52, 62], [64, 22, 62, 118], [128, 52, 54, 88], [184, 88, 42, 52]] as $k => [$x, $y, $w, $h]): ?>
    <rect x="<?= $x ?>" y="<?= $y ?>" width="<?= $w ?>" height="<?= $h ?>" rx="4" fill="url(#rk)" stroke="#6fb3ff" stroke-opacity=".6"/>
    <?php for ($r = 0; $r < intdiv($h - 10, 12); $r++): ?>
      <rect x="<?= $x + 6 ?>" y="<?= $y + 7 + $r * 12 ?>" width="<?= $w - 12 ?>" height="8" rx="2" fill="#0a2556" stroke="#3f7fe0" stroke-opacity=".5"/>
      <circle cx="<?= $x + 12 ?>" cy="<?= $y + 11 + $r * 12 ?>" r="1.6" fill="<?= ($r + $k) % 3 ? '#5eead4' : '#7cc4ff' ?>"/>
      <rect x="<?= $x + 20 ?>" y="<?= $y + 10 + $r * 12 ?>" width="<?= $w - 34 ?>" height="2" fill="#4da3ff" opacity=".45"/>
    <?php endfor; ?>
  <?php endforeach; ?>
</svg>
