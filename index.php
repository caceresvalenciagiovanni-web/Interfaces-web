<?php
session_start();

// 1. Cargar ORM y Configuración
require_once "config/database.php";

use App\Models\User;
use App\Models\RegistroSalario;

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // 2. BUSCAR USUARIO CON ELOQUENT
    // Reemplaza: "SELECT ... FROM users WHERE username = ?"
    $user = User::where('username', $username)->first();

    if ($user && password_verify($password, $user->password_hash)) {

        // ======== GUARDAR DATOS EN SESIÓN ========
        // Nota: Accedemos como objeto ($user->id) no como array
        $_SESSION["idUser"] = $user->id;
        $_SESSION["username"] = $user->username;
        $_SESSION["role"] = $user->role;
        $_SESSION["idPersona"] = $user->idPersona;

        // ==============================================
        //      GENERAR SALARIO SOLO PARA VENDEDORES
        // ==============================================
        if ($user->role === "Vendedor") {
            $idVendedor = $user->idPersona;
            $hoy = date("Y-m-d");

            try {
                // Verificar si YA existe salario del día con ORM
                // "Busca un registro donde vendedor sea X y fecha sea HOY"
                $existe = RegistroSalario::where('idVendedor', $idVendedor)
                                         ->where('fecha', $hoy)
                                         ->exists();

                // Si NO existe → crear uno nuevo
                if (!$existe) {
                    RegistroSalario::create([
                        'idVendedor'  => $idVendedor,
                        'fecha'       => $hoy,
                        'salarioBase' => 200,
                        'comisiones'  => 0
                    ]);
                }

            } catch (Exception $e) {
                // En producción es mejor loguear el error y no mostrarlo
                error_log("Error generando salario: " . $e->getMessage());
            }
        }

        // ======== REDIRIGIR SEGÚN ROL ========
        if ($user->role === "Admin") {
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
<link rel="stylesheet" href="style.css">
<style>
/* Ajustes específicos para el Login que no estaban en style.css */
body { background:#f4f4f4; font-family: 'Poppins', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin:0;}
.login-box{
    width:350px; padding:40px; background:white;
    border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
}
input{ width:100%; padding:12px; margin:10px 0; border:1px solid #ddd; border-radius:6px; box-sizing: border-box; }
button{ 
    padding:12px; width:100%; background:#3498db; color:white; 
    border:none; border-radius:6px; font-size:16px; cursor:pointer; transition: .3s;
}
button:hover { background:#2980b9; }
h2 { margin-bottom: 20px; color: #2c3e50; }
.error-msg { color: #e74c3c; background: #fadbd8; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 14px; }
</style>
</head>
<body>

<div class="login-box">
    <h2>Iniciar Sesión</h2>

    <?php if (!empty($error)): ?>
        <div class="error-msg"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>
