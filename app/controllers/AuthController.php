<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController extends Controller
{
    private UserModel $users;
    public function __construct() { $this->users = new UserModel(); }

    // GET /login
    public function loginForm(): void
    {
        if ($this->isLoggedIn()) { $this->redirect('home'); }
        $this->render('auth/login', ['error' => null], 'auth');
    }

    // POST /login
    public function login(): void
    {
        $email    = trim($this->post('email', ''));
        $password = $this->post('password', '');
        $remember = (bool)$this->post('remember_me');
        $error    = null;

        if (empty($email) || empty($password)) {
            $error = 'Please fill in all fields.';
        } else {
            $user = $this->users->findByEmail($email);
            if (!$user || !$this->users->verifyPassword($password, $user['password_hash'])) {
                $error = 'Invalid email or password.';
            } else {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['avatar']    = $user['avatar'];

                if ($remember) {
                    $token   = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', time() + 60 * 60 * 24 * 30); // 30 days
                    $this->users->setRememberToken($user['id'], $token, $expires);
                    setcookie('remember_token', $token, time() + 60 * 60 * 24 * 30, '/', '', false, true);
                }

                $this->redirect('home');
            }
        }

        $this->render('auth/login', compact('error'), 'auth');
    }

    // GET /register
    public function registerForm(): void
    {
        if ($this->isLoggedIn()) { $this->redirect('home'); }
        $this->render('auth/register', ['error' => null, 'old' => []], 'auth');
    }

    // POST /register
    public function register(): void
    {
        $name     = trim($this->post('name', ''));
        $email    = trim($this->post('email', ''));
        $password = $this->post('password', '');
        $confirm  = $this->post('confirm_password', '');
        $old      = compact('name', 'email');
        $error    = null;

        if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
            $error = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif ($this->users->emailExists($email)) {
            $error = 'Email already registered.';
        } else {
            $id = $this->users->create($name, $email, $password);
            $_SESSION['user_id']   = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = 'user';
            $this->redirect('home');
        }

        $this->render('auth/register', compact('error', 'old'), 'auth');
    }

    // GET /logout
    public function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->users->clearRememberToken((int)$_SESSION['user_id']);
        }
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        session_destroy();
        $this->redirect('login');
    }
}
