<?php
require '../conexion.php';
session_start();

// Validar rol
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

include 'header.php';

// Obtener productos
$query = $pdo->query("
    SELECT p.*, pr.nombre AS proveedor
    FROM Producto p
    INNER JOIN Proveedor pr ON p.idProveedor = pr.idProveedor
");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ======================  CSS MODERNO  ====================== -->
<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }

.page-content {
    margin-left: 260px;
    padding: 30px;
    animation: fade .3s ease-in-out;
}

@keyframes fade {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Título */
h2 {
    font-weight: 700;
    color: #343a40;
}

/* Botón agregar */
.btn-primary {
    background: #007bff;
    border-radius: 10px;
    padding: 10px 16px;
    border: none;
    transition: .2s;
}
.btn-primary:hover {
    background: #0069d9;
    transform: translateY(-2px);
}

/* Tabla moderna */
.table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.table thead {
    background: #343a40;
    color: white;
}

.table tbody tr:hover {
    background: #f1f4ff;
    transition: .2s;
}

.table td, .table th {
    vertical-align: middle;
    padding: 14px;
}

/* Botones de acciones */
.btn-sm {
    border-radius: 8px;
    padding: 6px 10px;
    font-size: 14px;
    transition: .2s;
}

.btn-warning {
    background: #ffc107;
    border: none;
}
.btn-warning:hover {
    background: #e0a800;
    transform: scale(1.05);
}

.btn-danger {
    background: #dc3545;
    border: none;
}
.btn-danger:hover {
    background: #c82333;
    transform: scale(1.05);
}
</style>
<!-- =========================================================== -->

<div class="page-content">
    <h2>Gestión de Productos</h2>
    <a href="producto_agregar.php" class="btn btn-primary mb-3">➕ Agregar Producto</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Proveedor</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $p): ?>
                <tr>
                    <td><?= $p['idProducto'] ?></td>
                    <td><?= $p['nombre'] ?></td>
                    <td>$<?= $p['precio'] ?></td>
                    <td><?= $p['stock'] ?></td>
                    <td><?= $p['proveedor'] ?></td>
                    <td>
                        <a href="producto_editar.php?id=<?= $p['idProducto'] ?>" class="btn btn-warning btn-sm">✏ Editar</a>
                        <a href="producto_eliminar.php?id=<?= $p['idProducto'] ?>" class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Eliminar este producto?')">🗑 Eliminar</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
