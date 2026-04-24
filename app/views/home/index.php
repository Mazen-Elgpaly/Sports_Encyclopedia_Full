<?php
$extraCss = ['index.css'];
// Pass data to inline JS
$sportCardsJson   = json_encode(array_map(fn($c) => [
    'id'          => $c['sport_id'] ?? ($c['id'] ?? 0),
    'name'        => $c['sport_name'],
    'header'      => $c['header_image'] ? (BASE_URL.'/'.$c['header_image']) : '',
    'logo'        => $c['logo_image']   ? (BASE_URL.'/'.$c['logo_image'])   : '',
    'card'        => $c['card_image']   ? (BASE_URL.'/'.$c['card_image'])   : '',
    'fact'        => $c['fact'] ?? '',
    'players'     => $c['total_players'] ?? 0,
    'leagues'     => $c['professional_leagues'] ?? 0,
    'popularity'  => $c['popularity_score'] ?? 0,
], $sportCards), JSON_UNESCAPED_UNICODE);

$rankingJson = json_encode(array_map(fn($r) => [
    'rank'    => $r['rank'],
    'name'    => $r['athlete_name'],
    'sport'   => $r['sport_name'],
    'metric'  => $r['metric'],
    'year'    => $r['metric_year'],
    'country' => $r['country_name'] ?? '—',
], $ranking), JSON_UNESCAPED_UNICODE);
?>

<!-- ══ HERO ══════════════════════════════════════════════════ -->
<section class="hero">
    <div class="hero-img-container">
        <img src="<?= BASE_URL ?>/images/main_page.jpg" alt="Sports Hero" class="hero-img">
        <div class="overlay"></div>
    </div>
    <div class="hero-content">
        <h1>Explore the World of Sports</h1>
        <p>Dive into the stories, history, and passion behind every game.</p>
    </div>
</section>

<!-- ══ TOP SPORTS CARDS (flip on hover, card-deal animation) ═ -->
<section class="cards-section">
    <h2 class="section-title">Top Sports</h2>
    <div class="cards-container" id="sportsCardsContainer">
        <?php foreach ($sportCards as $card):
            $sportId   = $card['sport_id'] ?? ($card['id'] ?? 0);
            $headerImg = $card['header_image'] ? BASE_URL.'/'.$card['header_image'] : '';
            $logoImg   = $card['logo_image']   ? BASE_URL.'/'.$card['logo_image']   : '';
            $cardImg   = $card['card_image']   ? BASE_URL.'/'.$card['card_image']   : '';
        ?>
        <div class="card" data-sport-id="<?= $sportId ?>">
            <div class="card-inner">
                <div class="card-front sport">
                    <?php if ($cardImg): ?>
                        <img src="<?= htmlspecialchars($cardImg) ?>" alt="<?= htmlspecialchars($card['sport_name']) ?>">
                    <?php else: ?>
                        <div style="height:180px;background:#1e293b;border-radius:15px;display:flex;align-items:center;justify-content:center;font-size:3rem;border:1px solid rgba(255,255,255,.1);">🏅</div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($card['sport_name']) ?></h3>
                    <p><?= htmlspecialchars(mb_substr($card['fact'] ?? 'Explore this amazing sport.', 0, 80)) ?>...</p>
                    <button class="explore-btn" data-sport-id="<?= $sportId ?>">Explore</button>
                </div>
                <div class="card-back">
                    <?php if ($logoImg): ?>
                        <div class="circle-img" data-mask="<?= htmlspecialchars($logoImg) ?>"></div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($card['sport_name']) ?></h3>
                    <ul class="stat-list" style="width:100%;padding:0 1rem;">
                        <li><span>Popularity</span><span><?= $card['popularity_score'] ?? 0 ?>%</span></li>
                        <li><span>Leagues</span><span><?= $card['professional_leagues'] ?? '—' ?></span></li>
                        <li><span>Players</span><span><?= number_format($card['total_players'] ?? 0) ?></span></li>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button class="hero-btn" onclick="window.location.href='<?= BASE_URL ?>/sports'">Discover More</button>
</section>

<!-- ══ TOP ATHLETES CARDS ════════════════════════════════════ -->
<section class="cards-section">
    <h2 class="section-title1">Top Athletes</h2>
    <div class="cards-container" id="athletesCardsContainer">
        <?php foreach ($topAthletes as $a):
            $bannerUrl = $a['banner'] ? BASE_URL.'/'.$a['banner'] : '';
            $imageUrl  = $a['image']  ? BASE_URL.'/'.$a['image']  : '';
            $profileUrl = BASE_URL.'/athletes/show/'.urlencode($a['slug']);
        ?>
        <div class="card">
            <div class="card-inner athlete" style="height:480px;">
                <div class="card-front">
                    <div class="banner-container">
                        <?php if ($bannerUrl): ?>
                            <img class="banner" src="<?= htmlspecialchars($bannerUrl) ?>" alt="<?= htmlspecialchars($a['name']) ?>" style="border:none;height:230px;object-fit:cover;width:100%;border-radius:10px;">
                        <?php else: ?>
                            <div style="height:230px;background:linear-gradient(135deg,#1e293b,#0f172a);display:flex;align-items:center;justify-content:center;font-size:4rem;border-radius:10px;">🏃</div>
                        <?php endif; ?>
                    </div>
                    <h3 class="player-name"><?= htmlspecialchars($a['name']) ?></h3>
                    <ul class="stat-list" style="padding:0;width:100%;">
                        <li><span class="label">Sport</span><span class="value" style="color:#0da6f2;"><?= htmlspecialchars($a['sport_name']) ?></span></li>
                        <li><span class="label">Country</span><span class="value"><?= htmlspecialchars($a['country_name'] ?? '—') ?></span></li>
                        <?php foreach (array_slice($a['stats'] ?? [], 0, 2) as $st): ?>
                        <li><span class="label"><?= htmlspecialchars($st['stat_label']) ?></span><span class="value"><?= number_format($st['stat_value']) ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                    <button class="explore-btn" onclick="window.location.href='<?= htmlspecialchars($profileUrl) ?>'">View Profile</button>
                </div>
                <div class="card-back athlete">
                    <?php if ($imageUrl): ?>
                        <div class="circle-img athlete" data-mask="<?= htmlspecialchars($imageUrl) ?>" style="background:linear-gradient(45deg,#FFD700,#FFB700,#FFC850);"></div>
                    <?php else: ?>
                        <div class="circle-img athlete" style="background:linear-gradient(45deg,#FFD700,#FFB700,#FFC850,#FFF8DC);"></div>
                    <?php endif; ?>
                    <h3 style="font-size:1.4rem;background:linear-gradient(45deg,#FFD700,#FFB700,#FFC850);-webkit-background-clip:text;-webkit-text-fill-color:transparent;font-weight:800;"><?= htmlspecialchars($a['sport_name']) ?></h3>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <button class="hero-btn athlete" onclick="window.location.href='<?= BASE_URL ?>/athletes'">Discover More</button>
</section>

<!-- ══ RANKING TABLE ══════════════════════════════════════════ -->
<section style="max-width:1200px;margin:3rem auto;padding:0 2rem;">
    <h2 class="section-title" style="font-size:2.5rem;">🏆 Top Ranking</h2>
    <div style="overflow-x:auto;background:#15171a;border-radius:14px;padding:8px;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <?php foreach(['#','Athlete','Sport','Metric','Year','Country'] as $h): ?>
                    <th style="padding:18px;text-align:left;color:#9aa3a6;font-size:13px;background:#0f1315;"><?= $h ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ranking as $row):
                    $color = match($row['rank']) { 1=>'#ffd700', 2=>'#cfd8dc', 3=>'#cd7f32', default=>'#ddd' };
                    $weight = $row['rank'] <= 3 ? 700 : 400;
                ?>
                <tr style="transition:transform .2s,background .2s;" onmouseover="this.style.background='rgba(255,255,255,.03)';this.style.transform='translateX(4px)'" onmouseout="this.style.background='';this.style.transform=''">
                    <td style="padding:18px;border-top:1px solid rgba(255,255,255,.03);color:<?= $color ?>;font-weight:<?= $weight ?>;"><?= $row['rank'] ?></td>
                    <td style="padding:18px;border-top:1px solid rgba(255,255,255,.03);color:<?= $color ?>;font-weight:<?= $weight ?>;"><?= htmlspecialchars($row['athlete_name']) ?></td>
                    <td style="padding:18px;border-top:1px solid rgba(255,255,255,.03);color:#0da6f2;"><?= htmlspecialchars($row['sport_name']) ?></td>
                    <td style="padding:18px;border-top:1px solid rgba(255,255,255,.03);"><?= htmlspecialchars($row['metric']) ?></td>
                    <td style="padding:18px;border-top:1px solid rgba(255,255,255,.03);"><?= $row['metric_year'] ?></td>
                    <td style="padding:18px;border-top:1px solid rgba(255,255,255,.03);"><?= htmlspecialchars($row['country_name'] ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- ══ QUICK NAV ══════════════════════════════════════════════ -->
<section style="max-width:1200px;margin:2rem auto 4rem;padding:0 2rem;">
    <h2 class="section-title" style="font-size:2.5rem;">🔗 Quick Navigation</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;">
        <?php foreach ([
            ['⚖️ Compare Sports',    '/sports/compare'],
            ['👥 Compare Athletes',  '/athletes/compare'],
            ['🏆 Championships',     '/sports/championships'],
            ['🏟️ Clubs & Fields',   '/sports/clubs'],
            ['💡 Tips',             '/tips'],
            ['🇪🇬 Egy Champions',  '/athletes/champions'],
            ['📊 Statistics',       '/statistics'],
            ['📢 Statements',       '/statements'],
        ] as [$label, $path]): ?>
        <a href="<?= BASE_URL . $path ?>" class="quick-nav-link"><?= $label ?></a>
        <?php endforeach; ?>
    </div>
</section>

<script>
/* ════ Card deal animation (exact replica of original index.html) ════ */
document.addEventListener('DOMContentLoaded', function () {
    // Run for EACH cards-container separately
    document.querySelectorAll('.cards-container').forEach(function(cardsContainer) {
        const cards = Array.from(cardsContainer.querySelectorAll('.card'));
        if (!cards.length) return;

        function calculateFinalPositions() {
            const positions = [];
            const containerStyle = getComputedStyle(cardsContainer);
            const cols = containerStyle.gridTemplateColumns.split(' ').length;
            const gap  = parseInt(containerStyle.gap) || 20;
            const colW = (cardsContainer.clientWidth - (gap * (cols - 1))) / cols;
            cards.forEach((card, i) => {
                const row = Math.floor(i / cols);
                const col = i % cols;
                positions.push({ x: col * (colW + gap), y: row * (380 + gap), row: row + 1, col: col + 1 });
            });
            return positions;
        }

        function calculateStackPosition() {
            const stackX = cardsContainer.getBoundingClientRect().width - 1700;
            return { stackX, stackY: 50 };
        }

        function initializeCards() {
            const { stackX, stackY } = calculateStackPosition();
            cards.forEach((card, i) => {
                card.style.setProperty('--card-index',       i);
                card.style.setProperty('--stack-translate-x', `${stackX}px`);
                card.style.setProperty('--stack-translate-y', `${stackY}px`);
                card.style.setProperty('--stack-offset-x',   '3px');
                card.style.setProperty('--stack-offset-y',   '-3px');
                card.style.setProperty('--stack-opacity',    `${0.9 - i * 0.05}`);
                card.classList.add('collected');
                card.style.pointerEvents = 'none';
            });
        }

        function distributeCards() {
            const positions = calculateFinalPositions();
            cards.forEach((card, i) => {
                setTimeout(() => {
                    card.classList.remove('collected');
                    card.style.removeProperty('--stack-translate-x');
                    card.style.removeProperty('--stack-translate-y');
                    card.style.removeProperty('--stack-offset-x');
                    card.style.removeProperty('--stack-offset-y');
                    card.style.removeProperty('--stack-opacity');
                    const pos = positions[i];
                    if (pos) card.style.gridArea = `${pos.row} / ${pos.col} / auto / auto`;
                    setTimeout(() => card.classList.add('show-front'), 500);
                    card.style.pointerEvents = 'auto';
                }, i * 100);
            });
        }

        setTimeout(() => { initializeCards(); setTimeout(distributeCards, 500); }, 550);

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const { stackX, stackY } = calculateStackPosition();
                cards.forEach(card => {
                    if (card.classList.contains('collected')) {
                        card.style.setProperty('--stack-translate-x', `${stackX}px`);
                        card.style.setProperty('--stack-translate-y', `${stackY}px`);
                    } else {
                        distributeCards();
                    }
                });
            }, 250);
        });
    });

    /* ── circle-img mask ─────────────── */
    document.querySelectorAll('.circle-img[data-mask]').forEach(icon => {
        const mp = icon.dataset.mask;
        if (mp) { icon.style.mask = `url('${mp}') center/contain no-repeat`; icon.style.webkitMask = `url('${mp}') center/contain no-repeat`; }
    });

    /* ── explore-btn → sport detail ─── */
    document.querySelectorAll('.explore-btn[data-sport-id]').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            const id = btn.dataset.sportId;
            if (id && id !== '0') window.location.href = '<?= BASE_URL ?>/sports/show/' + id;
        });
    });
});
</script>
