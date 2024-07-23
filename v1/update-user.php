<?php
require "../config/cors.php";
require '../vendor/autoload.php';
require '../config/database.php';

try {
    // Leer la entrada JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Validar entrada
    if (isset($input['nombre']) && $input['nombre'] !== "" && isset($input['email']) && $input['email'] !== "" && isset($input['delegacion']) && $input['delegacion'] !== "") {
        $nombre = $input['nombre'];
        $email = $input['email'];
        $delegacion = $input['delegacion'];

        // Comprobar si el correo electrónico existe
        $checkEmailSql = "SELECT COUNT(*) FROM users WHERE email = ?";
        $checkStmt = $pdo->prepare($checkEmailSql);
        $checkStmt->execute([$email]);
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
            // Preparar la consulta SQL para actualizar el usuario
            $sql = "UPDATE users SET nombre = ?, delegacion = ? WHERE email = ?";
            $stmt = $pdo->prepare($sql);

            // Ejecutar la consulta
            if ($stmt->execute([$nombre, $delegacion, $email])) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'message' => "El usuario ha sido actualizado exitosamente",
                    'email' => $email
                ]);
            } else {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['message' => 'Error al actualizar el usuario']);
            }
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['message' => 'El correo electrónico no existe',
            'email' => $email]);
        }
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['message' => 'Datos incompletos']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}
?>
