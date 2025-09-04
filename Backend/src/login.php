<?php

//REVISAR o formulario de login !
require_once __DIR__ . '/database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

session_start();

// Validação
if (!isset($_POST['email'], $_POST['password'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["message" => "Por favor, preencha todos os campos."]);
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

try {
    // Busca o usuário no banco de dados pelo e-mail
    $sql_select = "SELECT id, email, username, password FROM users WHERE email = ?";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute([$email]);
    $user = $stmt_select->fetch(PDO::FETCH_ASSOC);

    // Verifica se o usuário existe e se a senha está correta
    if ($user && password_verify($password, $user['password'])) {
        
        // Salva os dados do usuário na sessão
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Resposta de sucesso
        http_response_code(200);
        echo json_encode(["message" => "Login bem-sucedido!", "user" => ["username" => $user['username']]]);
        exit;
        
    } else {
        // Falha no login
        http_response_code(401); 
        echo json_encode(["message" => "E-mail ou senha incorretos."]);
        exit;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor. Por favor, tente novamente mais tarde.']);
    exit;
}
?>