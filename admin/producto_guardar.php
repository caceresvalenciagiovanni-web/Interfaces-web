<?php
require '../conexion.php';

$stmt = $pdo->prepare("
    INSERT INTO Producto
    (nombre, descripcion, costo, precio, stock, genero, etapaEdad, tipoProducto, material, categoria, idProveedor)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
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
    $_POST['idProveedor']
]);

header("Location: productos.php");
exit;
