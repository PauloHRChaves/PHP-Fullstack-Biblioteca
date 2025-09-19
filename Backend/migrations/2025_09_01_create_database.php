<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    // Tenta conectar sem especificar o banco de dados
    $dsnServer = "mysql:host={$host};charset=utf8mb4";
    $pdoServer = new PDO($dsnServer, $user, $password);
    $pdoServer->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cria o banco de dados se ele não existir
    $pdoServer->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}`;");
    echo "Banco de dados '{$dbname}' criado com sucesso ou já existente.\n";
    
    // Conecta ao banco de dados recém-criado
    $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erro ao executar a migração: " . $e->getMessage() . "\n");
}