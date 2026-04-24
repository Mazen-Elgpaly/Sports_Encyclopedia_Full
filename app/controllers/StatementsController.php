<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/StatementModel.php';
require_once __DIR__ . '/../../core/FileUpload.php';

class StatementsController extends Controller
{
    private StatementModel $model;
    public function __construct() { $this->model = new StatementModel(); }

    // GET /statements — all users (logged in) can view
    public function index(): void
    {
        $this->requireLogin();
        $userId     = (int)$_SESSION['user_id'];
        $statements = $this->model->getAllWithReactions($userId);
        $isAdmin    = $this->isAdmin();
        $error      = $_SESSION['stmt_error'] ?? null;
        unset($_SESSION['stmt_error']);
        $this->render('chat/statements', compact('statements','isAdmin','error'));
    }

    // POST /statements — admin only: post new statement
    public function store(): void
    {
        $this->requireAdmin();
        $body  = trim($this->post('body',''));
        $image = null;

        if (empty($body)) {
            $_SESSION['stmt_error'] = 'Statement cannot be empty.';
            $this->redirect('statements');
            return;
        }

        if (!empty($_FILES['image']['name'])) {
            try {
                $image = FileUpload::upload($_FILES['image'], 'chat', ['image/jpeg','image/png','image/webp','image/gif'], 5242880);
            } catch (\Exception $e) {
                $_SESSION['stmt_error'] = 'Image upload failed: ' . $e->getMessage();
                $this->redirect('statements');
                return;
            }
        }

        $this->model->create((int)$_SESSION['user_id'], $body, $image);
        $this->redirect('statements');
    }

    // POST /statements/react — user only (JSON API)
    public function react(): void
    {
        $this->requireLogin();

        if ($this->isAdmin()) {
            $this->json(['error' => 'Admins cannot react to statements.'], 403);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $stmtId = (int)($input['statement_id'] ?? 0);
        $emoji  = $input['emoji'] ?? '';

        $allowed = ['👍','❤️','🔥','😮','😂','👏'];
        if (!$stmtId || !in_array($emoji, $allowed, true)) {
            $this->json(['error' => 'Invalid input.'], 400);
            return;
        }

        $this->model->upsertReaction($stmtId, (int)$_SESSION['user_id'], $emoji);
        $reactions = $this->model->getReactions($stmtId);
        $myEmoji   = $this->model->getUserReaction($stmtId, (int)$_SESSION['user_id'])['emoji'] ?? null;
        $this->json(['reactions' => $reactions, 'my_emoji' => $myEmoji]);
    }
}
