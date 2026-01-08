<?php
session_start();
// 1. CORRECCIÓN: Cargar config
require '../config/database.php';

use App\Models\Producto;
use App\Models\Proveedor;

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'];

// 2. CORRECCIÓN: Eliminamos SQL manual. Usamos solo Eloquent.
$producto = Producto::find($id);

if (!$producto) {
    die("Producto no encontrado");
}

// 3. CORRECCIÓN: Proveedores con ORM
$proveedores = Proveedor::all();

// include 'header.php';
?>

<style>
body { background:#f5f6fa; font-family: 'Segoe UI', sans-serif; }
.edit-container { margin-left: 260px; padding: 30px; animation: fade .3s ease-in-out; }
@keyframes fade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
h2 { font-weight: 700; color: #343a40; margin-bottom: 20px; }
.form-card { background: white; padding: 25px; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 700px; }
.form-control { border-radius: 10px; padding: 10px; margin-bottom: 15px; border: 1px solid #ced4da; }
.form-control:focus { border-color: #007bff; box-shadow: 0 0 6px rgba(0,123,255,0.3); }
.btn-primary { background: #007bff; border-radius: 10px; padding: 12px 18px; border: none; transition: .2s; }
.btn-primary:hover { background: #0069d9; transform: translateY(-2px); }
</style>
<div class="edit-container">
    <h2>Editar Producto</h2>

    <div class="form-card">
        <form action="producto_actualizar.php" method="POST">
            <input type="hidden" name="idProducto" value="<?= $producto->idProducto ?>">

            <label><strong>Nombre:</strong></label>
            <input type="text" name="nombre" value="<?= $producto->nombre ?>" required class="form-control">

            <label><strong>Descripción:</strong></label>
            <textarea name="descripcion" class="form-control"><?= $producto->descripcion ?></textarea>

            <label><strong>Costo:</strong></label>
            <input type="number" name="costo" value="<?= $producto->costo ?>" step="0.01" required class="form-control">

            <label><strong>Precio:</strong></label>
            <input type="number" name="precio" value="<?= $producto->precio ?>" step="0.01" required class="form-control">

            <label><strong>Stock:</strong></label>
            <input type="number" name="stock" value="<?= $producto->stock ?>" required class="form-control">

            <label><strong>Género:</strong></label>
            <select name="genero" class="form-control">
                <option value="U" <?= $producto->genero == 'U' ? 'selected' : '' ?>>Unisex</option>
                <option value="H" <?= $producto->genero == 'H' ? 'selected' : '' ?>>Hombre</option>
                <option value="M" <?= $producto->genero == 'M' ? 'selected' : '' ?>>Mujer</option>
            </select>

            <label><strong>Etapa Edad:</strong></label>
            <select name="etapaEdad" class="form-control">
                <?php
                $etapas = ['Bebé','Niñez','Adolescencia','Adulto joven','Adulto','Adulto mayor'];
                foreach($etapas as $e){
                    // Ojo: $producto->etapaEdad
                    echo "<option value='$e' ".($producto->etapaEdad == $e ? 'selected' : '').">$e</option>";
                }
                ?>
            </select>

            <label><strong>Tipo:</strong></label>
            <input type="text" name="tipoProducto" value="<?= $producto->tipoProducto ?>" class="form-control">

            <label><strong>Material:</strong></label>
            <input type="text" name="material" value="<?= $producto->material ?>" class="form-control">

            <label><strong>Categoría:</strong></label>
            <input type="text" name="categoria" value="<?= $producto->categoria ?>" class="form-control">

            <label><strong>Proveedor:</strong></label>
            <select name="idProveedor" class="form-control">
                <?php foreach ($proveedores as $prov): ?>
                    <option value="<?= $prov->idProveedor ?>"
                        <?= $producto->idProveedor == $prov->idProveedor ? 'selected' : '' ?>>
                        <?= $prov->nombre ?>
                    </option>
                <?php endforeach ?>
            </select>

            <button class="btn btn-primary mt-3">Actualizar</button>
        </form>
    </div>
</div>

<?php 
// include 'footer.php'; 
?>
