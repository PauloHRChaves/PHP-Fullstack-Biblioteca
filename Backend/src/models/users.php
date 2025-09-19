<?php
require_once ROOT_PATH . 'src/config/database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = connectToDatabase();
    }

    public function findByEmail(string $email): array|false { // Login
        try {
            $sql = "SELECT ID, EMAIL, USERNAME, USER_PASSWORD FROM USERS WHERE EMAIL = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$email]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Database error in findByEmail: " . $e->getMessage());
            return false;
        }
    }

    public function findById(int $id): array|false { // Continuar logado
        try {
            $sql = "SELECT USERNAME, EMAIL FROM USERS WHERE ID = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Erro de banco de dados em findById: " . $e->getMessage());
            return false;
        }
    }

    public function createuser(string $email, string $username, string $password): string|bool { // Cadastro
        try {
            // Verifica se o email ou username já existem
            $sql_check = "SELECT EMAIL, USERNAME FROM USERS WHERE EMAIL = ? OR USERNAME = ?";
            $stmt_check = $this->pdo->prepare($sql_check);
            $stmt_check->execute([$email, $username]);
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                return "email_or_username_exists";
            }

            // Insere o novo usuário
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO USERS (EMAIL, USERNAME, USER_PASSWORD) VALUES (?, ?, ?)";
            $stmt_insert = $this->pdo->prepare($sql_insert);
            
            return $stmt_insert->execute([$email, $username, $hashed_password]);

        } catch (PDOException $e) {
            error_log("Erro de banco de dados: " . $e->getMessage());
            return false;
        }
    }
}
?>