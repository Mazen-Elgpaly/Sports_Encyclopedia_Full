/* ============================================================
   SPORTS ENCYCLOPEDIA — app-new.js
   UI interactions ONLY. No JSON fetch — data comes from PHP/DB.
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ── Mobile sidebar sub-menu toggles ───────────────────── */
    document.querySelectorAll('.sidebar-item.has-children .toggle-sub').forEach(btn => {
        btn.addEventListener('click', () => {
            const parent = btn.parentElement;
            // Close siblings
            document.querySelectorAll('.sidebar-item.has-children.open').forEach(el => {
                if (el !== parent) el.classList.remove('open');
            });
            parent.classList.toggle('open');
        });
    });

    /* ── Desktop dropdown: close on outside click ───────────── */
    document.addEventListener('click', e => {
        if (!e.target.closest('.nav1')) {
            // Dropdowns close via CSS :hover; nothing extra needed
        }
    });

    /* ── Footer sub-list toggles ────────────────────────────── */
    document.querySelectorAll('.footer-subtoggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.getAttribute('data-target'));
            if (target) target.classList.toggle('open');
        });
    });

    /* ── Navbar: keyboard support for dropdowns ─────────────── */
    document.querySelectorAll('.navul > li > a').forEach(link => {
        link.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') {
                const dropdown = link.parentElement.querySelector('.dropdown');
                if (dropdown) {
                    const visible = dropdown.style.opacity === '1';
                    dropdown.style.opacity        = visible ? '' : '1';
                    dropdown.style.transform      = visible ? '' : 'translateY(0)';
                    dropdown.style.pointerEvents  = visible ? '' : 'auto';
                    e.preventDefault();
                }
            }
        });
    });

    /* ── Navbar active highlight ─────────────────────────────── */
    const currentPath = window.location.pathname;
    document.querySelectorAll('.navul a').forEach(link => {
        const href = link.getAttribute('href') || '';
        try {
            const linkPath = new URL(href, window.location.origin).pathname;
            if (linkPath !== '/' && currentPath.startsWith(linkPath) && linkPath.length > 1) {
                link.classList.add('active');
            }
        } catch(e) {}
    });

    /* ── Stat bars animate on scroll ───────────────────────── */
    const observeTargets = document.querySelectorAll(
        '.progress div[data-target], .skill-bar .bar[data-target]'
    );
    if (observeTargets.length) {
        const io = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el     = entry.target;
                const target = el.dataset.target;
                el.style.width = '0';
                requestAnimationFrame(() => requestAnimationFrame(() => {
                    el.style.transition = 'width 1s ease-out';
                    el.style.width      = target + '%';
                }));
                io.unobserve(el);
            });
        }, { threshold: 0.15 });
        observeTargets.forEach(b => io.observe(b));
    }

    /* ── Flip card intersection observer ───────────────────── */
    const cards = document.querySelectorAll('.cards-container .card');
    if (cards.length) {
        const cardObserver = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('show');
                    cardObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });
        cards.forEach(c => cardObserver.observe(c));
    }

    /* ── circle-img CSS mask ────────────────────────────────── */
    document.querySelectorAll('.circle-img[data-mask]').forEach(icon => {
        const mp = icon.dataset.mask;
        if (mp) {
            icon.style.mask       = `url('${mp}') center/contain no-repeat`;
            icon.style.webkitMask = `url('${mp}') center/contain no-repeat`;
        }
    });

    /* ── Image error fallback ───────────────────────────────── */
    document.querySelectorAll('img[src]').forEach(img => {
        img.addEventListener('error', () => {
            img.style.display = 'none';
        });
    });

    /* ── Auto-close mobile sidebar on link click ────────────── */
    document.querySelectorAll('.sidebar1 .sidebar-list a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobileSidebar')?.classList.remove('open');
        });
    });

    /* ── Resize: close navul dropdowns on mobile ────────────── */
    window.addEventListener('resize', () => {
        if (window.innerWidth <= 980) {
            document.querySelectorAll('.navul > li .dropdown').forEach(d => {
                d.style.opacity = ''; d.style.transform = ''; d.style.pointerEvents = '';
            });
        }
    });

});
