<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

$pdo = null;

try {
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Se o erro for "banco de dados desconhecido" (código 1049)
    if ($e->getCode() === 1049) {
        try {
            // Conecta ao servidor MySQL, sem especificar um banco de dados
            $dsnServer = "mysql:host={$host};charset=utf8mb4";
            $pdoServer = new PDO($dsnServer, $user, $password);
            $pdoServer->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Cria o banco de dados
            $pdoServer->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}`;");

            // Conecta-se novamente, desta vez com o banco de dados criado
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die("Erro ao conectar e criar o banco de dados: " . $e->getMessage());
        }
    } else {
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }
}

?>