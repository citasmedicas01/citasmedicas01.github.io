<?php
require 'config.php';
$id = $_GET['id'];
$sql = "SELECT * FROM usuarios WHERE id = $id AND tipo_usuario = 'doctor'";
$result = $conn->query($sql);
$doctor = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Doctor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Editar Doctor</h1>
    <form action="actualizar_doctor.php" method="POST" class="formulario">
        <input type="hidden" name="id" value="<?php echo $doctor['id']; ?>">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $doctor['nombre']; ?>" required>

        <label>Especialidad:</label>
        <input type="text" name="especialidad" value="<?php echo $doctor['especialidad']; ?>" required>

        <label>Consultorio:</label>
        <input type="text" name="consultorio" value="<?php echo $doctor['consultorio']; ?>" required>

        <input type="submit" value="Actualizar Doctor" class="button">
    </form>
</body>
</html>
