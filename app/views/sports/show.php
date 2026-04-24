<?php $extraCss = ['sport_info.css']; ?>

<div class="container" style="padding:2rem;">
    <a href="<?= BASE_URL ?>/sports" style="color:#0da6f2;text-decoration:none;font-size:.9rem;">← Back to Sports</a>

    <!-- HERO -->
    <div class="hero" style="margin-top:1rem;border-radius:12px;overflow:hidden;max-height:320px;">
        <?php if ($sport['header_image']): ?>
            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($sport['header_image']) ?>" alt="<?= htmlspecialchars($sport['name']) ?>">
        <?php endif; ?>
        <h1><?= htmlspecialchars($sport['name']) ?></h1>
    </div>

    <!-- TABS -->
    <div class="tabs" style="margin-top:1.5rem;">
        <a href="#" class="active" onclick="switchTab(event,'description')">Overview</a>
        <a href="#" onclick="switchTab(event,'history')">History</a>
        <a href="#" onclick="switchTab(event,'rules')">Rules</a>
        <a href="#" onclick="switchTab(event,'equipment')">Equipment</a>
    </div>

    <div class="content">
        <div class="text-section">
            <!-- Tab: Description -->
            <div id="description" class="tab-content active">
                <?php foreach (($sport['description'] ?? []) as $p): ?>
                    <p><?= htmlspecialchars($p) ?></p>
                <?php endforeach; ?>
            </div>

            <!-- Tab: History -->
            <div id="history" class="tab-content">
                <?php foreach (($sport['history'] ?? []) as $p): ?>
                    <p><?= htmlspecialchars($p) ?></p>
                <?php endforeach; ?>
            </div>

            <!-- Tab: Rules -->
            <div id="rules" class="tab-content">
                <ol style="padding-left:1.5rem;color:#ccc;line-height:2;">
                    <?php foreach (($sport['rules'] ?? []) as $r): ?>
                        <li><?= htmlspecialchars($r) ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <!-- Tab: Equipment -->
            <div id="equipment" class="tab-content">
                <ul style="padding-left:1.5rem;color:#ccc;line-height:2;">
                    <?php foreach (($sport['equipment'] ?? []) as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- GALLERY — OUTSIDE tabs, appears once below all tabs (exact replica of original) -->
            <?php if (!empty($sport['gallery'])): ?>
            <h2>Gallery</h2>
            <div class="gallery">
                <?php foreach ($sport['gallery'] as $img): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($img['image_path']) ?>" alt="Gallery">
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <?php if (!empty($sport['stats'])): ?>
            <div class="card stats">
                <h3>⚡ Performance Stats</h3>
                <div class="stats">
                    <?php foreach ($sport['stats'] as $st): ?>
                    <div class="bar">
                        <span><?= htmlspecialchars($st['stat_name']) ?></span>
                        <div class="progress">
                            <div style="width:0" data-target="<?= (int)$st['stat_value'] ?>"></div>
                        </div>
                        <span class="value"><?= $st['stat_value'] ?>%</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($sport['fact']): ?>
            <div class="card fact">
                <h3>💡 Did You Know?</h3>
                <p><?= htmlspecialchars($sport['fact']) ?></p>
            </div>
            <?php endif; ?>

            <div class="card">
                <h3 style="margin-bottom:12px;">🔗 Explore More</h3>
                <a href="<?= BASE_URL ?>/sports/championships" style="display:block;color:#0da6f2;padding:6px 0;text-decoration:none;">🏆 Championships</a>
                <a href="<?= BASE_URL ?>/sports/clubs" style="display:block;color:#0da6f2;padding:6px 0;text-decoration:none;">🏟️ Clubs</a>
                <a href="<?= BASE_URL ?>/athletes" style="display:block;color:#0da6f2;padding:6px 0;text-decoration:none;">🏃 Athletes</a>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(e, id) {
    e.preventDefault();
    document.querySelectorAll('.tabs a').forEach(a => a.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    e.target.classList.add('active');
    document.getElementById(id).classList.add('active');
}

// Animate stat bars
document.querySelectorAll('.progress div[data-target]').forEach(bar => {
    const io = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) {
            const t = bar.dataset.target;
            bar.style.transition = 'width 1s ease-out';
            bar.style.width = t + '%';
            io.unobserve(bar);
        }
    }, { threshold: 0.3 });
    io.observe(bar);
});
</script>
