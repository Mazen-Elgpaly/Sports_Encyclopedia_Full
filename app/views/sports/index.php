<?php $extraCss = ['sports.css']; ?>

<!-- ══ HERO ══════════════════════════════════════════════════ -->
<section class="hero">
    <div class="hero-content">
        <h1>Explore the World of Sports</h1>
        <p>Dive into the stories, history, and passion behind every game.</p>
        <div class="search-bar" style="position:relative;">
            <input type="text" id="sportSearch" placeholder="Search for a sport...">
            <button onclick="runSearch()">Search</button>
            <div class="search-suggestions" id="searchSuggestions"></div>
        </div>
    </div>
</section>

<!-- ══ SPORTS CARDS ══════════════════════════════════════════ -->
<section class="cards-section">
    <h2 class="section-title">Popular Sports</h2>
    <div class="cards-container" id="cardsContainer">
        <?php foreach ($sports as $sport):
            $cardImg = $sport['card_image'] ? BASE_URL.'/'.$sport['card_image'] : '';
            $logoImg   = $sport['logo_image']   ? BASE_URL.'/'.$sport['logo_image']   : '';
        ?>
        <div class="card" data-sport-name="<?= htmlspecialchars(strtolower($sport['name'])) ?>">
            <div class="card-inner">
                <div class="card-front">
                    <?php if ($cardImg): ?>
                        <img src="<?= htmlspecialchars($cardImg) ?>" alt="<?= htmlspecialchars($sport['name']) ?>">
                    <?php else: ?>
                        <div style="height:180px;background:#1e293b;border-radius:15px;display:flex;align-items:center;justify-content:center;font-size:3rem;border:1px solid rgba(255,255,255,.1);">🏅</div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($sport['name']) ?></h3>
                    <?php if ($sport['fact']): ?>
                        <p><?= htmlspecialchars(mb_substr($sport['fact'], 0, 80)) ?>...</p>
                    <?php else: ?>
                        <p>Explore detailed information about <?= htmlspecialchars($sport['name']) ?>.</p>
                    <?php endif; ?>
                    <button class="explore-btn" data-sport="<?= htmlspecialchars($sport['name']) ?>"
                            onclick="window.location.href='<?= BASE_URL ?>/sports/show/<?= $sport['id'] ?>'">
                        Explore
                    </button>
                </div>
                <div class="card-back">
                    <?php if ($logoImg): ?>
                        <div class="circle-img" data-mask="<?= htmlspecialchars($logoImg) ?>"></div>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($sport['name']) ?></h3>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="no-results" id="noResults">No sports found 😕</div>
</section>

<script>
const cardsContainer = document.getElementById('cardsContainer');
const allCards       = Array.from(cardsContainer.querySelectorAll('.card'));
const noResults      = document.getElementById('noResults');
const searchInput    = document.getElementById('sportSearch');
const suggestionsBox = document.getElementById('searchSuggestions');

/* ── circle-img mask ───────────────────────────────────────── */
document.querySelectorAll('.circle-img[data-mask]').forEach(icon => {
    const mp = icon.dataset.mask;
    if (mp) { icon.style.mask = `url('${mp}') center/contain no-repeat`; icon.style.webkitMask = `url('${mp}') center/contain no-repeat`; }
});

/* ── Card deal animation (exact replica) ────────────────────── */
(function() {
    function getFinalPositions() {
        const positions = [];
        const cs   = getComputedStyle(cardsContainer);
        const cols = cs.gridTemplateColumns.split(' ').length;
        const gap  = parseInt(cs.gap) || 20;
        const colW = (cardsContainer.clientWidth - gap*(cols-1)) / cols;
        allCards.forEach((_, i) => {
            const row = Math.floor(i/cols), col = i%cols;
            positions.push({ row: row+1, col: col+1 });
        });
        return positions;
    }

    function getStackPos() {
        return { stackX: cardsContainer.getBoundingClientRect().width - 1700, stackY: 50 };
    }

    function initCards() {
        const { stackX, stackY } = getStackPos();
        allCards.forEach((card, i) => {
            card.style.setProperty('--card-index',       i);
            card.style.setProperty('--stack-translate-x', `${stackX}px`);
            card.style.setProperty('--stack-translate-y', `${stackY}px`);
            card.style.setProperty('--stack-offset-x',   '3px');
            card.style.setProperty('--stack-offset-y',   '-3px');
            card.style.setProperty('--stack-opacity',    `${0.9 - i*0.05}`);
            card.classList.add('collected');
            card.style.pointerEvents = 'none';
        });
    }

    function distributeCards() {
        const positions = getFinalPositions();
        allCards.forEach((card, i) => {
            setTimeout(() => {
                card.classList.remove('collected');
                ['--stack-translate-x','--stack-translate-y','--stack-offset-x','--stack-offset-y','--stack-opacity']
                    .forEach(p => card.style.removeProperty(p));
                const pos = positions[i];
                if (pos) card.style.gridArea = `${pos.row} / ${pos.col} / auto / auto`;
                setTimeout(() => card.classList.add('show-front'), 500);
                card.style.pointerEvents = 'auto';
            }, i * 100);
        });
    }

    setTimeout(() => { initCards(); setTimeout(distributeCards, 500); }, 550);

    let rt;
    window.addEventListener('resize', () => {
        clearTimeout(rt);
        rt = setTimeout(() => {
            if (allCards.some(c => c.classList.contains('collected'))) {
                const { stackX, stackY } = getStackPos();
                allCards.forEach(c => { c.style.setProperty('--stack-translate-x', `${stackX}px`); c.style.setProperty('--stack-translate-y', `${stackY}px`); });
            } else {
                distributeCards();
            }
        }, 250);
    });
})();

/* ── Search ─────────────────────────────────────────────────── */
const sportNames = allCards.map(c => c.dataset.sportName);

function runSearch() {
    const q = searchInput.value.trim().toLowerCase();
    let vis = 0;

    if (q) {
        cardsContainer.classList.add('searching');
        allCards.forEach(card => { card.classList.add('leave'); });

        setTimeout(() => {
            allCards.forEach(card => {
                card.classList.remove('collected');
                const name = card.dataset.sportName || '';
                if (name.includes(q)) {
                    card.style.display = '';
                    card.classList.remove('leave');
                    card.classList.add('enter');
                    requestAnimationFrame(() => card.classList.remove('enter'));
                    vis++;
                } else {
                    card.style.display = 'none';
                }
            });
            noResults.style.display = vis === 0 ? 'block' : 'none';
        }, 300);
    } else {
        cardsContainer.classList.remove('searching');
        allCards.forEach(c => { c.style.display = ''; c.classList.remove('leave'); });
        noResults.style.display = 'none';
    }
}

searchInput.addEventListener('keyup', e => { if (e.key === 'Enter') runSearch(); });

searchInput.addEventListener('input', () => {
    const v = searchInput.value.toLowerCase();
    suggestionsBox.innerHTML = '';

    if (!v) { runSearch(); return; }

    sportNames.filter(n => n.includes(v)).slice(0, 5).forEach(match => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'suggestion-btn';
        btn.textContent = match.charAt(0).toUpperCase() + match.slice(1);
        btn.addEventListener('click', () => {
            searchInput.value = match;
            suggestionsBox.innerHTML = '';
            runSearch();
        });
        suggestionsBox.appendChild(btn);
    });
});

document.addEventListener('click', e => {
    if (!e.target.closest('.search-bar')) suggestionsBox.innerHTML = '';
});
</script>
