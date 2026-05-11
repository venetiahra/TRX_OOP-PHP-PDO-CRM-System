<?php
class Database {
    private string $host = 'localhost';
    private string $db_name = 'trx_clawd_fortress';
    private string $username = 'root';
    private string $password = '';
    private ?PDO $conn = null;

    public function connect(): PDO {
        if ($this->conn !== null) return $this->conn;
        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $this->host, $this->db_name);
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            exit('Database connection failed: ' . $e->getMessage());
        }
        return $this->conn;
    }
}
?>