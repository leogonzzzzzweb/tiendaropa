
<?php

    include_once('conexion.php');

    $sql = "SELECT * FROM productos";

    $resultado = $conexion -> query($sql);

    while($producto = $resultado ->fetch_assoc()){
?>
<div class="card" style="width: 18rem;display:inline-block;margin:10px;">
    <h2> <?php echo $producto["nombre"]?> </h2>
    <p> <?php echo $producto["marca"]?> </p>
    <div> 
        <span> <?php echo $producto["talle"]?> </span>
        <span> <?php echo $producto["color"]?> </span>
    </div>
    <p> <?php echo $producto["precio"]?> </p>

    <form action="agregar_carrito.php" method="POST">

<input type="hidden" name="id"

value="<?php echo (int)$producto['id_producto']; ?>">

<label for="cantidad">Cantidad:</label>

<input type="number" id="cantidad" name="cantidad"

min="1" max="<?php echo (int)$producto['stock']; ?>"

value="1" required>

<button type="submit">Agregar al carrito</button>

</form>
<?php

    }