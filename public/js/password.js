document.addEventListener("DOMContentLoaded", function () {
    const password = document.getElementById('password');
    const bar = document.getElementById('bar');
    const strengthText = document.getElementById('strength');

    password.addEventListener('input', () => {
        const val = password.value;
        let score = 0;

        // ===== 1. Length (وزن عالي بس مش مبالغ) =====
        if (val.length >= 6) score += 1;
        if (val.length >= 8) score += 1;
        if (val.length >= 10) score += 1;
        if (val.length >= 12) score += 1;

        // ===== 2. Variety =====
        if (/[a-z]/.test(val)) score += 1;
        if (/[A-Z]/.test(val)) score += 1;
        if (/[0-9]/.test(val)) score += 1;
        if (/[^A-Za-z0-9]/.test(val)) score += 1;

        // ===== 3. Bonus (mix حقيقي) =====
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score += 1;
        if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score += 1;

        // ===== 4. Penalties (خفيفة مش عنيفة) =====
        if (/(.)\1{2,}/.test(val)) score -= 1; // aaa
        if (/123|abc/i.test(val)) score -= 1;

        // clamp
        score = Math.max(0, Math.min(score, 10));

        // ===== UI =====
        let width = (score / 10) * 100;
        let color = "#e74c3c";
        let text = "";

        if (score <= 2) {
            color = "#e74c3c";
            text = "Very Weak";
        } else if (score <= 4) {
            color = "#e67e22";
            text = "Weak";
        } else if (score <= 6) {
            color = "#f1c40f";
            text = "Good";
        } else if (score <= 8) {
            color = "#2ecc71";
            text = "Strong";
        } else {
            color = "#27ae60";
            text = "Excellent";
        }

        bar.style.width = width + "%";
        bar.style.background = color;
        strengthText.textContent = text;
    });
});