<?php
$host = 'db';
$db   = 'stay_app';
$user = 'stay_user';
$pass = 'stay_pass';
$charset = 'utf8mb4';
$conn = null;

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
    $sql_file = __DIR__ . '/initial_script.sql';
    if (file_exists($sql_file)) {
        $sql_commands = file_get_contents($sql_file);
        $conn->exec($sql_commands);
    }
} catch (\PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
}
