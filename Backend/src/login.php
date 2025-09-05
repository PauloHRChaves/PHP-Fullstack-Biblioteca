<?php

require_once __DIR__ . '/database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

// Validação de input
if (!isset($_POST['email']) || empty(trim($_POST['email'])) ||
    !isset($_POST['password']) || empty(trim($_POST['password']))
){
    http_response_code(400);
    echo json_encode(["message" => "Por favor, preencha todos os campos."]);
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

try {
    $sql_select = "SELECT ID, EMAIL, USERNAME, USER_PASSWORD FROM USERS WHERE EMAIL = ?";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute([$email]);
    $user = $stmt_select->fetch(PDO::FETCH_ASSOC);

    // Validação com o banco de dados
    if ($user && password_verify($password, $user['USER_PASSWORD'])) {
        
        // Salva os dados do usuário na sessão
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['username'] = $user['USERNAME'];
        $_SESSION['email'] = $user['EMAIL'];

        // Resposta de sucesso
        http_response_code(200);
        echo json_encode(["message" => "Login bem-sucedido!", "user" => ["username" => $user['USERNAME']]]);
        
    } else {
        http_response_code(401); 
        echo json_encode(["message" => "E-mail ou senha incorretos."]);
        exit;
    }

// Erro na conexão com server
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor. Por favor, tente novamente mais tarde.']);
    die;
}
?>