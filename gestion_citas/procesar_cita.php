<?php
require 'config.php';

$nombre = $_POST['nombre'] ?? '';
$email = $_POST['email'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$id_doctor = intval($_POST['id_doctor'] ?? 0);

// Validación básica
if (empty($nombre) || empty($email) || empty($fecha) || empty($hora) || !$id_doctor) {
    echo "Todos los campos son obligatorios.";
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Correo electrónico inválido.";
    exit;
}

// Verificar que el ID del doctor sea válido
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = ? AND tipo_usuario = 'doctor'");
$stmt->bind_param("i", $id_doctor);
$stmt->execute();
$result_doctor = $stmt->get_result();

if ($result_doctor->num_rows === 0) {
    echo "El ID del doctor no es válido.";
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
