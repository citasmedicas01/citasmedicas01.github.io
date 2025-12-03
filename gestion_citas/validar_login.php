<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, nombre, contrasena, tipo_usuario FROM usuarios WHERE email = ?");
    if (!$stmt) {
        die("Error en la preparación: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($password, $usuario['contrasena'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            if ($usuario['tipo_usuario'] === 'doctor') {
                header("Location: doctor_inicio.php");
            } elseif ($usuario['tipo_usuario'] === 'paciente') {
                header("Location: paciente_inicio.php");
            } else {
                header("Location: iniciar.php?error=Tipo%20de%20usuario%20no%20válido.");
            }
            exit;
        } else {
            header("Location: iniciar.php?error=Contraseña%20incorrecta.");
            exit;
        }
    } else {
        header("Location: iniciar.php?error=Usuario%20no%20encontrado.");
        exit;
    }
}
?>
