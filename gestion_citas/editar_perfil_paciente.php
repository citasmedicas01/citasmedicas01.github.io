<?php
session_start();
require 'config.php';

// Verifica que el usuario esté logueado y sea paciente
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: iniciar.html");
    exit();
}

$paciente_id = $_SESSION['id'];
$mensaje = "";

// Obtener datos actuales
$stmt = $conn->prepare("SELECT nombre, email, foto_perfil FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();
$paciente = $resultado->fetch_assoc();

// Actualizar datos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Manejar imagen (opcional)
    $foto_perfil = $paciente['foto_perfil'];
    if (!empty($_FILES['foto']['name'])) {
        $archivo_tmp = $_FILES['foto']['tmp_name'];
        $nombre_archivo = "img/" . basename($_FILES['foto']['name']);
        move_uploaded_file($archivo_tmp, $nombre_archivo);
        $foto_perfil = $nombre_archivo;
    }

    // Si se proporciona nueva contraseña
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ?, password = ?, foto_perfil = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $nombre, $email, $password_hashed, $foto_perfil, $paciente_id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ?, foto_perfil = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nombre, $email, $foto_perfil, $paciente_id);
    }

    if ($stmt->execute()) {
        $mensaje = "Perfil actualizado correctamente.";
        $_SESSION['usuario'] = $nombre;
    } else {
        $mensaje = "Error al actualizar el perfil.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1 class="titulo">Editar Perfil</h1>
    </header>

    <div class="nav-bg">
        <nav class="navegacion-principal contenedor">
            <a href="paciente_inicio.php">Inicio</a>
            <a href="ver_citas_paciente.php">Mis Citas</a>
            <a href="agendar.php">Agendar Cita</a>
            <a href="logout.php" class="button">Cerrar Sesión</a>
        </nav>
    </div>

    <section class="contenedor">
        <h2>Actualiza tu información</h2>

        <?php if ($mensaje): ?>
            <p style="color: green;"><strong><?= htmlspecialchars($mensaje) ?></strong></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="formulario">
            <fieldset>
                <legend>Datos personales</legend>

                <div class="campo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($paciente['nombre']) ?>" required>
                </div>

                <div class="campo">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($paciente['email']) ?>" required>
                </div>

                <div class="campo">
                    <label for="password">Nueva Contraseña (opcional):</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="campo">
                    <label for="foto">Foto de perfil (opcional):</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                </div>

                <?php if (!empty($paciente['foto_perfil'])): ?>
                    <div>
                        <p>Foto actual:</p>
                        <img src="<?= $paciente['foto_perfil'] ?>" alt="Foto de perfil" style="width: 100px; border-radius: 8px;">
                    </div>
                <?php endif; ?>

                <button type="submit" class="button">Guardar Cambios</button>
            </fieldset>
        </form>
    </section>

    <footer>
        <p>&copy; 2025 Gestión de Citas Médicas</p>
    </footer>
</body>
</html>
