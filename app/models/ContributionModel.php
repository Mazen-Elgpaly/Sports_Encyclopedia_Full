<?php
class ContributionModel extends Model
{
    public function create(int $userId, string $title, ?string $desc, string $filePath): int
    {
        $this->executeInsert(
            'INSERT INTO contributions (user_id, title, description, file_path) VALUES (?,?,?,?)',
            [$userId, $title, $desc, $filePath]
        );
        return $this->lastInsertId();
    }

    public function getByUser(int $userId): array
    {
        return $this->fetchAll(
            'SELECT id, title, description, file_path, status, admin_note, created_at FROM contributions WHERE user_id = ? ORDER BY created_at DESC',
            [$userId]
        );
    }

    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT c.*, u.name AS user_name, u.email AS user_email
             FROM contributions c JOIN users u ON u.id = c.user_id ORDER BY c.created_at DESC'
        );
    }

    public function getPending(): array
    {
        return $this->fetchAll(
            'SELECT c.*, u.name AS user_name FROM contributions c JOIN users u ON u.id = c.user_id WHERE c.status = "pending" ORDER BY c.created_at DESC'
        );
    }

    public function approve(int $id, ?string $note): bool
    {
        return $this->execute(
            'UPDATE contributions SET status = "approved", admin_note = ?, reviewed_at = NOW() WHERE id = ?',
            [$note, $id]
        );
    }

    public function reject(int $id, ?string $note): bool
    {
        return $this->execute(
            'UPDATE contributions SET status = "rejected", admin_note = ?, reviewed_at = NOW() WHERE id = ?',
            [$note, $id]
        );
    }
}
