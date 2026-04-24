<?php
class StatementModel extends Model
{
    public function getAll(): array
    {
        return $this->fetchAll(
            'SELECT s.id, s.body, s.image, s.created_at,
                    u.name AS admin_name, u.avatar AS admin_avatar
             FROM statements s JOIN users u ON u.id = s.admin_id
             ORDER BY s.created_at DESC'
        );
    }

    public function create(int $adminId, string $body, ?string $image): int
    {
        $this->executeInsert(
            'INSERT INTO statements (admin_id, body, image) VALUES (?,?,?)',
            [$adminId, $body, $image]
        );
        return $this->lastInsertId();
    }

    public function getReactions(int $statementId): array
    {
        return $this->fetchAll(
            'SELECT emoji, COUNT(*) AS cnt
             FROM statement_reactions WHERE statement_id = ? GROUP BY emoji',
            [$statementId]
        );
    }

    public function getUserReaction(int $statementId, int $userId): array|false
    {
        return $this->fetchOne(
            'SELECT emoji FROM statement_reactions WHERE statement_id = ? AND user_id = ?',
            [$statementId, $userId]
        );
    }

    public function upsertReaction(int $statementId, int $userId, string $emoji): void
    {
        $existing = $this->getUserReaction($statementId, $userId);
        if ($existing) {
            if ($existing['emoji'] === $emoji) {
                // toggle off — remove
                $this->execute('DELETE FROM statement_reactions WHERE statement_id = ? AND user_id = ?', [$statementId, $userId]);
            } else {
                $this->execute('UPDATE statement_reactions SET emoji = ? WHERE statement_id = ? AND user_id = ?', [$emoji, $statementId, $userId]);
            }
        } else {
            $this->executeInsert('INSERT INTO statement_reactions (statement_id, user_id, emoji) VALUES (?,?,?)', [$statementId, $userId, $emoji]);
        }
    }

    public function getAllWithReactions(int $currentUserId = 0): array
    {
        $statements = $this->getAll();
        foreach ($statements as &$s) {
            $s['reactions'] = $this->getReactions($s['id']);
            $s['my_emoji']  = $currentUserId ? ($this->getUserReaction($s['id'], $currentUserId)['emoji'] ?? null) : null;
        }
        return $statements;
    }
}
