<?php
require_once __DIR__ . '/../utils/validator.php'; // Conecta as validações
require_once __DIR__ . '/../models/users.php'; // Conecta a logica dos endpoints

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
            $_SESSION['user_id'] = $user['ID'];

            http_response_code(200);
            echo json_encode(["message" => "Login bem-sucedido!", "user" => ["username" => $user['USERNAME']]], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(401); 
            echo json_encode(["message" => "E-mail ou senha incorretos."], JSON_UNESCAPED_UNICODE);
        }
    }

    public function logged() {
        // Checa a sessão
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(["message" => "Usuário não autenticado."], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Buscar os dados do usuário
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        // logica de vericação
        if ($user) {
            http_response_code(200);
            echo json_encode($user, JSON_UNESCAPED_UNICODE);
        } else {
            // Se a sessão existir mas o usuário não for encontrado no banco (ex: deletado)
            http_response_code(404);
            echo json_encode(["message" => "Usuário não encontrado."], JSON_UNESCAPED_UNICODE);
        }
    }

    public function logout() {
        
        $_SESSION = [];
        
        session_destroy();
        
        http_response_code(200);
        echo json_encode(["message" => "Logout bem-sucedido."], JSON_UNESCAPED_UNICODE);
    }
}
?>