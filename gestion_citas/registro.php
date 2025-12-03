<?php
require 'config.php';

// Recibe campos del formulario
$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$password = $_POST['contrasena'] ?? '';
$fecha_nacimiento = $_POST['fechaNacimiento'] ?? null;

// Fuerza el tipo de usuario como paciente
$tipo_usuario = 'paciente';

// Estas variables quedan en null para pacientes
$especialidad = null;
$consultorio = null;

// Validaciones básicas
if (!$nombre || !$email || !$password) {
    echo "Faltan campos obligatorios.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Correo electrónico inválido.";
    exit;
}

// Encriptar la contraseña
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// CORRECCIÓN AQUÍ: usamos 'contrasena' en lugar de 'password'
$sql = "INSERT INTO usuarios (
    nombre, email, telefono, contrasena, tipo_usuario, fecha_nacimiento, especialidad, consultorio
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conn->error);
}

$stmt->bind_param("ssssssss", 
    $nombre, $email, $telefono, $passwordHash, 
    $tipo_usuario, $fecha_nacimiento, $especialidad, $consultorio
);

if ($stmt->execute()) {
    header("Location: iniciar.html");
    exit;
} else {
    echo "Error al registrar: " . $stmt->error;
}
?>
