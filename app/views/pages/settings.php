<?php
// Settings page — matches original settings.html exactly
// Dark/light mode, font size, language — all saved to localStorage via settings-loader.js
?>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<style>
.settings-root { max-width:1000px; margin:0 auto; padding:20px 30px; }
.settings-root .header { display:flex; align-items:center; gap:16px; margin-bottom:20px; }
.settings-root .page-title { font-size:36px; font-weight:700; margin:0; }
.settings-root .section { margin-top:40px; }
.settings-root .section-title { font-size:22px; font-weight:700; margin-bottom:12px; padding-bottom:4px; }
.settings-root .item {
    display:flex; align-items:center; justify-content:space-between;
    background:transparent; border-bottom:1px solid rgba(255,255,255,.1);
    padding:12px 16px; margin-bottom:12px; border-radius:8px;
}
.settings-root .left { display:flex; align-items:center; gap:12px; }
.settings-root .right { display:flex; align-items:center; gap:8px; }
.settings-root .icon {
    display:flex; justify-content:center; align-items:center;
    width:36px; height:36px; border-radius:8px; font-size:20px;
}
.settings-root .label { font-size:16px; font-weight:400; margin:0; }
.settings-root .sub-label { font-size:12px; color:#B3B3B3; margin:0; }
.settings-root a.link { color:#3B82F6; text-decoration:none; }
.settings-root a.link:hover { text-decoration:underline; }

/* Toggle switch — exact from settings.html */
.switch { position:relative; display:inline-block; width:51px; height:31px; }
.switch input { opacity:0; width:0; height:0; }
.slider {
    position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0;
    background-color:#c3c3c3; border-radius:50px; transition:.4s;
}
.slider::before {
    position:absolute; content:""; height:27px; width:27px;
    left:2px; bottom:2px; background-color:#fff; border-radius:50%; transition:.4s;
}
.switch input:checked + .slider { background-color:#3B82F6; box-shadow:0 0 10px #3B82F6,0 0 20px #3B82F6,0 0 30px #3B82F6; }
.switch input:checked + .slider::before { transform:translateX(20px); }

input[type="range"] { width:300px; accent-color:#3B82F6; }

.settings-select {
    background-color:#283339; color:#D1D5DB; border:1px solid #2E3A44;
    border-radius:8px; padding:6px 12px; appearance:none; cursor:pointer;
    font-size:14px; outline:none;
}
.settings-select:focus { border-color:#3B82F6; }
</style>

<div class="settings-root">
    <div class="header">
        <h1 class="page-title">Settings</h1>
    </div>

    <!-- ── Appearance ── -->
    <section class="section">
        <h2 class="section-title">Appearance</h2>

        <div class="item">
            <div class="left">
                <div class="icon"><span class="material-symbols-outlined">dark_mode</span></div>
                <p class="label" id="themeLabel">Dark Mode</p>
            </div>
            <div class="right">
                <label class="switch">
                    <input type="checkbox" id="theme-checkbox">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="item">
            <div class="left">
                <div class="icon"><span class="material-symbols-outlined">text_fields</span></div>
                <div>
                    <p class="label">Text Size</p>
                    <p class="sub-label">Adjust the text size to your preference</p>
                </div>
            </div>
            <div class="right">
                <input type="range" id="fontRange" min="50" max="150" value="100">
                <span id="fontLabel">100%</span>
            </div>
        </div>
    </section>

    <!-- ── Language ── -->
    <section class="section">
        <h2 class="section-title">Language</h2>
        <div class="item">
            <div class="left">
                <div class="icon"><span class="material-symbols-outlined">language</span></div>
                <div>
                    <p class="label">Language</p>
                    <p class="sub-label">Select your preferred language</p>
                </div>
            </div>
            <div class="right">
                <select class="settings-select" id="langSelect">
                    <option value="en">English</option>
                    <option value="ar">Arabic</option>
                    <option value="fr">French</option>
                </select>
            </div>
        </div>
    </section>

    <!-- ── Notifications ── -->
    <section class="section">
        <h2 class="section-title">Notifications</h2>

        <div class="item">
            <div class="left">
                <div class="icon"><span class="material-symbols-outlined">notifications</span></div>
                <p class="label">Match Reminders</p>
            </div>
            <div class="right">
                <label class="switch">
                    <input type="checkbox" id="notif-reminder" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="item">
            <div class="left">
                <div class="icon"><span class="material-symbols-outlined">article</span></div>
                <p class="label">News Alerts</p>
            </div>
            <div class="right">
                <label class="switch">
                    <input type="checkbox" id="news-alert">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </section>

    <!-- ── Privacy ── -->
    <section class="section">
        <h2 class="section-title">Privacy</h2>

        <div class="item">
            <div class="left">
                <div class="icon"><span class="material-symbols-outlined">policy</span></div>
                <p class="label">Privacy Policy</p>
            </div>
            <a href="#" class="link">View</a>
        </div>

        <div class="item">
            <div class="left">
                <div class="icon"><span class="material-symbols-outlined">share</span></div>
                <p class="label">Data Sharing</p>
            </div>
            <div class="right">
                <label class="switch">
                    <input type="checkbox" id="data-sharing" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="item">
            <div class="left">
                <div class="icon"><span class="material-symbols-outlined">ads_click</span></div>
                <p class="label">Ad Personalization</p>
            </div>
            <div class="right">
                <label class="switch">
                    <input type="checkbox" id="ad-personal">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
    </section>
</div>

<script>
/* ── Settings page UI — reads/writes same localStorage keys as settings-loader.js ── */
(function() {
    const themeKey = 'site_theme';
    const fontKey  = 'site_font_scale';
    const langKey  = 'site_lang';

    const savedTheme = localStorage.getItem(themeKey) || 'dark';
    const savedFont  = parseFloat(localStorage.getItem(fontKey) || '1');
    const savedLang  = localStorage.getItem(langKey)  || 'en';

    // Sync theme toggle to current state
    const themeCheckbox = document.getElementById('theme-checkbox');
    const themeLabel    = document.getElementById('themeLabel');
    const fontRange     = document.getElementById('fontRange');
    const fontLabel     = document.getElementById('fontLabel');
    const langSelect    = document.getElementById('langSelect');

    if (themeCheckbox) {
        themeCheckbox.checked = (savedTheme === 'dark');
        if (themeLabel) themeLabel.textContent = savedTheme === 'dark' ? 'Dark Mode' : 'Light Mode';

        themeCheckbox.addEventListener('change', e => {
            const t = e.target.checked ? 'dark' : 'light';
            localStorage.setItem(themeKey, t);
            if (themeLabel) themeLabel.textContent = t === 'dark' ? 'Dark Mode' : 'Light Mode';
            // Trigger the global settings-loader to apply the theme
            if (window.SiteSettings && window.SiteSettings.applyTheme) {
                window.SiteSettings.applyTheme(t);
            } else {
                // Fallback: apply directly
                document.body.classList.remove('dark','light');
                document.body.classList.add(t);
                document.body.style.backgroundColor = t === 'dark' ? '#0b0e13' : '#f0f4f8';
                document.body.style.color           = t === 'dark' ? '#e6f2fb' : '#1a1a2e';
            }
        });
    }

// ================= FONT SCALE SYSTEM =================
const pxBaselineMap = new Map();

function initializeBaseline() {
    document.querySelectorAll('*').forEach(el => {
        const style = window.getComputedStyle(el);

        // نسجل العناصر اللي عندها font-size بـ px فقط
        if (style.fontSize.endsWith('px') && !pxBaselineMap.has(el)) {
            const base = parseFloat(style.fontSize);
            pxBaselineMap.set(el, base);
        }
    });
}

function applyFontScale(scale) {
    // rem support
    document.documentElement.style.fontSize = (scale * 100) + '%';

    // px support
    pxBaselineMap.forEach((base, el) => {
        el.style.fontSize = (base * scale) + 'px';
    });

    if (fontLabel) fontLabel.textContent = Math.round(scale * 100) + '%';
}

// init
initializeBaseline();
applyFontScale(savedFont);

// UI control
if (fontRange) {
    fontRange.value = Math.round(savedFont * 100);
    if (fontLabel) fontLabel.textContent = fontRange.value + '%';

    const updateFont = () => {
        const scale = parseInt(fontRange.value) / 100;
        localStorage.setItem(fontKey, scale);
        applyFontScale(scale);
    };

    fontRange.addEventListener('input', updateFont);
    fontRange.addEventListener('change', updateFont);
}

    if (langSelect) {
        langSelect.value = savedLang;
        langSelect.addEventListener('change', e => {
            const l = e.target.value;
            localStorage.setItem(langKey, l);
            if (window.SiteSettings && window.SiteSettings.setLang) {
                window.SiteSettings.setLang(l);
            }
        });
    }

    // Other toggles
    [
        {id:'notif-reminder', key:'switch_state_notif'},
        {id:'news-alert',     key:'switch_state_news'},
        {id:'data-sharing',   key:'switch_state_data'},
        {id:'ad-personal',    key:'switch_state_ad'},
    ].forEach(sw => {
        const el = document.getElementById(sw.id);
        if (!el) return;
        const stored = localStorage.getItem(sw.key);
        if (stored !== null) el.checked = (stored === 'true');
        el.addEventListener('change', e => localStorage.setItem(sw.key, e.target.checked));
    });
})();
</script>
