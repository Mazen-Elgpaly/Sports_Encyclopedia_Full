<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/DashboardModel.php';
require_once __DIR__ . '/../models/RecordModel.php';
require_once __DIR__ . '/../models/SportModel.php';
require_once __DIR__ . '/../models/AthleteModel.php';

class HomeController extends Controller
{
    private DashboardModel $dash;
    private RecordModel    $rec;
    private SportModel     $sports;
    private AthleteModel   $athletes;

    public function __construct()
    {
        $this->dash     = new DashboardModel();
        $this->rec      = new RecordModel();
        $this->sports   = new SportModel();
        $this->athletes = new AthleteModel();
    }

    public function index(): void
    {
        $this->requireLogin();
        $sportCards  = $this->dash->getSportCards();
        $ranking     = $this->dash->getRanking();
        $sportNames  = $this->sports->getSportNames();

        // Top athletes for home cards: load first 4 athletes with full data (stats, banner, image)
        $rawAthletes = $this->athletes->getAll(null, null);
        $topAthletes = [];
        foreach (array_slice($rawAthletes, 0, 9) as $a) {
            $full = $this->athletes->getBySlug($a['slug']);
            if ($full) $topAthletes[] = $full;
        }

        $this->render('home/index', compact('sportCards','ranking','sportNames','topAthletes'));
    }

    public function statistics(): void
    {
        $this->requireLogin();
        $overview   = $this->dash->getOverview();
        $popularity = $this->dash->getPopularity();
        $ranking    = $this->dash->getRanking();
        $topPlayers = $this->dash->getTopPlayers();
        $sportCards = $this->dash->getSportCards();
        $this->render('dashboard/statistics', compact('overview','popularity','ranking','topPlayers','sportCards'));
    }

    public function records(): void
    {
        $this->requireLogin();
        $sport      = $this->get('sport');
        $search     = $this->get('search');
        $grouped    = $this->rec->getGroupedBySport($search);
        $sportNames = $this->rec->getSportNames();
        $filtered   = $sport ? [$sport => $grouped[$sport] ?? []] : $grouped;
        $this->render('home/records', compact('filtered','sportNames','sport','search'));
    }
}
