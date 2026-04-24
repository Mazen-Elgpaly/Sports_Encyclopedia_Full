<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:60vh;text-align:center;gap:1rem;padding:2rem;">
    <div style="font-size:5rem;animation:bounce 1.5s infinite;">⚽</div>
    <h1 style="font-size:5rem;font-weight:900;color:#0da6f2;line-height:1;">404</h1>
    <h2 style="font-size:1.5rem;">Page Not Found</h2>
    <p style="color:#9aa3a6;max-width:400px;">Looks like this page went off-side. Let's get you back in play.</p>
    <div style="display:flex;gap:.75rem;margin-top:1rem;">
        <a href="<?= BASE_URL ?>/home" style="background:#0da6f2;color:#000;font-weight:700;padding:.65rem 1.25rem;border-radius:10px;text-decoration:none;">🏠 Back to Home</a>
        <a href="<?= BASE_URL ?>/sports" style="background:#283339;color:#9aa3a6;font-weight:600;padding:.65rem 1.25rem;border-radius:10px;text-decoration:none;">⚽ Browse Sports</a>
    </div>
</div>
<style>@keyframes bounce{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}</style>
