<?php
class ClubModel extends Model
{
    public function getAll(?string $sport = null, ?string $search = null): array
    {
        $sql    = 'SELECT cl.id, cl.name, cl.governorate, cl.image, s.name AS sport_name
                   FROM clubs cl JOIN sports s ON s.id = cl.sport_id WHERE 1=1';
        $params = [];
        if ($sport)  { $sql .= ' AND s.name = ?'; $params[] = $sport; }
        if ($search) { $sql .= ' AND (cl.name LIKE ? OR cl.governorate LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
        $sql .= ' ORDER BY s.name, cl.name';
        return $this->fetchAll($sql, $params);
    }

    public function create(string $name, int $sportId, ?string $governorate, ?string $image): int
    {
        $this->executeInsert(
            'INSERT INTO clubs (name, sport_id, governorate, image) VALUES (?,?,?,?)',
            [$name, $sportId, $governorate, $image]
        );
        return $this->lastInsertId();
    }

    public function delete(int $id): bool
    {
        return $this->execute('DELETE FROM clubs WHERE id = ?', [$id]);
    }
}
