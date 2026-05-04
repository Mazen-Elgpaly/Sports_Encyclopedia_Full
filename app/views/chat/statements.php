<style>
    img.emoji {
    margin: 3px;
    width: 1.3em;
    vertical-align: -0.55em;
}
.flying-emoji{
    position:absolute;
    pointer-events:none;
    font-size:28px;
    animation:flyUp 1.2s ease-out forwards;
    z-index:9999;
}
.stmt-meta{
    display:flex;
    align-items:center;
    justify-content:space-between;
}

.stmt-reaction-slot{
    display:flex;
    align-items:center;
    gap:.25rem;
}
.statement-content{
    position: relative;
}

/* transition stable */
.stmt-preview,
.stmt-full{
    transition: all .35s ease;
}

/* default */
.stmt-full{
    max-height:0;
    opacity:0;
    /* overflow:hidden; */
    transform: translateY(10px);
}

/* expanded */
.stmt-card.expanded .stmt-full{
    max-height:2000px;
    opacity:1;
    transform: translateY(0);
}

.stmt-card.expanded .stmt-preview{
    opacity:0;
    max-height:0;
    overflow:hidden;
}

/* image animation */
.stmt-full img{
    opacity:0;
    transform: scale(0.98);
    transition: all .4s ease;
}

.stmt-card.expanded .stmt-full img{
    opacity:1;
}

/* pinned state */
.stmt-card.pinned .read-more-toggle{
    background: rgba(13,166,242,.2);
    border-color:#0da6f2;
}

/* button visibility fix */
.read-more-toggle{
    opacity:0;
    transform: translateY(-4px);
    transition:.2s ease;
}

.stmt-card:hover .read-more-toggle{
    opacity:1;
    transform: translateY(0);
}

/* pinned override */
.stmt-card.pinned .read-more-toggle{
    opacity:1;
}

.stmt-full img{
    max-width:100%;
    border-radius:12px;
    margin-bottom:1rem;
    max-height:400px;
    object-fit:cover;

    transition: transform .3s ease, box-shadow .3s ease;
    transform-origin: left bottom; /* هنا نقطة التمدد */
}

/* hover zoom */
.stmt-full img:hover{
    transform: scale(2); !important
    position: relative;
    z-index: 2000;
    box-shadow: 0 4px 15px rgba(255,255,255,.9);
        

}

@keyframes flyUp{
    0%{
        opacity:1;
        transform:translateY(0) scale(1);
    }
    50%{
        opacity:1;
        transform:translateY(-60px) scale(1.4);
    }
    100%{
        opacity:0;
        transform:translateY(-130px) scale(.8);
    }
}
</style>
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
            <div class="stmt-header" style="display:flex;align-items:center;gap:.75rem;">
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
                        <button class="read-more-toggle"
                style="
                    margin-left:auto;
                    background:transparent;
                    border:1px solid rgba(255,255,255,.15);
                    color:#0da6f2;
                    padding:.25rem .6rem;
                    border-radius:8px;
                    cursor:pointer;
                    font-size:.75rem;
                ">
                Read more
            </button>
        </div>

   
        <?php
$wordLimit = 5; 
$words     = preg_split('/\s+/', trim($s['body']));
$preview   = implode(' ', array_slice($words, 0, $wordLimit));
$hasMore   = count($words) > $wordLimit;
?>

<div class="statement-content">

    <!-- Preview -->
    <div class="stmt-preview" style= "margin-bottom: 10px;margin-top: 10px;">
        <p style="color:#e8eef0;line-height:1.7;margin-bottom:.5rem;display: inline;">
            <?= htmlspecialchars($preview) ?>
            <?php if ($hasMore): ?>...<?php endif; ?>
        </p>

        <?php if ($hasMore || $s['image']): ?>
            <button class="read-more-btn"
                style="background:none;border:none;color:#0da6f2;cursor:pointer;font-weight:600;padding:0;">
                Read more
            </button>
        <?php endif; ?>
    </div>

    <!-- Full -->
    <div class="stmt-full" >
        <p style="color:#e8eef0;line-height:1.7;margin-block-start: 0;white-space:pre-line;">
            <?= htmlspecialchars($s['body']) ?>
        </p>

        <?php if ($s['image']): ?>
            <img src="<?= htmlspecialchars(FileUpload::url($s['image'])) ?>" alt="Statement image"
                 style="max-width:100%;border-radius:12px;margin-bottom:1rem;max-height:400px;object-fit:cover;">
        <?php endif; ?>
    </div>

</div>

        <!-- Reactions -->
<div class="reactions-bar" style="display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;">

    <?php
    $allowed  = ['👍','❤️','🔥','😮','😂','👏'];
    $reactMap = [];
    foreach ($s['reactions'] as $r) $reactMap[$r['emoji']] = $r['cnt'];

    $myEmoji  = $s['my_emoji'] ?? null;
    $isAdmin  = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    ?>

    <!-- Reaction buttons -->
    <?php foreach ($allowed as $emoji): ?>
        <?php $cnt = $reactMap[$emoji] ?? 0; ?>

        <?php if (!$isAdmin): ?>
        <button
            class="react-btn"
            data-stmt="<?= $s['id'] ?>"
            data-emoji="<?= $emoji ?>"
            style="
                background:<?= ($myEmoji === $emoji) ? 'rgba(13,166,242,.25)' : 'rgba(255,255,255,.04)' ?>;
                border:1px solid <?= ($myEmoji === $emoji) ? '#0da6f2' : 'rgba(255,255,255,.08)' ?>;
                border-radius:999px;
                padding:.35rem .75rem;
                cursor:pointer;
                font-size:1rem;
                color:#fff;
                display:flex;
                align-items:center;
                gap:.35rem;
                transition:all .2s;
                position:relative;
                overflow:hidden;
            "
        >
            <span class="emoji"><?= $emoji ?></span>

            <?php if ($cnt > 0): ?>
                <span class="cnt"><?= $cnt ?></span>
            <?php endif; ?>
        </button>
        <?php endif; ?>

    <?php endforeach; ?>


    <!-- FIXED PLACEHOLDER (important part) -->
    <span
        class="my-reaction"
        data-id="<?= $s['id'] ?>"
        style="
            font-size:.85rem;
            color:#9aa3a6;
            margin-left:.5rem;
            display:<?= $myEmoji ? 'inline-block' : 'none' ?>;
        "
    >
        You reacted with <span class="my-emoji"><?= htmlspecialchars($myEmoji ?? '') ?></span>
    </span>

</div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (!$isAdmin): ?>
<script>
/* =========================
   TWEMOJI SAFE WRAPPER
========================= */
function parseTwemoji(scope) {
    if (!scope) return;
    twemoji.parse(scope, {
        folder: 'svg',
        ext: '.svg'
    });
}

/* =========================
   INIT EVENTS
========================= */
document.querySelectorAll('.react-btn').forEach(btn => {

    btn.addEventListener('click', async () => {

        const stmtId = btn.dataset.stmt;
        const emoji  = btn.dataset.emoji;
        const card   = btn.closest('.stmt-card');

        if (btn.disabled) return;
        btn.disabled = true;

        try {
            const res = await fetch('<?= BASE_URL ?>/statements/react', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    statement_id: parseInt(stmtId),
                    emoji
                })
            });

            if (!res.ok) return;

            const data = await res.json();

            /* =========================
               1) UPDATE BUTTONS (SAFE DOM)
            ========================= */
            card.querySelectorAll('.react-btn').forEach(b => {

                const e   = b.dataset.emoji;
                const cnt = (data.reactions.find(r => r.emoji === e) || {}).cnt || 0;
                const isMe = data.my_emoji === e;

                const emojiSpan = b.querySelector('.emoji');
                const cntSpan   = b.querySelector('.cnt');

                if (emojiSpan) emojiSpan.textContent = e;

                if (cntSpan) {
                    if (cnt > 0) cntSpan.textContent = cnt;
                    else cntSpan.remove();
                } else if (cnt > 0) {
                    const span = document.createElement('span');
                    span.className = 'cnt';
                    span.textContent = cnt;
                    b.appendChild(document.createTextNode(' '));
                    b.appendChild(span);
                }

                b.style.background = isMe
                    ? 'rgba(13,166,242,.25)'
                    : 'rgba(255,255,255,.04)';

                b.style.borderColor = isMe
                    ? '#0da6f2'
                    : 'rgba(255,255,255,.08)';
            });

            /* =========================
               2) YOU REACTED WITH (FIXED)
            ========================= */
            const box = card.querySelector('.my-reaction');
            const emojiBox = card.querySelector('.my-emoji');

            if (data.my_emoji) {
                box.style.display = 'inline-flex';
                emojiBox.textContent = data.my_emoji;
            } else {
                box.style.display = 'none';
                emojiBox.textContent = '';
            }

            /* =========================
               3) FLY EMOJI ANIMATION
            ========================= */
            const rect = btn.getBoundingClientRect();

            const flying = document.createElement('div');
            flying.className = 'flying-emoji';
            flying.textContent = emoji;

            flying.style.left = (rect.left + rect.width / 2) + 'px';
            flying.style.top  = (rect.top + window.scrollY) + 'px';

            document.body.appendChild(flying);

            setTimeout(() => flying.remove(), 1200);

            /* =========================
               4) SAFE TWEMOJI RE-PARSE
               (ONLY CURRENT CARD)
            ========================= */
            requestAnimationFrame(() => {
                parseTwemoji(card);
            });

        } finally {
            btn.disabled = false;
        }
    });

});
</script>
<?php endif; ?>

<script>
/* =========================
   IMAGE PREVIEW
========================= */
function previewImg(input) {
    const preview = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
    }
}

document.querySelectorAll('.stmt-card').forEach(card => {

    const btn = card.querySelector('.read-more-toggle');

    let pinned = false;
    let hoverLock = false;

    const open = () => {
        card.classList.add('expanded');
    };

    const close = () => {
        if (pinned) return;
        card.classList.remove('expanded');
    };

    /* =========================
       HOVER (NO LOOP FIX)
    ========================= */
    card.addEventListener('mouseenter', () => {
        hoverLock = true;
        open();
    });

    card.addEventListener('mouseleave', () => {
        hoverLock = false;
        close();
    });

    /* =========================
       PIN BUTTON
    ========================= */
    btn.addEventListener('click', (e) => {
        e.stopPropagation();

        pinned = !pinned;

        if (pinned) {
            card.classList.add('pinned');
            open();
            btn.textContent = "Pinned";
        } else {
            card.classList.remove('pinned');
            btn.textContent = "Read more";
            close();
        }
    });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/twemoji@14.0.2/dist/twemoji.min.js"></script>

<script>
/* =========================
   INITIAL PARSE (FULL PAGE)
========================= */
parseTwemoji(document.body);
</script>