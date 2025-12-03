<?php
require 'config.php';
session_start();

$id = $_SESSION['usuario_id'];
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$especialidad = $_POST['especialidad'];
$experiencia = $_POST['experiencia'];

// Subida de foto
$foto_nombre = null;
if (!empty($_FILES['foto']['name'])) {
    $foto_nombre = 'uploads/' . basename($_FILES['foto']['name']);
    move_uploaded_file($_FILES['foto']['tmp_name'], $foto_nombre);
}

$sql = "UPDATE usuarios SET nombre = ?, email = ?, especialidad = ?, experiencia = ?";
if ($foto_nombre) {
    $sql .= ", foto_perfil = ?";
}
$sql .= " WHERE id = ?";

$stmt = $conn->prepare($foto_nombre ?
    "$sql" : "$sql");

if ($foto_nombre) {
    $stmt->bind_param("sssssi", $nombre, $email, $especialidad, $experiencia, $foto_nombre, $id);
} else {
    $stmt->bind_param("ssssi", $nombre, $email, $especialidad, $experiencia, $id);
}

if ($stmt->execute()) {
    echo "Perfil actualizado con éxito.";
} else {
    echo "Error al actualizar el perfil.";
}
?>
