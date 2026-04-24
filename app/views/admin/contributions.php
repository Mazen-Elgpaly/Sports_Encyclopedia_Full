<div style="max-width:1100px;margin:2rem auto;padding:0 1.5rem;">
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
        <div>
            <a href="<?= BASE_URL ?>/admin" style="color:#0da6f2;text-decoration:none;font-size:.9rem;display:block;margin-bottom:.5rem;">← Back to Admin</a>
            <h1 style="font-size:1.75rem;font-weight:900;">📁 Contributions Review</h1>
            <p style="color:#9aa3a6;font-size:.9rem;">Review and approve or reject user submissions.</p>
        </div>
    </div>

    <?php if (empty($all)): ?>
        <div style="text-align:center;color:#9aa3a6;padding:4rem;background:#15181b;border-radius:14px;">
            No contributions have been submitted yet.
        </div>
    <?php endif; ?>

    <?php foreach ($all as $c):
        $statusStyle = match($c['status']) {
            'approved' => 'background:rgba(16,185,129,.15);color:#6ee7b7;border:1px solid rgba(16,185,129,.3);',
            'rejected' => 'background:rgba(239,68,68,.12);color:#fca5a5;border:1px solid rgba(239,68,68,.3);',
            default    => 'background:rgba(245,158,11,.12);color:#fcd34d;border:1px solid rgba(245,158,11,.3);',
        };
        $statusText = ['pending'=>'⏳ Pending','approved'=>'✅ Approved','rejected'=>'❌ Rejected'][$c['status']];
    ?>
    <div style="background:#15181b;border:1px solid #283339;border-radius:14px;padding:1.5rem;margin-bottom:1.25rem;">
        <div style="display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-start;">
            <div style="flex:1;min-width:280px;">
                <strong style="font-size:1rem;"><?= htmlspecialchars($c['title']) ?></strong>
                <div style="font-size:.8rem;color:#9aa3a6;margin-top:.35rem;">
                    By <strong style="color:#0da6f2;"><?= htmlspecialchars($c['user_name']) ?></strong>
                    (<?= htmlspecialchars($c['user_email']) ?>) ·
                    <?= date('M j, Y', strtotime($c['created_at'])) ?>
                </div>
                <?php if ($c['description']): ?>
                    <p style="color:#9aa3a6;font-size:.875rem;margin-top:.6rem;line-height:1.55;"><?= htmlspecialchars($c['description']) ?></p>
                <?php endif; ?>
                <?php if ($c['admin_note']): ?>
                    <div style="margin-top:.6rem;font-size:.85rem;color:#9aa3a6;padding:.5rem;background:#0b0e13;border-radius:8px;">
                        <strong>Admin note:</strong> <?= htmlspecialchars($c['admin_note']) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.5rem;min-width:180px;">
                <span style="<?= $statusStyle ?> padding:.25rem .85rem;border-radius:999px;font-size:.8rem;font-weight:600;">
                    <?= $statusText ?>
                </span>
                <a href="<?= htmlspecialchars(FileUpload::url($c['file_path'])) ?>" target="_blank"
                   style="color:#0da6f2;text-decoration:none;font-size:.875rem;font-weight:600;">
                    📥 Open PDF
                </a>
            </div>
        </div>

        <?php if ($c['status'] === 'pending'): ?>
        <div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid #283339;display:flex;flex-wrap:wrap;gap:.75rem;align-items:flex-end;">
            <!-- Approve -->
            <form method="POST" action="<?= BASE_URL ?>/admin/contributions/approve" style="display:flex;gap:.5rem;align-items:flex-end;flex:1;min-width:240px;">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <input type="text" name="note" placeholder="Optional note to user..."
                       style="flex:1;background:#0b0e13;color:#fff;border:1px solid #283339;border-radius:8px;padding:9px 12px;outline:none;font-size:.875rem;">
                <button type="submit" style="background:rgba(16,185,129,.8);color:#000;font-weight:700;padding:.5rem 1rem;border:none;border-radius:8px;cursor:pointer;white-space:nowrap;">
                    ✅ Approve
                </button>
            </form>
            <!-- Reject -->
            <form method="POST" action="<?= BASE_URL ?>/admin/contributions/reject" style="display:flex;gap:.5rem;align-items:flex-end;flex:1;min-width:240px;">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <input type="text" name="note" placeholder="Reason for rejection..."
                       style="flex:1;background:#0b0e13;color:#fff;border:1px solid #283339;border-radius:8px;padding:9px 12px;outline:none;font-size:.875rem;">
                <button type="submit" style="background:rgba(239,68,68,.8);color:#fff;font-weight:700;padding:.5rem 1rem;border:none;border-radius:8px;cursor:pointer;white-space:nowrap;">
                    ❌ Reject
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
