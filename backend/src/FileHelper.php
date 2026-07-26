<?php

class FileHelper
{
    public static function resolvePath(string $relativePath): string
    {
        $relativePath = ltrim(str_replace(['\\', '..'], ['/', ''], $relativePath), '/');
        return PROJECT_ROOT . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    }

    public static function uploadDir(): string
    {
        $dir = PROJECT_ROOT . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'files';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    /** Store relative path like uploads/files/xyz.ext for DB */
    public static function storeUpload(array $file): string
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . uniqid() . ($extension ? '.' . strtolower($extension) : '');
        $targetFs = self::uploadDir() . DIRECTORY_SEPARATOR . $fileName;
        $relative = 'uploads/files/' . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetFs)) {
            throw new RuntimeException('Upload failed');
        }

        return $relative;
    }

    public static function deleteIfExists(string $relativePath): void
    {
        $full = self::resolvePath($relativePath);
        if (is_file($full)) {
            unlink($full);
        }
        // Also try relative to cwd for legacy paths
        if (is_file($relativePath)) {
            unlink($relativePath);
        }
    }

    public static function stream(string $relativePath, string $downloadName, bool $inline = false, string $contentType = 'application/octet-stream'): void
    {
        $full = self::resolvePath($relativePath);
        if (!is_file($full)) {
            // Legacy: path may already be absolute-ish relative to old root
            if (is_file(PROJECT_ROOT . '/' . $relativePath)) {
                $full = PROJECT_ROOT . '/' . $relativePath;
            } elseif (is_file($relativePath)) {
                $full = $relativePath;
            } else {
                Response::error('File missing on server', 404);
            }
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        $disposition = $inline ? 'inline' : 'attachment';
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: ' . $disposition . '; filename="' . $downloadName . '"');
        header('Content-Length: ' . filesize($full));
        header('Accept-Ranges: bytes');
        readfile($full);
        exit;
    }
}
