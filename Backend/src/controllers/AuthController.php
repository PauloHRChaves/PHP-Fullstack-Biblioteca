<?php
require_once ROOT_PATH . 'src/utils/validator.php'; 
require_once ROOT_PATH . 'src/models/users.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {

    public function register() {
        // Validação dos dados
        $validationError = Validator::validateRegisterData($_POST);
        if ($validationError) {
            http_response_code(400);
            echo json_encode(["message" => $validationError], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Lógica de negócio (criar o usuário no banco)
        $userModel = new User();
        $createResult = $userModel->createuser(
            trim($_POST['register-Email']),
            trim($_POST['username']),
            trim($_POST['register-password'])
        );

        // logico de vericação
        if ($createResult === true) {
            http_response_code(201);
            echo json_encode(["message" => "Usuário cadastrado com sucesso!"], JSON_UNESCAPED_UNICODE);
        } 
        elseif ($createResult === "email_or_username_exists") {
            http_response_code(409);
            echo json_encode(['message' => 'Este e-mail ou password já está em uso.'], JSON_UNESCAPED_UNICODE);
        } 
        else {
            // erro generico
            http_response_code(500);
            echo json_encode(['message' => 'Erro interno do servidor. Por favor, tente novamente mais tarde.'], JSON_UNESCAPED_UNICODE);
        }
    }

    public function login() {
        // Validação de input
        $validationError = Validator::validateLoginData($_POST);
        if ($validationError) {
            http_response_code(400);
            echo json_encode(["message" => $validationError], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Lógica de negócio (logar)
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // logico de vericação
        if ($user && password_verify($password, $user['USER_PASSWORD'])) {
            // REMOVIDO
            //$_SESSION['user_id'] = $user['ID'];
            //error_log("Session user_id set: " . $_SESSION['user_id']);

            // Token
            $tokenload = [
                'iat'  => time(),                       // Horário em que o token foi gerado
                'exp'  => time() + (3600 * 24),         // Expira em 24 horas
                'data' => [
                    'id'       => $user['ID'],
                    'username' => $user['USERNAME']
                ]
            ];
            
            // Chave jwt para assinar o token
            $secretKey = $_ENV['JWT_KEY'];

            // Gerar token
            $token = JWT::encode($tokenload, $secretKey, 'HS256');

            // Resposta de sucesso
            http_response_code(200);
            echo json_encode([
                "message" => "Login bem-sucedido!",
                "token" => $token,
                "user" => ["username" => $user['USERNAME']]
            ], JSON_UNESCAPED_UNICODE);
            
        } else {
            http_response_code(401); 
            echo json_encode(["message" => "E-mail ou senha incorretos."], JSON_UNESCAPED_UNICODE);
        }
    }

    public function logged() {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        // Extrai o token
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Token de autenticação não fornecido."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        try {
            $secretKey = $_ENV['JWT_KEY'];
            // Decodifique e valide o token
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // ID do token para buscar os dados do usuário
            $userId = $decoded->data->id;
            $userModel = new User();
            $user = $userModel->findById($userId);

            if ($user) {
                http_response_code(200);
                echo json_encode($user, JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Usuário não encontrado."], JSON_UNESCAPED_UNICODE);
                exit;
            }

        } catch (Exception $e) {
            // Token inválido (expirado, modificado, etc.)
            http_response_code(401);
            echo json_encode(["message" => "Token inválido ou expirado."], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
?>