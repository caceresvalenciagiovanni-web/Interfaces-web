<?php
session_start();

// 1. Cargar configuración moderna
require '../config/database.php';

use App\Models\Producto;

// Validar rol
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

// include 'header.php'; // (Descomenta esto si tienes el archivo header.php)

// 2. OBTENER PRODUCTOS CON ORM
// "Trae todos los productos y cárgame también su proveedor"
// Esto reemplaza tu antiguo "SELECT inner join..."
$productos = Producto::with('proveedor')->get();
?>

<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }
.page-content { margin-left: 260px; padding: 30px; animation: fade .3s ease-in-out; }
@keyframes fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
h2 { font-weight: 700; color: #343a40; }
.btn-primary { background: #007bff; border-radius: 10px; padding: 10px 16px; border: none; transition: .2s; text-decoration: none; color: white; display: inline-block;}
.btn-primary:hover { background: #0069d9; transform: translateY(-2px); }
.table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.table thead { background: #343a40; color: white; }
.table th, .table td { padding: 14px; text-align: left; border-bottom: 1px solid #ddd; }
.table tbody tr:hover { background: #f1f4ff; }
.btn-sm { border-radius: 8px; padding: 6px 10px; font-size: 14px; text-decoration: none; color: white; display: inline-block;}
.btn-warning { background: #ffc107; color: #000; }
.btn-danger { background: #dc3545; }
</style>

<div style="width: 240px; height: 100vh; position: fixed; left: 0; top: 0; background: #343a40; padding-top: 20px;">
    <h4 style="color:white; text-align:center;">Admin Panel</h4>
    <a href="dashboard.php" style="color:white; padding:15px; display:block; text-decoration:none;">🏠 Dashboard</a>
    <a href="productos.php" style="color:white; padding:15px; display:block; text-decoration:none; background:#007bff;">📦 Gestión Productos</a>
    <a href="../logout.php" style="color:white; padding:15px; display:block; text-decoration:none; background:#dc3545; margin-top:20px;">🚪 Cerrar Sesión</a>
</div>

<div class="page-content">
    <h2>Gestión de Productos</h2>
    <a href="producto_agregar.php" class="btn btn-primary mb-3">➕ Agregar Producto</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Proveedor</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= $p->idProducto ?></td>
                    <td><?= $p->nombre ?></td>
                    <td>$<?= number_format($p->precio, 2) ?></td>
                    <td>
                        <?php if($p->stock < 5): ?>
                            <span style="color:red; font-weight:bold;"><?= $p->stock ?> (Bajo)</span>
                        <?php else: ?>
                            <?= $p->stock ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $p->proveedor ? $p->proveedor->nombre : 'Sin Prov.' ?>
                    </td>
                    <td>
                        <a href="producto_editar.php?id=<?= $p->idProducto ?>" class="btn btn-warning btn-sm">✏ Editar</a>
                        <a href="producto_eliminar.php?id=<?= $p->idProducto ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Estás seguro de eliminar este producto?')">🗑 Eliminar</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php 
// include 'footer.php'; 
?>
