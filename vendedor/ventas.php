<?php
    session_start();
    require "../conexion.php";

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Vendedor") {
        header("Location: ../index.php");
        exit;
    }

    $idVendedor = $_SESSION["idPersona"];
    $hoy = date("Y-m-d");

    // Obtener productos
    $productos = $pdo->query("SELECT idProducto, nombre, precio, stock FROM Producto")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener métodos de pago
    $metodos = $pdo->query("SELECT idMetodoPago, descripcion FROM MetodoPago")->fetchAll(PDO::FETCH_ASSOC);

    // Obtener clientes
    $clientes = $pdo->query("SELECT idPersona FROM Cliente")->fetchAll(PDO::FETCH_ASSOC);


    // PROCESAR VENTA
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $idProducto = $_POST["producto"];
        $cantidad = intval($_POST["cantidad"]);
        $idMetodoPago = $_POST["metodo"];
        $idCliente = $_POST["cliente"];
        

        // 1. Obtener info del producto
        $stmt = $pdo->prepare("SELECT precio, stock FROM Producto WHERE idProducto = ?");
        $stmt->execute([$idProducto]);
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prod) {
            $msg = "Producto no encontrado.";
        } elseif ($prod["stock"] < $cantidad) {
            $msg = "Stock insuficiente.";
        } else {
            $precio = $prod["precio"];
            $total = $precio * $cantidad;

            // INICIAR TRANSACCIÓN
            $pdo->beginTransaction();

            try {
                // 2. Registrar venta
                $stmt2 = $pdo->prepare("INSERT INTO Venta (fecha, idCliente, idEmpleado, idMetodoPago, total)
                            VALUES (?, ?, ?, ?, ?)");
                $stmt2->execute([$hoy, $idCliente, $idVendedor, $idMetodoPago, $total]);

                $idVenta = $pdo->lastInsertId();

                // 3. Registrar detalle
                $stmt3 = $pdo->prepare("INSERT INTO DetalleVenta (idVenta, idProducto, cantidad, precioUnitario)
                                        VALUES (?, ?, ?, ?)");
                $stmt3->execute([$idVenta, $idProducto, $cantidad, $precio]);

                // 4. Actualizar stock
                $stmt4 = $pdo->prepare("UPDATE Producto SET stock = stock - ?, cantidadVendida = cantidadVendida + ?
                                        WHERE idProducto = ?");
                $stmt4->execute([$cantidad, $cantidad, $idProducto]);

                // 5. Agregar comisión (10 pesos por artículo)
                $comisionNueva = $cantidad * 10;

                $stmt5 = $pdo->prepare("UPDATE RegistroSalario 
                                        SET comisiones = comisiones + ?
                                        WHERE idVendedor = ? AND fecha = ?");
                $stmt5->execute([$comisionNueva, $idVendedor, $hoy]);

                // 6. Depositar en la cuenta correcta
                if ($idMetodoPago == 1) {
                    $idCuenta = 1; // Caja
                } else {
                    $idCuenta = 2; // Banco
                }

                $stmt6 = $pdo->prepare("UPDATE Cuenta SET saldo = saldo + ? WHERE idCuenta = ?");
                $stmt6->execute([$total, $idCuenta]);

                $pdo->commit();
                $msg = "Venta registrada con éxito. Comisión generada: +$" . $comisionNueva;

            } catch (Exception $e) {
                $pdo->rollBack();
                $msg = "Error al registrar venta: " . $e->getMessage();
            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
    <meta charset="UTF-8">
    <title>Registrar Venta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body { background:#f5f6fa; }
    .sidebar {
        width: 240px; height: 100vh;
        position: fixed; left: 0; top: 0;
        background: #212529; padding-top: 20px;
    }
    .sidebar a {
        color: white; padding: 15px; display: block;
        text-decoration: none;
    }
    .sidebar a:hover { background: #198754; }
    .content { margin-left: 260px; padding: 25px; }
    </style>
    </head>

    <body>

    <div class="sidebar">
        <h4 class="text-white text-center">Vendedor</h4>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="ventas.php">💰 Registrar Venta</a>
        <a href="salario.php">📊 Mi Salario</a>
        <a href="../logout.php" style="background:#dc3545;">🚪 Cerrar Sesión</a>
    </div>

    <div class="content">
        <h2>Registrar Venta</h2>
        <p class="text-muted">Completa el formulario para generar una venta.</p>

        <?php if (!empty($msg)): ?>
            <div class="alert alert-info"><?= $msg ?></div>
        <?php endif; ?>

        <form method="POST" class="card p-4 shadow" style="max-width:450px;">
            <label>Cliente:</label>
            <select name="cliente" class="form-select" required>
                <option value="">Seleccionar...</option>
                <?php foreach ($clientes as $c): ?>
                    <option value="<?= $c['idPersona'] ?>">
                        Cliente ID: <?= $c['idPersona'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        
            <label>Producto:</label>
            <select name="producto" class="form-select" required>
                <option value="">Seleccionar...</option>
                <?php foreach ($productos as $p): ?>
                    <option value="<?= $p['idProducto'] ?>">
                        <?= $p["nombre"] ?> — $<?= $p["precio"] ?> (Stock: <?= $p["stock"] ?>)
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="mt-3">Cantidad:</label>
            <input type="number" name="cantidad" class="form-control" min="1" required>

            <label class="mt-3">Método de Pago:</label>
            <select name="metodo" class="form-select" required>
                <?php foreach ($metodos as $m): ?>
                    <option value="<?= $m['idMetodoPago'] ?>"><?= $m["descripcion"] ?></option>
                <?php endforeach; ?>
            </select>

            <button class="btn btn-success mt-4">Registrar Venta</button>
        </form>
    </div>

    </body>
    </html>
