<?php
session_start();
include("config.php");

// Verifica que el usuario esté logueado y sea doctor
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'doctor') {
    header("Location: iniciar.html");
    exit();
}

$doctor_id = $_SESSION['id'];

$sql = "SELECT c.id, c.fecha, c.hora, c.estado, u.nombre AS nombre_paciente
        FROM citas c
        JOIN usuarios u ON c.id_paciente = u.id
        WHERE c.id_doctor = ?
        ORDER BY c.fecha DESC, c.hora DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en la preparación: " . $conn->error);
}

$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Citas</title>
    <link rel="stylesheet" href="style.css">
    <style>
        main {
            max-width: 1000px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            padding: 0.75rem;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007acc;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f0f4f8;
        }
        button {
            padding: 0.4rem 0.8rem;
            margin: 0 2px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .confirmar {
            background-color: #28a745;
            color: white;
        }
        .cancelar {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <h1 class="titulo">Mis Citas</h1>
    </header>

    <main>
        <h2>Historial de Citas</h2>
        <table>
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre_paciente']) ?></td>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['hora']) ?></td>
                        <td><?= ucfirst($row['estado']) ?></td>
                        <td>
                            <?php if ($row['estado'] === 'pendiente'): ?>
                                <form action="actualizar_estado_cita.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="cita_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="accion" value="confirmar">
                                    <button class="confirmar">✔ Confirmar</button>
                                </form>
                                <form action="actualizar_estado_cita.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="cita_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="accion" value="cancelar">
                                    <button class="cancelar">✖ Cancelar</button>
                                </form>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2025 Gestión de Citas Médicas</p>
    </footer>
</body>
</html>
