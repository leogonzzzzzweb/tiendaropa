<?php

session_start();

//verificamos que existe el id del producto a eliminar
if(!isset($_POST["id"])){
    header("Location: carrito.php");
    exit();
}

//capturamos el id del producto a eliminar
$idProducto = intval($_POST["id"]);

//eliminamos el producto del carrito
if(isset($_SESSION["carrito"][$idProducto])){
    unset($_SESSION["carrito"][$idProducto]);
}

header("Location: carrito.php");

exit;

?>