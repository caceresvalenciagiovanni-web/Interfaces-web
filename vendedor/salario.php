<?php
session_start();
require "../conexion.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Vendedor") {
    header("Location: ../index.php");
    exit;
}

$idVendedor = $_SESSION["idPersona"];
$hoy = date("Y-m-d");

$stmt = $pdo->prepare("SELECT salarioBase, comisiones, totalDia
                       FROM RegistroSalario
                       WHERE idVendedor = ? AND fecha = ?");
$stmt->execute([$idVendedor, $hoy]);
$salario = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener número de ventas del día
$stmt2 = $pdo->prepare("SELECT COUNT(*) AS ventas FROM Venta 
                        WHERE idEmpleado = ? AND fecha = ?");
$stmt2->execute([$idVendedor, $hoy]);
$ventas = $stmt2->fetchColumn();

// Artículos vendidos
$stmt3 = $pdo->prepare("SELECT COALESCE(SUM(cantidad),0) AS totalArticulos
                        FROM DetalleVenta dv
                        JOIN Venta v ON dv.idVenta = v.idVenta
                        WHERE v.idEmpleado = ? AND v.fecha = ?");
$stmt3->execute([$idVendedor, $hoy]);
$articulos = $stmt3->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
<title>Mi Salario</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f5f6fa; }
.sidebar {
    width: 240px; height: 100vh;
    position: fixed; left: 0; top: 0;
    background: #212529; padding-top: 20px;
}
.sidebar a {
    color: white; padding: 15px; display: block;
    text-decoration: none;
}
.sidebar a:hover { background: #198754; }
.content { margin-left: 260px; padding: 25px; }
</style>
</head>

<body>

<div class="sidebar">
    <h4 class="text-white text-center">Vendedor</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="ventas.php">💰 Registrar Venta</a>
    <a href="salario.php">📊 Mi Salario</a>
    <a href="../logout.php" style="background:#dc3545;">🚪 Cerrar Sesión</a>
</div>

<div class="content">
    <h2>Mi Salario de Hoy</h2>
    <p class="text-muted">Detalles del salario generado el día de hoy.</p>

    <div class="card shadow p-4" style="max-width:500px;">
        <h4 class="mb-3">Salario del <?= $hoy ?></h4>

        <p><strong>Salario Base:</strong> $<?= $salario["salarioBase"] ?></p>
        <p><strong>Comisiones:</strong> $<?= $salario["comisiones"] ?></p>
        <p class="fs-4"><strong>Total del Día:</strong> $<?= $salario["totalDia"] ?></p>

        <hr>

        <p><strong>Ventas realizadas:</strong> <?= $ventas ?></p>
        <p><strong>Artículos vendidos:</strong> <?= $articulos ?></p>
    </div>
</div>

</body>
</html>
