<?php
session_start();
require 'config.php';

// Verificar si hay sesión iniciada
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar.php");
    exit;
}

$doctor_id = $_SESSION['usuario_id'];

// Obtener información del doctor (solo nombre y email)
$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ? AND tipo_usuario = 'doctor'");
if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Doctor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1 class="titulo">Bienvenido, Dr. <?= htmlspecialchars($doctor['nombre']) ?></h1>
    </header>

    <div class="nav-bg">
        <nav class="navegacion-principal contenedor">
            <a href="doctor_inicio.php">Inicio</a>
            <a href="ver_citas_doctor.php">Mis Citas</a>
            <a href="cerrar_sesion.php" class="button">Cerrar Sesión</a>
        </nav>
    </div>

    <section class="contenedor">
        <h2>Información del Perfil</h2>
        <div class="perfil-doctor">
            <p><strong>Nombre:</strong> <?= htmlspecialchars($doctor['nombre']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']) ?></p>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Gestión de Citas Médicas</p>
    </footer>
</body>
</html>
