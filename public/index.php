<?php

   declare(strict_types=1);

   require __DIR__ . '/../vendor/autoload.php';

   // Rota inicial só para teste
   header('Content-Type: application/json');
   echo json_encode([
      "status" => "ok",
      "msg" => "API rodando no Railway!"
   ]);

?>