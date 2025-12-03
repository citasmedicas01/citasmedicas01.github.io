<?php
session_start();
include("config.php");

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: iniciar.php");
    exit();
}

$id_paciente = $_SESSION['id'];
$mensaje = "";

// Obtener los datos actuales del paciente
$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ? AND tipo_usuario = 'paciente'");
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$resultado = $stmt->get_result();
$paciente = $resultado->fetch_assoc();

// Procesar formulario de actualización
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nuevo_nombre = $_POST['nombre'];
    $nuevo_email = $_POST['email'];

    $update_stmt = $conn->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ? AND tipo_usuario = 'paciente'");
    $update_stmt->bind_param("ssi", $nuevo_nombre, $nuevo_email, $id_paciente);
    
    if ($update_stmt->execute()) {
        $mensaje = "Perfil actualizado exitosamente.";
        $paciente['nombre'] = $nuevo_nombre;
        $paciente['email'] = $nuevo_email;
    } else {
        $mensaje = "Error al actualizar el perfil.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - Paciente</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .contenedor {
            max-width: 600px;
            margin: 30px auto;
            background-color: #f7fafc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #cbd5e0;
        }
        form label {
            display: block;
            margin-top: 15px;
            color: #2c5282;
            font-weight: 600;
        }
        form input[type="text"],
        form input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #a0aec0;
            border-radius: 5px;
        }
        .boton {
            margin-top: 20px;
            background-color: #2b6cb0;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }
        .boton:hover {
            background-color: #2c5282;
        }
        .mensaje {
            margin-top: 15px;
            color: green;
        }
    </style>
</head>
<body>
    <header>
        <h1 class="titulo">Editar Perfil</h1>
    </header>

    <div class="nav-bg">
        <nav class="navegacion-principal contenedor">
            <a href="paciente_inicio.php">Inicio</a>
            <a href="ver_citas_paciente.php">Mis Citas</a>
            <a href="paciente_agendar_cita.php">Agendar Cita</a>
            <a href="paciente_editar_perfil.php">Editar Perfil</a>
            <a href="logout.php" class="button">Cerrar Sesión</a>
        </nav>
    </div>

    <section class="contenedor">
        <h2>Actualizar Información</h2>
        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($paciente['nombre']) ?>" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($paciente['email']) ?>" required>

            <button type="submit" class="boton">Guardar Cambios</button>
        </form>
        <?php if ($mensaje): ?>
            <p class="mensaje"><?= $mensaje ?></p>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2025 Gestión de Citas Médicas</p>
    </footer>
</body>
</html>
