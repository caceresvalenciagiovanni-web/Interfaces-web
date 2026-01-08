<?php
require '../conexion.php';

$stmt = $pdo->prepare("
UPDATE Producto SET
    nombre=?, descripcion=?, costo=?, precio=?, stock=?,
    genero=?, etapaEdad=?, tipoProducto=?, material=?, categoria=?, idProveedor=?
WHERE idProducto = ?
");

$stmt->execute([
    $_POST['nombre'],
    $_POST['descripcion'],
    $_POST['costo'],
    $_POST['precio'],
    $_POST['stock'],
    $_POST['genero'],
    $_POST['etapaEdad'],
    $_POST['tipoProducto'],
    $_POST['material'],
    $_POST['categoria'],
    $_POST['idProveedor'],
    $_POST['idProducto']
]);

header("Location: productos.php");
exit;
