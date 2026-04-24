<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/AthleteModel.php';
require_once __DIR__ . '/../models/SportModel.php';

class AthletesController extends Controller
{
    private AthleteModel $athletes;
    private SportModel   $sports;

    public function __construct()
    {
        $this->athletes = new AthleteModel();
        $this->sports   = new SportModel();
    }

    public function index(): void
    {
        $this->requireLogin();
        $sport      = $this->get('sport');
        $search     = $this->get('search');
        $athletes   = $this->athletes->getAll($sport, $search);
        $sportNames = $this->sports->getSportNames();
        $this->render('athletes/index', compact('athletes','sportNames','sport','search'));
    }

    public function show(string $slug): void
    {
        $this->requireLogin();
        $athlete = $this->athletes->getBySlug($slug);
        if (!$athlete) { http_response_code(404); $this->render('pages/404'); return; }
        $this->render('athletes/show', compact('athlete'));
    }

    /**
     * Compare — unlimited athletes.
     * Loads ALL athletes with full data (stats+chart+timeline) once, keyed by slug.
     * No duplicate DB calls.
     */
    public function compare(): void
    {
        $this->requireLogin();
        $sport      = $this->get('sport');
        $count      = max(2, min(20, (int)$this->get('count', 2)));
        $sportNames = $this->sports->getSportNames();

        // Step 1: get lightweight list (slug, name, sport, country, image, banner)
        $rawList = $this->athletes->getAll($sport ?: null);

        // Step 2: load full data for each athlete ONCE, indexed by slug
        $bySlug = [];
        foreach ($rawList as $a) {
            if (!isset($bySlug[$a['slug']])) {
                $full = $this->athletes->getBySlug($a['slug']);
                if ($full) $bySlug[$a['slug']] = $full;
            }
        }
        $allAthletes = array_values($bySlug);

        // Step 3: collect pre-selected slugs from URL (a1, a2, ...)
        $selected = [];
        for ($i = 1; $i <= $count; $i++) {
            $slug = $this->get("a{$i}");
            $selected[$i] = ($slug && isset($bySlug[$slug])) ? $bySlug[$slug] : null;
        }

        $this->render('athletes/compare', compact('allAthletes','selected','sportNames','sport','count'));
    }

    public function champions(): void
    {
        $this->requireLogin();
        $sport = $this->get('sport');
        $all   = $this->athletes->getEgyptianChampions();
        if ($sport) $all = array_values(array_filter($all, fn($a) => $a['sport_name'] === $sport));
        $sportNames = array_unique(array_column($this->athletes->getEgyptianChampions(), 'sport_name'));
        $this->render('athletes/champions', ['champions' => $all, 'sportNames' => $sportNames, 'sport' => $sport]);
    }
}
