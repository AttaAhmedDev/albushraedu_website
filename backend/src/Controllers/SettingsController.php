<?php

require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Response.php';
require_once __DIR__ . '/../Middleware/RequireAdmin.php';

class SettingsController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getFooter(): void
    {
        $defaults = [
            'instagram' => 'https://instagram.com/albushra.kids',
            'email'     => 'Albushra.ayesh@gmail.com',
            'phone'     => '+201002345678',
        ];

        try {
            $stmt = $this->pdo->prepare(
                "SELECT setting_key, setting_value FROM site_settings
                 WHERE setting_key IN ('footer_instagram', 'footer_email', 'footer_phone')"
            );
            $stmt->execute();
            $settings = [];
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }

            $instagram = $settings['footer_instagram'] ?? $defaults['instagram'];
            $email = $settings['footer_email'] ?? $defaults['email'];
            $phone = $settings['footer_phone'] ?? $defaults['phone'];
        } catch (PDOException $e) {
            $instagram = $defaults['instagram'];
            $email = $defaults['email'];
            $phone = $defaults['phone'];
        }

        $instaHandle = '@albushra.kids';
        if (!empty($instagram)) {
            $parsed = parse_url($instagram);
            if (isset($parsed['path'])) {
                $instaHandle = '@' . trim($parsed['path'], '/');
            } else {
                $instaHandle = $instagram;
            }
        }

        Response::success([
            'instagram'    => $instagram,
            'email'        => $email,
            'phone'        => $phone,
            'insta_handle' => $instaHandle,
        ]);
    }

    public function updateFooter(): void
    {
        RequireAdmin::handle();
        $data = Auth::jsonBody();
        $instagram = trim($data['instagram'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::error('Invalid email format');
        }

        try {
            $sql = "INSERT INTO site_settings (setting_key, setting_value)
                    VALUES (:key, :value)
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
            $stmt = $this->pdo->prepare($sql);
            $this->pdo->beginTransaction();
            $stmt->execute([':key' => 'footer_instagram', ':value' => $instagram]);
            $stmt->execute([':key' => 'footer_email', ':value' => $email]);
            $stmt->execute([':key' => 'footer_phone', ':value' => $phone]);
            $this->pdo->commit();
            Response::success(['message' => 'Footer updated']);
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            Response::error('DB Error', 500);
        }
    }
}
