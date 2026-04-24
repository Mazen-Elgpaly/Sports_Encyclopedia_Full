<?php $extraCss = ['compare-athletes.css']; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<div class="container" style="max-width:1200px;margin:2rem auto;padding:0 2rem;">
    <a href="<?= BASE_URL ?>/athletes" style="color:#0da6f2;text-decoration:none;font-size:.9rem;">← Back to Athletes</a>

    <!-- Banner -->
    <?php if ($athlete['banner']): ?>
    <div style="position:relative;height:550px;border-radius:16px;overflow:hidden;margin:1rem 0;">
        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($athlete['banner']) ?>" alt=""
             style="width:100%;height:100%;object-fit:contain;object-position:top;">
        <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(15,23,42,.85),transparent 60%);"></div>
        <div style="position:absolute;bottom:1.5rem;left:1.75rem;display:flex;align-items:center;gap:1rem;">
            <?php if ($athlete['image']): ?>
                <img src="<?= BASE_URL ?>/<?= htmlspecialchars($athlete['image']) ?>" alt=""
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #0da6f2;">
            <?php endif; ?>
            <div>
                <h1 style="font-size:1.75rem;margin-bottom:.3rem;"><?= htmlspecialchars($athlete['name']) ?></h1>
                <span style="background:rgba(13,166,242,.2);color:#0da6f2;border-radius:999px;padding:.2rem .75rem;font-size:.85rem;">
                    <?= htmlspecialchars($athlete['sport_name']) ?>
                </span>
                <?php if ($athlete['country_name']): ?>
                <span style="background:rgba(16,185,129,.15);color:#6ee7b7;border-radius:999px;padding:.2rem .75rem;font-size:.85rem;margin-left:.5rem;">
                    🌍 <?= htmlspecialchars($athlete['country_name']) ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($athlete['is_egyptian_champion']): ?>
    <div style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;">
        <strong style="color:#fbbf24;">🇪🇬 Egyptian Champion</strong>
        <?php if ($athlete['champion_year']): ?> · <span style="color:#9aa3a6;">Class of <?= $athlete['champion_year'] ?></span><?php endif; ?>
        <?php if ($athlete['achievements']): ?>
            <p style="color:#9aa3a6;font-size:.875rem;margin-top:.5rem;"><?= htmlspecialchars($athlete['achievements']) ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Stats cards -->
    <?php if (!empty($athlete['stats'])): ?>
    <section style="margin-bottom:2rem;">
        <h2 style="font-size:1.2rem;font-weight:600;margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px solid #283339;">📊 Career Statistics</h2>
        <div style="display:flex;flex-wrap:wrap;gap:1rem;">
            <?php foreach ($athlete['stats'] as $st): ?>
            <div style="background:#15181b;border:1px solid #283339;border-radius:12px;padding:1rem 1.25rem;text-align:center;flex:1;min-width:120px;">
                <div style="font-size:1.75rem;font-weight:800;color:#0da6f2;"><?= number_format($st['stat_value']) ?></div>
                <div style="font-size:.8rem;color:#9aa3a6;margin-top:.2rem;"><?= htmlspecialchars($st['stat_label']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Chart -->
    <?php if (!empty($athlete['chart'])): ?>
    <section style="margin-bottom:2rem;">
        <h2 style="font-size:1.2rem;font-weight:600;margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px solid #283339;">
            📈 <?= htmlspecialchars($athlete['chart_about'] ?? 'Performance Chart') ?>
        </h2>
        <div style="background:#15181b;border-radius:12px;padding:1.25rem;height:280px;">
            <canvas id="perfChart"></canvas>
        </div>
        <script>
        new Chart(document.getElementById('perfChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($athlete['chart'], 'chart_year')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($athlete['chart'], 'chart_value')) ?>,
                    backgroundColor: '#0da6f2',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#9aa3a6' }, grid: { color: '#1a1d20' } },
                    x: { ticks: { color: '#9aa3a6' }, grid: { display: false } }
                }
            }
        });
        </script>
    </section>
    <?php endif; ?>

    <!-- Timeline -->
    <?php if (!empty($athlete['timeline'])): ?>
    <section style="margin-bottom:2rem;">
        <h2 style="font-size:1.2rem;font-weight:600;margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px solid #283339;">🕐 Career Timeline</h2>
        <div style="position:relative;padding-left:3.5rem;">
            <div style="position:absolute;left:50px;top:0;bottom:0;width:2px;background:#283339;"></div>
            <?php foreach ($athlete['timeline'] as $ev): ?>
            <div style="position:relative;padding:.75rem 0;display:flex;align-items:flex-start;gap:1rem;">
                <div style="position:absolute;left:-2.6rem;width:50px;text-align:right;font-weight:700;color:#0da6f2;font-size:.9rem;">
                    <?= $ev['event_year'] ?>
                </div>
                <div style="position:absolute;left:4px;width:10px;height:10px;border-radius:50%;background:#0da6f2;border:2px solid #0b0e13;top:.9rem;"></div>
                <div style="color:#9aa3a6;font-size:.9rem;padding-left:.75rem;"><?= htmlspecialchars($ev['event_text']) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <div style="margin-top:2rem;">
        <a href="<?= BASE_URL ?>/athletes/compare?left=<?= urlencode($athlete['slug']) ?>"
           style="display:inline-block;background:#0da6f2;color:#000;font-weight:700;padding:.6rem 1.25rem;border-radius:10px;text-decoration:none;margin-right:.75rem;">
           ⚖️ Compare with another
        </a>
        <a href="<?= BASE_URL ?>/athletes/champions"
           style="display:inline-block;border:1px solid #283339;color:#9aa3a6;padding:.6rem 1.25rem;border-radius:10px;text-decoration:none;">
           🇪🇬 Egyptian Champions
        </a>
    </div>
</div>
