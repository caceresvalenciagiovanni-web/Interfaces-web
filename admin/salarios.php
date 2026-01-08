<?php
session_start();
require "../conexion.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../index.php");
    exit;
}

$fechaHoy = date("Y-m-d");

$stmt = $pdo->prepare("
    SELECT p.nombre, p.apellidoP, p.apellidoM,
           r.salarioBase, r.comisiones, r.totalDia
    FROM RegistroSalario r
    JOIN Persona p ON p.idPersona = r.idVendedor
    WHERE r.fecha = ?
");
$stmt->execute([$fechaHoy]);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Salarios del Personal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f5f6fa; }
.sidebar {
    width: 240px; height:100vh;
    position:fixed; left:0;top:0;
    background:#212529; padding-top:20px;
}
.sidebar a {
    color:white; padding:15px; display:block;
    text-decoration:none;
}
.sidebar a:hover { background:#0d6efd; }
.content { margin-left:260px; padding:20px; }
</style>
</head>

<body>

<div class="sidebar">
    <h4 class="text-white text-center">Admin</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="salarios.php">📊 Salarios del Personal</a>
    <a href="../logout.php" style="background:#dc3545;">🚪 Cerrar Sesión</a>
</div>

<div class="content">
    <h2>Salarios del Personal - <?= $fechaHoy ?></h2>

    <table class="table table-bordered table-striped mt-4 shadow">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Salario Base</th>
                <th>Comisiones</th>
                <th>Total del Día</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registros as $r): ?>
            <tr>
                <td><?= $r["nombre"] . " " . $r["apellidoP"] . " " . $r["apellidoM"] ?></td>
                <td>$<?= number_format($r["salarioBase"],2) ?></td>
                <td>$<?= number_format($r["comisiones"],2) ?></td>
                <td><strong>$<?= number_format($r["totalDia"],2) ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
