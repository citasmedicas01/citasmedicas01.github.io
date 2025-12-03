<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validar campos vacíos
    if (empty($email) || empty($password)) {
        echo "Por favor, completa todos los campos.";
        exit();
    }

    // Consultar usuario por correo
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    if (!$stmt) {
        echo "Error en la consulta: " . $conn->error;
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $usuario['password'])) {
            // Establecer variables de sesión
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['usuario_id'] = $usuario['id']; // 👈 Usa esta
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            // Redirigir según el tipo de usuario
            if ($usuario['tipo_usuario'] === 'doctor') {
                header("Location: doctor_inicio.php");
                exit();
            } elseif ($usuario['tipo_usuario'] === 'paciente') {
                header("Location: paciente_inicio.php");
                exit();
            } else {
                echo "Tipo de usuario no válido.";
            }
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>
