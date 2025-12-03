<?php
require 'config.php';

// Obtener lista de doctores (opcional: puedes moverlo a otro archivo si prefieres 100% HTML aquí también)
$sql = "SELECT id, nombre, especialidad FROM usuarios WHERE tipo_usuario = 'doctor'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Agendar Cita Médica</title>
    <link rel="stylesheet" href="formulario.css" />
</head>
<body>
    <header>
        <h1>Agendar Cita Médica</h1>
    </header>

    <main class="contenedor">
        <form action="procesar_cita.php" method="POST" class="formulario">
            <label for="nombre">Nombre del paciente:</label>
            <input type="text" name="nombre" id="nombre" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" id="email" required>

            <label for="fecha">Fecha de la cita:</label>
            <input type="date" name="fecha" id="fecha" required>

            <label for="hora">Hora de la cita:</label>
            <input type="time" name="hora" id="hora" required>

            <label for="id_doctor">Selecciona un doctor:</label>
            <select name="id_doctor" id="id_doctor" required>
                <option value="">-- Selecciona --</option>
                <?php while($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>">
                        <?= htmlspecialchars($row['nombre']) ?> - <?= htmlspecialchars($row['especialidad']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="boton">Agendar Cita</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2025 Gestión de Citas Médicas</p>
    </footer>
</body>
</html>
