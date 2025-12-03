<?php
session_start();
require 'config.php';

if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: iniciar.php");
    exit();
}

// Obtener lista de doctores
$sql = "SELECT id, nombre, especialidad FROM usuarios WHERE tipo_usuario = 'doctor'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Agendar Cita</title>
    <link rel="stylesheet" href="style.css"> <!-- Puedes usar el estilo base -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }

        .contenedor {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #2b6cb0;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin: 12px 0 5px;
            color: #2d3748;
            font-weight: 500;
        }

        form input,
        form select {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e0;
            border-radius: 5px;
            font-size: 16px;
        }

        .boton {
            display: block;
            width: 100%;
            background-color: #3182ce;
            color: white;
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 5px;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .boton:hover {
            background-color: #2c5282;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Agendar Cita Médica</h1>
        <form action="procesar_cita.php" method="POST">
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
    </div>
</body>
</html>
