<?php
class Validator {
    public static function validateRegisterData(array $data) {
        if (!isset($data['email']) || empty(trim($data['email'])) ||
            !isset($data['username']) || empty(trim($data['username'])) ||
            !isset($data['password']) || empty(trim($data['password'])) ||
            !isset($data['confirmPassword']) || empty(trim($data['confirmPassword']))
        ) {
            return "Por favor, preencha todos os campos.";
        }

        if (!filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL)) {
            return "Formato de e-mail inválido.";
        }

        if (strlen(trim($data['username'])) < 4) {
            return "O nome de usuário deve ter no mínimo 4 caracteres.";
        }

        if (strlen(trim($data['password'])) < 8) {
            return "A senha deve ter no mínimo 8 caracteres.";
        }

        if (trim($data['password']) != trim($data['confirmPassword'])) {
            return "As senhas não coincidem!";
        }

        return null; // null pq passou das validações
    }

    public static function validateLoginData(array $data){
        if (!isset($_POST['email']) || empty(trim($_POST['email'])) || !isset($_POST['password']) || empty(trim($_POST['password']))){
            return "Por favor, preencha todos os campos.";
        }

        return null; // null pq passou das validações
    }
}
?>