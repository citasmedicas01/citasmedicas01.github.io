<?php
require 'config.php';

$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$id_doctor = $_POST['id_doctor'] ?? '';

// Validación
if (empty($nombre) || empty($email) || empty($fecha) || empty($hora) || empty($id_doctor)) {
    echo "Todos los campos son obligatorios.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Correo electrónico inválido.";
    exit;
}

// Buscar paciente
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND tipo_usuario = 'paciente'");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No se encontró un paciente con ese correo.";
    exit;
}

$paciente = $result->fetch_assoc();
$paciente_id = $paciente['id'];

// Guardar cita
$stmt = $conn->prepare("INSERT INTO citas (id_paciente, id_doctor, fecha, hora, estado, creado_en) VALUES (?, ?, ?, ?, 'pendiente', NOW())");
$stmt->bind_param("iiss", $paciente_id, $id_doctor, $fecha, $hora);

if ($stmt->execute()) {
    header("Location: admin_pacientes.php");
    exit;
} else {
    echo "Error al agendar la cita: " . $stmt->error;
}
?>
