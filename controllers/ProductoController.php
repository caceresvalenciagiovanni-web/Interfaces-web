<?php

namespace App\Controllers;

use App\Models\Producto;

class ProductoController
{
    // 1. MÉTODO PARA LISTAR (Reemplaza la lógica de admin/productos.php)
    public function index()
    {
        // Usamos el ORM para traer productos con su proveedor
        $productos = Producto::with('proveedor')->get();

        // Aquí cargaríamos la vista (HTML).
        // Por ahora, para probar, devolvemos los datos.
        return $productos;
    }

    // 2. MÉTODO PARA GUARDAR (Reemplaza admin/producto_guardar.php)
    public function store($datos)
    {
        try {
            // ¡Mira qué fácil es guardar con Eloquent!
            // Como definimos $fillable en el modelo, esto es seguro.
            Producto::create($datos);
            return ["success" => true, "mensaje" => "Producto guardado correctamente"];
        } catch (\Exception $e) {
            return ["success" => false, "error" => $e->getMessage()];
        }
    }

    // 3. MÉTODO PARA ELIMINAR (Reemplaza admin/producto_eliminar.php)
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if ($producto) {
            $producto->delete();
            return ["success" => true, "mensaje" => "Producto eliminado"];
        } else {
            return ["success" => false, "error" => "Producto no encontrado"];
        }
    }

    // ... (tus métodos index, store y destroy anteriores) ...

    // 4. MÉTODO PARA MOSTRAR UNO SOLO (Reemplaza la consulta de producto_editar.php)
    public function show($id)
    {
        // Busca por ID primaria. Si no encuentra, devuelve null.
        return Producto::find($id);
    }

    // 5. MÉTODO PARA ACTUALIZAR (Reemplaza admin/producto_actualizar.php)
    public function update($id, $datos)
    {
        // Primero buscamos si existe
        $producto = Producto::find($id);

        if ($producto) {
            // Eloquent llena los campos con el array $datos y guarda cambios
            $producto->update($datos);
            return ["success" => true, "mensaje" => "Producto actualizado correctamente"];
        } else {
            return ["success" => false, "error" => "Producto no encontrado"];
        }
    }
}
