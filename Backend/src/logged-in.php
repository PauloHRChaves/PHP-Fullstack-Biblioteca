<?php
// Testando pagina de Perfil

require_once __DIR__ . '/database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

// Validação se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Usuário não autenticado."]);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT USERNAME, EMAIL FROM USERS WHERE ID = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Resposta de sucesso
    http_response_code(200);
    echo json_encode($user);

// Erro na conexão com server
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor.']);
    die;
}
?>