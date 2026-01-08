<?php
session_start();
require "conexion.php";

// Activar errores de PDO para ver cualquier error SQL oculto
//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT u.id, u.username, u.password_hash, u.role, u.idPersona 
                           FROM users u 
                           WHERE u.username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password_hash"])) {

        // ======== GUARDAR DATOS EN SESIÓN ========
        $_SESSION["idUser"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["idPersona"] = $user["idPersona"];

        // ==============================================
        //      GENERAR SALARIO SOLO PARA VENDEDORES
        // ==============================================
        if ($user["role"] === "Vendedor") {
            $idVendedor = $user["idPersona"];
            $hoy = date("Y-m-d");

            try {
                // Verificar si YA existe salario del día
                $stmt2 = $pdo->prepare("SELECT idRegistro 
                                        FROM RegistroSalario 
                                        WHERE idVendedor = ? AND fecha = ?");
                $stmt2->execute([$idVendedor, $hoy]);
                $existe = $stmt2->fetch(PDO::FETCH_ASSOC);

                // Si NO existe → crear uno nuevo
                if (!$existe) {
                    $stmt3 = $pdo->prepare("INSERT INTO RegistroSalario (idVendedor, fecha, salarioBase, comisiones)
                                            VALUES (?, ?, 200, 0)");
                    $stmt3->execute([$idVendedor, $hoy]);
                }

            } catch (Exception $e) {
                echo "<pre>Error al generar salario:\n" . $e->getMessage() . "</pre>";
                exit;
            }
        }

        // ======== REDIRIGIR SEGÚN ROL ========
        if ($user["role"] === "Admin") {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: vendedor/dashboard.php");
        }
        exit;

    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Moda & Regalos</title>
<style>
body { background:#f4f4f4;font-family:Arial; }
.login-box{
    width:350px;padding:20px;background:white;
    margin:80px auto;border-radius:10px;
    box-shadow:0 0 10px #aaa;
}
input{width:100%;padding:10px;margin:8px 0;}
button{padding:10px;width:100%;background:#007bff;color:white;border:none;}
</style>
</head>
<body>

<div class="login-box">
    <h2>Iniciar Sesión</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>

