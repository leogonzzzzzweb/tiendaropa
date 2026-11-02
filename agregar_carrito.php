<?php
session_start();
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1 ||
    $cantidad === false || $cantidad === null || $cantidad < 1) {
    header("Location: index.php?mensaje=cantidad_invalida");
    exit;
}

$stmt = $conexion->prepare(
    "SELECT stock FROM productos WHERE id_producto = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto || (int)$producto["stock"] < 1) {
    header("Location: index.php?mensaje=sin_stock");
    exit;
}

if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

$cantidadActual = (int)($_SESSION["carrito"][$id] ?? 0);
$nuevaCantidad = $cantidadActual + $cantidad;
$stock = (int)$producto["stock"];

if ($nuevaCantidad > $stock) {
    header("Location: carrito.php?mensaje=stock_insuficiente");
    exit;
}

$_SESSION["carrito"][$id] = $nuevaCantidad;
header("Location: carrito.php?mensaje=agregado");
exit;
?>