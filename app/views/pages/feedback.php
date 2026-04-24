<link rel="stylesheet" href="<?= BASE_URL ?>/css/feedback.css">

<div class="page-root">
    <div class="layout-container">
        <div class="main">
            <?php if ($success): ?>
            <div style="text-align:center;padding:4rem 2rem;">
                <div style="font-size:4rem;margin-bottom:1rem;">🎉</div>
                <h2 style="font-size:1.75rem;margin-bottom:.75rem;">Thank you for your feedback!</h2>
                <p style="color:#9aa3a6;margin-bottom:2rem;">We read every message and will take your input into account.</p>
                <a href="<?= BASE_URL ?>/home" style="background:#0da6f2;color:#000;font-weight:700;padding:.75rem 1.5rem;border-radius:10px;text-decoration:none;">Back to Home</a>
            </div>
            <?php else: ?>
            <div class="content">
                <div>
                    <p class="title">Feedback</p>
                    <p class="subtitle">Help us improve Sports Encyclopedia.</p>
                </div>

                <?php if ($error): ?>
                    <div class="message error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form class="form" method="POST" action="<?= BASE_URL ?>/feedback">
                    <div class="row-2">
                        <div class="field">
                            <label class="label">Name *</label>
                            <input class="input" type="text" name="name"
                                   value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>"
                                   placeholder="Your name" required>
                        </div>
                        <div class="field">
                            <label class="label">Email *</label>
                            <input class="input" type="email" name="email"
                                   placeholder="you@example.com" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Your Rating</label>
                        <div class="stars" id="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star" data-v="<?= $i ?>" onclick="setRating(<?= $i ?>)">★</span>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" id="ratingInput" name="rating" value="0">
                    </div>

                    <div class="field">
                        <label class="label">Message *</label>
                        <textarea class="textarea" name="message" placeholder="Tell us what you think..." required></textarea>
                    </div>

                    <button type="submit" class="submit-btn">Send Feedback</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function setRating(v) {
    document.getElementById('ratingInput').value = v;
    document.querySelectorAll('.star').forEach((s, i) => {
        s.style.color = i < v ? '#0da6f2' : '';
    });
}
</script>
