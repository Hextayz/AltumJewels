<?php

require_once "inc/db.php";
require_once "inc/funciones.php";
iniciarSesion();


$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
  
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, nombre, precio, descripcion
    FROM productos
    WHERE nombre LIKE :busqueda
       OR descripcion LIKE :busqueda
    ORDER BY id DESC
");
$stmt->execute([':busqueda' => "%$q%"]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include "includes/header.php"; ?>

<h2 style="text-align:center; margin:1em;">
    Resultados de Búsqueda para "<?php echo htmlspecialchars($q); ?>"
</h2>

<?php if (count($resultados) > 0): ?>
    <div class="productos-grid">
        <?php foreach ($resultados as $prod): ?>
            <div class="producto-item">
            
                <img 
                    src="ver_imagen.php?id=<?php echo $prod['id']; ?>" 
                    alt="Imagen" 
                    style="max-width:100%; height:auto;"
                >
                
                <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
                <p>
                    Precio: 
                    <?php echo number_format($prod['precio'], 2, '.', ','); ?> €
                </p>
                <p>
                    <?php echo nl2br(htmlspecialchars($prod['descripcion'])); ?>
                </p>
             
                <a 
                    href="detalles_producto.php?id=<?php echo $prod['id']; ?>" 
                    style="display:inline-block; margin-top:0.5em; text-decoration:none; background-color:#000; color:#fff; padding:0.5em 1em; border-radius:4px;"
                >
                    Ver Detalles
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p style="text-align:center;">
        No se encontraron resultados para 
        <strong><?php echo htmlspecialchars($q); ?></strong>.
    </p>
<?php endif; ?>

<?php include "includes/footer.php"; ?>

</body>
</html>
