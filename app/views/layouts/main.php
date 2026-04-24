<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/nav.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">

    <?php if (isset($extraCss)): foreach ((array)$extraCss as $css): ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/<?= htmlspecialchars($css) ?>">
    <?php endforeach; endif; ?>
</head>
<body class="dark">

<!-- ════════════════════════════ NAVBAR ════════════════════════ -->
<nav class="nav1">

    <a href="<?= BASE_URL ?>/home" style="text-decoration:none;">
        <div class="nav-left">
            <div class="nav-logo navhead"></div>
            <div class="brand navhead"><?= APP_NAME ?></div>
        </div>
    </a>

    <div class="nav-center">
        <ul class="navul">
            <?php $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
            <li><a href="<?= BASE_URL ?>/home" <?= str_ends_with($uri,'/home')?'class="active"':'' ?>>Home</a></li>

            <li>
                <a href="#">Sports ▾</a>
                <div class="dropdown">
                    <div class="dropdown-grid">
                        <a class="dropdown-item" href="<?= BASE_URL ?>/sports">
                            <span class="icon"><i class="bi bi-info-circle"></i></span>
                            <div><strong>Sports Information</strong><div class="muted">Details, rules &amp; equipment</div></div>
                        </a>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/sports/compare">
                            <span class="icon"><i class="bi bi-diagram-3"></i></span>
                            <div><strong>Compare Sports</strong><div class="muted">Side-by-side comparison</div></div>
                        </a>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/sports/championships">
                            <span class="icon"><i class="bi bi-trophy"></i></span>
                            <div><strong>Official Championships</strong><div class="muted">Olympic &amp; continental</div></div>
                        </a>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/sports/clubs">
                            <span class="icon"><i class="bi bi-building"></i></span>
                            <div><strong>Clubs &amp; Fields</strong><div class="muted">Clubs &amp; venues</div></div>
                        </a>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/tips">
                            <span class="icon"><i class="bi bi-lightbulb"></i></span>
                            <div><strong>Tips &amp; Recommendations</strong><div class="muted">Training &amp; nutrition</div></div>
                        </a>
                    </div>
                </div>
            </li>

            <li>
                <a href="#">Athletes ▾</a>
                <div class="dropdown">
                    <div class="dropdown-grid">
                        <a class="dropdown-item" href="<?= BASE_URL ?>/athletes">
                            <span class="icon"><i class="bi bi-person-lines-fill"></i></span>
                            <div><strong>Player History</strong><div class="muted">Athlete timelines</div></div>
                        </a>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/athletes/compare">
                            <span class="icon"><i class="bi bi-people"></i></span>
                            <div><strong>Compare Athletes</strong><div class="muted">Stats vs stats</div></div>
                        </a>
                        <a class="dropdown-item" href="<?= BASE_URL ?>/athletes/champions">
                            <span class="icon"><i class="bi bi-award"></i></span>
                            <div><strong>Egyptian Champions</strong><div class="muted">Notable national athletes</div></div>
                        </a>
                    </div>
                </div>
            </li>

            <li><a href="<?= BASE_URL ?>/records"    <?= str_contains($uri,'/records')   ?'class="active"':'' ?>>Records</a></li>
            <li><a href="<?= BASE_URL ?>/statistics" <?= str_contains($uri,'/statistics')?'class="active"':'' ?>>Statistics</a></li>
            <li><a href="<?= BASE_URL ?>/statements" <?= str_contains($uri,'/statements')?'class="active"':'' ?>>Statements</a></li>
            <li><a href="<?= BASE_URL ?>/about"      <?= str_contains($uri,'/about')     ?'class="active"':'' ?>>About</a></li>
            <li><a href="<?= BASE_URL ?>/contact"    <?= str_contains($uri,'/contact')   ?'class="active"':'' ?>>Contact</a></li>
        </ul>
    </div>

    <div class="nav-right">
        <!-- FAQ always visible -->
        <a href="<?= BASE_URL ?>/faq" class="btn-outline" style="font-size:.85rem;padding:6px 12px;">FAQ</a>
        <!-- Settings icon — opens /settings page -->
        <a href="<?= BASE_URL ?>/settings" class="nav-item settings" title="Settings"></a>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin" class="btn" style="background:linear-gradient(90deg,#f59e0b,#d97706);color:#000;gap:6px;">
                    <i class="bi bi-shield-check"></i> Admin
                </a>
            <?php endif; ?>
            <!-- ONE profile button only -->
            <a id="profileBtn" href="<?= BASE_URL ?>/profile" style="gap:6px;">
                <!-- <i class="bi bi-person-circle"></i> -->
                 <div class="profilenav">
                    <img src="<?= ($_SESSION['avatar'] ?? null) ? FileUpload::url($_SESSION['avatar']) : null; ?>">
                    <span class="status"></span>
                </div>
                    <!-- <?= htmlspecialchars($_SESSION['user_name']) ?> -->
            </a>
            <a id="logoutBtn" href="<?= BASE_URL ?>/logout" class="btn-outline">Logout</a>
        <?php else: ?>
            <a id="loginBtn" href="<?= BASE_URL ?>/login" class="btn">Login</a>
            <a href="<?= BASE_URL ?>/register" class="btn-outline">Sign Up</a>
        <?php endif; ?>

        <button class="menu-toggle" onclick="document.getElementById('mobileSidebar').classList.add('open')" type="button">
            <i class="bi bi-list"></i>
        </button>
    </div>
</nav>

<!-- ════════════════════════════ MOBILE SIDEBAR ════════════════ -->
<div class="sidebar1" id="mobileSidebar">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <div style="font-weight:700;color:var(--main-color);"><?= APP_NAME ?></div>
        <button id="closeSidebar" onclick="document.getElementById('mobileSidebar').classList.remove('open')"
            style="margin-left:auto;background:transparent;border:none;color:#e6f2fb;font-size:22px;cursor:pointer;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul class="sidebar-list">
        <li><a href="<?= BASE_URL ?>/home">Home</a></li>

        <li class="sidebar-item has-children">
            <button class="toggle-sub" type="button">Sports <i class="bi bi-chevron-down"></i></button>
            <div class="sidebar-sub">
                <a href="<?= BASE_URL ?>/sports">Sports Information</a>
                <a href="<?= BASE_URL ?>/sports/compare">Compare Sports</a>
                <a href="<?= BASE_URL ?>/sports/championships">Official Championships</a>
                <a href="<?= BASE_URL ?>/sports/clubs">Clubs &amp; Fields</a>
                <a href="<?= BASE_URL ?>/tips">Tips &amp; Recommendations</a>
            </div>
        </li>

        <li class="sidebar-item has-children">
            <button class="toggle-sub" type="button">Athletes <i class="bi bi-chevron-down"></i></button>
            <div class="sidebar-sub">
                <a href="<?= BASE_URL ?>/athletes">Player History</a>
                <a href="<?= BASE_URL ?>/athletes/compare">Compare Athletes</a>
                <a href="<?= BASE_URL ?>/athletes/champions">Egyptian Champions</a>
            </div>
        </li>

        <li><a href="<?= BASE_URL ?>/records">Records</a></li>
        <li><a href="<?= BASE_URL ?>/statistics">Statistics</a></li>
        <li><a href="<?= BASE_URL ?>/statements">Statements</a></li>
        <li><a href="<?= BASE_URL ?>/about">About</a></li>
        <li><a href="<?= BASE_URL ?>/contact">Contact</a></li>
        <li><a href="<?= BASE_URL ?>/faq">FAQ</a></li>
        <li><a href="<?= BASE_URL ?>/feedback">Feedback</a></li>
    </ul>

    <div class="sidebar-cta">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a class="btn" href="<?= BASE_URL ?>/profile"><i class="bi bi-person-circle"></i> Profile</a>
            <a class="btn-outline" href="<?= BASE_URL ?>/logout">Logout</a>
        <?php else: ?>
            <a class="btn" href="<?= BASE_URL ?>/login">Login</a>
            <a class="btn-outline" href="<?= BASE_URL ?>/register">Sign Up</a>
        <?php endif; ?>
    </div>
</div>

<!-- ════════════════════════════ MAIN ══════════════════════════ -->
<main>
    <?= $content ?>
</main>

<!-- ════════════════════════════ FOOTER ════════════════════════ -->
<footer id="siteFooter" class="footer">
    <div class="footer-container">
        <div class="footer-left">
            <a href="<?= BASE_URL ?>/home" style="text-decoration:none;">
                <div class="footer-logo"></div>
                <h3 class="footer-brand"><?= APP_NAME ?></h3>
            </a>
            <p class="footer-tagline">Your ultimate source for sports information.</p>
        </div>
        <div class="footer-section">
            <h4 class="footer-title">Navigation</h4>
            <ul class="footer-nav">
                <li><a href="<?= BASE_URL ?>/home">Home</a></li>
                <li>
                    <button class="footer-subtoggle" data-target="footerSports">Sports ▾</button>
                    <ul id="footerSports" class="footer-sublist">
                        <li><a href="<?= BASE_URL ?>/sports">Sports Information</a></li>
                        <li><a href="<?= BASE_URL ?>/sports/compare">Compare Sports</a></li>
                        <li><a href="<?= BASE_URL ?>/sports/championships">Championships</a></li>
                        <li><a href="<?= BASE_URL ?>/sports/clubs">Clubs &amp; Fields</a></li>
                        <li><a href="<?= BASE_URL ?>/tips">Tips</a></li>
                    </ul>
                </li>
                <li>
                    <button class="footer-subtoggle" data-target="footerAthletes">Athletes ▾</button>
                    <ul id="footerAthletes" class="footer-sublist">
                        <li><a href="<?= BASE_URL ?>/athletes">Player History</a></li>
                        <li><a href="<?= BASE_URL ?>/athletes/compare">Compare Athletes</a></li>
                        <li><a href="<?= BASE_URL ?>/athletes/champions">Egyptian Champions</a></li>
                    </ul>
                </li>
                <li><a href="<?= BASE_URL ?>/records">Records</a></li>
                <li><a href="<?= BASE_URL ?>/statistics">Statistics</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4 class="footer-title">Help &amp; Contact</h4>
            <ul class="footer-nav">
                <li><a href="<?= BASE_URL ?>/faq">FAQ</a></li>
                <li><a href="<?= BASE_URL ?>/feedback">Feedback</a></li>
                <li><a href="<?= BASE_URL ?>/contact">Contact</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4 class="footer-title">Follow Us</h4>
            <div class="social-links">
                <a href="#"><i class="bi bi-facebook"></i></a>
                <a href="#"><i class="bi bi-twitter-x"></i></a>
                <a href="#"><i class="bi bi-instagram"></i></a>
            </div>
        </div>
    </div>
    <p class="footer-copy">&copy; <?= date('Y') ?> <?= APP_NAME ?>. All Rights Reserved.</p>
</footer>

<script src="<?= BASE_URL ?>/js/settings-loader.js"></script>
<script src="<?= BASE_URL ?>/js/app-new.js"></script>

<?php if (isset($extraJs)): foreach ((array)$extraJs as $js): ?>
<script src="<?= BASE_URL ?>/js/<?= htmlspecialchars($js) ?>"></script>
<?php endforeach; endif; ?>

</body>
</html>
