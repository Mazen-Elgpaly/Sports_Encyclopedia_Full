<?php
abstract class Model
{
    protected mysqli $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function query(string $sql, array $params = []): mysqli_stmt
    {
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            throw new RuntimeException('Prepare failed: ' . $this->db->error . ' | SQL: ' . $sql);
        }
        if (!empty($params)) {
            $types = '';
            foreach ($params as $p) {
                if (is_int($p))    $types .= 'i';
                elseif (is_float($p)) $types .= 'd';
                else               $types .= 's';
            }
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt   = $this->query($sql, $params);
        $result = $stmt->get_result();
        $rows   = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        $result->free();
        $stmt->close();
        return $rows;
    }

    protected function fetchOne(string $sql, array $params = []): array|false
    {
        $stmt   = $this->query($sql, $params);
        $result = $stmt->get_result();
        $row    = $result->fetch_assoc();
        $result->free();
        $stmt->close();
        return $row ?: false;
    }

    protected function execute(string $sql, array $params = []): bool
    {
        $stmt     = $this->query($sql, $params);
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }

    protected function executeInsert(string $sql, array $params = []): bool
    {
        $stmt = $this->query($sql, $params);
        $stmt->close();
        return true;
    }

    protected function lastInsertId(): int
    {
        return (int)$this->db->insert_id;
    }
}
