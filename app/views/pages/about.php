<div style="max-width:960px;margin:2rem auto;padding:0 2rem;">
    <h1 style="font-size:2.5rem;font-weight:900;margin-bottom:.5rem;">ℹ️ About Sports Encyclopedia</h1>
    <p style="color:#9aa3a6;margin-bottom:2.5rem;">Your ultimate source for sports information.</p>

    <div style="background:#15181b;border:1px solid #283339;border-radius:16px;padding:2rem;display:flex;gap:2rem;align-items:flex-start;margin-bottom:2rem;flex-wrap:wrap;">
        <div style="flex:1;min-width:260px;">
            <h2 style="font-size:1.4rem;margin-bottom:.75rem;">Your Ultimate Sports Encyclopedia</h2>
            <p style="color:#9aa3a6;line-height:1.7;margin-bottom:.75rem;">
                Sports Encyclopedia is a comprehensive platform dedicated to celebrating the world of sports —
                from global giants like Football and Basketball to Egyptian excellence in bodybuilding,
                squash, and beyond.
            </p>
            <p style="color:#9aa3a6;line-height:1.7;">
                We bring together athlete profiles, career timelines, official championship data,
                club directories, and live statistics in one place — all powered by a real database.
            </p>
        </div>
        <div style="font-size:5rem;flex-shrink:0;">🏆</div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.25rem;margin-bottom:2rem;">
        <?php $features = [
            ['⚽','Sports Information','Rules, equipment, history, and stats for every sport.'],
            ['🏃','Athlete Profiles','Career timelines, performance charts, and milestones.'],
            ['⚖️','Comparison Tools','Side-by-side comparisons for sports and athletes.'],
            ['🇪🇬','Egyptian Champions','A dedicated section honoring Egypt\'s greatest icons.'],
            ['🏅','Records','World and Olympic records catalogued by sport.'],
            ['📊','Statistics','Popularity trends, rankings, and performance data.'],
            ['📢','Statements','Official admin announcements with emoji reactions.'],
            ['📄','Contributions','Users can submit research PDFs for admin review.'],
        ]; foreach ($features as [$icon, $title, $desc]): ?>
        <div style="background:#15181b;border:1px solid #283339;border-radius:12px;padding:1.25rem;text-align:center;transition:border-color .2s;"
             onmouseover="this.style.borderColor='#0da6f2'" onmouseout="this.style.borderColor='#283339'">
            <div style="font-size:2rem;margin-bottom:.5rem;"><?= $icon ?></div>
            <h3 style="font-size:.95rem;margin-bottom:.4rem;"><?= $title ?></h3>
            <p style="font-size:.82rem;color:#9aa3a6;line-height:1.5;"><?= $desc ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <div style="display:flex;gap:.75rem;flex-wrap:wrap;">
        <a href="<?= BASE_URL ?>/contact" style="background:#0da6f2;color:#000;font-weight:700;padding:.65rem 1.25rem;border-radius:10px;text-decoration:none;">Contact Us</a>
        <a href="<?= BASE_URL ?>/feedback" style="background:#283339;color:#9aa3a6;font-weight:600;padding:.65rem 1.25rem;border-radius:10px;text-decoration:none;">Send Feedback</a>
    </div>
</div>
