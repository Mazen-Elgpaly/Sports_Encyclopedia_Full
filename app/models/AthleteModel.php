<?php
class AthleteModel extends Model
{
    public function getAll(?string $sport = null, ?string $search = null): array
    {
        $sql = 'SELECT a.id, a.slug, a.name, a.image, a.banner,
                       s.name AS sport_name, c.name AS country_name
                FROM athletes a
                JOIN sports s ON s.id = a.sport_id
                LEFT JOIN countries c ON c.id = a.country_id
                WHERE 1=1';
        $params = [];
        if ($sport)  { $sql .= ' AND s.name = ?';                      $params[] = $sport; }
        if ($search) { $sql .= ' AND (a.name LIKE ? OR c.name LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
        $sql .= ' ORDER BY a.name';
        return $this->fetchAll($sql, $params);
    }

    public function getBySlug(string $slug): array|false
    {
        $athlete = $this->fetchOne(
            'SELECT a.*, s.name AS sport_name, c.name AS country_name
             FROM athletes a
             JOIN sports s ON s.id = a.sport_id
             LEFT JOIN countries c ON c.id = a.country_id
             WHERE a.slug = ?', [$slug]
        );
        if (!$athlete) return false;
        $id = $athlete['id'];
        $athlete['stats']    = $this->fetchAll('SELECT stat_label, stat_value FROM athlete_stats WHERE athlete_id = ?', [$id]);
        $athlete['chart']    = $this->fetchAll('SELECT chart_year, chart_value FROM athlete_chart WHERE athlete_id = ? ORDER BY chart_year', [$id]);
        $athlete['timeline'] = $this->fetchAll('SELECT event_year, event_text FROM athlete_timeline WHERE athlete_id = ? ORDER BY event_year', [$id]);
        return $athlete;
    }

    public function getById(int $id): array|false
    {
        return $this->fetchOne(
            'SELECT a.*, s.name AS sport_name, c.name AS country_name
             FROM athletes a
             JOIN sports s ON s.id = a.sport_id
             LEFT JOIN countries c ON c.id = a.country_id
             WHERE a.id = ?', [$id]
        );
    }

    public function getEgyptianChampions(): array
    {
        return $this->fetchAll(
            'SELECT a.id, a.slug, a.name, a.image, a.champion_year, a.achievements, s.name AS sport_name
             FROM athletes a JOIN sports s ON s.id = a.sport_id
             WHERE a.is_egyptian_champion = 1 ORDER BY a.champion_year DESC, a.name'
        );
    }

    // ── Admin CRUD ────────────────────────────────────────────────────────────
    public function create(array $d): int
    {
        $this->executeInsert(
            'INSERT INTO athletes (slug, name, sport_id, country_id, image, banner, chart_about, is_egyptian_champion, champion_year, achievements)
             VALUES (?,?,?,?,?,?,?,?,?,?)',
            [$d['slug'], $d['name'], $d['sport_id'], $d['country_id'] ?: null,
             $d['image'] ?: null, $d['banner'] ?: null, $d['chart_about'] ?: null,
             $d['is_egyptian_champion'] ? 1 : 0, $d['champion_year'] ?: null, $d['achievements'] ?: null]
        );
        return $this->lastInsertId();
    }

    public function update(int $id, array $d): bool
    {
        return $this->execute(
            'UPDATE athletes SET name=?, sport_id=?, country_id=?, image=?, banner=?, chart_about=?, is_egyptian_champion=?, champion_year=?, achievements=? WHERE id=?',
            [$d['name'], $d['sport_id'], $d['country_id'] ?: null,
             $d['image'] ?: null, $d['banner'] ?: null, $d['chart_about'] ?: null,
             $d['is_egyptian_champion'] ? 1 : 0, $d['champion_year'] ?: null, $d['achievements'] ?: null, $id]
        );
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM athletes WHERE id = ?', [$id]);
    }
}
