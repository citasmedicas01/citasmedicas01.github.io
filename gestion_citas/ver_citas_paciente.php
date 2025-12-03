<?php
session_start();
include("config.php");

// Verificar que esté logueado y sea paciente
if (!isset($_SESSION['id']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: iniciar.html");
    exit();
}

$paciente_id = $_SESSION['id'];

$sql = "SELECT c.id, c.fecha, c.hora, c.estado, u.nombre AS nombre_doctor
        FROM citas c
        JOIN usuarios u ON c.id_doctor = u.id
        WHERE c.id_paciente = ?
        ORDER BY c.fecha DESC, c.hora DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $paciente_id);
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
        .cancelar {
            background-color: #dc3545;
            color: white;
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1 class="titulo">Mis Citas</h1>
    </header>

    <main>
        <h2>Listado de Citas</h2>
        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre_doctor']) ?></td>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= htmlspecialchars($row['hora']) ?></td>
                        <td><?= ucfirst($row['estado']) ?></td>
                        <td>
                            <?php if ($row['estado'] === 'pendiente'): ?>
                                <form action="cancelar_cita.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="cita_id" value="<?= $row['id'] ?>">
                                    <button class="cancelar" onclick="return confirm('¿Estás seguro de cancelar esta cita?');">✖ Cancelar</button>
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
