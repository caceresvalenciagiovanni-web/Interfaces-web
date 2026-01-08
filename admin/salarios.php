<?php
session_start();
require "../config/database.php";

use App\Models\RegistroSalario;

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Admin") {
    header("Location: ../index.php");
    exit;
}

$fechaHoy = date("Y-m-d");

// ORM: Traer registros de hoy, INCLUYENDO la información del vendedor
// Esto reemplaza tu antiguo "SELECT ... JOIN Persona ..."
$registros = RegistroSalario::with('vendedor')
                ->where('fecha', $fechaHoy)
                ->get();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Salarios del Personal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f5f6fa; }
.sidebar { width: 240px; height:100vh; position:fixed; left:0; top:0; background:#343a40; padding-top:20px; }
.sidebar a { color:white; padding:15px; display:block; text-decoration:none; }
.sidebar a:hover { background:#0d6efd; }
.content { margin-left:260px; padding:20px; }
</style>
</head>

<body>

<div class="sidebar">
    <h4 class="text-white text-center">Admin</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="productos.php">📦 Productos</a>
    <a href="salarios.php" class="bg-primary">📊 Salarios</a>
    <a href="../logout.php" style="background:#dc3545;">🚪 Salir</a>
</div>

<div class="content">
    <h2>Salarios del Personal - <?= $fechaHoy ?></h2>

    <table class="table table-bordered table-striped mt-4 shadow bg-white">
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
                <td>
                    <?php if ($r->vendedor): ?>
                        <?= $r->vendedor->nombre . " " . $r->vendedor->apellidoP . " " . $r->vendedor->apellidoM ?>
                    <?php else: ?>
                        <span class="text-muted">Desconocido (ID: <?= $r->idVendedor ?>)</span>
                    <?php endif; ?>
                </td>
                <td>$<?= number_format($r->salarioBase, 2) ?></td>
                <td>$<?= number_format($r->comisiones, 2) ?></td>
                <td><strong>$<?= number_format($r->salarioBase + $r->comisiones, 2) ?></strong></td>
            </tr>
            <?php endforeach; ?>
            
            <?php if ($registros->isEmpty()): ?>
                <tr><td colspan="4" class="text-center">No hay registros de salario para hoy.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
