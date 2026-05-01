<?php $extraCss = ['dashboard.css']; ?>

<?php
// Prepare data for JS injection
$overviewYears   = json_encode(array_column($overview, 'year'));
$overviewRecords = json_encode(array_column($overview, 'records_count'));
$popLabels       = json_encode(array_column($popularity, 'sport_name'));
$popValues       = json_encode(array_column($popularity, 'popularity_score'));

$rankingJson = json_encode(array_map(fn($r) => [
    'rank'    => $r['rank'],
    'athlete' => $r['athlete_name'],
    'sport'   => $r['sport_name'],
    'metric'  => $r['metric'],
    'year'    => $r['metric_year'],
    'country' => $r['country_name'] ?? '—',
], $ranking), JSON_UNESCAPED_UNICODE);

$sportsJson = json_encode(array_map(fn($sc) => [
    'name'       => $sc['sport_name'],
    'popularity' => $sc['popularity_score'] ?? 0,
    'country'    => 'Global',
    'year'       => 2023,
    'coordinates' => ['lat' => 0, 'lng' => 0],
    'topPlayers'  => $topPlayers[$sc['sport_name']] ?? [],
    'stats' => [
        'totalPlayers'        => $sc['total_players'] ?? 0,
        'professionalLeagues' => $sc['professional_leagues'] ?? 0,
        'worldCupYears'       => $sc['world_cup_years'] ?? 0,
    ],
], $sportCards), JSON_UNESCAPED_UNICODE);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<style>
    :root {
        --bg: #0b0e11;
        --panel: #121416;
        --card: #15171a;
        --muted: #9aa3a6;
        --accent: #00e0ff;
        --accent-2: #6b3cff;
        --card-radius: 14px;
        --gold: #FFD700;
        --silver: #C0C0C0;
        --bronze: #CD7F32;
        --success: #06d6a0;
        --warning: #ffd166;
        --danger: #ef476f;
    }

    .dash-container {
        max-width: 1200px;
        margin: 36px auto;
        padding: 0 20px;
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .page-title {
        font-size: 40px;
        margin: 0;
        color: #fff;
        font-weight: 800;
    }

    .sub {
        color: var(--muted);
        margin-bottom: 12px;
    }

    .filters-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
        padding: 20px;
        background: var(--card);
        border-radius: var(--card-radius);
        box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        font-size: 14px;
        color: var(--muted);
        font-weight: 600;
    }

    .filter-dropdown {
        padding: 12px 16px;
        border-radius: 10px;
        background: #101315;
        border: 1px solid rgba(255, 255, 255, .03);
        color: #e8eef0;
        font-size: 14px;
        cursor: pointer;
        transition: border-color .3s;
        outline: none;
    }

    .filter-dropdown:hover {
        border-color: rgba(0, 224, 255, .2);
    }

    .pill-filters {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .pill {
        background: #101315;
        border: 1px solid rgba(255, 255, 255, .03);
        padding: 10px 16px;
        border-radius: 10px;
        color: var(--muted);
        cursor: pointer;
        font-weight: 600;
        transition: all .3s ease;
    }

    .pill:hover {
        background: rgba(255, 255, 255, .05);
        color: #fff;
    }

    .pill.active {
        background: var(--accent);
        color: #000;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .stat-card {
        background: var(--card);
        padding: 20px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
        transition: transform .3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-icon {
        font-size: 24px;
        color: var(--accent);
        margin-bottom: 10px;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: #fff;
        margin: 10px 0;
    }

    .stat-label {
        font-size: 14px;
        color: var(--muted);
    }

    .overview-grid {
        display: flex;
        gap: 20px;
        align-items: stretch;
        flex-wrap: wrap;
    }

    .card {
        background: var(--card);
        padding: 24px;
        border-radius: var(--card-radius);
        flex: 1;
        min-width: 260px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .35);
    }

    .chart-card {
        min-width: 300px;
        flex-basis: 45%;
    }

    .metric-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex-basis: 25%;
    }

    .donut-card {
        flex-basis: 28%;
    }

    .card-title {
        font-size: 18px;
        color: #dce9eb;
        margin-bottom: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: var(--accent);
    }

    .chart-container {
        height: 160px;
        background: #0e1214;
        border-radius: 10px;
        position: relative;
    }

    .chart-foot {
        color: var(--muted);
        font-size: 13px;
        margin-top: 10px;
    }

    .big-number {
        font-size: 48px;
        font-weight: 800;
        color: #fff;
        margin: 10px 0;
    }

    .small-note {
        color: var(--muted);
        font-size: 14px;
    }

    .delta {
        color: var(--success);
        font-weight: 600;
    }

    .donut {
        height: 180px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .legend {
        margin-top: 10px;
        color: var(--muted);
        font-size: 13px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }

    .table-section {
        margin-top: 18px;
    }

    .table-card {
        padding: 8px;
    }

    .rank-table {
        width: 100%;
        border-collapse: collapse;
    }

    .rank-table thead th {
        background: #0f1315;
        color: var(--muted);
        padding: 18px;
        text-align: left;
        font-size: 13px;
    }

    .rank-table tbody td {
        background: transparent;
        padding: 18px;
        border-top: 1px solid rgba(255, 255, 255, .03);
        color: #ddd;
    }

    .rank-table tbody tr {
        transition: transform .2s, background .2s;
    }

    .rank-table tbody tr:hover {
        background: rgba(255, 255, 255, .03);
        transform: translateX(4px);
    }

    .ordered-list-container {
        margin-top: 30px;
    }

    .ordered-list-title {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 15px;
    }

    .sports-ordered-list {
        counter-reset: list-counter;
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .sports-ordered-list li {
        counter-increment: list-counter;
        padding: 20px;
        margin-bottom: 12px;
        background: var(--card);
        border-radius: 10px;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .sports-ordered-list li:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, .3);
    }

    .sports-ordered-list li::before {
        content: counter(list-counter);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-weight: 800;
        margin-right: 20px;
        font-size: 18px;
        flex-shrink: 0;
    }

    .sports-ordered-list li:nth-child(1)::before {
        background: var(--gold);
        color: #000;
        box-shadow: 0 0 15px rgba(255, 215, 0, .4);
    }

    .sports-ordered-list li:nth-child(2)::before {
        background: var(--silver);
        color: #000;
        box-shadow: 0 0 15px rgba(192, 192, 192, .4);
    }

    .sports-ordered-list li:nth-child(3)::before {
        background: var(--bronze);
        color: #fff;
        box-shadow: 0 0 15px rgba(205, 127, 50, .4);
    }

    .sports-ordered-list li:nth-child(n+4)::before {
        background: rgba(255, 255, 255, .1);
        color: #fff;
    }

    .sport-info {
        flex-grow: 1;
    }

    .sport-name {
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
        font-size: 18px;
    }

    .sport-details {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        font-size: 14px;
        color: var(--muted);
    }

    .players-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .player-card {
        background: var(--card);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
        transition: transform .3s ease;
    }

    .player-card:hover {
        transform: translateY(-5px);
    }

    .player-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .player-rank {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .player-rank.gold {
        background: var(--gold);
        color: #000;
    }

    .player-rank.silver {
        background: var(--silver);
        color: #000;
    }

    .player-rank.bronze {
        background: var(--bronze);
        color: #fff;
    }

    .player-rank.other {
        background: rgba(255, 255, 255, .1);
        color: #fff;
    }

    .player-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 15px;
    }

    .stat-item {
        background: rgba(255, 255, 255, .03);
        padding: 10px;
        border-radius: 8px;
    }

    .stat-value-small {
        font-weight: 700;
        color: #fff;
        font-size: 16px;
    }

    .stat-label-small {
        color: var(--muted);
        font-size: 12px;
        margin-top: 4px;
    }

    .no-data {
        text-align: center;
        padding: 40px;
        color: var(--muted);
        font-style: italic;
    }

    @media(max-width:900px) {
        .overview-grid {
            flex-direction: column;
        }

        .chart-card,
        .donut-card,
        .metric-card {
            flex-basis: 100%;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<main class="dash-container">
    <section class="hero-dashboard">
        <h1 class="page-title">Sports Statistics Dashboard</h1>
        <p class="sub">Dynamic Sports Insights with Real-time Data Filtering</p>

        <div class="filters-container">
            <div class="filter-group">
                <div class="filter-label"><i class="fas fa-calendar"></i> Select Year</div>
                <select class="filter-dropdown" id="yearFilter">
                    <option value="all">All Years</option>
                </select>
            </div>
            <div class="filter-group">
                <div class="filter-label"><i class="fas fa-globe"></i> Select Country</div>
                <select class="filter-dropdown" id="countryFilter">
                    <option value="all">All Countries</option>
                </select>
            </div>
            <div class="filter-group">
                <div class="filter-label"><i class="fas fa-running"></i> Select Sport</div>
                <select class="filter-dropdown" id="sportFilter">
                    <option value="all">All Sports</option>
                </select>
            </div>
        </div>
        <div class="pill-filters"></div>
    </section>

    <div id="content">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-running"></i></div>
                <div class="stat-value" id="totalSports">0</div>
                <div class="stat-label">Total Sports</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-globe"></i></div>
                <div class="stat-value" id="totalCountries">0</div>
                <div class="stat-label">Countries</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-value" id="totalPlayers">0</div>
                <div class="stat-label">Total Players</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-value" id="avgYear">0</div>
                <div class="stat-label">Avg. Year</div>
            </div>
        </div>

        <section class="overview-grid">
            <div class="card chart-card">
                <h3 class="card-title"><i class="fas fa-chart-line"></i> Record Trends</h3>
                <div class="chart-container"><canvas id="chart-records"></canvas></div>
                <div class="chart-foot">2020 — 2024</div>
            </div>
            <div class="card metric-card">
                <h3 class="card-title"><i class="fas fa-trophy"></i> Top Athlete</h3>
                <div class="big-number" id="topAthleteMetric">—</div>
                <div class="small-note" id="topAthleteDetail">Loading...</div>
            </div>
            <div class="card donut-card">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Sports Popularity</h3>
                <div class="donut"><canvas id="donut-pop"></canvas></div>
                <div class="legend" id="popLegend"></div>
            </div>
        </section>

        <section class="player-stats-section">
            <h3 class="ordered-list-title"><i class="fas fa-users"></i> Top Players by Sport</h3>
            <div class="players-grid" id="playersGrid"></div>
        </section>

        <section class="table-section">
            <div class="table-card card">
                <h3 class="card-title"><i class="fas fa-list-ol"></i> Top Athletes Ranking</h3>
                <table class="rank-table">
                    <thead>
                        <tr>
                            <th>RANK</th>
                            <th>ATHLETE</th>
                            <th>SPORT</th>
                            <th>METRIC</th>
                            <th>YEAR</th>
                            <th>COUNTRY</th>
                        </tr>
                    </thead>
                    <tbody id="rankingTable"></tbody>
                </table>
            </div>
        </section>

        <section class="ordered-list-container">
            <h3 class="ordered-list-title"><i class="fas fa-medal"></i> Top Sports by Popularity</h3>
            <ol class="sports-ordered-list" id="sportsOrderedList"></ol>
        </section>
    </div>
</main>

<script>
    const sportsData = {
        overview: {
            years: <?= $overviewYears ?>,
            records: <?= $overviewRecords ?>,
            popularity: {
                labels: <?= $popLabels ?>,
                values: <?= $popValues ?>
            }
        },
        ranking: <?= $rankingJson ?>,
        sports: <?= $sportsJson ?>
    };

    document.addEventListener('DOMContentLoaded', () => {
        const yearFilter = document.getElementById('yearFilter');
        const countryFilter = document.getElementById('countryFilter');
        const sportFilter = document.getElementById('sportFilter');
        const pillFilters = document.querySelector('.pill-filters');
        const rankingTable = document.getElementById('rankingTable');
        const orderedList = document.getElementById('sportsOrderedList');
        const playersGrid = document.getElementById('playersGrid');

        let lineChart = null,
            donutChart = null;

        /* ── Charts ── */
        const ctxLine = document.getElementById('chart-records').getContext('2d');
        lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: sportsData.overview.years,
                datasets: [{
                    label: 'Records',
                    data: sportsData.overview.records,
                    borderColor: '#00e0ff',
                    backgroundColor: 'rgba(0,224,255,.1)',
                    fill: true,
                    tension: .4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#9aa3a6'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        ticks: {
                            color: '#9aa3a6'
                        },
                        grid: {
                            color: '#1a1d20'
                        }
                    }
                }
            }
        });

        const ctxDonut = document.getElementById('donut-pop').getContext('2d');
        donutChart = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: sportsData.overview.popularity.labels,
                datasets: [{
                    data: sportsData.overview.popularity.values,
                    backgroundColor: ['#00e0ff', '#6b3cff', '#ffd166', '#ff5c8d', '#06d6a0', '#118ab2'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        /* ── Build legend ── */
        const colors = ['#00e0ff', '#6b3cff', '#ffd166', '#ff5c8d', '#06d6a0', '#118ab2'];
        const legend = document.getElementById('popLegend');
        sportsData.overview.popularity.labels.slice(0, 6).forEach((l, i) => {
            legend.innerHTML += `<div class="legend-item"><span class="dot" style="background:${colors[i]}"></span> ${l}</div>`;
        });

        /* ── Populate dropdowns ── */
        const years = [...new Set(sportsData.ranking.map(r => r.year))].sort((a, b) => b - a);
        const countries = [...new Set([...sportsData.ranking.map(r => r.country), ...sportsData.sports.map(s => s.country)])].sort();
        const sports = sportsData.sports.map(s => s.name).sort();

        years.forEach(y => {
            const o = document.createElement('option');
            o.value = y;
            o.textContent = y;
            yearFilter.appendChild(o);
        });
        countries.forEach(c => {
            const o = document.createElement('option');
            o.value = c;
            o.textContent = c;
            countryFilter.appendChild(o);
        });
        sports.forEach(s => {
            const o = document.createElement('option');
            o.value = s;
            o.textContent = s;
            sportFilter.appendChild(o);
        });

        /* ── Pill filters ── */
        const allPill = document.createElement('button');
        allPill.className = 'pill active';
        allPill.dataset.sport = 'all';
        allPill.innerHTML = '<i class="fas fa-list"></i> All Sports';
        pillFilters.appendChild(allPill);
        sportsData.sports.slice(0, 8).forEach(sport => {
            const pill = document.createElement('button');
            pill.className = 'pill';
            pill.dataset.sport = sport.name;
            let icon = 'fas fa-running';
            if (sport.name.includes('Football')) icon = 'fas fa-futbol';
            else if (sport.name.includes('Basketball')) icon = 'fas fa-basketball-ball';
            else if (sport.name.includes('Tennis')) icon = 'fas fa-baseball-ball';
            pill.innerHTML = `<i class="${icon}"></i> ${sport.name}`;
            pillFilters.appendChild(pill);
        });

        /* ── Event listeners ── */
        [yearFilter, countryFilter, sportFilter].forEach(el => el.addEventListener('change', applyFilters));
        document.querySelectorAll('.pill').forEach(pill => {
            pill.addEventListener('click', () => {
                document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                sportFilter.value = pill.dataset.sport;
                applyFilters();
            });
        });

        applyFilters();

        function applyFilters() {
            const yr = yearFilter.value,
                co = countryFilter.value,
                sp = sportFilter.value;
            const filtRanking = sportsData.ranking.filter(r => (yr === 'all' || r.year == yr) && (co === 'all' || r.country === co) && (sp === 'all' || r.sport === sp));
            const filtSports = sportsData.sports.filter(s => (yr === 'all' || s.year == yr) && (co === 'all' || s.country === co) && (sp === 'all' || s.name === sp));
            renderRanking(filtRanking);
            renderOrderedList(filtSports);
            renderPlayers(sp === 'all' ? sportsData.sports : filtSports);
            updateStats(filtSports, filtRanking);
        }

        function updateStats(fs, fr) {
            document.getElementById('totalSports').textContent = fs.length;
            document.getElementById('totalCountries').textContent = [...new Set(fs.map(s => s.country))].length;
            document.getElementById('totalPlayers').textContent = fs.reduce((s, sp) => s + (sp.topPlayers ? sp.topPlayers.length : 0), 0);
            const avg = fs.length ? (fs.reduce((s, sp) => s + sp.year, 0) / fs.length).toFixed(1) : '0';
            document.getElementById('avgYear').textContent = avg;
            if (fr.length > 0) {
                document.getElementById('topAthleteMetric').textContent = fr[0].metric;
                document.getElementById('topAthleteDetail').innerHTML = `${fr[0].athlete} - ${fr[0].sport} <span class="delta">${fr[0].year}</span>`;
            } else {
                document.getElementById('topAthleteMetric').textContent = 'N/A';
                document.getElementById('topAthleteDetail').textContent = 'No data';
            }
        }

        function renderRanking(list) {
            rankingTable.innerHTML = '';
            if (!list.length) {
                rankingTable.innerHTML = '<tr><td colspan="6" class="no-data">No matching records found</td></tr>';
                return;
            }
            list.slice(0, 20).forEach(item => {
                const color = item.rank === 1 ? 'var(--gold)' : item.rank === 2 ? 'var(--silver)' : item.rank === 3 ? 'var(--bronze)' : '#ddd';
                const fw = item.rank <= 3 ? 700 : 400;
                const tr = document.createElement('tr');
                tr.innerHTML = `<td style="color:${color};font-weight:${fw};">${item.rank}</td><td>${item.athlete}</td><td>${item.sport}</td><td>${item.metric}</td><td>${item.year}</td><td>${item.country}</td>`;
                rankingTable.appendChild(tr);
            });
        }

        function renderOrderedList(list) {
            orderedList.innerHTML = '';
            if (!list.length) {
                orderedList.innerHTML = '<li class="no-data">No matching sports found</li>';
                return;
            }
            [...list].sort((a, b) => b.popularity - a.popularity).slice(0, 10).forEach(sport => {
                const li = document.createElement('li');
                const players = sport.topPlayers ? sport.topPlayers.length : 0;
                li.innerHTML = `<div class="sport-info"><div class="sport-name">${sport.name}</div><div class="sport-details"><span><i class="fas fa-globe"></i> ${sport.country}</span><span><i class="fas fa-calendar"></i> ${sport.year}</span><span><i class="fas fa-users"></i> ${players} players</span></div></div><div style="font-weight:800;color:var(--accent);font-size:20px;">${sport.popularity}%</div>`;
                orderedList.appendChild(li);
            });
        }

        function renderPlayers(list) {
            playersGrid.innerHTML = '';
            if (!list.length) {
                playersGrid.innerHTML = '<div class="no-data">No sports data available</div>';
                return;
            }
            list.slice(0, 3).forEach(sport => {
                if (!sport.topPlayers || !sport.topPlayers.length) return;
                const hdr = document.createElement('div');
                hdr.style.gridColumn = '1/-1';
                hdr.style.margin = '20px 0 10px';
                hdr.innerHTML = `<h4 style="color:#fff;font-size:18px;margin:0;"><i class="fas fa-running"></i> ${sport.name} — Top Players</h4>`;
                playersGrid.appendChild(hdr);
                sport.topPlayers.slice(0, 6).forEach(player => {
                    const rankClass = player.rank === 1 ? 'gold' : player.rank === 2 ? 'silver' : player.rank === 3 ? 'bronze' : 'other';
                    const card = document.createElement('div');
                    card.className = 'player-card';
                    const m = player.metrics || {};
                    card.innerHTML = `<div class="player-header"><div class="player-rank ${rankClass}">${player.rank}</div><div><div class="player-name">${player.player_name}</div><div class="player-sport">${player.country||''}</div></div></div><div class="player-stats">${m.goals!==undefined?`<div class="stat-item"><div class="stat-value-small">${m.goals}</div><div class="stat-label-small">Goals</div></div>`:''}${m.assists!==undefined?`<div class="stat-item"><div class="stat-value-small">${m.assists}</div><div class="stat-label-small">Assists</div></div>`:''}${m.matches!==undefined?`<div class="stat-item"><div class="stat-value-small">${m.matches}</div><div class="stat-label-small">Matches</div></div>`:''}${player.age!==undefined?`<div class="stat-item"><div class="stat-value-small">${player.age}</div><div class="stat-label-small">Age</div></div>`:''}</div>`;
                    playersGrid.appendChild(card);
                });
            });
        }
    });
</script>