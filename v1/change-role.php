<?php
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Maneja solicitudes preflight
    http_response_code(200);
    exit;
}

// Obtener el cuerpo de la solicitud y decodificar el JSON
$input = json_decode(file_get_contents('php://input'), true);

if ( isset($input['email']) && isset($input['role'])) {
    $email = $input['email'];
    $role = $input['role'];

    // Consulta SQL para actualizar el usuario
    $sql = "UPDATE users SET role = ? WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$role, $email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => "El rol ha cambiado para el $email a $role"]);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No hemos cambiado el rol']);
    }
} else {
    http_response_code(400);
    echo json_encode(['message' => 'Datos incompletos']);
}
?>