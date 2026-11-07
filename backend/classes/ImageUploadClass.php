<?php
class ImageUploadClass
{
    private const MAX_SIZE = 2097152;
    private const ALLOWED  = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];
    private const FOLDERS  = ['products', 'categories'];

    private function uploadsroot(): string
    {
        return dirname(__DIR__, 2) . '/uploads';
    }

    public function save(array $file, string $folder): string
    {
        $error = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($error === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        $invalid = 'Invalid file. Only JPG, PNG or WEBP images up to 2 MB are allowed.';

        if (!in_array($folder, self::FOLDERS, true)
            || $error !== UPLOAD_ERR_OK
            || ($file['size'] ?? 0) > self::MAX_SIZE
            || !is_uploaded_file($file['tmp_name'] ?? '')
        ) {
            throw new RuntimeException($invalid);
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime]) || @getimagesize($file['tmp_name']) === false) {
            throw new RuntimeException($invalid);
        }

        $dir = $this->uploadsroot() . '/' . $folder;
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new RuntimeException('Could not save the image on the server.');
        }

        $name = bin2hex(random_bytes(8)) . '.' . self::ALLOWED[$mime];
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            throw new RuntimeException('Could not save the image on the server.');
        }

        $base = defined('SITE_URL') ? SITE_URL : '';
        return $base . '/uploads/' . $folder . '/' . $name;
    }

    public function delete(string $url): void
    {
        $pos = strpos($url, '/uploads/');
        if ($pos === false) {
            return;
        }

        $root = realpath($this->uploadsroot());
        $path = realpath(dirname(__DIR__, 2) . substr($url, $pos));

        if ($root !== false && $path !== false && strpos($path, $root) === 0 && is_file($path)) {
            unlink($path);
        }
    }
}
