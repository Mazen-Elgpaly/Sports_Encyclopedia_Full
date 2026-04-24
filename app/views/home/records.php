<?php $extraCss = ['global_records.css']; ?>

<div class="layout-container">
    <div class="layout-inner">
        <div class="main">
            <h1 class="page-title">🏅 Global Records</h1>

            <!-- Controls -->
            <div class="controls-panel">
                <div class="filters-row">
                    <div class="filter">
                        <label>Sport / Category</label>
                        <select id="filterCategory">
                            <option value="all">All</option>
                            <?php foreach ($sportNames as $s): ?>
                                <option value="<?= htmlspecialchars($s['name']) ?>" <?= ($sport === $s['name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter">
                        <label>Search</label>
                        <input id="globalSearch" type="text" placeholder="Athlete, record, specialty..."
                               value="<?= htmlspecialchars($search ?? '') ?>">
                    </div>
                    <div class="filter">
                        <button class="btn" onclick="applyFilters()">Filter</button>
                    </div>
                    <div class="filter">
                        <a href="<?= BASE_URL ?>/records" class="btn">Clear</a>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table class="records-table">
                    <thead>
                        <tr>
                            <th>Sport</th>
                            <th>Specialty</th>
                            <th>Athlete</th>
                            <th>Record</th>
                            <th>Date</th>
                            <th>Country</th>
                            <th class="details-col">Details</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php foreach ($filtered as $sportName => $records):
                              foreach ($records as $rec): ?>
                        <tr data-category="<?= htmlspecialchars($sportName) ?>"
                            data-athlete="<?= htmlspecialchars(strtolower($rec['athlete_name'])) ?>"
                            data-record="<?= htmlspecialchars(strtolower($rec['record_text'])) ?>"
                            data-specialty="<?= htmlspecialchars(strtolower($rec['specialty'] ?? '')) ?>">
                            <td class="text-primary small"><?= htmlspecialchars($sportName) ?></td>
                            <td><?= htmlspecialchars($rec['specialty'] ?? '—') ?></td>
                            <td class="text-primary"><?= htmlspecialchars($rec['athlete_name']) ?></td>
                            <td><?= htmlspecialchars($rec['record_text']) ?></td>
                            <td class="small gray-muted"><?= $rec['record_date'] ? date('M j, Y', strtotime($rec['record_date'])) : '—' ?></td>
                            <td class="small"><?= htmlspecialchars($rec['country_name'] ?? '—') ?></td>
                            <td class="details-col">
                                <button class="btn details-btn"
                                    data-details='<?= htmlspecialchars(json_encode(array_filter([
                                        'Age'                 => $rec['age'] ?? null,
                                        'Team'                => $rec['team'] ?? null,
                                        'Height'              => $rec['height'] ?? null,
                                        'Weight'              => $rec['weight'] ?? null,
                                        'Olympic Golds'       => $rec['olympic_golds'] ?? null,
                                        'World Championships' => $rec['world_championships'] ?? null,
                                        'Career Wins'         => $rec['career_wins'] ?? null,
                                        'World Ranking'       => $rec['world_ranking'] ?? null,
                                        'Olympic Medals'      => $rec['olympic_medals'] ?? null,
                                        'World Cup Wins'      => $rec['world_cup_wins'] ?? null,
                                        'Retired'             => $rec['is_retired'] ? 'Yes' : null,
                                        'Achievements'        => !empty($rec['extra']['achievements']) ? implode(', ', $rec['extra']['achievements']) : null,
                                        'Specialties'         => !empty($rec['extra']['specialties']) ? implode(', ', $rec['extra']['specialties']) : null,
                                        'Classic Wins'        => !empty($rec['extra']['classic_wins']) ? implode(', ', $rec['extra']['classic_wins']) : null,
                                    ], fn($v) => $v !== null && $v !== '' && $v !== false), JSON_UNESCAPED_UNICODE)) ?>'
                                    onclick="openPanel(this)">Details</button>
                            </td>
                        </tr>
                        <?php endforeach; endforeach; ?>
                        <?php if (empty($filtered)): ?>
                            <tr><td colspan="7" class="loading">No records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Side panel -->
<div class="side-panel closed" id="sidePanel">
    <div class="side-header">
        <strong>Record Details</strong>
        <button class="btn" onclick="document.getElementById('sidePanel').classList.add('closed')">✕</button>
    </div>
    <div class="side-content" id="sideContent"></div>
</div>

<script>
function openPanel(btn) {
    const details = JSON.parse(btn.dataset.details);
    const content = document.getElementById('sideContent');
    content.innerHTML = '';
    Object.entries(details).forEach(([k, v]) => {
        if (!v && v !== 0) return;
        const div = document.createElement('div');
        div.className = 'detail-row';
        div.innerHTML = `<b>${k}</b><span>${v}</span>`;
        content.appendChild(div);
    });
    document.getElementById('sidePanel').classList.remove('closed');
}

function applyFilters() {
    const cat    = document.getElementById('filterCategory').value;
    const search = document.getElementById('globalSearch').value.toLowerCase().trim();
    document.querySelectorAll('#tableBody tr[data-category]').forEach(row => {
        const matchCat    = cat === 'all' || row.dataset.category === cat;
        const matchSearch = !search || row.dataset.athlete.includes(search) || row.dataset.record.includes(search) || row.dataset.specialty.includes(search);
        row.style.display = (matchCat && matchSearch) ? '' : 'none';
    });
}

document.getElementById('filterCategory').addEventListener('change', applyFilters);
document.getElementById('globalSearch').addEventListener('input', applyFilters);
</script>
