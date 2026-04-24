<?php
class FileUpload
{
    /**
     * Upload a file to a subdirectory of UPLOAD_DIR.
     * Returns the relative path (from UPLOAD_DIR) on success, or throws on failure.
     */
    public static function upload(array $file, string $subDir, array $allowedTypes, int $maxBytes = 5242880): string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload error code: ' . $file['error']);
        }
        if ($file['size'] > $maxBytes) {
            throw new RuntimeException('File too large (max ' . ($maxBytes / 1048576) . 'MB).');
        }
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowedTypes, true)) {
            throw new RuntimeException('File type not allowed: ' . $mime);
        }

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('', true) . '.' . strtolower($ext);
        $dir      = rtrim(UPLOAD_DIR, '/') . '/' . $subDir . '/';

        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new RuntimeException('Cannot create upload directory.');
        }

        if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        return $subDir . '/' . $filename;
    }

    /** Return public URL for a stored relative path */
    public static function url(?string $relativePath): string
    {
        if (!$relativePath) return '';
        return UPLOAD_URL . ltrim($relativePath, '/');
    }
}
