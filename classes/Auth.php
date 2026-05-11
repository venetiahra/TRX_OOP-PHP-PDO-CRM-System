<?php
class Auth {
    private PDO $conn;
    private string $table = 'users';
    public function __construct(PDO $db) { $this->conn = $db; }
    private function clean(?string $v): string { return trim((string)$v); }

    public function login(string $username, string $password) {
        $username = $this->clean($username); $password = $this->clean($password);
        if ($username === '' || $password === '') return 'Username and password are required.';
        $stmt = $this->conn->prepare("SELECT id, full_name, username, password FROM {$this->table} WHERE username=:username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        if (!$user) return 'User not found.';
        if (!password_verify($password, $user['password'])) return 'Invalid password.';
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id']; $_SESSION['full_name'] = $user['full_name']; $_SESSION['username'] = $user['username'];
        return true;
    }

    public function register(array $data) {
        $fullName = $this->clean($data['full_name'] ?? '');
        $username = $this->clean($data['username'] ?? '');
        $password = $this->clean($data['password'] ?? '');
        $confirm = $this->clean($data['confirm_password'] ?? '');
        $errors = [];
        if ($fullName === '') $errors[] = 'Full name is required.';
        if ($username === '') $errors[] = 'Username is required.';
        if (strlen($username) < 4) $errors[] = 'Username must be at least 4 characters.';
        if ($password === '') $errors[] = 'Password is required.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if ($password !== $confirm) $errors[] = 'Passwords do not match.';
        $check = $this->conn->prepare("SELECT id FROM {$this->table} WHERE username=:username LIMIT 1");
        $check->execute([':username' => $username]);
        if ($check->fetch()) $errors[] = 'Username is already taken.';
        if ($errors) return $errors;
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (full_name, username, password) VALUES (:full_name, :username, :password)");
        return $stmt->execute([':full_name' => e($fullName), ':username' => e($username), ':password' => password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function changePassword(int $userId, string $current, string $new, string $confirm) {
        $errors = [];
        if ($current === '') $errors[] = 'Current password is required.';
        if ($new === '') $errors[] = 'New password is required.';
        if (strlen($new) < 8) $errors[] = 'New password must be at least 8 characters.';
        if ($new !== $confirm) $errors[] = 'Password confirmation does not match.';
        if ($errors) return $errors;
        $stmt = $this->conn->prepare("SELECT password FROM {$this->table} WHERE id=:id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($current, $user['password'])) return ['Current password is incorrect.'];
        $upd = $this->conn->prepare("UPDATE {$this->table} SET password=:password, updated_at=CURRENT_TIMESTAMP WHERE id=:id");
        return $upd->execute([':password' => password_hash($new, PASSWORD_DEFAULT), ':id' => $userId]);
    }

    public function logout(): void {
        session_unset(); session_destroy();
    }
}
?>