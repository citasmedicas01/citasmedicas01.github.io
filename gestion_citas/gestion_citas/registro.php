<?php
require 'config.php';

// Recibe campos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$password = $_POST['contrasena'] ?? '';
$tipo_usuario = $_POST['tipoUsuario'] ?? '';
$fecha_nacimiento = $_POST['fechaNacimiento'] ?? null;
$especialidad = $_POST['especialidad'] ?? null;
$consultorio = $_POST['consultorio'] ?? null;

// Validaciones básicas
if (!$nombre || !$email || !$password || !$tipo_usuario) {
    echo "Faltan campos obligatorios.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Correo electrónico inválido.";
    exit;
}

// Encriptar la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// Prepara SQL (agregamos el teléfono también)
$sql = "INSERT INTO usuarios (
            nombre, email, telefono, password, tipo_usuario, fecha_nacimiento, especialidad, consultorio
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Enlaza los parámetros
$stmt->bind_param("ssssssss", 
    $nombre, $email, $telefono, $passwordHash, 
    $tipo_usuario, $fecha_nacimiento, $especialidad, $consultorio
);

// Ejecuta la consulta
if ($stmt->execute()) {
    header("Location: iniciar.php");
    exit;
} else {
    echo "Error al registrar: " . $stmt->error;
}
?>
