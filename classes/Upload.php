<?php
class Upload {
    private string $uploadDir;
    private array $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    private int $maxBytes = 2000000;
    public function __construct(string $uploadDir) { $this->uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR); }
    public function saveProfileImage(array $file, ?string $oldFile = null): array {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) return ['success' => true, 'filename' => $oldFile];
        if ($file['error'] !== UPLOAD_ERR_OK) return ['success' => false, 'message' => 'File upload failed.'];
        if (($file['size'] ?? 0) > $this->maxBytes) return ['success' => false, 'message' => 'Profile image must be 2MB or smaller.'];
        $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions, true)) return ['success' => false, 'message' => 'Allowed file types: JPG, JPEG, PNG, WEBP.'];
        if (@getimagesize($file['tmp_name']) === false) return ['success' => false, 'message' => 'Uploaded file is not a valid image.'];
        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0777, true);
        $newFilename = 'client_' . bin2hex(random_bytes(12)) . '.' . $extension;
        $target = $this->uploadDir . DIRECTORY_SEPARATOR . $newFilename;
        if (!move_uploaded_file($file['tmp_name'], $target)) return ['success' => false, 'message' => 'Could not save the profile image.'];
        if ($oldFile && $oldFile !== $newFilename) {
            $oldPath = $this->uploadDir . DIRECTORY_SEPARATOR . basename($oldFile);
            if (is_file($oldPath)) @unlink($oldPath);
        }
        return ['success' => true, 'filename' => $newFilename];
    }
}
?>