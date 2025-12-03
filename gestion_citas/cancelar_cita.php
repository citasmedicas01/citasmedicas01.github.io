<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cita_id'])) {
    $cita_id = intval($_POST['cita_id']);

    $sql = "DELETE FROM citas WHERE id = $cita_id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_pacientes.php");
        exit;
    } else {
        echo "Error al cancelar la cita: " . $conn->error;
    }
} else {
    echo "Solicitud inválida.";
}
?>
