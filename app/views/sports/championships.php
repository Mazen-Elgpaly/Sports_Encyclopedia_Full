<style>
body{background-color:#121212;color:#EAEAEA;}
:root{--primary:#007BFF;--background-dark:#121212;--secondary-dark:#282828;--card-dark:#1A1A1A;--text-muted:#A0A0A0;--panel:#121416;}
.champ-container{display:flex;flex-direction:column;}
.champ-main{padding:2rem 2.5rem;flex:1;}
.champ-main h1{font-family:'Playfair Display',serif;font-size:3rem;font-weight:700;margin:0 0 1rem;}
#sports-dropdown{padding:10px 14px;background:var(--panel);border:1px solid rgba(255,255,255,.1);border-radius:10px;color:#fff;outline:none;}
.search-box{display:flex;height:40px;width:260px;}
.search-icon{display:flex;align-items:center;justify-content:center;background:var(--secondary-dark);padding-left:12px;border-radius:8px 0 0 8px;color:var(--text-muted);}
.search-box input{flex:1;border:none;outline:none;padding:0 12px;background:var(--secondary-dark);border-radius:0 8px 8px 0;color:#fff;font-size:1rem;}
.search-box input::placeholder{color:var(--text-muted);}
.filters-row{display:flex;gap:0.75rem;margin-bottom:2rem;flex-wrap:wrap;align-items:center;}
.cards-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(450px,1fr));gap:1.5rem;}
.card{background:var(--card-dark);border-radius:.75rem;padding:1.5rem;display:flex;flex-direction:column;gap:1rem;box-shadow:0 4px 6px rgba(0,0,0,.5);transition:all .3s ease;}
.card:hover{box-shadow:0 4px 12px rgba(0,123,255,.4);transform:translateY(-4px);}
.card-content .card-title{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:1.25rem;margin:0;}
.card-content .card-subtitle{font-weight:400;font-size:.875rem;color:var(--text-muted);margin:0;}
.card-image{width:100%;height:400px;border-radius:.5rem;background-size:cover;background-position:center;background-repeat:no-repeat;background-color:#282828;}
.card-btn{width:30%;padding:0 1.25rem;height:40px;background:var(--secondary-dark);border-radius:.5rem;border:none;color:white;cursor:pointer;font-size:.9rem;font-weight:500;transition:background .2s;}
.card-btn:hover{background:var(--primary);}
.no-results-msg{text-align:center;color:var(--text-muted);padding:2rem;font-size:1.1rem;}
</style>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=Playfair+Display:wght@700&family=Inter:wght@400;500&display=swap" rel="stylesheet">

<div class="champ-container">
    <main class="champ-main">
        <div><h1>Official Championships</h1></div>

        <!-- Filters row — dropdown + search, exactly like original -->
        <div class="filters-row">
            <select id="sports-dropdown">
                <option value="All">All</option>
                <?php foreach ($names as $n): ?>
                    <option value="<?= htmlspecialchars($n) ?>" <?= ($sport === $n)?'selected':'' ?>>
                        <?= htmlspecialchars($n) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="search-box">
                <div class="search-icon"><i class="bi bi-search"></i></div>
                <input id="champSearch" type="text" placeholder="Search championship...">
            </div>
        </div>

        <!-- Cards grid -->
        <div id="cards-grid" class="cards-grid">
            <?php
            // Build flat list with sport info for JS filtering
            $allChamps = [];
            foreach ($grouped as $sportName => $champs) {
                foreach ($champs as $ch) {
                    $allChamps[] = [
                        'id'    => $ch['id'],
                        'name'  => $ch['name'],
                        'image' => $ch['image'] ? (BASE_URL.'/'.$ch['image']) : '',
                        'sport' => $sportName,
                    ];
                }
            }
            foreach ($allChamps as $ch):
            ?>
            <div class="card"
                 data-sport="<?= htmlspecialchars($ch['sport']) ?>"
                 data-name="<?= htmlspecialchars(strtolower($ch['name'])) ?>">
                <div class="card-image"
                     style="background-image:url('<?= htmlspecialchars($ch['image']) ?>')">
                </div>
                <div class="card-content">
                    <p class="card-title"><?= htmlspecialchars($ch['name']) ?></p>
                    <p class="card-subtitle"><?= htmlspecialchars($ch['sport']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="no-results-msg" id="noResults" style="display:none;">No championships found.</div>
    </main>
</div>

<script>
const dropdown    = document.getElementById('sports-dropdown');
const searchInput = document.getElementById('champSearch');
const cards       = Array.from(document.querySelectorAll('#cards-grid .card'));
const noResults   = document.getElementById('noResults');

function filterCards() {
    const sport  = dropdown.value;
    const search = searchInput.value.toLowerCase().trim();
    let vis = 0;
    cards.forEach(card => {
        const matchSport  = sport === 'All' || card.dataset.sport === sport;
        const matchSearch = !search || card.dataset.name.includes(search);
        const show = matchSport && matchSearch;
        card.style.display = show ? '' : 'none';
        if (show) vis++;
    });
    noResults.style.display = vis === 0 ? 'block' : 'none';
}

dropdown.addEventListener('change', filterCards);
searchInput.addEventListener('input', filterCards);
</script>
