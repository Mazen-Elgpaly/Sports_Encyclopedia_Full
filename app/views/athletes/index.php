<?php $extraCss = ['players.css']; ?>

<main style="flex:1;padding:2rem 1rem;">
    <!-- Search -->
    <div class="search-section">
        <div class="search-box">
            <div class="search-inner">
                <span class="search-icon"><i class="bi bi-search"></i></span>
                <input type="text" id="searchInput" placeholder="Search athletes..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <div class="filters" style="margin-top:1rem;">
                <select id="sportFilter">
                    <option value="">All Sports</option>
                    <?php foreach ($sportNames as $s): ?>
                        <option value="<?= htmlspecialchars($s['name']) ?>" <?= ($sport === $s['name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button class="clear-btn" onclick="clearFilters()">Clear</button>
            </div>
        </div>
    </div>

    <div class="results-grid" id="resultsGrid">
        <?php foreach ($athletes as $a): ?>
        <a href="<?= BASE_URL ?>/athletes/show/<?= urlencode($a['slug']) ?>" class="result-card"
           data-name="<?= htmlspecialchars(strtolower($a['name'])) ?>"
           data-sport="<?= htmlspecialchars($a['sport_name']) ?>"
           data-country="<?= htmlspecialchars(strtolower($a['country_name'] ?? '')) ?>"
           style="text-decoration:none;color:inherit;">
            <div class="card-image">
                <?php if ($a['banner']): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($a['banner']) ?>" alt="<?= htmlspecialchars($a['name']) ?>">
                <?php else: ?>
                    <div style="width:100%;aspect-ratio:1;background:#1a1a1a;display:flex;align-items:center;justify-content:center;font-size:3rem;border-radius:.5rem;">🏃</div>
                <?php endif; ?>
            </div>
            <div class="card-info">
                <p class="name"><?= htmlspecialchars($a['name']) ?></p>
                <p class="country"> <span class="flag"> <?= htmlspecialchars($a['country_name'] ?? '—') ?> </span> </p>
                <p class="sport"><?= htmlspecialchars($a['sport_name']) ?></p>
            </div>
        </a>
        <?php endforeach; ?>
        <?php if (empty($athletes)): ?>
            <p style="color:#9aa3a6;grid-column:1/-1;text-align:center;padding:3rem;">No athletes found.</p>
        <?php endif; ?>
    </div>
</main>

<script>
const allCards = Array.from(document.querySelectorAll('#resultsGrid .result-card'));
const sportFilter = document.getElementById('sportFilter');
const searchInput = document.getElementById('searchInput');

function filterAthletes() {
    const sport = sportFilter.value.toLowerCase();
    const q     = searchInput.value.trim().toLowerCase();
    allCards.forEach(card => {
        const matchSport = !sport || card.dataset.sport.toLowerCase() === sport;
        const matchQ     = !q || card.dataset.name.includes(q) || card.dataset.country.includes(q);
        card.style.display = (matchSport && matchQ) ? '' : 'none';
    });
}

sportFilter.addEventListener('change', () => { window.location.href = '<?= BASE_URL ?>/athletes?sport=' + encodeURIComponent(sportFilter.value); });
searchInput.addEventListener('input', filterAthletes);
function clearFilters() { window.location.href = '<?= BASE_URL ?>/athletes'; }


</script>
<script src="https://cdn.jsdelivr.net/npm/twemoji@14.0.2/dist/twemoji.min.js"></script>
<script>
    document.querySelectorAll('.flag').forEach(el => {
    twemoji.parse(el, {
        folder: 'svg',
        ext: '.svg'
    });
});
</script>