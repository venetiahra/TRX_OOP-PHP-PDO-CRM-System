<?php
class Client {
    private PDO $conn; private string $table = 'clients';
    public function __construct(PDO $db) { $this->conn = $db; }
    private function sanitize(?string $v): string { return e(trim((string)$v)); }

    private function baseWhere(string $search): array {
        $where = ''; $params = [];
        if ($search !== '') { $where = " WHERE full_name LIKE :search OR company_name LIKE :search OR email LIKE :search OR contact_number LIKE :search "; $params[':search'] = '%' . $search . '%'; }
        return [$where, $params];
    }
    private function existsByEmail(string $email, ?int $excludeId = null): bool {
        $sql = "SELECT id FROM {$this->table} WHERE email=:email"; if ($excludeId !== null) $sql .= " AND id != :exclude_id"; $sql .= " LIMIT 1";
        $stmt = $this->conn->prepare($sql); $stmt->bindValue(':email', $email, PDO::PARAM_STR); if ($excludeId !== null) $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT); $stmt->execute(); return (bool)$stmt->fetch();
    }
    public function validate(array $data, ?int $excludeId = null): array {
        $errors = [];
        if (empty(trim($data['full_name'] ?? ''))) $errors[] = 'Full name is required.';
        if (empty(trim($data['email'] ?? ''))) $errors[] = 'Email is required.'; elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.'; elseif ($this->existsByEmail(strtolower(trim($data['email'])), $excludeId)) $errors[] = 'Email is already used by another client.';
        if (empty(trim($data['contact_number'] ?? ''))) $errors[] = 'Contact number is required.';
        if (empty(trim($data['company_name'] ?? ''))) $errors[] = 'Company name is required.';
        if (empty(trim($data['address'] ?? ''))) $errors[] = 'Address is required.';
        if (!in_array($data['status'] ?? 'Active', ['Active', 'Inactive'], true)) $errors[] = 'Invalid status.';
        return $errors;
    }
    public function validateSelfUpdate(array $data, int $excludeId): array {
        $errors = [];
        if (empty(trim($data['full_name'] ?? ''))) $errors[] = 'Full name is required.';
        if (empty(trim($data['email'] ?? ''))) $errors[] = 'Email is required.'; elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format.'; elseif ($this->existsByEmail(strtolower(trim($data['email'])), $excludeId)) $errors[] = 'Email is already used by another client.';
        if (empty(trim($data['contact_number'] ?? ''))) $errors[] = 'Contact number is required.';
        if (empty(trim($data['address'] ?? ''))) $errors[] = 'Address is required.';
        return $errors;
    }
    public function create(array $data) {
        $errors = $this->validate($data); if ($errors) return $errors;
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (full_name,email,contact_number,company_name,address,status) VALUES (:full_name,:email,:contact_number,:company_name,:address,:status)");
        return $stmt->execute([':full_name' => $this->sanitize($data['full_name']), ':email' => strtolower($this->sanitize($data['email'])), ':contact_number' => $this->sanitize($data['contact_number']), ':company_name' => $this->sanitize($data['company_name']), ':address' => $this->sanitize($data['address']), ':status' => $this->sanitize($data['status'] ?? 'Active')]);
    }
    public function update(int $id, array $data) {
        $errors = $this->validate($data, $id); if ($errors) return $errors;
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET full_name=:full_name,email=:email,contact_number=:contact_number,company_name=:company_name,address=:address,status=:status,updated_at=CURRENT_TIMESTAMP WHERE id=:id");
        return $stmt->execute([':id' => $id, ':full_name' => $this->sanitize($data['full_name']), ':email' => strtolower($this->sanitize($data['email'])), ':contact_number' => $this->sanitize($data['contact_number']), ':company_name' => $this->sanitize($data['company_name']), ':address' => $this->sanitize($data['address']), ':status' => $this->sanitize($data['status'] ?? 'Active')]);
    }
    public function updateSelf(int $id, array $data) {
        $errors = $this->validateSelfUpdate($data, $id); if ($errors) return $errors;
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET full_name=:full_name,email=:email,contact_number=:contact_number,address=:address,updated_at=CURRENT_TIMESTAMP WHERE id=:id");
        return $stmt->execute([':id' => $id, ':full_name' => $this->sanitize($data['full_name']), ':email' => strtolower($this->sanitize($data['email'])), ':contact_number' => $this->sanitize($data['contact_number']), ':address' => $this->sanitize($data['address'])]);
    }
    public function delete(int $id): bool { $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=:id"); return $stmt->execute([':id' => $id]); }
    public function getById(int $id): ?array { $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id=:id LIMIT 1"); $stmt->execute([':id' => $id]); $row = $stmt->fetch(); return $row ?: null; }
    public function getAll(string $search = ''): array { [$where,$params] = $this->baseWhere($search); $stmt = $this->conn->prepare("SELECT * FROM {$this->table}" . $where . " ORDER BY created_at DESC, id DESC"); $stmt->execute($params); return $stmt->fetchAll(); }
    public function getPaginated(string $search, int $page = 1, int $perPage = 5): array { $page=max(1,$page); $offset=($page-1)*$perPage; [$where,$params] = $this->baseWhere($search); $sql = "SELECT * FROM {$this->table}".$where." ORDER BY created_at DESC, id DESC LIMIT :limit OFFSET :offset"; $stmt = $this->conn->prepare($sql); foreach($params as $k=>$v) $stmt->bindValue($k,$v,PDO::PARAM_STR); $stmt->bindValue(':limit',$perPage,PDO::PARAM_INT); $stmt->bindValue(':offset',$offset,PDO::PARAM_INT); $stmt->execute(); return $stmt->fetchAll(); }
    public function countFiltered(string $search = ''): int { [$where,$params] = $this->baseWhere($search); $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table}".$where); $stmt->execute($params); return (int)$stmt->fetchColumn(); }
    public function countAll(): int { return (int)$this->conn->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn(); }
    public function countByStatus(string $status): int { $stmt = $this->conn->prepare("SELECT COUNT(*) FROM {$this->table} WHERE status=:status"); $stmt->execute([':status'=>$status]); return (int)$stmt->fetchColumn(); }
    public function recent(int $limit = 6): array { $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC, id DESC LIMIT :limit"); $stmt->bindValue(':limit',$limit,PDO::PARAM_INT); $stmt->execute(); return $stmt->fetchAll(); }
    public function monthlyStats(): array { $rows = $this->conn->query("SELECT DATE_FORMAT(created_at, '%b') AS month_label, MONTH(created_at) AS month_number, COUNT(*) AS total FROM {$this->table} WHERE YEAR(created_at)=YEAR(CURDATE()) GROUP BY MONTH(created_at), DATE_FORMAT(created_at, '%b') ORDER BY month_number ASC")->fetchAll(); $months=['Jan'=>0,'Feb'=>0,'Mar'=>0,'Apr'=>0,'May'=>0,'Jun'=>0,'Jul'=>0,'Aug'=>0,'Sep'=>0,'Oct'=>0,'Nov'=>0,'Dec'=>0]; foreach($rows as $row){ $months[$row['month_label']] = (int)$row['total']; } return $months; }
    public function topCompanies(int $limit=5): array { $stmt = $this->conn->prepare("SELECT company_name, COUNT(*) AS total FROM {$this->table} GROUP BY company_name ORDER BY total DESC, company_name ASC LIMIT :limit"); $stmt->bindValue(':limit',$limit,PDO::PARAM_INT); $stmt->execute(); return $stmt->fetchAll(); }
    public function getByCompanyName(string $companyName, ?int $excludeId = null): array { $sql = "SELECT id, full_name, email, contact_number, company_name, status, created_at FROM {$this->table} WHERE company_name=:company_name"; if ($excludeId !== null) $sql .= " AND id != :exclude_id"; $sql .= " ORDER BY full_name ASC"; $stmt=$this->conn->prepare($sql); $stmt->bindValue(':company_name',$companyName,PDO::PARAM_STR); if($excludeId !== null) $stmt->bindValue(':exclude_id',$excludeId,PDO::PARAM_INT); $stmt->execute(); return $stmt->fetchAll(); }
    public function getCompanySummary(string $companyName): array { $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_contacts, SUM(CASE WHEN status='Active' THEN 1 ELSE 0 END) AS active_contacts, SUM(CASE WHEN status='Inactive' THEN 1 ELSE 0 END) AS inactive_contacts FROM {$this->table} WHERE company_name=:company_name"); $stmt->execute([':company_name'=>$companyName]); $row=$stmt->fetch(); return $row ?: ['total_contacts'=>0,'active_contacts'=>0,'inactive_contacts'=>0]; }
}
?>