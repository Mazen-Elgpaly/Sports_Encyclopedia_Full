<?php $extraCss = ['compare-athletes.css']; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<?php
// Build athletes array for JS — same shape as original JSON
$athletesForJs = array_map(fn($a) => [
    'id'          => $a['slug'],
    'name'        => $a['name'],
    'sport'       => $a['sport_name'],
    'country'     => $a['country_name'] ?? '',
    'image'       => $a['image']  ? (BASE_URL.'/'.$a['image'])  : '',
    'banner'      => $a['banner'] ? (BASE_URL.'/'.$a['banner']) : '',
    'chart_about' => $a['chart_about'] ?? 'Performance Chart',
    'stats'       => array_map(fn($s) => ['label'=>$s['stat_label'],'value'=>$s['stat_value']], $a['stats'] ?? []),
    'chart'       => array_column($a['chart'] ?? [], 'chart_value'),
    'chart_years' => array_column($a['chart'] ?? [], 'chart_year'),
    'timeline'    => array_map(fn($t) => ['year'=>$t['event_year'],'event'=>$t['event_text']], $a['timeline'] ?? []),
], $allAthletes);
?>

<main class="container">
    <section class="compare-hero">
        <h1>Compare Athletes</h1>
        <p>Select two athletes to see a side-by-side comparison of their career stats, achievements, and more.</p>

        <!-- ── Controls row: exactly matches original HTML structure ── -->
        <div class="compare-header">
            <div class="sport-filter">
                <label>Choose Sport:</label>
                <select id="sportSelect">
                    <option value="all">All Sports</option>
                    <?php foreach ($sportNames as $s): ?>
                        <option value="<?= htmlspecialchars($s['name']) ?>"
                            <?= ($sport === $s['name']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="compare-controls">
                <label for="compareCount">Number of Players:</label>
                <select id="compareCount"></select>
                <button class="resetbtn" style="display:none;">reset</button>
            </div>
            <!-- athlete selects injected by JS here, inside .compare-header -->
        </div>

        <div class="compare-container">
            <div class="athlete-card" id="athlete1Card"></div>
            <div class="athlete-card" id="athlete2Card"></div>
        </div>
    </section>

    <!-- Profiles row -->
    <section class="profiles">
        <!-- injected by JS -->
    </section>

    <!-- Tabs -->
    <div class="tabs" style="display:none;">
        <button class="tab active">Career Stats</button>
        <button class="tab">Achievements</button>
        <button class="tab">Head to Head</button>
    </div>

    <!-- Tab panes -->
    <div id="tab-stats"        class="tab-pane active"><section class="stats-grid"></section></div>
    <div id="tab-achievements" class="tab-pane"></div>
    <div id="tab-headtohead"   class="tab-pane"></div>
</main>

<script>
/* ════ PHP data — no JSON fetch needed ════ */
const athletes_raw = <?= json_encode($athletesForJs, JSON_UNESCAPED_UNICODE) ?>;
const BASE_URL_PHP = '<?= BASE_URL ?>';
const INIT_SPORT   = '<?= htmlspecialchars($sport ?? 'all') ?>';
const INIT_COUNT   = <?= (int)$count ?>;
const INIT_SLUGS   = <?= json_encode(array_values(array_map(fn($a) => $a ? $a['slug'] : null, $selected)), JSON_UNESCAPED_UNICODE) ?>;

/* ════ Exact replica of original compare-athletes.js ════ */
document.addEventListener("DOMContentLoaded", () => {
    const MAX_PLAYERS    = 20;
    const compareCount   = document.getElementById("compareCount");
    const compareHeader  = document.querySelector(".compare-header");
    const statsGrid      = document.querySelector(".stats-grid");
    const tabsContainer  = document.querySelector(".tabs");
    const profiles       = document.querySelector(".profiles");
    const sportSelect    = document.getElementById("sportSelect");

    let athletes = [];
    let filteredAthletes = [];
    let currentSport = "all";
    let currentCount = 2;
    let selectedAthletesBySport = {};
    let chartInstances = {};

    // Load data (from PHP, not fetch)
    athletes = athletes_raw;

    // Build sport options (already in HTML from PHP, but keep JS in sync)
    const sports = [...new Set(athletes.map(a => a.sport))];
    // sportSelect already has options from PHP

    // Restore initial sport filter
    if (INIT_SPORT && INIT_SPORT !== 'all') {
        currentSport = INIT_SPORT;
        sportSelect.value = INIT_SPORT;
    }
    applySportFilter();

    // Build count selector
    for (let i = 2; i <= MAX_PLAYERS; i++) {
        const opt = document.createElement("option");
        opt.value = i; opt.textContent = i;
        compareCount.appendChild(opt);
    }
    compareCount.value = INIT_COUNT > 2 ? INIT_COUNT : 2;
    currentCount = parseInt(compareCount.value);

    compareCount.addEventListener("change", () => {
        const newCount = parseInt(compareCount.value);
        adjustSlots(newCount);
    });

    initTabs();
    rebuildUI(currentCount, false);

    // Restore selections from URL params
    INIT_SLUGS.forEach((slug, idx) => {
        if (!slug) return;
        const i = idx + 1;
        if (!selectedAthletesBySport[currentSport]) selectedAthletesBySport[currentSport] = {};
        selectedAthletesBySport[currentSport][i] = slug;
        const sel = document.getElementById(`athlete${i}Select`);
        if (sel) { sel.value = slug; updateAthleteData(i, slug); }
    });
    rebuildDropdowns();
    refreshAchievements();
    updateResetButtonVisibility();

    localStorage.removeItem("selectedSport");

    /* ─── Functions ─────────────────────────────────────────── */
    function applySportFilter() {
        if (currentSport === "all") {
            filteredAthletes = [...athletes];
        } else {
            filteredAthletes = athletes.filter(a => a.sport === currentSport);
        }
    }

    sportSelect.addEventListener("change", () => {
        currentSport = sportSelect.value;
        applySportFilter();
        const oldSelects = compareHeader.querySelectorAll("select[id^='athlete']");
        oldSelects.forEach(s => s.remove());
        if (statsGrid) statsGrid.innerHTML = "";
        profiles.innerHTML = "";
        rebuildUI(currentCount, true);
        setTimeout(() => refreshAchievements(), 100);
        pushURL();
    });

    function adjustSlots(newCount) {
        if (newCount === currentCount) return;
        if (!selectedAthletesBySport[currentSport]) selectedAthletesBySport[currentSport] = {};

        const allSelects = compareHeader.querySelectorAll("select[id^='athlete']");
        const allCards   = statsGrid ? statsGrid.querySelectorAll(".stat-card") : [];
        const allProfs   = profiles.querySelectorAll(".profile");

        for (let i = allSelects.length; i > newCount; i--) {
            selectedAthletesBySport[currentSport][i] = null;
            if (allSelects[i-1]) allSelects[i-1].remove();
            if (allCards[i-1])   allCards[i-1].remove();
            if (allProfs[i-1])   allProfs[i-1].remove();
        }
        for (let i = allSelects.length + 1; i <= newCount; i++) addSlot(i);
        currentCount = newCount;
        refreshAchievements();
        pushURL();
    }

    function addSlot(i) {
        if (!selectedAthletesBySport[currentSport]) selectedAthletesBySport[currentSport] = {};

        const select = document.createElement("select");
        select.id = `athlete${i}Select`;
        // Insert BEFORE the .compare-controls div so selects stay in correct order
        const controls = compareHeader.querySelector('.compare-controls');
        compareHeader.insertBefore(select, controls);

        select.addEventListener("change", () => {
            selectedAthletesBySport[currentSport][i] = select.value || null;
            updateAthleteData(i, select.value);
            rebuildDropdowns();
            updateResetButtonVisibility();
            refreshAchievements();
            pushURL();
        });

        rebuildDropdowns();

        // Profile placeholder
        const profile = document.createElement("div");
        profile.classList.add("profile");
        profile.innerHTML = `<div class="avatar" style="display:none;"><img src="" alt=""></div><div class="name"></div>`;
        profiles.appendChild(profile);

        // Stat card placeholder
        if (statsGrid) {
            const card = document.createElement("div");
            card.classList.add("card", "stat-card");
            card.id = `stats-card-${i}`;
            card.style.display = "none";
            card.innerHTML = `
                <div class="banner-container"><img class="banner" src="" alt=""></div>
                <h3 class="player-name"></h3>
                <ul class="stat-list" id="stats-list-${i}"></ul>
            `;
            statsGrid.appendChild(card);
        }
    }

    function rebuildUI(count, rebuildPlayersOnly) {
        currentCount = count;
        if (!selectedAthletesBySport[currentSport]) selectedAthletesBySport[currentSport] = {};

        if (rebuildPlayersOnly) {
            compareHeader.querySelectorAll("select[id^='athlete']").forEach(s => s.remove());
            if (statsGrid) statsGrid.innerHTML = "";
            profiles.innerHTML = "";
        }

        for (let i = 1; i <= count; i++) {
            addSlot(i);
            const sel = document.getElementById(`athlete${i}Select`);
            if (sel && selectedAthletesBySport[currentSport][i]) {
                sel.value = selectedAthletesBySport[currentSport][i];
                updateAthleteData(i, sel.value);
            }
        }
        rebuildDropdowns();
        refreshAchievements();
        updateResetButtonVisibility();
    }

    function rebuildDropdowns() {
        const allSelects = compareHeader.querySelectorAll("select[id^='athlete']");
        allSelects.forEach((select, idx) => {
            const slotIndex = idx + 1;
            const usedIds = Array.from(allSelects)
                .filter((s, j) => j !== idx).map(s => s.value).filter(v => v);

            select.innerHTML = `<option value="">Select Athlete ${slotIndex}</option>`;
            filteredAthletes.forEach(a => {
                const opt = document.createElement("option");
                opt.value = a.id; opt.textContent = a.name;
                if (usedIds.includes(a.id)) opt.disabled = true;
                select.appendChild(opt);
            });

            const saved = selectedAthletesBySport[currentSport] && selectedAthletesBySport[currentSport][slotIndex];
            if (saved) select.value = saved;
        });
    }

    function initTabs() {
        const tabButtons = document.querySelectorAll('.tab');
        const tabPanes   = ['tab-stats','tab-achievements','tab-headtohead'].map(id => document.getElementById(id));
        tabButtons.forEach((btn, idx) => {
            btn.addEventListener('click', () => {
                tabButtons.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p && p.classList.remove('active'));
                btn.classList.add('active');
                if (tabPanes[idx]) tabPanes[idx].classList.add('active');
            });
        });
    }

    function animateCard(card) {
        if (!card) return;
        card.classList.remove("animated"); void card.offsetWidth; card.classList.add("animated");
    }

    function updateAthleteData(slot, id) {
        const card    = document.getElementById(`stats-card-${slot}`);
        const profile = document.querySelector(`.profiles .profile:nth-child(${slot})`);

        if (!id) {
            if (card)    { card.style.opacity = 0; card.style.transform = "translateY(20px)"; }
            if (profile) profile.style.opacity = 0;
            setTimeout(() => {
                if (card)    { card.style.display = "none"; card.style.opacity = 1; card.style.transform = "translateY(20px)"; }
                if (profile) {
                    const av = profile.querySelector(".avatar"); if (av) av.style.display = "none";
                    const n  = profile.querySelector(".name");   if (n)  n.innerHTML = "";
                    profile.style.opacity = 1;
                }
                refreshAchievements();
            }, 400);
            return;
        }

        const athlete = athletes.find(a => a.id === id);
        if (!athlete) return;

        if (statsGrid) statsGrid.style.display = "grid";
        if (tabsContainer) tabsContainer.style.display = "flex";
        if (card) { card.style.display = "block"; animateCard(card); }

        if (profile) {
            const avatarDiv = profile.querySelector(".avatar");
            const img       = avatarDiv.querySelector("img");
            const nameDiv   = profile.querySelector(".name");
            if (athlete.image) { img.src = athlete.image; img.alt = athlete.name; avatarDiv.style.display = "block"; avatarDiv.classList.add("visible"); }
            nameDiv.innerHTML = `${athlete.name}<div class="sub">${athlete.sport}</div>`;
            profile.style.opacity = 1;
        }

        // Stats list
        const list = document.getElementById(`stats-list-${slot}`);
        if (list) {
            list.innerHTML = "";
            (athlete.stats || []).forEach(s => {
                const li = document.createElement("li");
                li.innerHTML = `<span class="label">${s.label}</span><span class="value">${s.value}</span>`;
                list.appendChild(li);
            });
        }

        if (card) {
            const bannerImg = card.querySelector(".banner");
            if (athlete.banner && bannerImg) bannerImg.src = athlete.banner;
            const nameEl = card.querySelector(".player-name");
            if (nameEl) nameEl.textContent = athlete.name;
        }

        // Chart — ensure only ONE chart container per slot
        if (chartInstances[slot]) { chartInstances[slot].destroy(); chartInstances[slot] = null; }
        // Remove ALL existing chart divs in this card to prevent doubles
        if (card) card.querySelectorAll(".chart").forEach(el => el.remove());

        const chartContainerId = `athlete-chart-${slot}`;
        const chartContainer = document.createElement("div");
        chartContainer.className = "chart";
        chartContainer.id = chartContainerId;
        chartContainer.dataset.player = id;
        if (card) card.appendChild(chartContainer);

        const title = document.createElement("h4");
        title.className = "chart-title";
        title.textContent = athlete.chart_about || "Performance Chart";
        chartContainer.appendChild(title);

        const canvas = document.createElement("canvas");
        chartContainer.appendChild(canvas);

        const years     = athlete.chart_years || [];
        const chartData = athlete.chart       || [];
        const color     = slot % 2 === 0 ? "#ffaa00" : "#00e0ff";

        const chart = new Chart(canvas, {
            type: "bar",
            data: {
                labels: years,
                datasets: [{
                    data: chartData.map(() => 0),
                    backgroundColor: color,
                    borderRadius: 6,
                    barPercentage: 1.0,
                    categoryPercentage: 0.7
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, animation: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, display: false },
                    x: { ticks: { color: "#9aa3a6" }, grid: { display: false } }
                }
            }
        });
        chartInstances[slot] = chart;

        let ci = 0;
        const interval = setInterval(() => {
            if (ci >= chartData.length) { clearInterval(interval); return; }
            chart.data.datasets[0].data[ci] = chartData[ci];
            chart.options.animation = { duration: 1500, easing: "easeOutQuart" };
            chart.update();
            ci++;
        }, 150);
    }

    function refreshAchievements() {
        const achievementsPane = document.getElementById("tab-achievements");
        if (!achievementsPane) return;

        let timelineContainer = achievementsPane.querySelector(".achievements-timeline");
        if (!timelineContainer) {
            timelineContainer = document.createElement("div");
            timelineContainer.classList.add("achievements-timeline");
            achievementsPane.appendChild(timelineContainer);
        }
        timelineContainer.innerHTML = "";

        for (let i = 1; i <= currentCount; i++) {
            const sel = document.getElementById(`athlete${i}Select`);
            if (!sel || !sel.value) continue;
            const athlete = athletes.find(a => a.id === sel.value);
            if (!athlete) continue;

            const playerBlock = document.createElement("div");
            playerBlock.classList.add("ach-player-block");

            const header = document.createElement("div");
            header.classList.add("ach-player-header");
            header.style.cssText = "display:flex;align-items:center;gap:12px;margin-bottom:8px;";

            const img = document.createElement("img");
            img.classList.add("ach-player-img");
            img.src = athlete.image || ""; img.alt = athlete.name;
            img.style.cssText = "width:56px;height:56px;border-radius:50%;object-fit:cover;background:#222;";

            const nameDiv = document.createElement("div");
            nameDiv.innerHTML = `<strong style="color:#fff">${athlete.name}</strong><div class="sub" style="color:#A0A0A0;font-size:.9rem">${athlete.sport}</div>`;

            header.appendChild(img); header.appendChild(nameDiv);

            const eventsWrapper = document.createElement("div");
            eventsWrapper.classList.add("ach-events");

            (athlete.timeline || []).forEach(ev => {
                const evDiv = document.createElement("div");
                evDiv.classList.add("event");
                evDiv.style.cssText = "background:rgba(255,255,255,.02);padding:10px;margin-bottom:8px;border-radius:8px;";
                evDiv.innerHTML = `<span class="year blue" style="color:#0da6f2;font-weight:700;display:block;margin-bottom:6px">${ev.year}</span><div class="ev-text" style="color:#e6f2fb">${ev.event}</div>`;
                eventsWrapper.appendChild(evDiv);
            });

            playerBlock.appendChild(header);
            playerBlock.appendChild(eventsWrapper);
            timelineContainer.appendChild(playerBlock);
        }

        // Head-to-head
        const h2hPane = document.getElementById("tab-headtohead");
        if (!h2hPane) return;
        h2hPane.innerHTML = '';
        const active = [];
        for (let i = 1; i <= currentCount; i++) {
            const sel = document.getElementById(`athlete${i}Select`);
            if (sel && sel.value) active.push(athletes.find(a => a.id === sel.value));
        }
        if (active.length < 2) {
            h2hPane.innerHTML = '<p style="color:#9aa3a6;padding:2rem;text-align:center;">Select at least 2 athletes to see head-to-head comparison.</p>';
            return;
        }
        const statKeys = [];
        active.forEach(a => { (a.stats||[]).forEach(s => { if (!statKeys.includes(s.label)) statKeys.push(s.label); }); });
        const tbl = document.createElement('table');
        tbl.style.cssText = 'width:100%;border-collapse:collapse;';
        tbl.innerHTML = `<thead><tr><th style="padding:14px 16px;background:#0f1315;text-align:left;color:#9aa3a6;">Stat</th>${active.map(a=>`<th style="padding:14px 16px;background:#0f1315;color:#0da6f2;">${a.name}</th>`).join('')}</tr></thead>`;
        const tbody = document.createElement('tbody');
        statKeys.forEach(key => {
            const vals = active.map(a => { const s=(a.stats||[]).find(x=>x.label===key); return s?s.value:null; });
            const max  = Math.max(...vals.filter(v=>v!==null));
            const tr = document.createElement('tr');
            tr.innerHTML = `<td style="padding:12px 16px;border-top:1px solid rgba(255,255,255,.03);color:#9aa3a6;">${key}</td>${vals.map(v=>`<td style="padding:12px 16px;border-top:1px solid rgba(255,255,255,.03);font-weight:${v===max?700:400};color:${v===max?'#0da6f2':'#ddd'};">${v!==null?Number(v).toLocaleString():'—'}</td>`).join('')}`;
            tbody.appendChild(tr);
        });
        tbl.appendChild(tbody);
        h2hPane.appendChild(tbl);
    }

    function updateResetButtonVisibility() {
        const btn = document.querySelector(".resetbtn");
        if (!btn) return;
        const sp  = selectedAthletesBySport[currentSport];
        btn.style.display = (sp && Object.values(sp).some(v => v && v !== '')) ? "inline-block" : "none";
    }

    document.querySelector(".resetbtn")?.addEventListener("click", () => {
        if (selectedAthletesBySport[currentSport]) {
            Object.keys(selectedAthletesBySport[currentSport]).forEach(k => selectedAthletesBySport[currentSport][k] = null);
        }
        compareHeader.querySelectorAll("select[id^='athlete']").forEach(s => s.remove());
        if (statsGrid) statsGrid.innerHTML = "";
        profiles.innerHTML = "";
        Object.values(chartInstances).forEach(c => c && c.destroy());
        chartInstances = {};
        rebuildUI(currentCount, true);
        updateResetButtonVisibility();
        pushURL();
    });

    function pushURL() {
        const params = new URLSearchParams();
        if (currentSport && currentSport !== 'all') params.set('sport', currentSport);
        params.set('count', currentCount);
        const sp = selectedAthletesBySport[currentSport] || {};
        for (let i = 1; i <= currentCount; i++) { if (sp[i]) params.set(`a${i}`, sp[i]); }
        history.replaceState(null, '', `${BASE_URL_PHP}/athletes/compare?${params}`);
    }
});
</script>
