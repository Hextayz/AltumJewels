<?php

require_once "inc/db.php";
require_once "inc/funciones.php";
iniciarSesion();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID de producto inválido.");
}

$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die("Producto no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Producto - Altum Jewels</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include "includes/header.php"; ?>

<h2 style="text-align:center; margin:1em;">Detalles del Producto</h2>

<div class="producto-detalle">

    <img src="ver_imagen.php?id=<?php echo $producto['id']; ?>" alt="Imagen" style="max-width:100%; height:auto; margin-bottom:1em;">
    
    <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
    <p><strong>Precio:</strong> <?php echo number_format($producto['precio'], 2, '.', ','); ?> €</p>
    <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
    
    <?php if (!isset($_SESSION['user_id'])): ?>
        <p style="color:red; text-align:center;">
            Debes <a href="auth/login.php">iniciar sesión</a> o 
            <a href="auth/register.php">registrarte</a> para agregar al carrito.
        </p>
    <?php else: ?>
        <form action="carrito.php" method="POST" style="margin-top:1em;">
            <input type="hidden" name="id_producto" value="<?php echo $producto['id']; ?>">
            <label for="talla">Elige tu talla:</label>
            <select name="talla" id="talla" required>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>
            <button type="submit">Agregar al Carrito</button>
        </form>
    <?php endif; ?>
</div>

<p style="text-align:center; margin-top:1em;"><a href="index.php">Volver a la Tienda</a></p>

<?php include "includes/footer.php"; ?>

</body>
</html>
