<?php
session_start();
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Vendedor") {
    header("Location: ../index.php");
    exit;
}
$username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Vendedor</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f5f6fa; }
.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    left: 0; top: 0;
    background: #212529;
    padding-top: 20px;
}
.sidebar a {
    color: white;
    padding: 15px;
    display: block;
    text-decoration: none;
}
.sidebar a:hover {
    background: #198754;
}
.content {
    margin-left: 260px;
    padding: 25px;
}
</style>
</head>

<body>

<div class="sidebar">
    <h4 class="text-white text-center">Vendedor</h4>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="ventas.php">💰 Registrar Venta</a>
    <a href="../logout.php" style="background:#dc3545;">🚪 Cerrar Sesión</a>
</div>

<div class="content">
    <h2>Hola, <strong><?= $username ?></strong></h2>
    <p class="text-muted">Panel del Vendedor</p>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Registrar Venta</h5>
                    <p class="card-text">Realiza una venta y obtén tu comisión.</p>
                    <a href="ventas.php" class="btn btn-success">Registrar Venta</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">Revisar Salario</h5>
                    <p class="card-text">Revisa salario más tu comisión.</p>
                    <a href="salario.php" class="btn btn-success">Registrar Venta</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
