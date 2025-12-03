<?php
require 'config.php';

$id = $_GET['id'];

$sql = "DELETE FROM usuarios WHERE id = $id AND tipo_usuario = 'doctor'";

if ($conn->query($sql) === TRUE) {
    header("Location: admin_doctores.php");
} else {
    echo "Error al eliminar: " . $conn->error;
}
?>
