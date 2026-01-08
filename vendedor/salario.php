<?php
session_start();
// 1. Cargamos la configuración moderna (Eloquent)
require "../config/database.php";

use App\Models\RegistroSalario;
use App\Models\Venta;

// Validación de seguridad
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Vendedor") {
    header("Location: ../index.php");
    exit;
}

$idVendedor = $_SESSION["idPersona"];
$hoy = date("Y-m-d");

// ==========================================
// CONSULTAS CON ORM (Sin SQL manual)
// ==========================================

// A. Obtener Salario del día
$salario = RegistroSalario::where('idVendedor', $idVendedor)
                          ->where('fecha', $hoy)
                          ->first();

// Si por alguna razón no existe registro (raro), inicializamos valores en 0 visualmente
if (!$salario) {
    // Creamos un objeto "falso" o array simple para no romper el HTML abajo
    $salario = (object) ['salarioBase' => 0, 'comisiones' => 0, 'totalDia' => 0];
} else {
    // Calculamos totalDia si no viene de la BD
    if (!isset($salario->totalDia)) {
        $salario->totalDia = $salario->salarioBase + $salario->comisiones;
    }
}

// B. Obtener Ventas y Artículos del día
// Traemos todas las ventas de hoy de este empleado
$ventasHoy = Venta::where('idEmpleado', $idVendedor)
                  ->where('fecha', $hoy)
                  ->get();

$cantidadVentas = $ventasHoy->count();

// C. Calcular Total de Artículos Vendidos
// Recorremos las ventas y sumamos la cantidad de sus detalles
$articulosVendidos = 0;
foreach ($ventasHoy as $venta) {
    // Usamos la relación 'detalles' que definimos en el modelo Venta
    $articulosVendidos += $venta->detalles()->sum('cantidad');
}
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

        <p><strong>Salario Base:</strong> $<?= number_format($salario->salarioBase, 2) ?></p>
        <p><strong>Comisiones:</strong> $<?= number_format($salario->comisiones, 2) ?></p>
        <p class="fs-4"><strong>Total del Día:</strong> $<?= number_format($salario->totalDia, 2) ?></p>

        <hr>

        <p><strong>Ventas realizadas:</strong> <?= $cantidadVentas ?></p>
        <p><strong>Artículos vendidos:</strong> <?= $articulosVendidos ?></p>
    </div>
</div>

</body>
</html>
