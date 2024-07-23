<?php
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$required_fields = ['id'];
foreach ($required_fields as $field) {
    if (empty($input[$field]) && ($input[$field]!='')) {
        echo json_encode(['message' => "Error: El campo '$field' es requerido"]);
        http_response_code(400);
        exit;
    }
}

$id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

// Eliminar el usuario
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->execute([$id])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Usuario y sus códigos QR asociados eliminados exitosamente']);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error al eliminar el usuario']);
}
?>
