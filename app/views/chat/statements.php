<div style="max-width:900px;margin:2rem auto;padding:0 1.5rem;">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:2rem;font-weight:900;margin-bottom:.25rem;">📢 Official Statements</h1>
            <p style="color:#9aa3a6;">Admin announcements and news. React with emojis to show your response.</p>
        </div>
    </div>

    <?php if ($error): ?>
        <div style="background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);padding:12px 16px;border-radius:10px;margin-bottom:1rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Admin compose box -->
    <?php if ($isAdmin): ?>
    <div style="background:#15181b;border:1px solid #283339;border-radius:16px;padding:1.5rem;margin-bottom:2rem;">
        <h3 style="margin-bottom:1rem;font-size:1rem;color:#9aa3a6;">Post a new statement</h3>
        <form method="POST" action="<?= BASE_URL ?>/statements" enctype="multipart/form-data">
            <textarea name="body" rows="4" placeholder="Write your statement here..."
                style="width:100%;background:#0b0e13;color:#fff;border:1px solid #283339;border-radius:10px;padding:12px;font-size:1rem;resize:vertical;outline:none;font-family:inherit;"></textarea>

            <div style="display:flex;align-items:center;gap:1rem;margin-top:1rem;flex-wrap:wrap;">
                <label style="display:flex;align-items:center;gap:.5rem;color:#9aa3a6;cursor:pointer;font-size:.9rem;">
                    <i class="bi bi-image" style="font-size:1.2rem;color:#0da6f2;"></i>
                    Attach image
                    <input type="file" name="image" accept="image/*" style="display:none;" onchange="previewImg(this)">
                </label>
                <img id="imgPreview" src="" alt="" style="display:none;height:60px;border-radius:8px;object-fit:cover;">
                <button type="submit"
                    style="margin-left:auto;background:linear-gradient(90deg,#0da6f2,#007bff);color:#000;font-weight:700;padding:.65rem 1.5rem;border:none;border-radius:10px;cursor:pointer;">
                    Post Statement
                </button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Statements feed -->
    <?php if (empty($statements)): ?>
        <div style="text-align:center;color:#9aa3a6;padding:4rem;">No statements yet.</div>
    <?php endif; ?>

    <?php foreach ($statements as $s): ?>
    <div class="stmt-card" data-id="<?= $s['id'] ?>"
         style="background:#15181b;border:1px solid #283339;border-radius:16px;padding:1.5rem;margin-bottom:1.25rem;">

        <!-- Header -->
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
            <?php $avatar = $s['admin_avatar'] ? FileUpload::url($s['admin_avatar']) : null; ?>
            <?php if ($avatar): ?>
                <img src="<?= htmlspecialchars($avatar) ?>" alt="" style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid #fbbf24;">
            <?php else: ?>
                <div style="width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;font-weight:700;color:#000;font-size:1.1rem;">
                    <?= strtoupper(substr($s['admin_name'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <div>
                <strong style="color:#fbbf24;"><?= htmlspecialchars($s['admin_name']) ?></strong>
                <span style="background:rgba(245,158,11,.15);color:#fbbf24;font-size:.7rem;padding:.1rem .5rem;border-radius:999px;margin-left:.4rem;">Admin</span>
                <div style="font-size:.8rem;color:#9aa3a6;"><?= date('M j, Y · g:i A', strtotime($s['created_at'])) ?></div>
            </div>
        </div>

        <!-- Body -->
        <p style="color:#e8eef0;line-height:1.7;margin-bottom:1rem;white-space:pre-wrap;"><?= htmlspecialchars($s['body']) ?></p>

        <!-- Image -->
        <?php if ($s['image']): ?>
            <img src="<?= htmlspecialchars(FileUpload::url($s['image'])) ?>" alt="Statement image"
                 style="max-width:100%;border-radius:12px;margin-bottom:1rem;max-height:400px;object-fit:cover;">
        <?php endif; ?>

        <!-- Reactions -->
        <div style="display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;">
            <?php
            $allowed  = ['👍','❤️','🔥','😮','😂','👏'];
            $reactMap = [];
            foreach ($s['reactions'] as $r) $reactMap[$r['emoji']] = $r['cnt'];
            $myEmoji  = $s['my_emoji'];
            $isAdmin  = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
            ?>

            <?php foreach ($allowed as $emoji): ?>
            <?php $cnt = $reactMap[$emoji] ?? 0; ?>
            <?php if (!$isAdmin): ?>
            <button class="react-btn"
                data-stmt="<?= $s['id'] ?>" data-emoji="<?= $emoji ?>"
                style="background:<?= ($myEmoji === $emoji) ? 'rgba(13,166,242,.25)' : 'rgba(255,255,255,.04)' ?>;
                       border:1px solid <?= ($myEmoji === $emoji) ? '#0da6f2' : 'rgba(255,255,255,.08)' ?>;
                       border-radius:999px;padding:.35rem .75rem;cursor:pointer;font-size:1rem;
                       color:#fff;display:flex;align-items:center;gap:.35rem;transition:all .2s;">
                <?= $emoji ?> <?php if ($cnt > 0): ?><span class="cnt"><?= $cnt ?></span><?php endif; ?>
            </button>
            <?php else: ?>
            <?php if ($cnt > 0): ?>
            <span style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:999px;padding:.35rem .75rem;font-size:1rem;color:#9aa3a6;">
                <?= $emoji ?> <span><?= $cnt ?></span>
            </span>
            <?php endif; ?>
            <?php endif; ?>
            <?php endforeach; ?>

            <?php if (!$isAdmin && $myEmoji): ?>
            <span style="font-size:.8rem;color:#9aa3a6;margin-left:.25rem;">You reacted with <?= $myEmoji ?></span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (!$isAdmin): ?>
<script>
document.querySelectorAll('.react-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const stmtId = btn.dataset.stmt;
        const emoji  = btn.dataset.emoji;

        const res = await fetch('<?= BASE_URL ?>/statements/react', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ statement_id: parseInt(stmtId), emoji })
        });

        if (!res.ok) return;
        const data = await res.json();

        // Update reaction buttons in this card
        const card = btn.closest('.stmt-card');
        card.querySelectorAll('.react-btn').forEach(b => {
            const e = b.dataset.emoji;
            const cnt = (data.reactions.find(r => r.emoji === e) || {}).cnt || 0;
            const isMe = data.my_emoji === e;

            b.style.background = isMe ? 'rgba(13,166,242,.25)' : 'rgba(255,255,255,.04)';
            b.style.borderColor = isMe ? '#0da6f2' : 'rgba(255,255,255,.08)';

            const cntEl = b.querySelector('.cnt');
            if (cnt > 0) {
                if (cntEl) cntEl.textContent = cnt;
                else b.innerHTML = e + ' <span class="cnt">' + cnt + '</span>';
            } else {
                if (cntEl) cntEl.remove();
                else b.textContent = e;
            }
        });
    });
});
</script>
<?php endif; ?>

<script>
function previewImg(input) {
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
    }
}
</script>
