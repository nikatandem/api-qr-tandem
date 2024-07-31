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

$sql = "DELETE FROM qr_codes WHERE id = ?";
$stmt = $pdo->prepare($sql);

if ($stmt->rowCount() > 0) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Código QR eliminado exitosamente']);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error: No se encontró ningún código QR con el ID especificado']);
    http_response_code(404); // Not Found
}
?>
