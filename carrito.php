<?php

require_once "inc/db.php";
require_once "inc/funciones.php";
iniciarSesion();


if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    $idProd = intval($_POST['id_producto']);
    $talla  = isset($_POST['talla']) ? $_POST['talla'] : '';
    if ($idProd > 0) {
 
        $stmt = $pdo->prepare("SELECT id, nombre, precio FROM productos WHERE id = :id");
        $stmt->execute([':id' => $idProd]);
        $prod = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($prod) {
            $_SESSION['carrito'][] = [
                'id'     => $prod['id'],
                'nombre' => $prod['nombre'],
                'precio' => $prod['precio'],
                'talla'  => $talla  
            ];
        }
    }
    header("Location: carrito.php");
    exit;
}


if (isset($_GET['eliminar'])) {
    $indice = intval($_GET['eliminar']);
    if (isset($_SESSION['carrito'][$indice])) {
        unset($_SESSION['carrito'][$indice]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
    header("Location: carrito.php");
    exit;
}

$carrito = $_SESSION['carrito'];
$total = 0;
foreach ($carrito as $item) {
    $total += $item['precio'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras - Altum Jewels</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include "includes/header.php"; ?>

<h2 style="text-align:center; margin:1em;">Carrito de Compras</h2>

<?php if (count($carrito) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Talla</th>
                <th>Precio</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($carrito as $index => $item): ?>
                <tr>
                    <td>
                        <img src="ver_imagen.php?id=<?php echo $item['id']; ?>" alt="Imagen" width="60">
                    </td>
                    <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($item['talla']); ?></td>
                    <td><?php echo number_format($item['precio'], 2, '.', ','); ?> €</td>
                    <td>
                        <a href="carrito.php?eliminar=<?php echo $index; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p style="text-align:center; margin-top:1em;"><strong>Total:</strong> <?php echo number_format($total, 2, '.', ','); ?> €</p>
<?php else: ?>
    <p style="text-align:center;">El carrito está vacío.</p>
<?php endif; ?>

<p style="text-align:center; margin-top:1em;"><a href="index.php">Seguir Comprando</a></p>

<?php include "includes/footer.php"; ?>

</body>
</html>
