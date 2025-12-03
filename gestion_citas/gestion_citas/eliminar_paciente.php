<?php
require 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Primero elimina citas asociadas
    $conn->query("DELETE FROM citas WHERE paciente_id = $id");

    // Luego elimina el paciente
    $sql = "DELETE FROM usuarios WHERE id = $id AND tipo_usuario = 'paciente'";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_pacientes.php");
        exit;
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
} else {
    echo "ID no proporcionado.";
}
?>
