<?php
class Validator {
    public static function validateRegisterData(array $data) {
        // Validação inicial para verificar se todos os campos estão presentes e não vazios
        if (!isset($data['register-Email']) || empty(trim($data['register-Email'])) ||
            !isset($data['username']) || empty(trim($data['username'])) ||
            !isset($data['register-password']) || empty(trim($data['register-password'])) ||
            !isset($data['confirm-password']) || empty(trim($data['confirm-password']))
        ) {
            return "Por favor, preencha todos os campos.";
        }
        
        $email = trim($data['register-Email']);
        $username = trim($data['username']);
        $password = trim($data['register-password']);
        $confirmPassword = trim($data['confirm-password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Formato de e-mail inválido.";
        }

        if (strlen($username) < 4) {
            return "O nome de usuário deve ter no mínimo 4 caracteres.";
        }

        if ($password !== $confirmPassword) {
            return "As senhas não coincidem!";
        }

        if (strlen($password) < 8) {
            return "A senha deve ter no mínimo 8 caracteres.";
        }

        return null;
    }

    public static function validateLoginData(array $data){
        if (!isset($_POST['email']) || empty(trim($_POST['email'])) || !isset($_POST['password']) || empty(trim($_POST['password']))){
            return "Por favor, preencha todos os campos.";
        }

        return null; // null pq passou das validações
    }
}
?>