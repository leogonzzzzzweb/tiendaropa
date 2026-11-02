<?php

    include_once('conexion.php');

    $id = $_GET['id'];

    $stmt = $conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();


    if($_SERVER["REQUEST_METHOD"]=== "POST")
    {
        $nombre = $_POST['nombre'];
        $marca = $_POST['marca'];  
        $color = $_POST['color'];
        $talle = $_POST['talle'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        $stmt = $conexion-> prepare("UPDATE productos SET nombre = ?, marca = ?, color = ?, talle = ?, precio = ?, stock = ? WHERE id_producto = ?");

        $stmt->bind_param("ssssiii", $nombre, $marca, $color, $talle, $precio, $stock, $id);

        if($stmt->execute()){
            echo "Producto modificado correctamente.";
            header('location: index.php');

        }else{
            echo "Error al modificar el producto.";
        }
    }
    
?>
   

   <form method="POST"  >
    Nombre:<input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>"required> <br>
    Marca:<input type="text" name="marca" value="<?php echo $producto['marca']; ?>" required> <br>
    Color:<input type="text" name="color" value="<?php echo $producto['color']; ?>" required><br>
    Talle:<input type="text" name="talle" value="<?php echo $producto['talle']; ?>" required> <br>
    Precio:<input type="text" name="precio" value="<?php echo $producto['precio']; ?>" required> <br>
    Stock:<input type="number" name="stock" value="<?php echo $producto['stock']; ?>"required> <br>

   
    <input type="submit" name="enviar" value="Guardar Cambios">

</form>