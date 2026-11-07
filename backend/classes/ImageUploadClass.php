<?php
// Saves an uploaded image inside /uploads/<folder>/ and returns its path
// (example: "uploads/products/ab12cd34.jpg"). Returns '' if no file was chosen.
class ImageUploadClass
{
    public function save(array $file, string $folder): string
    {
        $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        $invalid = 'Invalid file. Only JPG, PNG or WEBP images up to 2 MB are allowed.';

        if ($error !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
            throw new RuntimeException($invalid);
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
        ];
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        if (!isset($allowed[$mime])) {
            throw new RuntimeException($invalid);
        }

        $dir = dirname(__DIR__, 2) . '/uploads/' . $folder;
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new RuntimeException('Could not save the image on the server.');
        }

        $name = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            throw new RuntimeException('Could not save the image on the server.');
        }

        return 'uploads/' . $folder . '/' . $name;
    }


    public function delete(string $path): void
    {
        if (strpos($path, 'uploads/') !== 0 || strpos($path, '..') !== false) {
            return;
        }
        $file = dirname(__DIR__, 2) . '/' . $path;
        if (is_file($file)) {
            unlink($file);
        }
    }
}
