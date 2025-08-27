<?php

    // Inclua o autoload do Composer para carregar a biblioteca
    require __DIR__ . '/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    $host = $_ENV['PGHOST'];
    $db_name = $_ENV['PGDATABASE'];
    $user = $_ENV['PGUSER'];
    $password = $_ENV['PGPASSWORD'];
    $port = $_ENV['PGPORT'];

    try {
        $dsn = "pgsql:host={$host};port={$port};dbname={$db_name};user={$user};password={$password}";

        $pdo = new PDO($dsn);
        
        // Define o modo de erro para lançar exceções em caso de falha
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "Conexão com o banco de dados estabelecida com sucesso!";

    } catch (PDOException $e) {
        // Exibe a mensagem de erro
        die("Erro na conexão com o banco de dados: " . $e->getMessage());
    }

?>