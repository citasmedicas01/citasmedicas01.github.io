
<?php
require 'config.php';

$nombre = $_POST['nombre'];
$especialidad = $_POST['especialidad'];
$consultorio = $_POST['consultorio'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nombre, especialidad, consultorio, contrasena, tipo_usuario) 
        VALUES ('$nombre', '$especialidad', '$consultorio', '$contrasena', 'doctor')";

if ($conn->query($sql) === TRUE) {
    header("Location: admin_doctores.php");
} else {
    echo "Error al registrar: " . $conn->error;
}
?>
