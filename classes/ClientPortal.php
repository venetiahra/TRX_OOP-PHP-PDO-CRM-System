<?php
class ClientPortal {
    private PDO $conn; private string $table = 'client_portal_accounts';
    public function __construct(PDO $db) { $this->conn = $db; }
    private function clean(?string $v): string { return trim((string)$v); }

    public function login(string $username, string $password) {
        $username = $this->clean($username); $password = $this->clean($password);
        if ($username === '' || $password === '') return 'Portal username and password are required.';
        $sql = "SELECT cpa.id AS account_id, cpa.client_id, cpa.username, cpa.password, c.full_name, c.company_name FROM {$this->table} cpa INNER JOIN clients c ON c.id = cpa.client_id WHERE cpa.username=:username LIMIT 1";
        $stmt = $this->conn->prepare($sql); $stmt->execute([':username' => $username]); $row = $stmt->fetch();
        if (!$row) return 'Client portal account not found.';
        if (!password_verify($password, $row['password'])) return 'Invalid portal password.';
        session_regenerate_id(true);
        $_SESSION['client_portal_account_id'] = (int)$row['account_id'];
        $_SESSION['client_id'] = (int)$row['client_id'];
        $_SESSION['client_username'] = $row['username'];
        $_SESSION['client_full_name'] = $row['full_name'];
        $_SESSION['client_company_name'] = $row['company_name'];
        return true;
    }

    public function logout(): void {
        unset($_SESSION['client_portal_account_id'], $_SESSION['client_id'], $_SESSION['client_username'], $_SESSION['client_full_name'], $_SESSION['client_company_name']);
    }

    public function getByClientId(int $clientId): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE client_id=:client_id LIMIT 1");
        $stmt->execute([':client_id' => $clientId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private function usernameExists(string $username, ?int $excludeClientId = null): bool {
        $sql = "SELECT id FROM {$this->table} WHERE username=:username";
        if ($excludeClientId !== null) $sql .= " AND client_id != :client_id";
        $sql .= " LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        if ($excludeClientId !== null) $stmt->bindValue(':client_id', $excludeClientId, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetch();
    }

    public function createOrUpdateAccount(int $clientId, string $username, ?string $password = null) {
        $username = $this->clean($username); $password = $password !== null ? $this->clean($password) : null;
        $errors = [];
        if ($username === '') $errors[] = 'Portal username is required.';
        if (strlen($username) < 4) $errors[] = 'Portal username must be at least 4 characters.';
        if ($this->usernameExists($username, $clientId)) $errors[] = 'Portal username is already used.';
        $existing = $this->getByClientId($clientId);
        if (!$existing && ($password === null || $password === '')) $errors[] = 'Initial portal password is required.';
        if ($password !== null && $password !== '' && strlen($password) < 8) $errors[] = 'Portal password must be at least 8 characters.';
        if ($errors) return $errors;
        if ($existing) {
            if ($password !== null && $password !== '') {
                $stmt = $this->conn->prepare("UPDATE {$this->table} SET username=:username, password=:password, updated_at=CURRENT_TIMESTAMP WHERE client_id=:client_id");
                return $stmt->execute([':username' => e($username), ':password' => password_hash($password, PASSWORD_DEFAULT), ':client_id' => $clientId]);
            }
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET username=:username, updated_at=CURRENT_TIMESTAMP WHERE client_id=:client_id");
            return $stmt->execute([':username' => e($username), ':client_id' => $clientId]);
        }
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (client_id, username, password) VALUES (:client_id, :username, :password)");
        return $stmt->execute([':client_id' => $clientId, ':username' => e($username), ':password' => password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function changePassword(int $clientId, string $current, string $new, string $confirm) {
        $errors = [];
        if ($current === '') $errors[] = 'Current password is required.';
        if ($new === '') $errors[] = 'New password is required.';
        if (strlen($new) < 8) $errors[] = 'New password must be at least 8 characters.';
        if ($new !== $confirm) $errors[] = 'Password confirmation does not match.';
        if ($errors) return $errors;
        $account = $this->getByClientId($clientId);
        if (!$account || !password_verify($current, $account['password'])) return ['Current password is incorrect.'];
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET password=:password, updated_at=CURRENT_TIMESTAMP WHERE client_id=:client_id");
        return $stmt->execute([':password' => password_hash($new, PASSWORD_DEFAULT), ':client_id' => $clientId]);
    }
}
?>