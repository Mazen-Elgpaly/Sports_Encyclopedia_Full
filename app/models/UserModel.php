<?php
class UserModel extends Model
{
    public function findByEmail(string $email): array|false
    {
        return $this->fetchOne('SELECT * FROM users WHERE email = ? LIMIT 1', [$email]);
    }

    public function findById(int $id): array|false
    {
        return $this->fetchOne('SELECT * FROM users WHERE id = ? LIMIT 1', [$id]);
    }

    public function emailExists(string $email): bool
    {
        return (bool)$this->fetchOne('SELECT id FROM users WHERE email = ? LIMIT 1', [$email]);
    }

    public function create(string $name, string $email, string $password): int
    {
        $this->executeInsert(
            'INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, "user")',
            [$name, $email, password_hash($password, PASSWORD_BCRYPT)]
        );
        return $this->lastInsertId();
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    // ── Remember Me ───────────────────────────────────────────────────────────
    public function setRememberToken(int $id, string $token, string $expires): void
    {
        $this->execute(
            'UPDATE users SET remember_token = ?, token_expires = ? WHERE id = ?',
            [$token, $expires, $id]
        );
    }

    public function clearRememberToken(int $id): void
    {
        $this->execute('UPDATE users SET remember_token = NULL, token_expires = NULL WHERE id = ?', [$id]);
    }

    // ── Profile ───────────────────────────────────────────────────────────────
    public function updateProfile(int $id, string $name, string $email): bool
    {
        return $this->execute('UPDATE users SET name = ?, email = ? WHERE id = ?', [$name, $email, $id]);
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        return $this->execute(
            'UPDATE users SET password_hash = ? WHERE id = ?',
            [password_hash($newPassword, PASSWORD_BCRYPT), $id]
        );
    }

    public function updateAvatar(int $id, string $path): bool
    {
        return $this->execute('UPDATE users SET avatar = ? WHERE id = ?', [$path, $id]);
    }

    // ── Admin: user list ──────────────────────────────────────────────────────
    public function getAll(): array
    {
        return $this->fetchAll('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC');
    }
}
