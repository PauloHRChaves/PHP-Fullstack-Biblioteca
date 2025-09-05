<?php

require_once __DIR__ . '/database.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// // Validação de input
if (!isset($_POST['register-Email']) || empty(trim($_POST['register-Email'])) ||
    !isset($_POST['username']) || empty(trim($_POST['username'])) ||
    !isset($_POST['register-password']) || empty(trim($_POST['register-password'])) ||
    !isset($_POST['confirm-password']) || empty(trim($_POST['confirm-password']))
){
    http_response_code(400);
    echo json_encode(["message" => "Por favor, preencha todos os campos."]);
    exit;
}

if (!filter_var(trim($_POST['register-Email']), FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["message" => "Formato de e-mail inválido."]);
    exit;
}
if (strlen(trim($_POST['username'])) < 4) {
    http_response_code(400);
    echo json_encode(["message" => "O nome de usuário deve ter no mínimo 4 caracteres."]);
    exit;
}
if (strlen(trim($_POST['register-password'])) < 8) {
    http_response_code(400);
    echo json_encode(["message" => "A senha deve ter no mínimo 8 caracteres."]);
    exit;
}
if (trim($_POST['register-password']) != trim($_POST['confirm-password'])) {
    http_response_code(400);
    echo json_encode(["message" => "As senhas não coincidem!"]);
    exit;
}

$email = $_POST['register-Email'];
$username = $_POST['username'];
$password = $_POST['register-password'];
$con_password = $_POST['confirm-password'];

try {
    $sql_check = "SELECT EMAIL, USERNAME FROM USERS WHERE EMAIL = ? OR USERNAME = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$email, $username]);
    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    // Validação com o banco de dados
    if ($result) {
        http_response_code(409);
        if ($result['EMAIL'] === $email) {
            echo json_encode(['message' => 'Este e-mail já está em uso.']);
        } elseif ($result['USERNAME'] === $username) {
            echo json_encode(['message' => 'Este nome de usuário já está em uso.']);
        }
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO USERS (EMAIL, USERNAME, USER_PASSWORD) VALUES (?, ?, ?)";
    $stmt_insert = $pdo->prepare($sql_insert);
    
    $stmt_insert->execute([$email, $username, $hashed_password]);

    // Resposta de sucesso
    http_response_code(201);
    echo json_encode(["message" => "Usuário cadastrado com sucesso!"]);

// Erro na conexão com server
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor. Por favor, tente novamente mais tarde.']);
    die;
}
?>