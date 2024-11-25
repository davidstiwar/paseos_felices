<?php
$host = 'localhost';
$dbname = 'sis_venta';
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexion->exec("set names utf8");
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>