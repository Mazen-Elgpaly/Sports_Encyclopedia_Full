<?php
class SportModel extends Model
{
    public function getAll(): array
    {
        return $this->fetchAll('SELECT id, name, header_image, logo_image, card_image, fact FROM sports ORDER BY name');
    }

    public function getById(int $id): array|false
    {
        return $this->fetchOne('SELECT * FROM sports WHERE id = ?', [$id]);
    }

    public function getStats(int $sportId): array
    {
        return $this->fetchAll('SELECT stat_name, stat_value FROM sport_stats WHERE sport_id = ?', [$sportId]);
    }

    public function getGallery(int $sportId): array
    {
        return $this->fetchAll('SELECT image_path FROM sport_gallery WHERE sport_id = ? ORDER BY sort_order', [$sportId]);
    }

    public function getSkills(int $sportId): array
    {
        return $this->fetchAll('SELECT skill_name, skill_level FROM sport_skills WHERE sport_id = ? ORDER BY id', [$sportId]);
    }

    public function getChartData(int $sportId): array
    {
        return $this->fetchAll('SELECT chart_year, chart_value FROM sport_popularity_chart WHERE sport_id = ? ORDER BY chart_year', [$sportId]);
    }

    public function getAllChampionships(): array
    {
        $rows = $this->fetchAll(
            'SELECT c.id, c.name, c.image, s.name AS sport_name
             FROM championships c JOIN sports s ON s.id = c.sport_id ORDER BY s.name, c.name'
        );
        $grouped = [];
        foreach ($rows as $row) $grouped[$row['sport_name']][] = $row;
        return $grouped;
    }

    public function getAllClubs(?string $sport = null, ?string $search = null): array
    {
        $sql    = 'SELECT cl.id, cl.name, cl.governorate, cl.image, s.name AS sport_name
                   FROM clubs cl JOIN sports s ON s.id = cl.sport_id WHERE 1=1';
        $params = [];
        if ($sport)  { $sql .= ' AND s.name = ?'; $params[] = $sport; }
        if ($search) { $sql .= ' AND (cl.name LIKE ? OR cl.governorate LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
        $sql .= ' ORDER BY s.name, cl.name';
        return $this->fetchAll($sql, $params);
    }

    public function getSportNames(): array
    {
        return $this->fetchAll('SELECT DISTINCT name FROM sports ORDER BY name');
    }

    // ── Admin CRUD ────────────────────────────────────────────
    public function create(string $name, ?string $headerImage, ?string $logoImage, ?string $description, ?string $history, ?string $rules, ?string $equipment, ?string $fact): int
    {
        $this->executeInsert(
            'INSERT INTO sports (name, header_image, logo_image, description, history, rules, equipment, fact) VALUES (?,?,?,?,?,?,?,?)',
            [$name, $headerImage, $logoImage, $description, $history, $rules, $equipment, $fact]
        );
        return $this->lastInsertId();
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM sports WHERE id = ?', [$id]);
    }
}
