<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require '../config/database.php';
$input = json_decode(file_get_contents('php://input'), true);


$nombre = $input['nombre'];
$delegacion = $input['delegacion'];
$email = $input['email'];
$password = $input['password'];



$sql = "SELECT COUNT(*) FROM users WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$emailCount = $stmt->fetchColumn();
if ($emailCount > 0) {
    echo json_encode(['message' => "Error: El email '$email' ya está registrado"]);
    http_response_code(409);
    exit;
}

$passwordHashed = password_hash($password, PASSWORD_DEFAULT);

$admin_email ='admin@gmail.com';
$subject = "Se ha registrado $nombre en la app qrcode";
$message = "El usuario $nombre con email $email de la delegación $delegacion solicita permisos para utilizar la aplicación";
$headers = "From: $email";

$sql = "INSERT INTO users (nombre, delegacion, email, password) VALUES (?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
if ($stmt->execute([$nombre, $delegacion, $email, $passwordHashed])) {
    if(mail($admin_email,$subject,$message,$headers)){
        $sendemail = "El mensaje a $email ha sido enviado correctamente";
    } else {
        $sendemail = "Error al enviar en mensaje";
    }

    echo json_encode([
        'message' => "$nombre registrado exitosamente",
        'email' => $email,
        'delegacion' => $delegacion,
        'sendemailadmin' => $sendemail
    ]);
    http_response_code(201);
} else {
    echo json_encode(['message' => "Error al registrar $nombre"]);
    http_response_code(500);
}
?>
