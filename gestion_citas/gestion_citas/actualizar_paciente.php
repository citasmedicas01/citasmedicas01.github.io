<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $fecha = $_POST['fecha_nacimiento'];

    $sql = "UPDATE usuarios SET nombre = '$nombre', fecha_nacimiento = '$fecha' WHERE id = $id AND tipo_usuario = 'paciente'";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_pacientes.php");
        exit;
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}
?>
