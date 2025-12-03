<?php
require 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Doctores</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1 class="titulo">Gestión de Doctores</h1>
    </header>

    <div class="nav-bg">
        <nav class="navegacion-principal contenedor">
            <a href="index.html">Inicio</a>
            <a href="Agendar.html">Agendar Cita</a>
            <a href="admin_pacientes.php">Pacientes</a>
            <a href="admin_doctores.php">Doctores</a>
            <a href="Contacto.html">Contacto</a>
            <a href="registro.html" class="button">Registrar</a>
            <a href="iniciar.html" class="button">Iniciar Sesión</a>
        </nav>
    </div>

    <section class="contenedor">
        <div class="acciones">
            <a href="AgregarDoctor.html" class="button">Agregar Doctor</a>
        </div>

        <h2>Lista de Doctores</h2>

        <table class="tabla-doctores">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Especialidad</th>
                    <th>Consultorio</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id, nombre, especialidad, consultorio, email FROM usuarios WHERE tipo_usuario = 'doctor'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['especialidad']}</td>
                            <td>{$row['consultorio']}</td>
                            <td>{$row['email']}</td>
                            <td>
                                <a href='editar_doctor.php?id={$row['id']}' class='button'>Editar</a>
                                <a href='eliminar_doctor.php?id={$row['id']}' class='button' onclick=\"return confirm('¿Estás seguro de eliminar este doctor?');\">Eliminar</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay doctores registrados.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </section>

    <footer>
        <p>&copy; 2025 Gestión de Citas Médicas</p>
    </footer>
</body>
</html>
