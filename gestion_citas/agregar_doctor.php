<?php
require 'config.php';

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$especialidad = $_POST['especialidad'];
$consultorio = $_POST['consultorio'];
$email = $_POST['email'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

// Validar que el email no esté vacío
if (empty($email)) {
    die("El campo email es obligatorio.");
}

// Insertar en la base de datos
$sql = "INSERT INTO usuarios (nombre, especialidad, consultorio, contrasena, email, tipo_usuario) 
        VALUES ('$nombre', '$especialidad', '$consultorio', '$contrasena', '$email', 'doctor')";

if ($conn->query($sql) === TRUE) {
    header("Location: admin_doctores.php");
} else {
    echo "Error al registrar: " . $conn->error;
}
?>
