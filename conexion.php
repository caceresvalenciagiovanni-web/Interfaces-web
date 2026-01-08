<?php
$host = "sql.freedb.tech";
$dbname = "freedb_Proyecto";
$username = "freedb_Luis03";
$password = "rUx4MnjFwTd*Ayf";
$port = 3306;

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
