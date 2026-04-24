<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/SportModel.php';

class SportsController extends Controller
{
    private SportModel $sports;
    public function __construct() { $this->sports = new SportModel(); }

    public function index(): void
    {
        $this->requireLogin();
        $search = $this->get('search');
        $all    = $this->sports->getAll();
        if ($search) $all = array_values(array_filter($all, fn($s) => stripos($s['name'], $search) !== false));
        $this->render('sports/index', ['sports' => $all, 'search' => $search]);
    }

    public function show(string $id): void
    {
        $this->requireLogin();
        $sport = $this->sports->getById((int)$id);
        if (!$sport) { http_response_code(404); $this->render('pages/404'); return; }
        $sport['stats']   = $this->sports->getStats((int)$id);
        $sport['gallery'] = $this->sports->getGallery((int)$id);
        foreach (['description','history','rules','equipment'] as $f) {
            $sport[$f] = $sport[$f] ? json_decode($sport[$f], true) : [];
        }
        $this->render('sports/show', compact('sport'));
    }

    /**
     * Compare — pass ALL sports with skills + chart already attached.
     * The view uses pure JS to pick/show any number of them (up to MAX_SPORTS=10).
     */
    public function compare(): void
    {
        $this->requireLogin();
        $rawAll = $this->sports->getAll();
        $all = [];
        foreach ($rawAll as $s) {
            $s['skills'] = $this->sports->getSkills((int)$s['id']);
            $s['chart']  = $this->sports->getChartData((int)$s['id']);
            // decode JSON text fields for rules/equipment display in cards
            foreach (['rules','equipment'] as $f) {
                // keep as-is (raw JSON string), JS will handle or we decode here
                $s[$f] = $s[$f] ?? '[]';
            }
            $all[] = $s;
        }
        $this->render('sports/compare', compact('all'));
    }

    public function championships(): void
    {
        $this->requireLogin();
        $sport   = $this->get('sport');
        $all     = $this->sports->getAllChampionships();
        $grouped = ($sport && isset($all[$sport])) ? [$sport => $all[$sport]] : $all;
        $names   = array_keys($all);
        $this->render('sports/championships', compact('grouped','names','sport'));
    }

    public function clubs(): void
    {
        $this->requireLogin();
        $sport      = $this->get('sport');
        $search     = $this->get('search');
        $clubs      = $this->sports->getAllClubs($sport, $search);
        $sportNames = $this->sports->getSportNames();
        $grouped    = [];
        foreach ($clubs as $c) $grouped[$c['sport_name']][] = $c;
        $this->render('sports/clubs', compact('grouped','sportNames','sport','search'));
    }
}
