<?php $extraCss = ['egyptian-champions.css']; ?>

<div style="padding:1rem;">
    <div class="title-container" style="padding:1rem;">
        <p style="font-size:2.5rem;font-weight:900;">🇪🇬 Egyptian Champions</p>
        <p style="color:#9aa3a6;">Celebrating Egypt's greatest athletes and their international achievements.</p>
    </div>

    <div class="filters">
        <select id="sportFilter" onchange="filterChamps()">
            <option value="">All Sports</option>
            <?php foreach ($sportNames as $s): ?>
                <option value="<?= htmlspecialchars($s) ?>" <?= ($sport === $s) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="grid" id="champsGrid">
        <?php foreach ($champions as $ch): ?>
        <div class="card" data-sport="<?= htmlspecialchars($ch['sport_name']) ?>">
            <div class="card-img">
                <?php if ($ch['image']): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($ch['image']) ?>" alt="<?= htmlspecialchars($ch['name']) ?>">
                <?php else: ?>
                    <div style="width:100%;aspect-ratio:3/4;background:#1e293b;display:flex;align-items:center;justify-content:center;font-size:4rem;border-radius:.75rem;">🏆</div>
                <?php endif; ?>
            </div>
            <div class="card-info">
                <p class="name"><?= htmlspecialchars($ch['name']) ?></p>
                <p class="sport"><?= htmlspecialchars($ch['sport_name']) ?>
                    <?php if ($ch['champion_year']): ?> · <?= $ch['champion_year'] ?><?php endif; ?></p>
                <?php if ($ch['achievements']): ?>
                    <p class="achievements" style="-webkit-line-clamp:3;display:-webkit-box;-webkit-box-orient:vertical;overflow:hidden;">
                        <?= htmlspecialchars($ch['achievements']) ?>
                    </p>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/athletes/show/<?= urlencode($ch['slug']) ?>"
                   style="display:inline-block;margin-top:.5rem;color:#0da6f2;font-size:.85rem;text-decoration:none;">
                   View Profile →
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (empty($champions)): ?>
            <p style="color:#9aa3a6;padding:2rem;">No champions found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function filterChamps() {
    const sport = document.getElementById('sportFilter').value.toLowerCase();
    document.querySelectorAll('#champsGrid .card').forEach(card => {
        const cs = card.dataset.sport.toLowerCase();
        card.style.display = (!sport || cs === sport) ? '' : 'none';
    });
}
</script>
