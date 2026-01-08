<?php
require '../conexion.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

// Obtener proveedores
$proveedores = $pdo->query("SELECT * FROM Proveedor")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<!-- ======================  CSS MODERNO  ====================== -->
<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }

.add-container {
    margin-left: 260px;
    padding: 30px;
    animation: fade .3s ease-in-out;
}

@keyframes fade {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

h2 {
    font-weight: 700;
    color: #343a40;
    margin-bottom: 20px;
}

/* Card moderna */
.form-card {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    max-width: 700px;
}

/* Inputs */
.form-control {
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ced4da;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 6px rgba(0,123,255,0.3);
}

/* Botón guardar moderno */
.btn-success {
    background: #28a745;
    border-radius: 10px;
    padding: 12px 18px;
    border: none;
    transition: .2s;
}
.btn-success:hover {
    background: #218838;
    transform: translateY(-2px);
}
</style>
<!-- =========================================================== -->

<div class="add-container">
    <h2>Agregar Producto</h2>

    <div class="form-card">
        <form action="producto_guardar.php" method="POST">

            <label><strong>Nombre:</strong></label>
            <input type="text" name="nombre" required class="form-control">

            <label><strong>Descripción:</strong></label>
            <textarea name="descripcion" class="form-control"></textarea>

            <label><strong>Costo:</strong></label>
            <input type="number" name="costo" step="0.01" required class="form-control">

            <label><strong>Precio:</strong></label>
            <input type="number" name="precio" step="0.01" required class="form-control">

            <label><strong>Stock:</strong></label>
            <input type="number" name="stock" required class="form-control">

            <label><strong>Género:</strong></label>
            <select name="genero" class="form-control">
                <option value="U">Unisex</option>
                <option value="H">Hombre</option>
                <option value="M">Mujer</option>
            </select>

            <label><strong>Etapa Edad:</strong></label>
            <select name="etapaEdad" class="form-control">
                <option value="Bebé">Bebé</option>
                <option value="Niñez">Niñez</option>
                <option value="Adolescencia">Adolescencia</option>
                <option value="Adulto joven">Adulto joven</option>
                <option value="Adulto">Adulto</option>
                <option value="Adulto mayor">Adulto mayor</option>
            </select>

            <label><strong>Tipo de Producto:</strong></label>
            <input type="text" name="tipoProducto" class="form-control">

            <label><strong>Material:</strong></label>
            <input type="text" name="material" class="form-control">

            <label><strong>Categoría:</strong></label>
            <input type="text" name="categoria" class="form-control">

            <label><strong>Proveedor:</strong></label>
            <select name="idProveedor" required class="form-control">
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?= $prov['idProveedor'] ?>">
                        <?= $prov['nombre'] ?>
                    </option>
                <?php endforeach ?>
            </select>

            <button class="btn btn-success mt-3">Guardar</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
