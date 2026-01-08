<?php
session_start();

// 1. CARGAMOS LA NUEVA CONFIGURACIÓN (Incluye el Autoloader y Eloquent)
// Ya no usamos "require ../conexion.php"
require_once __DIR__ . '/../config/database.php';

use App\Models\Producto;
use App\Models\Cliente;
use App\Models\MetodoPago;
use App\Controllers\VentaController;

// Verificación de seguridad (Rol)
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Vendedor") {
    header("Location: ../index.php");
    exit;
}

$idVendedor = $_SESSION["idPersona"]; // Esto viene del login
$mensaje = "";
$tipoMensaje = ""; // Para poner colores (verde/rojo)

// =========================================================
// 2. PROCESAR EL FORMULARIO (Usando tu nuevo Controlador)
// =========================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Preparamos los datos tal como los espera tu controlador
    $datosVenta = [
        'producto'   => $_POST["producto"],
        'cantidad'   => $_POST["cantidad"],
        'cliente'    => $_POST["cliente"],
        'metodo'     => $_POST["metodo"],
        'idVendedor' => $idVendedor
    ];

    // Instanciamos el "Cerebro" y le pedimos que trabaje
    $controlador = new VentaController();
    $resultado = $controlador->store($datosVenta);

    if ($resultado['success']) {
        $mensaje = $resultado['mensaje'];
        $tipoMensaje = "alert-success"; // Verde de Bootstrap
    } else {
        $mensaje = $resultado['error'];
        $tipoMensaje = "alert-danger"; // Rojo de Bootstrap
    }
}

// =========================================================
// 3. OBTENER DATOS PARA LA VISTA (Usando Modelos, no SQL)
// =========================================================
// Mira qué limpio es esto comparado con los $pdo->query("SELECT...") antiguos
$productos = Producto::all(); 
$clientes  = Cliente::all();
$metodos   = MetodoPago::all();

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registrar Venta (Versión MVC)</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../style.css" rel="stylesheet">

<style>
/* Estilos específicos para el sidebar (los mantenemos igual) */
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
    <h2>Registrar Venta</h2>
    <p class="text-muted">Sistema poteciado por ORM Eloquent</p>

    <?php if (!empty($mensaje)): ?>
        <div class="alert <?= $tipoMensaje ?> shadow-sm">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow" style="max-width:500px;">
        
        <label class="fw-bold">Cliente:</label>
        <select name="cliente" class="form-select mb-3" required>
            <option value="">Seleccionar...</option>
            <?php foreach ($clientes as $c): ?>
                <option value="<?= $c->idPersona ?>">
                    <?= $c->nombre ?> <?= $c->apellidoP ?> (ID: <?= $c->idPersona ?>)
                </option>
            <?php endforeach; ?>
        </select>
    
        <label class="fw-bold">Producto:</label>
        <select name="producto" class="form-select mb-3" required>
            <option value="">Seleccionar...</option>
            <?php foreach ($productos as $p): ?>
                <option value="<?= $p->idProducto ?>">
                    <?= $p->nombre ?> — $<?= $p->precio ?> (Stock: <?= $p->stock ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label class="fw-bold">Cantidad:</label>
        <input type="number" name="cantidad" class="form-control mb-3" min="1" required>

        <label class="fw-bold">Método de Pago:</label>
        <select name="metodo" class="form-select mb-3" required>
            <?php foreach ($metodos as $m): ?>
                <option value="<?= $m->idMetodoPago ?>"><?= $m->descripcion ?></option>
            <?php endforeach; ?>
        </select>

        <button class="btn btn-success w-100 mt-2">✅ Registrar Venta</button>
    </form>
</div>

</body>
</html>
