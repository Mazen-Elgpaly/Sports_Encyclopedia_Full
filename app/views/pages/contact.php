
<?php
$extraCss = ['contactus.css']; 
?>
<div style="max-width:900px;margin:2rem auto;padding:0 2rem;">
    <h1 style="font-size:2.5rem;font-weight:900;margin-bottom:.5rem;">📬 Contact Us</h1>
    <p style="color:#9aa3a6;margin-bottom:2rem;">Send us a message and we'll get back to you soon.</p>

    <?php if ($success): ?>
    <div style="text-align:center;padding:3rem;background:#15181b;border-radius:14px;">
        <div style="font-size:3rem;margin-bottom:1rem;">✉️</div>
        <h2>Message Sent!</h2>
        <p style="color:#9aa3a6;margin:.75rem 0 1.5rem;">We'll respond within 24 hours on business days.</p>
        <a href="<?= BASE_URL ?>/home" style="background:#0da6f2;color:#000;font-weight:700;padding:.65rem 1.25rem;border-radius:10px;text-decoration:none;">Back to Home</a>
    </div>
    <?php else: ?>

    <?php if ($error): ?>
        <div style="background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);padding:12px 16px;border-radius:10px;margin-bottom:1.25rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div style="display:grid;grid-template-columns:1fr 280px;gap:1.5rem;align-items:start;flex-wrap:wrap;">
        <form method="POST" action="<?= BASE_URL ?>/contact"
              style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.75rem;display:flex;flex-direction:column;gap:1rem;">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.35rem;">Full Name *</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>"
                           required style="width:100%;background:#0b0e13;color:#fff;border:1px solid #283339;border-radius:10px;padding:11px 13px;outline:none;">
                </div>
                <div>
                    <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.35rem;">Email *</label>
                    <input type="email" name="email" required
                           style="width:100%;background:#0b0e13;color:#fff;border:1px solid #283339;border-radius:10px;padding:11px 13px;outline:none;">
                </div>
            </div>
            <div>
                <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.35rem;">Subject *</label>
                <input type="text" name="subject" required
                       style="width:100%;background:#0b0e13;color:#fff;border:1px solid #283339;border-radius:10px;padding:11px 13px;outline:none;">
            </div>
            <div>
                <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.35rem;">Message *</label>
                <textarea name="message" rows="6" required
                          style="width:100%;background:#0b0e13;color:#fff;border:1px solid #283339;border-radius:10px;padding:11px 13px;outline:none;resize:vertical;font-family:inherit;"></textarea>
            </div>
            <button type="submit" style="background:#0da6f2;color:#000;font-weight:700;padding:.65rem 1.5rem;border:none;border-radius:10px;cursor:pointer;align-self:flex-start;">
                Send Message
            </button>
        </form>

        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.25rem;">
                <h3 style="font-size:1rem;margin-bottom:.75rem;">📌 Contact Info</h3>
                <p style="color:#9aa3a6;font-size:.875rem;margin-bottom:.5rem;">✉️ info@sportshub.eg</p>
                <p style="color:#9aa3a6;font-size:.875rem;margin-bottom:.5rem;">📞 +20 100 000 0000</p>
                <p style="color:#9aa3a6;font-size:.875rem;">📍 Cairo, Egypt</p>
            </div>
            <div style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.25rem;">
                <h3 style="font-size:1rem;margin-bottom:.5rem;">⏱️ Response Time</h3>
                <p style="color:#9aa3a6;font-size:.875rem;">We typically respond within <strong>24 hours</strong> on business days.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
