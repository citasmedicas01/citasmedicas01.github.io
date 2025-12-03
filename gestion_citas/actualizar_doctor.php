<?php
require 'config.php';

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$especialidad = $_POST['especialidad'];
$consultorio = $_POST['consultorio'];

$sql = "UPDATE usuarios SET nombre='$nombre', especialidad='$especialidad', consultorio='$consultorio' 
        WHERE id=$id AND tipo_usuario='doctor'";

if ($conn->query($sql) === TRUE) {
    header("Location: admin_doctores.php");
} else {
    echo "Error al actualizar: " . $conn->error;
}
?>
