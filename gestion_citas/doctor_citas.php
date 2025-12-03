<?php
session_start();
include("config.php");

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] !== 'doctor') {
    header("Location: iniciar.html");
    exit();
}

$doctor_id = $_SESSION['usuario_id'];

$sql = "SELECT c.id, c.fecha, c.hora, c.estado, u.nombre AS nombre_paciente
        FROM citas c
        JOIN usuarios u ON c.id_paciente = u.id
        WHERE c.id_doctor = ?
        ORDER BY c.fecha DESC, c.hora DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}

$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Citas del Doctor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/modern-normalize/2.0.0/modern-normalize.min.css">
    <style>
        /* ... mismo estilo CSS ... */
    </style>
</head>
<body>
    <header>
        <h1>Panel del Doctor</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
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
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_paciente']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                    <td><?php echo htmlspecialchars($row['hora']); ?></td>
                    <td><?php echo ucfirst($row['estado']); ?></td>
                    <td class="acciones">
                        <?php if ($row['estado'] === 'pendiente'): ?>
                            <form action="actualizar_estado_cita.php" method="POST">
                                <input type="hidden" name="cita_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="accion" value="confirmar">
                                <button class="confirmar">✔ Confirmar</button>
                            </form>
                            <form action="actualizar_estado_cita.php" method="POST">
                                <input type="hidden" name="cita_id" value="<?php echo $row['id']; ?>">
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
