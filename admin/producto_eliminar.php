<?php
session_start();
require '../config/database.php'; // Cargar ORM

use App\Controllers\ProductoController;

// Seguridad
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Instanciamos el controlador y usamos el método que creamos
    $controller = new ProductoController();
    $controller->destroy($id);
}

// Redirigir de vuelta a la lista
header("Location: productos.php");
exit;
