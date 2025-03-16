<?php

require_once "inc/db.php";
require_once "inc/funciones.php";
iniciarSesion();


$stmt = $pdo->query("SELECT id, nombre, precio, descripcion FROM productos ORDER BY id DESC");
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include "includes/header.php"; ?>


<div class="video-container">
    <video autoplay muted loop id="video-fondo">
        <source src="imagenes/video_fondo.mp4" type="video/mp4">
        Tu navegador no soporta el video HTML5.
    </video>
    <div class="texto-superpuesto">
        <h2>Alta Elegancia y Estilo para Hombres</h2>
    </div>
</div>


<form action="busqueda.php" method="GET" class="form-busqueda">
    <input type="text" name="q" placeholder="Buscar joyas..." required>
    <button type="submit">Buscar</button>
</form>


<h2 style="text-align:center; margin:1em;">Nuestros Productos</h2>
<div class="productos-grid">
    <?php foreach ($productos as $prod): ?>
        <div class="producto-item">
           
            <img src="ver_imagen.php?id=<?php echo $prod['id']; ?>" alt="Imagen" style="max-width:100%; height:auto;">

            <h3><?php echo htmlspecialchars($prod['nombre']); ?></h3>
            <p>Precio: <?php echo number_format($prod['precio'], 2, '.', ','); ?> €</p>
            <p><?php echo nl2br(htmlspecialchars($prod['descripcion'])); ?></p>
            
          
            <a href="detalles_producto.php?id=<?php echo $prod['id']; ?>" style="display:inline-block; margin-top:0.5em; text-decoration:none; background-color:#000; color:#fff; padding:0.5em 1em; border-radius:4px;">
                Ver Detalles
            </a>
        </div>
    <?php endforeach; ?>
</div>

<?php include "includes/footer.php"; ?>
