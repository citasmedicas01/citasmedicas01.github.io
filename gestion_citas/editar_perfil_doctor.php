<?php
session_start();
require 'config.php';

// Verifica sesión activa de doctor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'doctor') {
    header("Location: iniciar.html");
    exit;
}

$doctor_id = $_SESSION['usuario_id'];

// Obtener datos actuales del doctor
$stmt = $conn->prepare("SELECT nombre, email, especialidad, experiencia, foto_perfil FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
$doctor = $result->fetch_assoc();

$mensaje = "";

// Actualización de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $especialidad = $_POST['especialidad'];
    $experiencia = $_POST['experiencia'];
    
    // Subida de foto
    if (!empty($_FILES['foto']['name'])) {
        $nombre_archivo = 'foto_' . $doctor_id . '_' . basename($_FILES['foto']['name']);
        $ruta_archivo = 'uploads/' . $nombre_archivo;
        move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_archivo);
    } else {
        $ruta_archivo = $doctor['foto_perfil'];
    }

    // Cambiar contraseña si se ingresó
    if (!empty($_POST['nueva_contraseña'])) {
        $nueva_contraseña = password_hash($_POST['nueva_contraseña'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
        $stmt->bind_param("si", $nueva_contraseña, $doctor_id);
        $stmt->execute();
    }

    // Actualizar el resto del perfil
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ?, especialidad = ?, experiencia = ?, foto_perfil = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $nombre, $email, $especialidad, $experiencia, $ruta_archivo, $doctor_id);

    if ($stmt->execute()) {
        $mensaje = "Perfil actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Doctor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Editar Perfil</h2>

    <?php if ($mensaje): ?>
        <p><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($doctor['nombre']) ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($doctor['email']) ?>" required><br>

        <label>Especialidad:</label>
        <input type="text" name="especialidad" value="<?= htmlspecialchars($doctor['especialidad']) ?>" required><br>

        <label>Experiencia:</label>
        <textarea name="experiencia" rows="4"><?= htmlspecialchars($doctor['experiencia']) ?></textarea><br>

        <label>Nueva Contraseña:</label>
        <input type="password" name="nueva_contraseña"><br>

        <label>Foto de Perfil:</label>
        <?php if (!empty($doctor['foto_perfil'])): ?>
            <img src="<?= $doctor['foto_perfil'] ?>" alt="Foto actual" width="100"><br>
        <?php endif; ?>
        <input type="file" name="foto"><br>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
