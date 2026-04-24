<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/AthleteModel.php';
require_once __DIR__ . '/../models/ClubModel.php';
require_once __DIR__ . '/../models/SportModel.php';
require_once __DIR__ . '/../models/ContributionModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../../core/FileUpload.php';

class AdminController extends Controller
{
    private AthleteModel      $athletes;
    private ClubModel         $clubs;
    private SportModel        $sports;
    private ContributionModel $contrib;

    public function __construct()
    {
        $this->athletes = new AthleteModel();
        $this->clubs    = new ClubModel();
        $this->sports   = new SportModel();
        $this->contrib  = new ContributionModel();
    }

    // ── Dashboard ─────────────────────────────────────────────────────────────
    public function index(): void
    {
        $this->requireAdmin();
        $pending  = $this->contrib->getPending();
        $athletes = $this->athletes->getAll();
        $sports   = $this->sports->getAll();
        $clubs    = $this->clubs->getAll();
        $this->render('admin/index', compact('pending','athletes','sports','clubs'));
    }

    // ── Athletes ──────────────────────────────────────────────────────────────
    public function createAthlete(): void
    {
        $this->requireAdmin();
        $sports = $this->sports->getAll();
        $this->render('admin/athlete_form', ['sports' => $sports, 'athlete' => null, 'error' => null]);
    }

    public function storeAthlete(): void
    {
        $this->requireAdmin();
        $d = $this->collectAthletePost();
        try {
            // Upload image & banner if provided
            if (!empty($_FILES['image']['name']))  $d['image']  = FileUpload::upload($_FILES['image'],  'athletes', ['image/jpeg','image/png','image/webp']);
            if (!empty($_FILES['banner']['name'])) $d['banner'] = FileUpload::upload($_FILES['banner'], 'athletes', ['image/jpeg','image/png','image/webp']);
            $this->athletes->create($d);
            $this->redirect('admin');
        } catch (\Exception $e) {
            $sports = $this->sports->getAll();
            $this->render('admin/athlete_form', ['sports' => $sports, 'athlete' => $d, 'error' => $e->getMessage()]);
        }
    }

    public function editAthlete(string $id): void
    {
        $this->requireAdmin();
        $athlete = $this->athletes->getById((int)$id);
        if (!$athlete) { $this->redirect('admin'); return; }
        $sports = $this->sports->getAll();
        $this->render('admin/athlete_form', compact('sports','athlete') + ['error' => null]);
    }

    public function updateAthlete(string $id): void
    {
        $this->requireAdmin();
        $d = $this->collectAthletePost();
        try {
            if (!empty($_FILES['image']['name']))  $d['image']  = FileUpload::upload($_FILES['image'],  'athletes', ['image/jpeg','image/png','image/webp']);
            if (!empty($_FILES['banner']['name'])) $d['banner'] = FileUpload::upload($_FILES['banner'], 'athletes', ['image/jpeg','image/png','image/webp']);
            $this->athletes->update((int)$id, $d);
            $this->redirect('admin');
        } catch (\Exception $e) {
            $athlete = array_merge($this->athletes->getById((int)$id) ?: [], $d, ['id' => (int)$id]);
            $sports  = $this->sports->getAll();
            $this->render('admin/athlete_form', compact('sports','athlete') + ['error' => $e->getMessage()]);
        }
    }

    public function deleteAthlete(): void
    {
        $this->requireAdmin();
        $this->athletes->delete((int)$this->post('id',0));
        $this->redirect('admin');
    }

    // ── Clubs ─────────────────────────────────────────────────────────────────
    public function createClub(): void
    {
        $this->requireAdmin();
        $sports = $this->sports->getAll();
        $this->render('admin/club_form', ['sports' => $sports, 'error' => null]);
    }

    public function storeClub(): void
    {
        $this->requireAdmin();
        $name  = trim($this->post('name',''));
        $sId   = (int)$this->post('sport_id',0);
        $gov   = trim($this->post('governorate','')) ?: null;
        $image = null;

        if (empty($name) || !$sId) {
            $sports = $this->sports->getAll();
            $this->render('admin/club_form', ['sports' => $sports, 'error' => 'Name and sport are required.']);
            return;
        }
        if (!empty($_FILES['image']['name'])) {
            try { $image = FileUpload::upload($_FILES['image'], 'clubs', ['image/jpeg','image/png','image/webp']); } catch (\Exception $e) {}
        }
        $this->clubs->create($name, $sId, $gov, $image);
        $this->redirect('admin');
    }

    public function deleteClub(): void
    {
        $this->requireAdmin();
        $this->clubs->delete((int)$this->post('id',0));
        $this->redirect('admin');
    }

    // ── Sports ────────────────────────────────────────────────────────────────
    public function createSport(): void
    {
        $this->requireAdmin();
        $this->render('admin/sport_form', ['error' => null]);
    }

    public function storeSport(): void
    {
        $this->requireAdmin();
        $name = trim($this->post('name',''));
        if (empty($name)) {
            $this->render('admin/sport_form', ['error' => 'Sport name is required.']);
            return;
        }
        $headerImage = $logoImage = null;
        if (!empty($_FILES['header_image']['name'])) {
            try { $headerImage = FileUpload::upload($_FILES['header_image'], 'sports', ['image/jpeg','image/png','image/webp']); } catch (\Exception $e) {}
        }
        if (!empty($_FILES['logo_image']['name'])) {
            try { $logoImage = FileUpload::upload($_FILES['logo_image'], 'sports', ['image/png','image/webp']); } catch (\Exception $e) {}
        }
        $this->sports->create(
            $name, $headerImage, $logoImage,
            $this->post('description') ?: null,
            $this->post('history') ?: null,
            $this->post('rules') ?: null,
            $this->post('equipment') ?: null,
            $this->post('fact') ?: null
        );
        $this->redirect('admin');
    }

    public function deleteSport(): void
    {
        $this->requireAdmin();
        $this->sports->delete((int)$this->post('id',0));
        $this->redirect('admin');
    }

    // ── Contributions ─────────────────────────────────────────────────────────
    public function contributions(): void
    {
        $this->requireAdmin();
        $all = $this->contrib->getAll();
        $this->render('admin/contributions', compact('all'));
    }

    public function approveContribution(): void
    {
        $this->requireAdmin();
        $this->contrib->approve((int)$this->post('id',0), $this->post('note') ?: null);
        $this->redirect('admin/contributions');
    }

    public function rejectContribution(): void
    {
        $this->requireAdmin();
        $this->contrib->reject((int)$this->post('id',0), $this->post('note') ?: null);
        $this->redirect('admin/contributions');
    }

    // ── Helper ────────────────────────────────────────────────────────────────
    private function collectAthletePost(): array
    {
        return [
            'slug'                 => trim($this->post('slug','')),
            'name'                 => trim($this->post('name','')),
            'sport_id'             => (int)$this->post('sport_id',0),
            'country_id'           => (int)$this->post('country_id',0) ?: null,
            'image'                => null,
            'banner'               => null,
            'chart_about'          => trim($this->post('chart_about','')) ?: null,
            'is_egyptian_champion' => (bool)$this->post('is_egyptian_champion'),
            'champion_year'        => (int)$this->post('champion_year',0) ?: null,
            'achievements'         => trim($this->post('achievements','')) ?: null,
        ];
    }
}
