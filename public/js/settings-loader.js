/**
 * settings-loader.js
 * Loads saved theme, font scale, and language on every page.
 * Exact replica of original settings-loader.js logic.
 * Keys: site_theme, site_font_scale, site_lang
 */
(function () {
    const themeKey = 'site_theme';
    const fontKey  = 'site_font_scale';
    const langKey  = 'site_lang';

    const savedTheme = localStorage.getItem(themeKey) || 'dark';
    const savedFont  = parseFloat(localStorage.getItem(fontKey) || '1');
    const savedLang  = localStorage.getItem(langKey)  || 'en';

    /* ── Apply theme ───────────────────────────────────────── */
    function applyTheme(theme) {
        const body = document.body;
        body.classList.remove('dark', 'light');
        body.classList.add(theme);

        if (theme === 'dark') {
            body.style.setProperty('background-color', '#0b0e13', 'important');
            body.style.setProperty('color', '#e6f2fb', 'important');
        } else {
            body.style.setProperty('background-color', '#f0f4f8', 'important');
            body.style.setProperty('color', '#1a1a2e', 'important');
        }

        // Apply to html element too (for CSS selectors like html.dark)
        document.documentElement.classList.remove('dark', 'light');
        document.documentElement.classList.add(theme);
    }

    /* ── Apply font scale ──────────────────────────────────── */
    function applyFontScale(scale) {
        document.documentElement.style.fontSize = (scale * 100) + '%';
    }

    /* ── Apply language (RTL/LTR) ──────────────────────────── */
    async function applyLanguage(lang) {
        try {
            document.documentElement.setAttribute('dir', lang === 'ar' ? 'rtl' : 'ltr');
            // Translation fetch removed (no translations folder) — dir only
        } catch (e) {}
    }

    // Apply immediately (before DOMContentLoaded for no flash)
    applyFontScale(savedFont);
    applyTheme(savedTheme);
    applyLanguage(savedLang);

    // Expose global API for settings page
    window.SiteSettings = {
        applyTheme: (t) => {
            localStorage.setItem(themeKey, t);
            applyTheme(t);
        },
        applyFontScale: (s) => {
            localStorage.setItem(fontKey, s);
            applyFontScale(s);
        },
        setLang: (l) => {
            localStorage.setItem(langKey, l);
            applyLanguage(l);
        },
        getTheme:     () => localStorage.getItem(themeKey) || 'dark',
        getFontScale: () => parseFloat(localStorage.getItem(fontKey) || '1'),
        getLang:      () => localStorage.getItem(langKey)  || 'en',
    };
})();
