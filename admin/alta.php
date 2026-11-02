<?php

    include_once('conexion.php');

    if($_SERVER["REQUEST_METHOD"]=== "POST"){
        $nombre = $_POST['nombre'];
        $marca = $_POST['marca'];
        $color = $_POST['color'];
        $talle = $_POST['talle'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        $stmt = $conexion->prepare("INSERT INTO productos (nombre, marca, color, talle, precio, stock) VALUES (?, ?, ?, ?, ?, ?)");

        // s = string
        // i = interger
        // d = decimal o double

        $stmt->bind_param("ssssii", $nombre, $marca, $color, $talle, $precio, $stock);

        if($stmt->execute()){
            echo "Producto registrado correctamente.";
        }else{
            echo "Error al registrar el producto.";
        }

        // if($nombre=="" || $marca=="" || $color=="" || $talle=="" || $precio=="" || $stock==""){
        //     echo "Todos los campos son obligatorios.";
    
        // }else{
    }



?>
<form method="POST" action="alta.php">
    Nombre:<input type="text" name="nombre" required> <br>
    Marca:<input type="text" name="marca" required> <br>
    Color:<input type="text" name="color" required><br>
    Talle:<input type="text" name="talle" required> <br>
    Precio:<input type="text" name="precio" required> <br>
    Stock:<input type="number" name="stock" required> <br>

    <button type="submit" name="enviar">Guardar</button>

    <input type="submit" name="enviar" value="Registrar">

</form>