<?php

require_once __DIR__ . '/../Auth.php';
require_once __DIR__ . '/../Response.php';
require_once __DIR__ . '/../Middleware/RequireAdmin.php';

class GameController
{
    private const CONFIG = [
        'english' => ['table' => 'game_settings', 'name' => 'gName', 'link' => 'gLink'],
        'math'    => ['table' => 'math_settings', 'name' => 'mName', 'link' => 'mLink'],
    ];

    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function config(string $subject): array
    {
        if (!isset(self::CONFIG[$subject])) {
            Response::error('Invalid game subject', 400);
        }
        return self::CONFIG[$subject];
    }

    public function list(array $params): void
    {
        $cfg = $this->config($params['subject']);
        $table = $cfg['table'];
        $stmt = $this->pdo->prepare("SELECT * FROM `$table` ORDER BY id DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $items = array_map(function ($row) use ($cfg) {
            return [
                'id'   => (int)$row['id'],
                'name' => $row[$cfg['name']],
                'link' => $row[$cfg['link']],
            ];
        }, $rows);

        Response::success(['items' => $items]);
    }

    public function create(array $params): void
    {
        RequireAdmin::handle();
        $cfg = $this->config($params['subject']);
        $data = Auth::jsonBody();
        $name = trim($data['name'] ?? '');
        $link = trim($data['link'] ?? '');

        if ($name === '' || $link === '') {
            Response::error('Missing required fields');
        }

        $table = $cfg['table'];
        $nameCol = $cfg['name'];
        $linkCol = $cfg['link'];

        $sql = "INSERT INTO `$table` (`$nameCol`, `$linkCol`) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE `$linkCol` = VALUES(`$linkCol`)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$name, $link]);
        Response::success(['message' => 'Game saved']);
    }

    public function delete(array $params): void
    {
        RequireAdmin::handle();
        $cfg = $this->config($params['subject']);
        $id = (int)($params['id'] ?? 0);
        if ($id < 1) {
            Response::error('Invalid ID');
        }

        $stmt = $this->pdo->prepare('DELETE FROM `' . $cfg['table'] . '` WHERE id = ?');
        $stmt->execute([$id]);
        Response::success(['message' => 'Game deleted']);
    }
}
