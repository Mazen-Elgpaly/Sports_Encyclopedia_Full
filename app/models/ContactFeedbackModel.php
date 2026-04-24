<?php
class FeedbackModel extends Model
{
    public function create(int|null $userId, string $name, string $email, string $message, int $rating): int
    {
        $this->executeInsert(
            'INSERT INTO feedback (user_id, name, email, message, rating) VALUES (?,?,?,?,?)',
            [$userId, $name, $email, $message, $rating]
        );
        return $this->lastInsertId();
    }

    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT f.*, u.name AS user_name
             FROM feedback f LEFT JOIN users u ON u.id = f.user_id
             ORDER BY f.created_at DESC'
        );
    }
}

class ContactModel extends Model
{
    public function create(int|null $userId, string $name, string $email, string $subject, string $message): int
    {
        $this->executeInsert(
            'INSERT INTO contact_messages (user_id, name, email, subject, message) VALUES (?,?,?,?,?)',
            [$userId, $name, $email, $subject, $message]
        );
        return $this->lastInsertId();
    }

    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT c.*, u.name AS user_name
             FROM contact_messages c LEFT JOIN users u ON u.id = c.user_id
             ORDER BY c.created_at DESC'
        );
    }

    public function getUnreadCount(): int
    {
        $row = $this->fetchOne('SELECT COUNT(*) AS cnt FROM contact_messages WHERE is_read = 0');
        return (int)($row['cnt'] ?? 0);
    }

    public function markRead(int $id): void
    {
        $this->execute('UPDATE contact_messages SET is_read = 1 WHERE id = ?', [$id]);
    }
}
