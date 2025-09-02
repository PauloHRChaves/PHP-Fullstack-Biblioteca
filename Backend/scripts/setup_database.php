<?php

require __DIR__ . '/../src/database.php';

date_default_timezone_set('America/Sao_Paulo');

try {
    // Define a query SQL para criar a tabela USERS
    $sql = "
    CREATE TABLE IF NOT EXISTS USERS (
        ID INT(11) PRIMARY KEY AUTO_INCREMENT,
        EMAIL VARCHAR(255) NOT NULL UNIQUE,
        USERNAME VARCHAR(25) NOT NULL UNIQUE,
        PASSWORD VARCHAR(255) NOT NULL,
        DATA_CREATE DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ";
    // Executa a query para criar a tabela
    $pdo->exec($sql);

    echo "Tabela 'USERS' criada com sucesso!" . PHP_EOL;

} catch (PDOException $e) {
    // Em caso de erro, exibe a mensagem de erro
    die("Erro ao criar a tabela: " . $e->getMessage());
}
?>