<div style="max-width:1200px;margin:2rem auto;padding:0 1.5rem;">

    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
        <div>
            <h1 style="font-size:2rem;font-weight:900;">🛡️ Admin Control Panel</h1>
            <p style="color:#9aa3a6;">Manage content, review contributions, and post statements.</p>
        </div>
        <a href="<?= BASE_URL ?>/statements" style="margin-left:auto;background:linear-gradient(90deg,#0da6f2,#007bff);color:#000;font-weight:700;padding:.65rem 1.25rem;border-radius:10px;text-decoration:none;">
            📢 Post Statement
        </a>
    </div>

    <!-- Pending contributions alert -->
    <?php if (!empty($pending)): ?>
        <div style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);border-radius:14px;padding:1.25rem;margin-bottom:2rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
            <div>
                <strong style="color:#fbbf24;">⏳ <?= count($pending) ?> pending contribution<?= count($pending) > 1 ? 's' : '' ?></strong>
                <div style="color:#9aa3a6;font-size:.875rem;margin-top:.25rem;">User submissions are awaiting your review.</div>
            </div>
            <a href="<?= BASE_URL ?>/admin/contributions" style="background:#fbbf24;color:#000;font-weight:700;padding:.5rem 1rem;border-radius:8px;text-decoration:none;font-size:.9rem;">Review Now</a>
        </div>
    <?php endif; ?>

    <!-- Quick actions grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;margin-bottom:2.5rem;">
        <!-- Athletes -->
        <div style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.5rem;">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;">
                🏃 Athletes <span style="margin-left:auto;background:#0da6f2;color:#000;border-radius:999px;padding:.1rem .6rem;font-size:.8rem;"><?= count($athletes) ?></span>
            </h3>
            <div style="display:flex;flex-direction:column;gap:.5rem;">
                <a href="<?= BASE_URL ?>/admin/athletes/create" style="background:#0da6f2;color:#000;font-weight:600;padding:.5rem 1rem;border-radius:8px;text-decoration:none;text-align:center;">+ Add Athlete</a>
            </div>
            <input type="text" id="athletesSearch" placeholder="Search athletes..."
                style="width:100%;padding:.5rem;margin-top:1rem;background:#0f1315;border:1px solid #283339;color:#fff;border-radius:8px;">
            <div data-athletes-list style="margin-top:1rem;max-height:200px;overflow-y:auto;">
                <?php foreach ($athletes as $a): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #283339;font-size:.875rem;">
                        <span><?= htmlspecialchars($a['name']) ?></span>
                        <div style="display:flex;gap:.5rem;">
                            <a href="<?= BASE_URL ?>/admin/athletes/edit/<?= $a['id'] ?>" style="color:#0da6f2;text-decoration:none;font-size:.8rem;">Edit</a>
                            <form method="POST" action="<?= BASE_URL ?>/admin/athletes/delete" style="display:inline;"
                                onsubmit="return confirm('Delete <?= htmlspecialchars(addslashes($a['name'])) ?>?')">
                                <input type="hidden" name="id" value="<?= $a['id'] ?>">
                                <button type="submit" style="background:none;border:none;color:#f87171;cursor:pointer;font-size:.8rem;">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Clubs -->
        <div style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.5rem;">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;">
                🏟️ Clubs <span style="margin-left:auto;background:#0da6f2;color:#000;border-radius:999px;padding:.1rem .6rem;font-size:.8rem;"><?= count($clubs) ?></span>
            </h3>
            <a href="<?= BASE_URL ?>/admin/clubs/create" style="background:#0da6f2;color:#000;font-weight:600;padding:.5rem 1rem;border-radius:8px;text-decoration:none;text-align:center;display:block;">+ Add Club</a>
            <input type="text" id="clubsSearch" placeholder="Search clubs..."
                style="width:100%;padding:.5rem;margin-top:1rem;background:#0f1315;border:1px solid #283339;color:#fff;border-radius:8px;">
            <div data-clubs-list style="margin-top:1rem;max-height:200px;overflow-y:auto;">
                <?php foreach ($clubs as $c): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #283339;font-size:.875rem;">
                        <span><?= htmlspecialchars($c['name']) ?></span>
                        <form method="POST" action="<?= BASE_URL ?>/admin/clubs/delete"
                            onsubmit="return confirm('Delete this club?')">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <button type="submit" style="background:none;border:none;color:#f87171;cursor:pointer;font-size:.8rem;">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sports -->
        <div style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.5rem;">
            <h3 style="font-size:1rem;font-weight:700;margin-bottom:1rem;display:flex;align-items:center;gap:.5rem;">
                ⚽ Sports <span style="margin-left:auto;background:#0da6f2;color:#000;border-radius:999px;padding:.1rem .6rem;font-size:.8rem;"><?= count($sports) ?></span>
            </h3>
            <a href="<?= BASE_URL ?>/admin/sports/create" style="background:#0da6f2;color:#000;font-weight:600;padding:.5rem 1rem;border-radius:8px;text-decoration:none;text-align:center;display:block;">+ Add Sport</a>
            <input type="text" id="sportsSearch" placeholder="Search sports..."
                style="width:100%;padding:.5rem;margin-top:1rem;background:#0f1315;border:1px solid #283339;color:#fff;border-radius:8px;">
            <div data-sports-list style="margin-top:1rem;max-height:200px;overflow-y:auto;">
                <?php foreach ($sports as $s): ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid #283339;font-size:.875rem;">
                        <span><?= htmlspecialchars($s['name']) ?></span>
                        <form method="POST" action="<?= BASE_URL ?>/admin/sports/delete"
                            onsubmit="return confirm('Delete this sport?')">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <button type="submit" style="background:none;border:none;color:#f87171;cursor:pointer;font-size:.8rem;">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script>
function setupSearch(inputId, containerSelector) {
    const input = document.getElementById(inputId);
    const items = document.querySelectorAll(containerSelector + " > div");

    input.addEventListener("input", () => {
        const q = input.value.toLowerCase();

        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(q) ? "flex" : "none";
        });
    });
}

setupSearch("athletesSearch", "[data-athletes-list]");
setupSearch("clubsSearch", "[data-clubs-list]");
setupSearch("sportsSearch", "[data-sports-list]");
</script>