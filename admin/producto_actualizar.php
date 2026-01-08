<?php
session_start();
require '../config/database.php';

use App\Controllers\ProductoController;

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos el ID que viene oculto en el formulario
    $id = $_POST['idProducto'];

    // Instanciamos el controlador
    $controller = new ProductoController();
    
    // Le pasamos el ID y todos los datos del formulario ($_POST)
    // El controlador se encarga de la magia con Eloquent
    $resultado = $controller->update($id, $_POST);

    // Opcional: Podrías guardar $resultado['mensaje'] en sesión para mostrarlo
}

// Redirigimos a la lista de productos
header("Location: productos.php");
exit;
