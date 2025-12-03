<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, nombre, password, tipo_usuario FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($password, $usuario['password'])) {
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            if ($usuario['tipo_usuario'] === 'doctor') {
                header("Location: doctor_inicio.php");
            } elseif ($usuario['tipo_usuario'] === 'paciente') {
                header("Location: paciente_inicio.php");
            } else {
                $_SESSION['login_error'] = "Tipo de usuario no válido.";
                header("Location: iniciar.php");
            }
            exit;
        } else {
            $_SESSION['login_error'] = "Contraseña incorrecta.";
        }
    } else {
        $_SESSION['login_error'] = "Usuario no encontrado.";
    }

    header("Location: iniciar.php");
    exit;
}
