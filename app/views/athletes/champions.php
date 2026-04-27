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
                   
                    <button
                        class="view-profile-btn"
                        data-name="<?= htmlspecialchars($ch['name']) ?>"
                        data-sport="<?= htmlspecialchars($ch['sport_name']) ?>"
                        data-year="<?= htmlspecialchars($ch['champion_year'] ?? '') ?>"
                        data-achievements="<?= htmlspecialchars($ch['achievements'] ?? '') ?>"
                        data-image="<?= htmlspecialchars($ch['image'] ?? '') ?>">
                        View Profile →
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($champions)): ?>
            <p style="color:#9aa3a6;padding:2rem;">No champions found.</p>
        <?php endif; ?>
    </div>
    <div id="championModal" class="champion-modal">

        <div class="modal-backdrop"></div>

        <button class="modal-arrow left" id="prevChampion">❮</button>

        <div class="modal-content">

            <div class="modal-image-box">
                <img id="modalImage" src="" alt="">
            </div>

            <div class="modal-details">
                <h2 id="modalName"></h2>
                <p id="modalSport"></p>
                <p id="modalYear"></p>
                <div id="modalAchievements"></div>
            </div>

        </div>

        <button class="modal-arrow right" id="nextChampion">❯</button>

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

const modal = document.getElementById('championModal');
const backdrop = document.querySelector('.modal-backdrop');
const buttons = Array.from(document.querySelectorAll('.view-profile-btn'));

let currentIndex = 0;

function openChampion(index) {
    currentIndex = index;

    const btn = buttons[index];

    document.getElementById('modalName').textContent =
        btn.dataset.name;

    document.getElementById('modalSport').textContent =
        btn.dataset.sport;

    document.getElementById('modalYear').textContent =
        btn.dataset.year ? 'Year: ' + btn.dataset.year : '';

    document.getElementById('modalAchievements').textContent =
        btn.dataset.achievements;

    document.getElementById('modalImage').src =
        '<?= BASE_URL ?>/' + btn.dataset.image;

    modal.classList.add('active');
}

buttons.forEach((btn, index) => {
    btn.addEventListener('click', () => openChampion(index));
});

document.getElementById('nextChampion').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % buttons.length;
    openChampion(currentIndex);
});

document.getElementById('prevChampion').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + buttons.length) % buttons.length;
    openChampion(currentIndex);
});

backdrop.addEventListener('click', () => {
    modal.classList.remove('active');
});
</script>