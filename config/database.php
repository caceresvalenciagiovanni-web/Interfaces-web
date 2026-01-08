<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

// 1. Cargar las variables del archivo .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

// 2. Iniciar la "Cápsula" de Eloquent
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'database'  => $_ENV['DB_DATABASE'] ?? 'forge',
    'username'  => $_ENV['DB_USERNAME'] ?? 'root',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => $_ENV['DB_CHARSET'] ?? 'utf8',
    'collation' => $_ENV['DB_COLLATION'] ?? 'utf8_unicode_ci',
    'prefix'    => '',
]);

// 3. Hacer que la conexión sea global (para poder usar Model::all() en cualquier lado)
$capsule->setAsGlobal();

// 4. Arrancar los modelos (inicia el ORM)
$capsule->bootEloquent();
