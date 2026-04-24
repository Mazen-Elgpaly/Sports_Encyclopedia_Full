<?php
class RecordModel extends Model
{
    public function getAll(?string $sport = null, ?string $search = null): array
    {
        $sql    = 'SELECT r.*, s.name AS sport_name, c.name AS country_name
                   FROM records r JOIN sports s ON s.id = r.sport_id LEFT JOIN countries c ON c.id = r.country_id WHERE 1=1';
        $params = [];
        if ($sport)  { $sql .= ' AND s.name = ?'; $params[] = $sport; }
        if ($search) { $sql .= ' AND (r.athlete_name LIKE ? OR r.record_text LIKE ? OR r.specialty LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; $params[] = "%$search%"; }
        $sql .= ' ORDER BY s.name, r.record_date DESC';
        $rows = $this->fetchAll($sql, $params);
        foreach ($rows as &$row) { $row['extra'] = $row['extra_json'] ? json_decode($row['extra_json'], true) : []; unset($row['extra_json']); }
        return $rows;
    }

    public function getGroupedBySport(?string $search = null): array
    {
        $records = $this->getAll(null, $search);
        $grouped = [];
        foreach ($records as $r) $grouped[$r['sport_name']][] = $r;
        return $grouped;
    }

    public function getSportNames(): array
    {
        return $this->fetchAll('SELECT DISTINCT s.name FROM records r JOIN sports s ON s.id = r.sport_id ORDER BY s.name');
    }
}
