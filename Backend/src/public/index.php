<?php
define('ROOT_PATH', __DIR__ . '/../../');

require ROOT_PATH . 'vendor/autoload.php';
require_once ROOT_PATH . 'src/controllers/AuthController.php';

// Carrega as variáveis do .env
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

header("Access-Control-Allow-Origin: http://127.0.0.1:5000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// Lida com a requisição OPTIONS (pré-voo do CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();

if ($request_uri === '/register' && $request_method === 'POST') {
    $authController->register();

} elseif ($request_uri === '/login' && $request_method === 'POST') {
    $authController->login();

} elseif ($request_uri === '/logged-in' && $request_method === 'GET') {
    $authController->logged();
    
} 
else { // futuramente retornar uma pagina personalizada de error 404
    http_response_code(404);
    echo json_encode(["message" => "Endpoint não encontrado."], JSON_UNESCAPED_UNICODE);
}