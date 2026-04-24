<div style="max-width:600px;margin:2rem auto;padding:0 1.5rem;">
    <a href="<?= BASE_URL ?>/admin" style="color:#0da6f2;text-decoration:none;font-size:.9rem;">← Back to Admin</a>
    <h1 style="font-size:1.75rem;font-weight:900;margin:1rem 0;">🏟️ Add Club</h1>

    <?php if ($error): ?>
        <div style="background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);padding:12px 16px;border-radius:10px;margin-bottom:1.25rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/admin/clubs/create" enctype="multipart/form-data"
          style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.75rem;display:flex;flex-direction:column;gap:1rem;">

        <div>
            <label class="adm-label">Club Name *</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required class="adm-input">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
                <label class="adm-label">Sport *</label>
                <select name="sport_id" required class="adm-input">
                    <option value="">Select sport...</option>
                    <?php foreach ($sports as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="adm-label">City / Governorate</label>
                <input type="text" name="governorate" value="<?= htmlspecialchars($_POST['governorate'] ?? '') ?>"
                       placeholder="Cairo" class="adm-input">
            </div>
        </div>

        <div>
            <label class="adm-label">Logo / Image</label>
            <input type="file" name="image" accept="image/*" class="adm-file">
        </div>

        <div style="display:flex;gap:.75rem;margin-top:.5rem;">
            <button type="submit" style="background:#0da6f2;color:#000;font-weight:700;padding:.65rem 1.5rem;border:none;border-radius:10px;cursor:pointer;">Create Club</button>
            <a href="<?= BASE_URL ?>/admin" style="background:#283339;color:#9aa3a6;font-weight:600;padding:.65rem 1.25rem;border-radius:10px;text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>

<style>
.adm-label { display:block; font-size:.875rem; color:#9aa3a6; margin-bottom:.35rem; }
.adm-input { width:100%; background:#0b0e13; color:#fff; border:1px solid #283339; border-radius:10px; padding:11px 13px; outline:none; font-size:.9rem; font-family:inherit; }
.adm-file { color:#9aa3a6; font-size:.875rem; }
</style>
