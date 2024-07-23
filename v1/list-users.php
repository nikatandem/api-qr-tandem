<?php
require '../vendor/autoload.php';
require '../config/cors.php';
include "../config/database.php";
require '../config/auth_middleware.php';
//$decoded = authenticate(); // Llama al middleware y almacena los datos decodificados si el token es válido


$sql = "SELECT * FROM users";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        ['message' => 'Lista de usuarios actualizada',
        'users' => $users]
    );
    ?>