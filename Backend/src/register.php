<?php

require_once __DIR__ . '/database.php';

// Permite a comunicação com o front-end
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

//Validação dos dados: Email, Username e Password
if (!isset($_POST['email'], $_POST['username'], $_POST['password'])) {
    http_response_code(400);
    echo json_encode(["message" => "Por favor, preencha todos os campos."]);
    exit;
}

if (strlen($username) < 4) {
    http_response_code(400);
    echo json_encode(["message" => "O nome de usuário deve ter no mínimo 4 caracteres."]);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(["message" => "A senha deve ter no mínimo 8 caracteres."]);
    exit;
}

try {
    //Verifica se o email ou o nome de usuário já existem
    $sql_check = "SELECT EMAIL, USERNAME FROM USERS WHERE EMAIL = ? OR USERNAME = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$email, $username]);
    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

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

    $sql_insert = "INSERT INTO USERS (EMAIL, USERNAME, PASSWORD) VALUES (?, ?, ?)";
    $stmt_insert = $pdo->prepare($sql_insert);
    
    $stmt_insert->execute([$email, $username, $hashed_password]);

    //Resposta de sucesso
    http_response_code(201);
    echo json_encode(["message" => "Usuário cadastrado com sucesso!"]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Erro interno do servidor. Por favor, tente novamente mais tarde.']);
}
?>