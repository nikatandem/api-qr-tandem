<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['nombre'], $input['delegacion'], $input['email'], $input['password'])) {
    $nombre = $input['nombre'];
    $delegacion = $input['delegacion'];
    $email = $input['email'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (nombre, delegacion, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $delegacion, $email, $password]);

        http_response_code(200);
        echo json_encode(['message' => 'Usuario creado correctamente']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Error en la base de datos', 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Datos incompletos para crear el usuario']);
}
?>
