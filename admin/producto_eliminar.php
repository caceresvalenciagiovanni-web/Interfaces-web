<?php
require '../conexion.php';

$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM Producto WHERE idProducto = ?");
$stmt->execute([$id]);

header("Location: productos.php");
exit;
