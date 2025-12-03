<?php
require 'config.php';

// Consulta pacientes con su cita (si la tienen)
$sql = "SELECT u.id, u.nombre, u.fecha_nacimiento, c.id AS cita_id, c.fecha AS fecha_cita, c.hora AS hora_cita
        FROM usuarios u
        LEFT JOIN citas c ON u.id = c.id_paciente
        WHERE u.tipo_usuario = 'paciente'";
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pacientes - MediBook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1 class="titulo">Gestión de Pacientes</h1>
</header>
<div class="nav-bg">
    <nav class="navegacion-principal contenedor">
        <a href="index.html">Inicio</a>
        <a href="Agendar.php">Agendar Cita</a>
        <a href="admin_pacientes.php">Pacientes</a>
        <a href="admin_doctores.php">Doctores</a>
        <a href="Contacto.html">Contacto</a>
        <a href="registro.html" class="button">Registrar</a>
        <a href="iniciar.html" class="button">Iniciar Sesión</a>
    </nav>
</div>

<section class="contenedor">
    <div class="acciones">
    </div>

    <h2>Lista de Pacientes</h2>

    <table class="tabla-pacientes">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha de Nacimiento</th>
                <th>Fecha de Cita</th>
                <th>Hora de Cita</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['nombre'] ?></td>
                        <td><?= $row['fecha_nacimiento'] ?></td>
                        <td><?= $row['fecha_cita'] ?? '—' ?></td>
                        <td><?= $row['hora_cita'] ?? '—' ?></td>
                        <td>
                            <a href="editar_paciente.php?id=<?= $row['id'] ?>" class="button">Editar</a>
                            <a href="eliminar_paciente.php?id=<?= $row['id'] ?>" class="button" onclick="return confirm('¿Estás seguro de eliminar este paciente?')">Eliminar</a>
                            <?php if (!empty($row['cita_id'])): ?>
                                <form action="cancelar_cita.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="cita_id" value="<?= $row['cita_id'] ?>">
                                    <button type="submit" class="button">Cancelar Cita</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No hay pacientes registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>

<footer>
    <p>&copy; 2025 Gestión de Citas Médicas</p>
</footer>
</body>
</html>
