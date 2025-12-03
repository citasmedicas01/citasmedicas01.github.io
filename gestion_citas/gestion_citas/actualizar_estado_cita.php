<?php
session_start();
include("config.php");

// Verifica que el usuario esté logueado y sea doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'doctor') {
    header("Location: iniciar.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cita_id = intval($_POST['cita_id'] ?? 0);
    $accion = $_POST['accion'] ?? '';

    if (!$cita_id || !in_array($accion, ['confirmar', 'cancelar'])) {
        exit("Datos inválidos.");
    }

    // Determina el nuevo estado
    $nuevo_estado = $accion === 'confirmar' ? 'confirmada' : 'cancelada';

    // Validar que la cita le pertenece al doctor logueado
    $doctor_id = $_SESSION['usuario_id'];
    $verificacion = $conn->prepare("SELECT id FROM citas WHERE id = ? AND id_doctor = ?");
    if (!$verificacion) {
        exit("Error al preparar verificación: " . $conn->error);
    }

    $verificacion->bind_param("ii", $cita_id, $doctor_id);
    $verificacion->execute();
    $resultado = $verificacion->get_result();

    if ($resultado->num_rows === 0) {
        exit("No tienes permiso para modificar esta cita.");
    }

    // Actualizar estado de la cita
    $stmt = $conn->prepare("UPDATE citas SET estado = ? WHERE id = ?");
    if (!$stmt) {
        exit("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("si", $nuevo_estado, $cita_id);
    $stmt->execute();

    header("Location: ver_citas_doctor.php");
    exit();
}
?>
