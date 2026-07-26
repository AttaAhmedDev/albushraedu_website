<?php

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once dirname(__DIR__) . '/config/db.php';
require_once dirname(__DIR__) . '/src/Response.php';
require_once dirname(__DIR__) . '/src/Router.php';
require_once dirname(__DIR__) . '/src/Auth.php';
require_once dirname(__DIR__) . '/src/FileHelper.php';
require_once dirname(__DIR__) . '/src/Middleware/RequireAdmin.php';
require_once dirname(__DIR__) . '/src/Controllers/AuthController.php';
require_once dirname(__DIR__) . '/src/Controllers/WorksheetController.php';
require_once dirname(__DIR__) . '/src/Controllers/PresentationController.php';
require_once dirname(__DIR__) . '/src/Controllers/GameController.php';
require_once dirname(__DIR__) . '/src/Controllers/FlashcardController.php';
require_once dirname(__DIR__) . '/src/Controllers/SettingsController.php';
require_once dirname(__DIR__) . '/src/Controllers/AdminController.php';

Auth::startSession();

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$override = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ?? '';
if ($override !== '') {
    $method = strtoupper($override);
}

$route = $_GET['route'] ?? '';
// Support PATH_INFO / rewritten paths without query
if ($route === '' && !empty($_SERVER['PATH_INFO'])) {
    $route = ltrim($_SERVER['PATH_INFO'], '/');
}
$route = trim($route, '/');

$auth = new AuthController($pdo);
$worksheets = new WorksheetController($pdo);
$presentations = new PresentationController($pdo);
$games = new GameController($pdo);
$flashcards = new FlashcardController($pdo);
$settings = new SettingsController($pdo);
$admin = new AdminController($pdo);

$router = new Router();

$router->get('auth/me', fn() => $auth->me());
$router->post('auth/login', fn() => $auth->login());
$router->post('auth/register', fn() => $auth->register());
$router->post('auth/logout', fn() => $auth->logout());

$router->get('worksheets/{type}', fn($p) => $worksheets->list($p));
$router->post('worksheets/{type}', fn($p) => $worksheets->upload($p));
$router->delete('worksheets/{type}/{id}', fn($p) => $worksheets->delete($p));
$router->get('worksheets/{type}/{id}/view', fn($p) => $worksheets->view($p));
$router->get('worksheets/{type}/{id}/download', fn($p) => $worksheets->download($p));

$router->get('presentations/{type}', fn($p) => $presentations->list($p));
$router->post('presentations/{type}', fn($p) => $presentations->upload($p));
$router->delete('presentations/{type}/{id}', fn($p) => $presentations->delete($p));
$router->get('presentations/{type}/{id}/download', fn($p) => $presentations->download($p));

$router->get('games/{subject}', fn($p) => $games->list($p));
$router->post('games/{subject}', fn($p) => $games->create($p));
$router->delete('games/{subject}/{id}', fn($p) => $games->delete($p));

$router->get('flashcards/{kind}', fn($p) => $flashcards->list($p));
$router->post('flashcards/{kind}', fn($p) => $flashcards->upload($p));
$router->delete('flashcards/{kind}/{id}', fn($p) => $flashcards->delete($p));
$router->get('flashcards/{kind}/{key}/download', fn($p) => $flashcards->download($p));

$router->get('settings/footer', fn() => $settings->getFooter());
$router->put('settings/footer', fn() => $settings->updateFooter());

$router->put('admin/profile', fn() => $admin->updateProfile());

$router->dispatch($method, $route);
