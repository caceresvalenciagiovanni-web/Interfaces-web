<?php
session_start();
require '../config/database.php';

use App\Controllers\ProductoController;

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new ProductoController();
    
    // El método store() espera un array, $_POST es perfecto
    $resultado = $controller->store($_POST);

    // Podrías agregar lógica para mostrar errores, 
    // pero por ahora redirigimos al listado.
}

header("Location: productos.php");
exit;
