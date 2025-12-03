<?php
session_start();
require 'config.php';

// Verificar si hay sesión iniciada y que sea un doctor
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'doctor') {
    header("Location: iniciar.html");
    exit;
}

$doctor_id = $_SESSION['id'];

// Obtener información del doctor
$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ? AND tipo_usuario = 'doctor'");
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

if (!$doctor) {
    echo "No se encontró información del doctor.";
    exit;
}
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
            <a href="logout_doctor.php" class="button">Cerrar Sesión</a>
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
