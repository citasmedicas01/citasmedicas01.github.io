<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: iniciar.php");
    exit();
}

$paciente_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT nombre, email, foto_perfil FROM usuarios WHERE id = ? AND tipo_usuario = 'paciente'");
if (!$stmt) {
    die("Error en la consulta SQL: " . $conn->error);
}
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();
$paciente = $resultado->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Paciente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1 class="titulo">Bienvenido, <?= htmlspecialchars($paciente['nombre']) ?></h1>
    </header>

    <div class="nav-bg">
        <nav class="navegacion-principal contenedor">
            <a href="paciente_inicio.php">Inicio</a>
            <a href="ver_citas_paciente.php">Mis Citas</a>
            <a href="agendar_cita_paciente.php">Agendar Cita</a>
            <a href="paciente_editar_perfil.php">Editar Perfil</a>
            <a href="logout.php" class="button">Cerrar Sesión</a>
        </nav>
    </div>

    <section class="contenedor">
        <h2>Información del Perfil</h2>
        <div class="perfil-doctor">
            <?php if (!empty($paciente['foto_perfil'])): ?>
                <img src="<?= $paciente['foto_perfil'] ?>" alt="Foto de perfil" style="width: 120px; border-radius: 50px;">
            <?php endif; ?>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($paciente['nombre']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($paciente['email']) ?></p>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Gestión de Citas Médicas</p>
    </footer>
</body>
</html>