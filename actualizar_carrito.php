<?php
session_start();
require_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: carrito.php");
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$cantidad = filter_input(INPUT_POST, "cantidad", FILTER_VALIDATE_INT);

if ($id === false || $id === null || $id < 1 ||
    $cantidad === false || $cantidad === null || $cantidad < 1 ||
    !isset($_SESSION["carrito"][$id])) {
    header("Location: carrito.php?mensaje=cantidad_invalida");
    exit;
}

$stmt = $conexion->prepare(
    "SELECT stock FROM productos WHERE id_producto = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    unset($_SESSION["carrito"][$id]);
    header("Location: carrito.php?mensaje=producto_no_encontrado");
    exit;
}

$stock = (int)$producto["stock"];

if ($cantidad > $stock) {
    header("Location: carrito.php?mensaje=stock_insuficiente");
    exit;
}

$_SESSION["carrito"][$id] = $cantidad;

header("Location: carrito.php?mensaje=carrito_actualizado");
exit;
?>