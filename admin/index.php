<?php

    include_once('conexion.php');

    $sql = "SELECT * FROM productos";

    $resultado = $conexion -> query($sql);
?>
    <a href="alta.php">Agregar producto</a>

    <table border=1> 
            <tr >
                <th>Nombre</th>
                <th>Marca</th>
                <th>Talle</th>
                <th>Color</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>

    <?php
    while($producto = $resultado ->fetch_assoc()){
    ?>
        
            <tr>
                <td><?php echo $producto['nombre']?></td> 
                <td><?php echo $producto['marca']?> </td> 
                <td> <?php echo $producto['talle']?></td>
                <td><?php echo $producto['color']?></td>
                <td><?php echo $producto['precio']?></td> 
                <td>
                    <a href="modificar.php?id=<?php echo $producto['id_producto']?>">Editar</a>
                    <a href="eliminar.php?id=<?php echo $producto['id_producto']?>">Eliminar</a> 
                    
                </td>
            </tr>
    <?php
    }
    ?>

    </table>