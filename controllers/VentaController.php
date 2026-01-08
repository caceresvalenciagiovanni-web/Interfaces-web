<?php

namespace App\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\RegistroSalario;
use App\Models\Cuenta;
use Illuminate\Database\Capsule\Manager as DB;
use Exception;

class VentaController
{
    public function store($datos)
    {
        // Extraemos los datos para que el código sea más legible
        $idProducto   = $datos['producto'];
        $cantidad     = (int) $datos['cantidad'];
        $idCliente    = $datos['cliente'];
        $idMetodoPago = $datos['metodo'];
        $idVendedor   = $datos['idVendedor']; // Vendrá de la sesión
        $fechaHoy     = date("Y-m-d");

        // =======================================================
        // PASO 1: VALIDACIONES PREVIAS (Antes de la transacción)
        // =======================================================
        
        $producto = Producto::find($idProducto);

        if (!$producto) {
            return ['success' => false, 'error' => 'Producto no encontrado.'];
        }

        if ($producto->stock < $cantidad) {
            return ['success' => false, 'error' => "Stock insuficiente. Solo quedan {$producto->stock} unidades."];
        }

        $totalVenta = $producto->precio * $cantidad;

        // =======================================================
        // PASO 2: INICIO DE TRANSACCIÓN BLINDADA
        // =======================================================
        DB::beginTransaction();

        try {
            // A. Registrar la Venta (Cabecera)
            $venta = Venta::create([
                'fecha'        => $fechaHoy,
                'idCliente'    => $idCliente,
                'idEmpleado'   => $idVendedor,
                'idMetodoPago' => $idMetodoPago,
                'total'        => $totalVenta
            ]);

            // B. Registrar el Detalle
            // ¡Mira qué elegante! Usamos la relación que creamos en el modelo Venta.
            // Eloquent automáticamente asigna el 'idVenta' correcto.
            $venta->detalles()->create([
                'idProducto'     => $idProducto,
                'cantidad'       => $cantidad,
                'precioUnitario' => $producto->precio
            ]);

            // C. Actualizar Stock del Producto
            // decrement() e increment() son métodos mágicos de Eloquent
            $producto->decrement('stock', $cantidad);
            $producto->increment('cantidadVendida', $cantidad);

            // D. Calcular y Asignar Comisión (10 por artículo)
            $comisionGanada = $cantidad * 10;
            
            // Buscamos el registro de salario de HOY para este vendedor
            $salario = RegistroSalario::where('idVendedor', $idVendedor)
                                      ->where('fecha', $fechaHoy)
                                      ->first();

            // Si existe (debería, porque se crea al login), sumamos la comisión
            if ($salario) {
                $salario->increment('comisiones', $comisionGanada);
            }

            // E. Actualizar Saldo de la Cuenta (Caja o Banco)
            // Lógica: Si método es 1 (Efectivo) -> Cuenta 1 (Caja), si no -> Cuenta 2 (Banco)
            $idCuentaDestino = ($idMetodoPago == 1) ? 1 : 2;
            
            $cuenta = Cuenta::find($idCuentaDestino);
            if ($cuenta) {
                $cuenta->increment('saldo', $totalVenta);
            }

            // SI LLEGAMOS AQUÍ SIN ERRORES, CONFIRMAMOS TODO
            DB::commit();

            return [
                'success' => true, 
                'mensaje' => "Venta registrada con éxito. Comisión generada: +$$comisionGanada"
            ];

        } catch (Exception $e) {
            // SI ALGO FALLA, DESHACEMOS CUALQUIER CAMBIO
            DB::rollBack();
            return ['success' => false, 'error' => 'Error crítico al procesar venta: ' . $e->getMessage()];
        }
    }
}
