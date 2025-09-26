<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__ . '/../src/config/database.php'; 

$pdo = connectToDatabase();

date_default_timezone_set('America/Sao_Paulo');

try {
    // Criação da tabela USERS
    $sql = "
    CREATE TABLE IF NOT EXISTS USERS (
        ID INT(11) PRIMARY KEY AUTO_INCREMENT,
        EMAIL VARCHAR(255) NOT NULL UNIQUE,
        USERNAME VARCHAR(25) NOT NULL UNIQUE,
        USER_PASSWORD VARCHAR(255) NOT NULL,
        DATA_CREATE DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ";

    $pdo->exec($sql);

    echo "Tabela 'USERS' criada com sucesso!" . PHP_EOL;
    // . PHP_EOL; apenas para garantir quebra de linha

} catch (PDOException $e) {
    die("Erro ao criar a tabela: " . $e->getMessage());
}
?>