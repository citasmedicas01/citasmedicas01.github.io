<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM usuarios WHERE id = $id AND tipo_usuario = 'paciente'";
    $result = $conn->query($sql);
    $paciente = $result->fetch_assoc();
} else {
    die("ID inválido");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Paciente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1 class="titulo">Editar Paciente</h1>
</header>

<section class="contenedor">
    <form action="actualizar_paciente.php" method="POST" class="formulario">
        <input type="hidden" name="id" value="<?= $paciente['id'] ?>">

        <div class="campo">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= $paciente['nombre'] ?>" required>
        </div>

        <div class="campo">
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $paciente['fecha_nacimiento'] ?>" required>
        </div>

        <button type="submit" class="button">Actualizar</button>
        <button type="button" class="button volver" onclick="window.location.href='admin_pacientes.php'">Cancelar</button>
    </form>
</section>

<footer>
    <p>&copy; 2025 Gestión de Citas Médicas</p>
</footer>
</body>
</html>
