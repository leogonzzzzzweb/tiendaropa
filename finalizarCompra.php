<?php
session_start();

//conectamos a la base de datos
include_once('conexion.php');

//verificamos si el carrito existe, y que no este vacio, sino lo enviamos al carrito.php
if(!isset($_SESSION["carrito"]) || empty($_SESSION["carrito"])){
    header("location: carrito.php");
}

//colocamos total en 0
$total = 0;

//calculamos el total del carrito
foreach($_SESSION["carrito"] as $id_producto => $cantidad){

    //convertimos a entero el id del producto y la cantidad
    $id_producto = intval($id_producto);
    $cantidad = intval($cantidad);

    //seleccionamos el producto desde la base de datos
    $sql = "SELECT * FROM productos WHERE id_producto = $id_producto";

    //ejecutamos la consulta
    $resultado = mysqli_query($conexion, $sql);

    $producto = mysqli_fetch_assoc($resultado);

    //convertimos a entero el stock y el precio
    $stock = intval($producto["stock"]);
    $precio = floatval($producto["precio"]);

    //verificamos el stock
    if($cantidad < $stock){
        echo "No hay stock suficiente para el producto: " . $producto["nombre"];
        exit;
    }

    //calculamos el subtotal y el total del carrito
    $subtotal = $precio * $cantidad;

    //sumamos el total
    $total += $subtotal;

    //guardamos el id del cliente en este caso en 1
    $id_cliente = 1;

    //guardamos la venta en la tabla ventas
    $sql_ventas = "INSERT INTO ventas(id_cliente, fecha, total) VALUES($id_cliente, NOW(),$total)";

    // verificamos si la venta se guardo correctamente
    if($sql_ventas){
        mysqli_query($conexion, $sql_ventas);
    }else{
        echo "Error al guardar";
    }
}

    // foreach ($_SESSION["carrito"] as $id_producto => $cantidad) {

    //     //convertimos a entero el id del producto y la cantidad
    //     $id_producto = intval($id_producto);
    //     $cantidad = intval($cantidad);

    //     // Buscamos nuevamente el producto
    //     $sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
    //     $resultado = mysqli_query($conexion, $sql);
    //     $producto = mysqli_fetch_assoc($resultado);

    //     //convertimos a entero el stock y el precio
    //     $stock = intval($producto["stock"]);
    //     $precio = floatval($producto["precio"]);

    //     //calculamos el subtotal
    //     $subtotal = $precio * $cantidad;

    //     //creamos la consulta para insertar en detalle de venta

    //     //verificamos que la consulta se haya preparado correctamente

    //     //actualizamos el stock del producto

    //     //modificamos el stock del producto en la base de datos
    // }



//vaciamos el carrito
$_SESSION["carrito"] = [];

?>
<!-- MOSTRAMOS EL DETALLE DE LA COMPRA -->
<!-- 
<h2>Compra finalizada</h2>
<p>Gracias por su compra, su pedido ha sido procesado correctamente.</p>    

<p>Numero de Venta:     <?php //echo $id_venta; ?></p>
<p>El total de su compra es: <?php //echo $total ?> </p>

<a href="index.php">Volver a la tienda</a> -->