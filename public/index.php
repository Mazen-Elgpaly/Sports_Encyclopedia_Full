<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/Autoloader.php';

session_start();

// ── Remember-me cookie auto-login ─────────────────────────────────────────────
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $db    = Database::getInstance();
    $token = $_COOKIE['remember_token'];
    $stmt  = $db->prepare('SELECT id, name, role FROM users WHERE remember_token = ? AND token_expires > NOW() LIMIT 1');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $res  = $stmt->get_result();
    $user = $res->fetch_assoc();
    $stmt->close();
    if ($user) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
    }
}

$router = new Router();

// ── Auth ──────────────────────────────────────────────────────────────────────
$router->get( '/login',                  'AuthController',         'loginForm');
$router->post('/login',                  'AuthController',         'login');
$router->get( '/register',               'AuthController',         'registerForm');
$router->post('/register',               'AuthController',         'register');
$router->get( '/logout',                 'AuthController',         'logout');

// ── Home / Dashboard ──────────────────────────────────────────────────────────
$router->get( '/home',                   'HomeController',         'index');
$router->get( '/statistics',             'HomeController',         'statistics');
$router->get( '/records',                'HomeController',         'records');

// ── Static pages ──────────────────────────────────────────────────────────────
$router->get( '/faq',                    'PagesController',        'faq');
$router->get( '/feedback',               'PagesController',        'feedback');
$router->post('/feedback',               'PagesController',        'feedbackSubmit');
$router->get( '/about',                  'PagesController',        'about');
$router->get( '/settings',               'PagesController',        'settings');
$router->get( '/contact',                'PagesController',        'contact');
$router->post('/contact',                'PagesController',        'contactSubmit');
$router->get( '/tips',                   'PagesController',        'tips');

// ── Sports ────────────────────────────────────────────────────────────────────
$router->get( '/sports',                 'SportsController',       'index');
$router->get( '/sports/show/{id}',       'SportsController',       'show');
$router->get( '/sports/compare',         'SportsController',       'compare');
$router->get( '/sports/championships',   'SportsController',       'championships');
$router->get( '/sports/clubs',           'SportsController',       'clubs');

// ── Athletes ──────────────────────────────────────────────────────────────────
$router->get( '/athletes',               'AthletesController',     'index');
$router->get( '/athletes/show/{slug}',   'AthletesController',     'show');
$router->get( '/athletes/compare',       'AthletesController',     'compare');
$router->get( '/athletes/champions',     'AthletesController',     'champions');

// ── Profile (user) ────────────────────────────────────────────────────────────
$router->get( '/profile',                'ProfileController',      'index');
$router->post('/profile',                'ProfileController',      'update');
$router->get( '/profile/settings',       'ProfileController',      'settings');
$router->post('/profile/settings',       'ProfileController',      'saveSettings');
$router->post('/profile/contribute',     'ProfileController',      'submitContribution');
$router->post('/profile/delete-avatar',  'ProfileController',      'deleteAvatar');

// ── Statements (chat) — public read, admin write ──────────────────────────────
$router->get( '/statements',             'StatementsController',   'index');
$router->post('/statements',             'StatementsController',   'store');        // admin
$router->post('/statements/react',       'StatementsController',   'react');        // user (JSON)

// ── Admin panel ───────────────────────────────────────────────────────────────
$router->get( '/admin',                  'AdminController',        'index');
$router->get( '/admin/athletes/create',  'AdminController',        'createAthlete');
$router->post('/admin/athletes/create',  'AdminController',        'storeAthlete');
$router->get( '/admin/athletes/edit/{id}','AdminController',       'editAthlete');
$router->post('/admin/athletes/edit/{id}','AdminController',       'updateAthlete');
$router->post('/admin/athletes/delete',  'AdminController',        'deleteAthlete');

$router->get( '/admin/clubs/create',     'AdminController',        'createClub');
$router->post('/admin/clubs/create',     'AdminController',        'storeClub');
$router->post('/admin/clubs/delete',     'AdminController',        'deleteClub');

$router->get( '/admin/sports/create',    'AdminController',        'createSport');
$router->post('/admin/sports/create',    'AdminController',        'storeSport');
$router->post('/admin/sports/delete',    'AdminController',        'deleteSport');

$router->get( '/admin/contributions',    'AdminController',        'contributions');
$router->post('/admin/contributions/approve', 'AdminController',   'approveContribution');
$router->post('/admin/contributions/reject',  'AdminController',   'rejectContribution');

// ── reset pass  ──────────────────────────────────────────────────────────────────────
$router->get( '/reset',                  'PagesController',         'reset');
$router->get('/otp',                  'PagesController',         'otp');
$router->get( '/resetpass',               'PagesController',         'resetpass');


$router->dispatch();
