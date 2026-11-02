<?php
session_start();
require_once("conexion.php");

$carrito = $_SESSION["carrito"] ?? [];
$total = 0.0;
$productosCarrito = [];

foreach ($carrito as $id => $cantidad) {
    $id = (int)$id;
    $cantidad = (int)$cantidad;

    $stmt = $conexion->prepare(
        "SELECT id_producto, nombre, precio, stock
        FROM productos
        WHERE id_producto = ?"
    );
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $producto = $stmt->get_result()->fetch_assoc();

    if (!$producto) {
        unset($_SESSION["carrito"][$id]);
        continue;
    }

    $producto["cantidad"] = $cantidad;
    $producto["subtotal"] = (float)$producto["precio"] * $cantidad;
    $productosCarrito[] = $producto;
    $total += $producto["subtotal"];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi carrito</title>
</head>
<body>
<h1>Mi carrito</h1>

<?php if (isset($_GET["mensaje"])): ?>
    <?php
    $mensajes = [
        "agregado" => "Producto agregado al carrito.",
        "carrito_actualizado" => "Cantidad actualizada correctamente.",
        "producto_eliminado" => "Producto eliminado del carrito.",
        "carrito_vaciado" => "Carrito vaciado.",
        "cantidad_invalida" => "La cantidad ingresada no es válida.",
        "stock_insuficiente" => "La cantidad solicitada supera el stock disponible.",
        "producto_no_encontrado" => "El producto ya no está disponible.",
        "error_compra" => "No se pudo completar la compra. Revisá el carrito e intentá nuevamente."
    ];
    $codigoMensaje = (string)$_GET["mensaje"];
    if (isset($mensajes[$codigoMensaje])):
    ?>
        <p><?= htmlspecialchars($mensajes[$codigoMensaje], ENT_QUOTES, "UTF-8") ?></p>
    <?php endif; ?>
<?php endif; ?>

<?php if (empty($productosCarrito)): ?>
    <p>El carrito está vacío.</p>
    <a href="index.php">Ver productos</a>
<?php else: ?>
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio unitario</th>
            <th>Subtotal</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($productosCarrito as $producto): ?>
        <tr>
            <td><?= htmlspecialchars($producto["nombre"], ENT_QUOTES, "UTF-8") ?></td>
            <td>
                <form action="actualizar_carrito.php" method="POST">
                    <input type="hidden" name="id" value="<?= (int)$producto["id_producto"] ?>">
                    <input type="number" name="cantidad" min="1"
                            max="<?= (int)$producto["stock"] ?>"
                            value="<?= (int)$producto["cantidad"] ?>" required>
                    <button type="submit">Actualizar</button>
                </form>
            </td>
            <td>$<?= number_format((float)$producto["precio"], 2, ",", ".") ?></td>
            <td>$<?= number_format((float)$producto["subtotal"], 2, ",", ".") ?></td>
            <td>
                <form action="eliminarItem.php" method="POST"
                        onsubmit="return confirm('¿Eliminar este producto del carrito?');">
                    <input type="hidden" name="id" value="<?= (int)$producto["id_producto"] ?>">
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
        <tr>
            <td colspan="3" style="text-align:right;"><strong>Total</strong></td>
            <td colspan="2"><strong>$<?= number_format($total, 2, ",", ".") ?></strong></td>
        </tr>
    </tbody>
</table>

<p><a href="index.php">Seguir comprando</a></p>
<form action="vaciar_carrito.php" method="POST"
        onsubmit="return confirm('¿Vaciar todo el carrito?');">
    <button type="submit">Vaciar carrito</button>
</form>
<form action="finalizarCompra1.php" method="POST">
    <button type="submit">Finalizar compra</button>
</form>
<?php endif; ?>
</body>
</html>
