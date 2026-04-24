<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/ContactFeedbackModel.php';

class PagesController extends Controller
{
    private FeedbackModel $feedbackModel;
    private ContactModel  $contactModel;

    public function __construct()
    {
        $this->feedbackModel = new FeedbackModel();
        $this->contactModel  = new ContactModel();
    }

    public function faq(): void
    {
        $this->requireLogin();
        $faqs = [
            ['q' => 'How do I compare two or more athletes?',
             'a' => 'Go to Athletes → Compare Athletes. Use the count selector to add up to 20 athletes and pick each one from the dropdowns.'],
            ['q' => 'What sports are covered?',
             'a' => 'Football, Basketball, Tennis, Swimming, Athletics, Cycling, Boxing, Bodybuilding, Squash and more.'],
            ['q' => 'How often is data updated?',
             'a' => 'Data is reviewed and updated regularly by our admin team.'],
            ['q' => 'Can I submit content?',
             'a' => 'Yes! Go to your Profile and use the Contribution section to upload a PDF for admin review.'],
            ['q' => 'What is the Statements page?',
             'a' => 'Admins post official statements and announcements. Regular users can react with emojis.'],
            ['q' => 'How do I change my password?',
             'a' => 'Go to Profile → Settings.'],
            ['q' => 'Are clubs limited to Egypt?',
             'a' => 'No, the Clubs section includes both Egyptian and international clubs.'],
        ];
        $this->render('pages/faq', compact('faqs'));
    }

    public function feedback(): void
    {
        $this->requireLogin();
        $this->render('pages/feedback', ['success' => false, 'error' => null]);
    }

    public function feedbackSubmit(): void
    {
        $this->requireLogin();
        $name    = trim($this->post('name', ''));
        $email   = trim($this->post('email', ''));
        $message = trim($this->post('message', ''));
        $rating  = max(0, min(5, (int)$this->post('rating', 0)));

        if (empty($name) || empty($email) || empty($message)) {
            $this->render('pages/feedback', ['success' => false, 'error' => 'Please fill in all fields.']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('pages/feedback', ['success' => false, 'error' => 'Invalid email address.']);
            return;
        }

        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $this->feedbackModel->create($userId, $name, $email, $message, $rating);
        $this->render('pages/feedback', ['success' => true, 'error' => null]);
    }

    public function contact(): void
    {
        $this->requireLogin();
        $this->render('pages/contact', ['success' => false, 'error' => null]);
    }

    public function contactSubmit(): void
    {
        $this->requireLogin();
        $name    = trim($this->post('name', ''));
        $email   = trim($this->post('email', ''));
        $subject = trim($this->post('subject', ''));
        $message = trim($this->post('message', ''));

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $this->render('pages/contact', ['success' => false, 'error' => 'Please fill in all fields.']);
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->render('pages/contact', ['success' => false, 'error' => 'Invalid email address.']);
            return;
        }

        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $this->contactModel->create($userId, $name, $email, $subject, $message);
        $this->render('pages/contact', ['success' => true, 'error' => null]);
    }

    public function about(): void  { $this->requireLogin(); $this->render('pages/about'); }
    public function settings(): void { $this->requireLogin(); $this->render('pages/settings'); }
    public function tips(): void   { $this->requireLogin(); $this->render('pages/tips'); }
    public function notFound(): void { http_response_code(404); $this->render('pages/404'); }
    public function reset(): void { $this->render('pages/reset',[], 'auth'); }
    public function otp(): void { $this->render('pages/otp',[], 'auth'); }
    public function resetpass(): void { $this->render('pages/resetpass',[], 'auth'); }
}
