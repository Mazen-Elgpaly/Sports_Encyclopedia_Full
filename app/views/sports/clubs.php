<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html.dark {
        --primary: #0da6f2;
        --bg-dark: #101c22;
        --bg-card: #1a242a;
        --bg-input: #283339;
        --text-main: #ffffff;
        --text-muted: #9cb0ba;
        --border-dark: #283339;
    }

    body {
        min-height: 100vh;
        background: var(--bg-dark);
        font-family: "Space Grotesk", sans-serif;
        color: var(--text-main);
    }

    .material-symbols-outlined {
        font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24;
    }

    .design-root {
        display: flex;
        flex-direction: column;
    }

    .layout-container {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .layout-wrapper {
        padding: 20px 40px;
        display: flex;
        justify-content: center;
    }

    .layout-content {
        width: 100%;
        max-width: 1400px;
    }

    .hero {
        padding: 16px;
        margin-top: 24px;
        display: flex;
        justify-content: space-between;
        gap: 16px;
    }

    .hero h1 {
        font-size: 36px;
        font-weight: 900;
        letter-spacing: -.03em;
    }

    .hero p {
        color: var(--text-muted);
        margin-top: 4px;
    }

    .search-box {
        padding: 12px 16px;
    }

    .search-inner {
        height: 48px;
        display: flex;
    }

    .search-icon {
        width: 48px;
        background: var(--bg-input);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px 0 0 8px;
        color: var(--text-muted);
    }

    .search-icon .material-symbols-outlined {
        font-family: 'Material Symbols Outlined';
        font-size: 20px;
    }

    .search-inner input {
        flex: 1;
        background: var(--bg-input);
        border: none;
        outline: none;
        color: white;
        padding: 0 16px;
        border-radius: 0 8px 8px 0;
        font-size: 16px;
    }

    .search-inner input::placeholder {
        color: var(--text-muted);
    }

    .filters {
        padding: 12px;
        display: flex;
        gap: 12px;
        overflow-x: auto;
        flex-wrap: wrap;
    }

    .filter-select {
        height: 32px;
        background: var(--bg-input);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0 12px;
        cursor: pointer;
        outline: none;
    }

    .grid {
        padding: 16px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
    }

    .card {
        background: var(--bg-card);
        padding: 16px;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        cursor: pointer;
        transition: transform .3s;
    }

    .card:hover {
        transform: scale(1.05);
    }

    .card-img {
        aspect-ratio: 1/1;
        width: 100%;
        background-size: cover;
        background-position: center;
        border-radius: 8px;
        background-color: #283339;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }

    .card h3 {
        font-size: 16px;
        font-weight: 700;
    }

    .card p {
        font-size: 14px;
        color: var(--text-muted);
    }

    .empty {
        grid-column: 1/-1;
        background: var(--bg-card);
        padding: 40px;
        border-radius: 12px;
        text-align: center;
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-items: center;
    }

    .empty span {
        font-size: 48px;
        color: #6b7280;
    }

    .club-btn {
        margin-top: .5rem;
        width: fit-content;
        padding: .6rem 1rem;
        border: none;
        border-radius: 8px;
        background: var(--bg-input);
        color: white;
        cursor: pointer;
        transition: .2s;
    }

    .club-btn:hover {
        background: var(--primary);
    }

    .club-modal {
        position: fixed;
        inset: 0;
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .club-modal.active {
        display: flex;
    }

    .club-overlay {
        position: absolute;
        inset: 0;
        backdrop-filter: blur(12px);
        background: rgba(0, 0, 0, .55);
    }

    .club-modal-content {
        position: relative;
        z-index: 2;
        width: min(1000px, 90%);
        min-height: 550px;
        background: var(--bg-card);
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        animation: clubPopup .3s ease;
    }

    .club-modal-image {
        width: 50%;
    }

    .club-modal-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .club-modal-details {
        width: 50%;
        padding: 2rem;
        overflow-y: auto;
    }

    .club-modal-details h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .club-modal-details p {
        margin-bottom: .8rem;
        color: var(--text-muted);
    }

    #clubModalHistory {
        margin-top: 1rem;
        line-height: 1.8;
    }

    .club-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 3;
        width: 55px;
        height: 55px;
        border: none;
        border-radius: 50%;
        background: rgba(255, 255, 255, .1);
        color: white;
        cursor: pointer;
        font-size: 1.4rem;
    }

    .club-arrow.left {
        left: 30px;
    }

    .club-arrow.right {
        right: 30px;
    }

    @keyframes clubPopup {
        from {
            opacity: 0;
            transform: scale(.85);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

<div class="design-root">
    <div class="layout-container">
        <div class="layout-wrapper">
            <div class="layout-content">

                <!-- HERO -->
                <section class="hero">
                    <div>
                        <h1>Clubs &amp; Fields</h1>
                        <p>Find and explore sports clubs and fields across the world.</p>
                    </div>
                </section>

                <!-- SEARCH -->
                <div class="search-box">
                    <div class="search-inner">
                        <div class="search-icon">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <input id="clubSearch" placeholder="Search for a club...">
                    </div>
                </div>

                <!-- FILTERS -->
                <div class="filters">
                    <select id="sportFilter" class="filter-select">
                        <option value="">All Sports</option>
                        <?php foreach ($sportNames as $s): ?>
                            <option value="<?= htmlspecialchars($s['name']) ?>"
                                <?= ($sport === $s['name']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select id="governorateFilter" class="filter-select">
                        <option value="">All Governorates</option>
                        <?php
                        $govs = array_unique(array_column(array_merge(...array_values($grouped)), 'governorate'));
                        sort($govs);
                        foreach ($govs as $g): if (!$g) continue; ?>
                            <option value="<?= htmlspecialchars($g) ?>"><?= htmlspecialchars($g) ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>

                <!-- GRID -->
                <div class="grid" id="clubGrid">
                    <?php
                    // Build flat list
                    $allClubs = [];
                    foreach ($grouped as $sportName => $clubList) {
                        foreach ($clubList as $club) {
                            $allClubs[] = array_merge($club, ['sport_name' => $sportName]);
                        }
                    }
                    foreach ($allClubs as $club):
                        $imgStyle = $club['image']
                            ? "background-image:url('" . BASE_URL . '/' . htmlspecialchars($club['image']) . "')"
                            : '';
                    ?>
                        <div class="card"
                            data-name="<?= htmlspecialchars(strtolower($club['name'])) ?>"
                            data-title="<?= htmlspecialchars($club['name']) ?>"
                            data-gov="<?= htmlspecialchars(strtolower($club['governorate'] ?? '')) ?>"
                            data-governorate="<?= htmlspecialchars($club['governorate'] ?? '') ?>"
                            data-sport="<?= htmlspecialchars($club['sport_name']) ?>"
                            data-history="<?= htmlspecialchars($club['history'] ?? '') ?>"
                            data-image="<?= htmlspecialchars($club['image'] ?? '') ?>">
                            <div class="card-img" style="<?= $imgStyle ?>">
                                <?= !$club['image'] ? '🏟️' : '' ?>
                            </div>
                            <h3><?= htmlspecialchars($club['name']) ?></h3>
                            <p><?= htmlspecialchars($club['governorate'] ?? '—') ?>, <?= htmlspecialchars($club['sport_name']) ?></p>
                            <button class="club-btn view-club-btn">
                                View Club →
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="clubModal" class="club-modal">

                    <div class="club-overlay"></div>

                    <button id="prevClub" class="club-arrow left">❮</button>

                    <div class="club-modal-content">

                        <div class="club-modal-image">
                            <img id="clubModalImage" src="" alt="">
                        </div>

                        <div class="club-modal-details">
                            <h2 id="clubModalName"></h2>
                            <p id="clubModalGov"></p>
                            <p id="clubModalSport"></p>
                            <div id="clubModalHistory"></div>
                        </div>

                    </div>

                    <button id="nextClub" class="club-arrow right">❯</button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const clubCards = Array.from(document.querySelectorAll('#clubGrid .card'));
    const searchInp = document.getElementById('clubSearch');
    const govFilter = document.getElementById('governorateFilter');
    const spFilter = document.getElementById('sportFilter');

    function applyFilters() {
        const gov = govFilter.value.toLowerCase();
        const sport = spFilter.value.toLowerCase();
        const search = searchInp.value.toLowerCase().trim();
        let vis = 0;
        clubCards.forEach(card => {
            const matchGov = !gov || card.dataset.gov.includes(gov);
            const matchSport = !sport || card.dataset.sport.toLowerCase() === sport;
            const matchSearch = !search || card.dataset.name.includes(search);
            const show = matchGov && matchSport && matchSearch;
            card.style.display = show ? '' : 'none';
            if (show) vis++;
        });

        // Show empty state
        let empty = document.getElementById('emptyState');
        if (vis === 0) {
            if (!empty) {
                empty = document.createElement('div');
                empty.id = 'emptyState';
                empty.className = 'empty';
                empty.innerHTML = '<span class="material-symbols-outlined">search_off</span><h3>No clubs found</h3><p>Try adjusting your filters or search terms.</p>';
                document.getElementById('clubGrid').appendChild(empty);
            }
            empty.style.display = '';
        } else if (empty) {
            empty.style.display = 'none';
        }
    }

    // Update governorate options based on sport filter (same as original JS)
    spFilter.addEventListener('change', () => {
        const sport = spFilter.value;
        const availGovs = [...new Set(clubCards.filter(c => !sport || c.dataset.sport === sport).map(c => c.dataset.gov).filter(g => g))];
        const curGov = govFilter.value;
        govFilter.innerHTML = '<option value="">All Governorates</option>';
        [...new Set(availGovs)].sort().forEach(g => {
            const o = document.createElement('option');
            o.value = g;
            o.textContent = g.charAt(0).toUpperCase() + g.slice(1);
            if (g === curGov) o.selected = true;
            govFilter.appendChild(o);
        });
        applyFilters();
    });

    govFilter.addEventListener('change', applyFilters);
    searchInp.addEventListener('input', applyFilters);

    const modal = document.getElementById('clubModal');
    const overlay = document.querySelector('.club-overlay');
    const viewButtons = Array.from(document.querySelectorAll('.view-club-btn'));

    let currentClub = 0;

    function openClub(index) {
        currentClub = index;

        const card = clubCards[index];

        document.getElementById('clubModalName').textContent =
            card.dataset.title;

        document.getElementById('clubModalGov').textContent =
            card.dataset.governorate;

        document.getElementById('clubModalSport').textContent =
            card.dataset.sport;

        document.getElementById('clubModalHistory').textContent =
            card.dataset.history;

        const image = card.dataset.image;

        document.getElementById('clubModalImage').src =
            image ? '<?= BASE_URL ?>/' + image : '';
        modal.classList.add('active');
    }

    viewButtons.forEach((btn, index) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            openClub(index);
        });
    });

    document.getElementById('nextClub').addEventListener('click', () => {
        currentClub = (currentClub + 1) % clubCards.length;
        openClub(currentClub);
    });

    document.getElementById('prevClub').addEventListener('click', () => {
        currentClub = (currentClub - 1 + clubCards.length) % clubCards.length;
        openClub(currentClub);
    });

    overlay.addEventListener('click', () => {
        modal.classList.remove('active');
    });
</script>