<?php $extraCss = ['compare-sports.css']; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<div class="container">
    <div class="compare-hero">
        <h1>Compare Sports</h1>
        <p>Select up to 10 sports to compare rules, skills, and popularity trends side by side.</p>
    </div>

    <!-- Controls: count selector + sport selects injected by JS -->
    <div class="compare-header" id="compareHeader">
        <select id="compareCount"></select>
        <!-- sport selects added by JS -->
    </div>

    <!-- Cards injected by JS -->
    <div class="compare-cards" id="compareCards"></div>
</div>

<script>
/* ── PHP data injected — no JSON fetch ── */
const SPORTS_DATA = <?= json_encode(array_map(fn($s) => [
    'id'    => $s['id'],
    'name'  => $s['name'],
    'image' => $s['logo_image'] ? (BASE_URL . '/' . $s['logo_image']) : '',
    'rules'    => json_decode($s['rules']    ?? '[]', true) ?: [],
    'equipment'=> json_decode($s['equipment'] ?? '[]', true) ?: [],
    'skills' => array_map(fn($sk) => [
        'name'  => $sk['skill_name'],
        'level' => $sk['skill_level'],
    ], $s['skills'] ?? []),
    'chart' => [
        'years'  => array_column($s['chart'] ?? [], 'chart_year'),
        'values' => array_column($s['chart'] ?? [], 'chart_value'),
    ],
], $all), JSON_UNESCAPED_UNICODE) ?>;

const MAX_SPORTS    = 10;
const compareHeader = document.getElementById('compareHeader');
const cardsGrid     = document.getElementById('compareCards');
const compareCount  = document.getElementById('compareCount');

// Populate count dropdown
for (let i = 2; i <= MAX_SPORTS; i++) {
    const opt = document.createElement('option');
    opt.value = i; opt.textContent = i;
    compareCount.appendChild(opt);
}
compareCount.value = 2;
rebuildUI(2);

compareCount.addEventListener('change', e => rebuildUI(parseInt(e.target.value)));

function rebuildUI(count) {
    // Save current selections
    const oldValues = {};
    compareHeader.querySelectorAll("select[id^='sport']").forEach((sel, idx) => {
        oldValues[idx + 1] = sel.value;
    });

    // Clear
    compareHeader.querySelectorAll("select[id^='sport']").forEach(s => s.remove());
    cardsGrid.innerHTML = '';

    for (let i = 1; i <= count; i++) {
        const select = document.createElement('select');
        select.id = `sport${i}Select`;
        select.innerHTML = `<option value="">Select Sport ${i}</option>`;
        SPORTS_DATA.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id; opt.textContent = s.name;
            select.appendChild(opt);
        });
        if (oldValues[i]) select.value = oldValues[i];
        compareHeader.appendChild(select);

        select.addEventListener('change', () => {
            updateSportData(i, select.value);
            updateDisabledOptions();
        });

        const card = document.createElement('div');
        card.classList.add('sport-card', 'card');
        card.id = `sport-card-${i}`;
        card.style.cssText = 'display:none;opacity:0;transition:opacity .6s ease,transform .5s ease;transform:translateY(20px);';
        cardsGrid.appendChild(card);

        if (oldValues[i]) updateSportData(i, oldValues[i]);
    }
    updateDisabledOptions();
}

function updateDisabledOptions() {
    const selected = Array.from(compareHeader.querySelectorAll("select[id^='sport']"))
        .map(s => s.value).filter(v => v !== '');
    compareHeader.querySelectorAll("select[id^='sport']").forEach(select => {
        const cur = select.value;
        Array.from(select.options).forEach(opt => {
            opt.disabled = (opt.value !== '' && opt.value !== cur && selected.includes(opt.value));
        });
    });
}

function updateSportData(slot, id, skipExit = false) {
    const card  = document.getElementById(`sport-card-${slot}`);
    const sport = SPORTS_DATA.find(s => s.id == id);

    if (!id) {
        if (card.chartInstance) { card.chartInstance.destroy(); card.chartInstance = null; }
        card.style.display = 'none'; card.innerHTML = '';
        return;
    }
    if (!sport) return;

    // Animate out if already showing
    if (!skipExit && card.style.display === 'block' && card.style.opacity === '1') {
        card.style.opacity = 0; card.style.transform = 'translateY(20px)';
        card.addEventListener('transitionend', function h(e) {
            if (e.propertyName === 'opacity') { card.removeEventListener('transitionend', h); updateSportData(slot, id, true); }
        });
        return;
    }

    card.style.display = 'block';
    requestAnimationFrame(() => { card.style.opacity = 1; card.style.transform = 'translateY(0)'; });

    card.innerHTML = `
        <div class="card-header">
            <div class="circle-img" data-mask="${sport.image}"></div>
            <h2>${sport.name}</h2>
        </div>
        <div class="card-body">
            <h3 class="section">Rules</h3>
            <ul class="rules">${(sport.rules||[]).map(r=>`<li>${r}</li>`).join('')}</ul>

            <h3 class="section">Equipment</h3>
            <div class="equipment-row">${(sport.equipment||[]).map(eq=>`<div class="eq">${eq}</div>`).join('')}</div>

            <h3 class="section">Skills</h3>
            <div class="skills">
                ${(sport.skills||[]).map((sk,i)=>`
                <div class="skill">
                    <div class="skill-name">${sk.name}</div>
                    <div class="skill-bar">
                        <div class="bar" style="width:0" data-target="${sk.level}"></div>
                        <span class="value">${sk.level}%</span>
                    </div>
                </div>`).join('')}
            </div>

            <h3 class="section chart-title">Popularity Over Time</h3>
            <div class="chart"><canvas id="chart-${slot}"></canvas></div>
        </div>
    `;

    // Apply mask
    const circle = card.querySelector('.circle-img');
    if (circle && circle.dataset.mask) {
        circle.style.mask = `url('${circle.dataset.mask}') center/contain no-repeat`;
        circle.style.webkitMask = `url('${circle.dataset.mask}') center/contain no-repeat`;
    }

    // Animate skill bars
    requestAnimationFrame(() => {
        card.querySelectorAll('.skill-bar').forEach((barWrap, i) => {
            const bar   = barWrap.querySelector('.bar');
            const valEl = barWrap.querySelector('.value');
            const target = sport.skills[i].level;
            let progress = 0;
            setTimeout(() => { bar.style.width = target + '%'; }, i * 150);
            const iv = setInterval(() => {
                if (progress < target) { progress++; valEl.textContent = progress + '%'; }
                else clearInterval(iv);
            }, 15);
        });
    });

    // Chart
    if (card.chartInstance) { card.chartInstance.destroy(); card.chartInstance = null; }
    setTimeout(() => drawChart(card, sport, slot));
}

function drawChart(card, sport, slot) {
    if (!sport.chart || !sport.chart.values || !sport.chart.values.length) return;
    const ctx    = card.querySelector(`#chart-${slot}`);
    if (!ctx) return;
    const values = sport.chart.values;
    const years  = sport.chart.years;
    const color  = slot % 2 === 0 ? '#ffaa00' : '#00e0ff';
    const firstVal = values[0];

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: years,
            datasets: [{
                label: 'Popularity (%)',
                data: values.map(() => firstVal),
                borderColor: color,
                backgroundColor: 'transparent',
                tension: 0.35,
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointRadius: 4,
            }]
        },
        options: {
            animation: { duration: 0 },
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#9aa3a6' }, grid: { display: false } },
                y: { min: firstVal, max: 100, ticks: { color: '#9aa3a6', callback: v => v+'%' }, grid: { color: '#1a1d20' } }
            }
        }
    });
    card.chartInstance = chart;

    let progress = 0;
    const steps = 60;
    const anim = setInterval(() => {
        progress++;
        const factor = 1 - Math.pow(1 - progress/steps, 3);
        chart.data.datasets[0].data = values.map(v => firstVal + (v - firstVal) * factor);
        chart.update('none');
        if (progress >= steps) { clearInterval(anim); chart.data.datasets[0].data = values; chart.update(); }
    }, 1500 / steps);
}
</script>
