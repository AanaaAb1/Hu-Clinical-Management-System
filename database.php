<?php
class Database
{
    private static $pdo = null;

    public static function connect()
    {
        if (self::$pdo === null) {
            try {
                $host = 'localhost';
                $dbname = 'haramaya_cms';
                $username = 'root';
                $password = '';

                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    // Optional: Add getInstance method if you prefer that name
    public static function getInstance()
    {
        return self::connect();
    }

    // Helper method for insert
    public static function insert($table, $data)
    {
        $pdo = self::connect();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute(array_values($data));
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Database insert failed: " . $e->getMessage());
        }
    }

    // Helper method for select
    public static function select($table, $conditions = [], $limit = null)
    {
        $pdo = self::connect();
        $sql = "SELECT * FROM {$table}";
        $params = [];

        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "{$key} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        if ($limit !== null) {
            $sql .= " LIMIT " . intval($limit);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ADDED: Fetch single row
    public static function fetch($sql, $params = [])
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    // ADDED: Execute query and return all rows
    public static function query($sql, $params = [])
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // ADDED: Update method
    public static function update($table, $data, $where, $whereParams = [])
    {
        $pdo = self::connect();

        $setClauses = [];
        $params = [];

        foreach ($data as $key => $value) {
            $setClauses[] = "{$key} = ?";
            $params[] = $value;
        }

        // Add where parameters
        $params = array_merge($params, $whereParams);

        $sql = "UPDATE {$table} SET " . implode(', ', $setClauses) . " WHERE {$where}";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($params);
    }

    // ADDED: Delete method
    public static function delete($table, $where, $params = [])
    {
        $pdo = self::connect();
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // ADDED: Execute raw SQL (for CREATE, ALTER, DROP)
    public static function execute($sql, $params = [])
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }
    // ADDED: Count rows
    public static function count($table, $conditions = [])
    {
        $pdo = self::connect();
        $sql = "SELECT COUNT(*) as count FROM {$table}";
        $params = [];

        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "{$key} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}