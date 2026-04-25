<?php $extraCss = ['settings.css']; ?>

<div class="settings-panel" style="max-width:600px;margin:2rem auto;">
    <a href="<?= BASE_URL ?>/profile" class="back-link" style="display:inline-flex;align-items:center;gap:.4rem;text-decoration:none;margin-bottom:1.5rem;font-size:.9rem;">
        ← Back to Profile
    </a>
    <h1 class="settings-title">⚙️ Settings</h1>

    <?php if (!empty($success)): ?>
        <div style="background:rgba(16,185,129,.15);color:#6ee7b7;border:1px solid rgba(16,185,129,.3);padding:12px 16px;border-radius:10px;margin:1rem 0;">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div style="background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);padding:12px 16px;border-radius:10px;margin:1rem 0;">
            ❌ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Account info -->
    <div class="section">
        <h3>Account Info</h3>
        <div class="row">
            <div class="left"><div class="icon-box"><i class="bi bi-person"></i></div><div><div><?= htmlspecialchars($user['name']) ?></div><div style="color:#9aa3a6;font-size:.85rem;"><?= htmlspecialchars($user['email']) ?></div></div></div>
        </div>
    </div>

    <!-- Change password -->
    <div class="section">
        <h3>Change Password</h3>
        <form method="POST" action="<?= BASE_URL ?>/profile/settings">
            <div style="display:flex;flex-direction:column;gap:.75rem;margin-top:.75rem;">
                <div>
                    <label style="font-size:.875rem;color:#9aa3a6;display:block;margin-bottom:.35rem;">Current Password</label>
                    <input type="password" name="current_password" placeholder="••••••••" required
                           style="width:100%;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:12px;color:#fff;outline:none;">
                </div>
                <div>
                    <label style="font-size:.875rem;color:#9aa3a6;display:block;margin-bottom:.35rem;">New Password</label>
                    <input type="password" name="new_password" placeholder="At least 6 characters" required
                           style="width:100%;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:12px;color:#fff;outline:none;">
                </div>
                <div>
                    <label style="font-size:.875rem;color:#9aa3a6;display:block;margin-bottom:.35rem;">Confirm New Password</label>
                    <input type="password" name="confirm_password" placeholder="Repeat new password" required
                           style="width:100%;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:12px;color:#fff;outline:none;">
                </div>
                <button type="submit" class="btn">Update Password</button>
            </div>
        </form>
    </div>
</div>
