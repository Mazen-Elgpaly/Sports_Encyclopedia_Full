<style>
    .adm-label {
        display: block;
        font-size: .875rem;
        color: #9aa3a6;
        margin-bottom: .35rem;
    }

    .adm-input {
        width: 100%;
        background: #0b0e13;
        color: #fff;
        border: 1px solid #283339;
        border-radius: 10px;
        padding: 11px 13px;
        outline: none;
        font-size: .9rem;
        font-family: inherit;
    }

    .adm-file {
        color: #9aa3a6;
        font-size: .875rem;
    }

    input[type="file"] {
        display: none;
    }

    button,
    input[type="submit"] {
        border: none;
        outline: none;
        box-shadow: none;
    }

    .btn {
        text-decoration: none;
        display: inline-block;
        padding: 12px 20px;
        background: linear-gradient(135deg, #1877f2, #0d5fd3);
        color: white;
        border-radius: 12px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease,
            background 0.25s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(24, 119, 242, 0.35);
    }

    .btn:active {
        transform: translateY(0);
    }
</style>
<div style="max-width:700px;margin:2rem auto;padding:0 1.5rem;">
    <a href="<?= BASE_URL ?>/admin" style="color:#0da6f2;text-decoration:none;font-size:.9rem;">← Back to Admin</a>
    <h1 style="font-size:1.75rem;font-weight:900;margin:1rem 0;">⚽ Add Sport</h1>

    <?php if ($error): ?>
        <div style="background:rgba(239,68,68,.15);color:#fca5a5;border:1px solid rgba(239,68,68,.3);padding:12px 16px;border-radius:10px;margin-bottom:1.25rem;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>/admin/sports/create" enctype="multipart/form-data"
        style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.75rem;display:flex;flex-direction:column;gap:1rem;">

        <div>
            <label class="adm-label">Sport Name *</label>
            <input type="text" name="name" required class="adm-input" placeholder="e.g. Volleyball">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div style="max-width: 300px;">
                <label class="adm-label">Header Image</label>
                <label for="header_image" class="upload btn">
                    Upload Image
                </label><span id="file-name-header" style="margin-left: 10px;">No file selected</span>
                <input type="file" id="header_image" name="header_image" accept="image/*" class="adm-file">
            </div>
            <div>
                <label class="adm-label">Logo / Mask Image (PNG)</label>
                <label for="logo_image" class="upload btn">
                    Upload Image
                </label><span id="file-name-logo" style="margin-left: 10px;">No file selected</span>
                <input type="file" id="logo_image" name="logo_image" accept="image/png,image/webp" class="adm-file">
            </div>
        </div>

        <div>
            <label class="adm-label">Description (JSON array of paragraphs)</label>
            <textarea name="description" rows="3" class="adm-input" style="resize:vertical;"
                placeholder='["Paragraph 1.", "Paragraph 2."]'></textarea>
        </div>

        <div>
            <label class="adm-label">History (JSON array)</label>
            <textarea name="history" rows="2" class="adm-input" style="resize:vertical;" placeholder='["History paragraph..."]'></textarea>
        </div>

        <div>
            <label class="adm-label">Rules (JSON array)</label>
            <textarea name="rules" rows="2" class="adm-input" style="resize:vertical;" placeholder='["Rule 1.", "Rule 2."]'></textarea>
        </div>

        <div>
            <label class="adm-label">Equipment (JSON array)</label>
            <textarea name="equipment" rows="2" class="adm-input" style="resize:vertical;" placeholder='["Ball", "Net"]'></textarea>
        </div>

        <div>
            <label class="adm-label">Fun Fact</label>
            <input type="text" name="fact" class="adm-input" placeholder="The fastest goal was scored in 2.4 seconds.">
        </div>

        <div style="display:flex;gap:.75rem;margin-top:.5rem;">
            <button type="submit" class="btn" >Create Sport</button>
            <a href="<?= BASE_URL ?>/admin" class="btn" style="background:#283339;color:#9aa3a6;font-weight:600;padding:.65rem 1.25rem;border-radius:10px;text-decoration:none;">Cancel</a>
        </div>
    </form>
</div>
<script>
    document.getElementById('logo_image').addEventListener('change', function() {
        document.getElementById('file-name-logo').textContent =
            this.files[0]?.name || 'No file selected';
    });

     document.getElementById('header_image').addEventListener('change', function() {
        document.getElementById('file-name-header').textContent =
            this.files[0]?.name || 'No file selected';
    });
</script>