<?php
require '../config/cors.php';
require '../vendor/autoload.php';
require '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
$required_fields = ['data', 'nombre_ref', 'description', 'created_by'];
foreach ($required_fields as $field) {
    if (empty($input[$field]) && ($input[$field]!='')) {
        echo json_encode(['message' => "Error: El campo '$field' es requerido y no puede estar repetido"]);
        http_response_code(400);
        exit;
    }
}

$data = filter_var($input['data'], FILTER_SANITIZE_STRING);
$nombre_ref = filter_var($input['nombre_ref'], FILTER_SANITIZE_STRING);
$description = filter_var($input['description'], FILTER_SANITIZE_STRING);
$created_by = $input['created_by'];


$sql = "INSERT INTO qr_codes (data,nombre_ref, description, created_by) VALUES (?, ?, ?,?)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$data, $nombre_ref, $description, $created_by])) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'message' => 'Código QR creado exitosamente'
    ]);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error al crear código QR']);
}
?>

