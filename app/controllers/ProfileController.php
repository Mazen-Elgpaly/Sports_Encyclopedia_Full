<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ContributionModel.php';
require_once __DIR__ . '/../../core/FileUpload.php';

class ProfileController extends Controller
{
    private UserModel         $users;
    private ContributionModel $contrib;

    public function __construct()
    {
        $this->users   = new UserModel();
        $this->contrib = new ContributionModel();
    }

    public function index(): void
    {
        $this->requireLogin();
        $user          = $this->users->findById((int)$_SESSION['user_id']);
        $contributions = $this->contrib->getByUser((int)$_SESSION['user_id']);
        $this->render('profile/index', compact('user','contributions'));
    }

    public function update(): void
    {
        $this->requireLogin();
        $id      = (int)$_SESSION['user_id'];
        $name    = trim($this->post('name',''));
        $email   = trim($this->post('email',''));
        $error   = $success = null;

        // Avatar upload
        if (!empty($_FILES['avatar']['name'])) {
            try {
                $path = FileUpload::upload($_FILES['avatar'], 'avatars', ['image/jpeg','image/png','image/webp'], 2097152);
                $this->users->updateAvatar($id, $path);
                $_SESSION['avatar'] = $path;
            } catch (\Exception $e) {
                $error = 'Avatar: ' . $e->getMessage();
            }
        }

        if (!$error) {
            if (empty($name) || empty($email)) {
                $error = 'Name and email are required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email.';
            } else {
                $this->users->updateProfile($id, $name, $email);
                $_SESSION['user_name'] = $name;
                $success = 'Profile updated successfully.';
            }
        }

        $user          = $this->users->findById($id);
        $contributions = $this->contrib->getByUser($id);
        $this->render('profile/index', compact('user','contributions','error','success'));
    }


   public function deleteAvatar(): void
{
    $this->requireLogin();

    $id = (int)$_SESSION['user_id'];

    $this->users->updateAvatar($id, null);

    if (!empty($_SESSION['avatar'])) {
        $file = __DIR__ . '/../../public/uploads/' . $_SESSION['avatar'];

        if (file_exists($file)) {
            unlink($file);
        }
    }

    unset($_SESSION['avatar']);

    // مهم جدًا: إعادة تحميل البيانات
    $user = $this->users->findById($id);
    $contributions = $this->contrib->getByUser($id);

    $success = "Avatar deleted successfully";

    $this->render('profile/index', compact('user','contributions','success'));
}

    public function settings(): void
    {
        $this->requireLogin();
        $user = $this->users->findById((int)$_SESSION['user_id']);
        $this->render('profile/settings', compact('user'));
    }

    public function saveSettings(): void
    {
        $this->requireLogin();
        $id      = (int)$_SESSION['user_id'];
        $current = $this->post('current_password','');
        $newPw   = $this->post('new_password','');
        $confirm = $this->post('confirm_password','');
        $error   = $success = null;
        $user    = $this->users->findById($id);

        if (!empty($newPw)) {
            if (!$this->users->verifyPassword($current, $user['password_hash'])) {
                $error = 'Current password is incorrect.';
            } elseif (strlen($newPw) < 6) {
                $error = 'New password must be at least 6 characters.';
            } elseif ($newPw !== $confirm) {
                $error = 'Passwords do not match.';
            } else {
                $this->users->updatePassword($id, $newPw);
                $success = 'Password updated successfully.';
            }
        }

        $this->render('profile/settings', compact('user','error','success'));
    }

    public function submitContribution(): void
    {
        $this->requireLogin();
        $id    = (int)$_SESSION['user_id'];
        $title = trim($this->post('title',''));
        $desc  = trim($this->post('description',''));
        $error = $success = null;

        if (empty($title)) {
            $error = 'Title is required.';
        } elseif (empty($_FILES['pdf']['name'])) {
            $error = 'Please attach a PDF file.';
        } else {
            try {
                $path = FileUpload::upload($_FILES['pdf'], 'contributions', ['application/pdf'], 10485760);
                $this->contrib->create($id, $title, $desc ?: null, $path);
                $success = 'Submission received! It is now under review.';
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        $user          = $this->users->findById($id);
        $contributions = $this->contrib->getByUser($id);
        $this->render('profile/index', compact('user','contributions','error','success'));
    }
}
