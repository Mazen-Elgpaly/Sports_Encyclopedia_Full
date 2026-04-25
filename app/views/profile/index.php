<?php $extraCss = ['user_account.css']; ?>

<main class="main">
    <div class="profile" style="max-width:900px;">

        <!-- Alerts -->
        <?php if (!empty($success)): ?>
            <div style="background:rgba(16,185,129,.15);color:#6ee7b7;border:1px solid rgba(16,185,129,.3);padding:12px 16px;border-radius:10px;margin-bottom:1.5rem;">
                ✅ <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div style="background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);padding:12px 16px;border-radius:10px;margin-bottom:1.5rem;">
                ❌ <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Profile header -->
        <div class="profile-header">
            <div class="avatar-lg-wrapper">
                <?php $avatarSrc = ($user['avatar'] ?? null) ? FileUpload::url($user['avatar']) : null; ?>
                <div class="avatar-lg" style="background:<?= $avatarSrc ? "url('$avatarSrc') center/cover" : 'linear-gradient(135deg,#0da6f2,#007bff)' ?>;display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:700;color:#000;">
                    <?= $avatarSrc ? '' : strtoupper(substr($user['name'], 0, 1)) ?>
                </div>
                <div class="avatar-ring"></div>
            </div>
            <h1><?= htmlspecialchars($user['name']) ?></h1>
            <p><?= htmlspecialchars($user['email']) ?></p>
            <p style="color:#9aa3a6;font-size:.85rem;">
                Member since <?= date('M Y', strtotime($user['created_at'])) ?>
                <?php if ($user['role'] === 'admin'): ?>
                    · <span style="color:#fbbf24;font-weight:700;">Admin</span>
                <?php endif; ?>
            </p>
        </div>

        <!-- Edit profile form -->
        <form method="POST" action="<?= BASE_URL ?>/profile" enctype="multipart/form-data" style="background:#1a262c;border:1px solid #283339;border-radius:16px;padding:1.5rem;margin-top:2rem;">
            <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;">✏️ Edit Profile</h2>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.4rem;">Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>"
                           style="width:100%;background:#101c22;color:#fff;border:1px solid #283339;border-radius:10px;padding:12px;outline:none;font-size:.95rem;" required>
                </div>
                <div>
                    <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.4rem;">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"
                           style="width:100%;background:#101c22;color:#fff;border:1px solid #283339;border-radius:10px;padding:12px;outline:none;font-size:.95rem;" required>
                </div>
            </div>
            <div style="margin-bottom:1.25rem;">
                <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.4rem;">Profile Photo</label>
                <label for="upload-btn" class="upload btn">
    Upload Image
</label><span id="file-name" style="margin-left: 10px;">No file selected</span>
                <input id = "upload-btn" type="file" name="avatar" accept="image/*"
                       style="color:#9aa3a6;font-size:.875rem;">
            </div>
            <div style="display:flex;gap:.75rem;">
                <button type="submit" class="btn primary glow">Save Changes</button>
                <a href="<?= BASE_URL ?>/profile/settings" class="btn dark">⚙️ Change Password</a>
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/admin" class="btn" style="background:linear-gradient(90deg,#f59e0b,#d97706);color:#000;">🛡️ Admin Panel</a>
                <?php endif; ?>
            </div>
        </form>

        <!-- Contribution submit form — USER ONLY -->
        <?php if ($user["role"] === "user"): ?>
        <div style="background:#1a262c;border:1px solid #283339;border-radius:16px;padding:1.5rem;margin-top:2rem;">
            <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:.5rem;">📄 Submit a Contribution</h2>
            <p style="color:#9aa3a6;font-size:.875rem;margin-bottom:1.25rem;">Upload a PDF with sports information or research you'd like the admin team to review.</p>

            <form method="POST" action="<?= BASE_URL ?>/profile/contribute" enctype="multipart/form-data">
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.4rem;">Title *</label>
                    <input type="text" name="title" placeholder="e.g. Egyptian Athletics Statistics 2024"
                           style="width:100%;background:#101c22;color:#fff;border:1px solid #283339;border-radius:10px;padding:12px;outline:none;" required>
                </div>
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.4rem;">Description</label>
                    <textarea name="description" rows="3" placeholder="Brief description of your contribution..."
                              style="width:100%;background:#101c22;color:#fff;border:1px solid #283339;border-radius:10px;padding:12px;outline:none;resize:vertical;font-family:inherit;"></textarea>
                </div>
                <div style="margin-bottom:1.25rem;">
                    <label style="display:block;font-size:.875rem;color:#9aa3a6;margin-bottom:.4rem;">PDF File * (max 10MB)</label>
                    <label for="pdf-apload" class="upload btn">
    Upload PDF
</label>
                    <input id= "pdf-apload" type="file" name="pdf" accept="application/pdf" required style="color:#9aa3a6;">
                </div>
                <button type="submit" class="btn primary">📤 Submit for Review</button>
            </form>
        </div>

        <?php endif; // end user-only contribution form ?>

        <!-- My contributions -->
        <?php if (!empty($contributions)): ?>
        <div style="background:#1a262c;border:1px solid #283339;border-radius:16px;padding:1.5rem;margin-top:2rem;">
            <h2 style="font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;">📁 My Contributions</h2>
            <?php foreach ($contributions as $c): ?>
            <?php
                $statusStyle = match($c['status']) {
                    'approved' => 'background:rgba(16,185,129,.15);color:#6ee7b7;border:1px solid rgba(16,185,129,.3);',
                    'rejected' => 'background:rgba(239,68,68,.12);color:#fca5a5;border:1px solid rgba(239,68,68,.3);',
                    default    => 'background:rgba(245,158,11,.12);color:#fcd34d;border:1px solid rgba(245,158,11,.3);',
                };
                $statusText = ['pending'=>'⏳ Under Review','approved'=>'✅ Approved','rejected'=>'❌ Rejected'][$c['status']];
            ?>
            <div style="border:1px solid #283339;border-radius:12px;padding:1rem;margin-bottom:.75rem;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:.75rem;flex-wrap:wrap;">
                    <div>
                        <strong><?= htmlspecialchars($c['title']) ?></strong>
                        <div style="font-size:.8rem;color:#9aa3a6;margin-top:.25rem;"><?= date('M j, Y', strtotime($c['created_at'])) ?></div>
                        <?php if ($c['admin_note']): ?>
                            <div style="font-size:.85rem;color:#9aa3a6;margin-top:.4rem;padding:.5rem;background:#101c22;border-radius:8px;">
                                <strong>Admin note:</strong> <?= htmlspecialchars($c['admin_note']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-shrink:0;">
                        <a class="btn" href="<?= htmlspecialchars(FileUpload::url($c['file_path'])) ?>" target="_blank"
                           style="font-size:.85rem;text-decoration:none;">📥 View PDF</a>
                        <span style="<?= $statusStyle ?> padding:.25rem .75rem;border-radius:999px;font-size:.8rem;font-weight:600;">
                            <?= $statusText ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Logout -->
        <div class="actions" style="margin-top:2rem;">
            <a href="<?= BASE_URL ?>/logout" class="btn ghost">🚪 Log Out</a>
        </div>

    </div>
</main>
<script>
    document.getElementById('upload-btn').addEventListener('change', function () {
    document.getElementById('file-name').textContent =
        this.files[0]?.name || 'No file selected';
});
</script>