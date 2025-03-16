<?php

require_once "../inc/db.php";
require_once "../inc/funciones.php";
iniciarSesion();


if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}


if (isset($_GET['borrar'])) {
    $idBorrar = intval($_GET['borrar']);
    $stmtDel = $pdo->prepare("DELETE FROM productos WHERE id = :id");
    $stmtDel->execute([':id' => $idBorrar]);
    header("Location: productos.php");
    exit;
}


$stmtMarcas = $pdo->query("SELECT id, nombre FROM marcas");
$marcas = $stmtMarcas->fetchAll(PDO::FETCH_ASSOC);


 
function leerImagen($campo) {
  
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
        return [ "", "" ]; 
    }

    $binario = file_get_contents($_FILES[$campo]['tmp_name']);
    $tipo = $_FILES[$campo]['type'];
    return [ $binario, $tipo ];
}


if (isset($_POST['action']) && $_POST['action'] === 'crearProducto') {
    $nombre      = trim($_POST['nombre'] ?? '');
    $precio      = floatval($_POST['precio'] ?? 0);
    $marca_id    = intval($_POST['marca_id'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    

    list($binImagen, $tipoImagen) = leerImagen('imagen');
    
 
    $stmtCrear = $pdo->prepare("
        INSERT INTO productos (nombre, precio, descripcion, marca_id, imagen_blob, imagen_tipo)
        VALUES (:n, :p, :d, :m, :blob, :tipo)
    ");
    $stmtCrear->execute([
        ':n'    => $nombre,
        ':p'    => $precio,
        ':d'    => $descripcion,
        ':m'    => $marca_id,
        ':blob' => $binImagen, 
        ':tipo' => $tipoImagen, 
    ]);
    header("Location: productos.php");
    exit;
}


if (isset($_POST['action']) && $_POST['action'] === 'editarProducto') {
    $idProd      = intval($_POST['id']);
    $nombre      = trim($_POST['nombre'] ?? '');
    $precio      = floatval($_POST['precio'] ?? 0);
    $marca_id    = intval($_POST['marca_id'] ?? 0);
    $descripcion = trim($_POST['descripcion'] ?? '');
    
 
    list($binImagen, $tipoImagen) = leerImagen('imagen');
    
  
    if ($binImagen === "") {
      
        $stmtOld = $pdo->prepare("SELECT imagen_blob, imagen_tipo FROM productos WHERE id=:id");
        $stmtOld->execute([':id' => $idProd]);
        $oldData = $stmtOld->fetch(PDO::FETCH_ASSOC);
        if ($oldData) {
            $binImagen  = $oldData['imagen_blob'];
            $tipoImagen = $oldData['imagen_tipo'];
        }
    }
    
    // Update
    $stmtEditar = $pdo->prepare("
        UPDATE productos
        SET nombre = :n,
            precio = :p,
            descripcion = :d,
            marca_id = :m,
            imagen_blob = :blob,
            imagen_tipo = :tipo
        WHERE id = :id
    ");
    $stmtEditar->execute([
        ':n'    => $nombre,
        ':p'    => $precio,
        ':d'    => $descripcion,
        ':m'    => $marca_id,
        ':blob' => $binImagen,
        ':tipo' => $tipoImagen,
        ':id'   => $idProd
    ]);
    header("Location: productos.php");
    exit;
}


$idEditar = isset($_GET['editar']) ? intval($_GET['editar']) : 0;
$productoEditar = null;
if ($idEditar > 0) {
    $stmtE = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
    $stmtE->execute([':id' => $idEditar]);
    $productoEditar = $stmtE->fetch(PDO::FETCH_ASSOC);
}


$sqlList = "
    SELECT p.id, p.nombre, p.precio, p.descripcion, p.marca_id, 
           m.nombre AS nombre_marca
    FROM productos p
    LEFT JOIN marcas m ON p.marca_id = m.id
    ORDER BY p.id DESC
";
$stmtP = $pdo->query($sqlList);
$productos = $stmtP->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Productos - Altum Jewels</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<h1 class="admin-header">Gestionar Productos</h1>
<div class="admin-nav">
    <a href="dashboard.php">Volver al Panel</a>
    <a href="../index.php">Ir a la Tienda</a>
</div>

<?php if ($productoEditar): ?>
 
    <div class="formulario-admin">
        <h2>Editar Producto (ID: <?php echo $productoEditar['id']; ?>)</h2>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="editarProducto">
            <input type="hidden" name="id" value="<?php echo $productoEditar['id']; ?>">

            <label for="nombreEditar">Nombre del Producto:</label>
            <input type="text" id="nombreEditar" name="nombre" 
                   value="<?php echo htmlspecialchars($productoEditar['nombre']); ?>" required>

            <label for="precioEditar">Precio:</label>
            <input type="number" step="0.01" id="precioEditar" name="precio"
                   value="<?php echo htmlspecialchars($productoEditar['precio']); ?>" required>

            <label for="marcaEditar">Marca:</label>
            <select name="marca_id" id="marcaEditar">
                <?php foreach ($marcas as $ma): ?>
                    <option 
                      value="<?php echo $ma['id']; ?>"
                      <?php if ($productoEditar['marca_id'] == $ma['id']) echo 'selected'; ?>
                    >
                      <?php echo htmlspecialchars($ma['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="descEditar">Descripción:</label>
            <textarea id="descEditar" name="descripcion" rows="4"><?php 
                echo htmlspecialchars($productoEditar['descripcion']); 
            ?></textarea>

            
            <p>Imagen actual: 
               <img src="../ver_imagen.php?id=<?php echo $productoEditar['id']; ?>" alt="Img" width="60">
            </p>
            <label for="imagenEditar">Nueva imagen (opcional):</label>
            <input type="file" id="imagenEditar" name="imagen">
            
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
<?php else: ?>

    <div class="formulario-admin">
        <h2>Crear Producto</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="crearProducto">

            <label for="nombreNuevo">Nombre del Producto:</label>
            <input type="text" id="nombreNuevo" name="nombre" required>

            <label for="precioNuevo">Precio:</label>
            <input type="number" step="0.01" id="precioNuevo" name="precio" required>

            <label for="marcaNueva">Marca:</label>
            <select name="marca_id" id="marcaNueva">
                <?php foreach ($marcas as $ma): ?>
                    <option value="<?php echo $ma['id']; ?>">
                        <?php echo htmlspecialchars($ma['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="descNuevo">Descripción:</label>
            <textarea id="descNuevo" name="descripcion" rows="4"></textarea>

            <label for="imagenNueva">Imagen (archivo):</label>
            <input type="file" id="imagenNueva" name="imagen" required>

            <button type="submit">Crear Producto</button>
        </form>
    </div>
<?php endif; ?>


<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Marca</th>
            <th>Precio</th>
            <th>Imagen</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($productos as $prod): ?>
        <tr>
            <td><?php echo $prod['id']; ?></td>
            <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
            <td><?php echo htmlspecialchars($prod['nombre_marca']); ?></td>
            <td><?php echo number_format($prod['precio'], 2, '.', ','); ?></td>
            <td>
                <img src="../ver_imagen.php?id=<?php echo $prod['id']; ?>" alt="Img" width="60">
            </td>
            <td><?php echo nl2br(htmlspecialchars($prod['descripcion'])); ?></td>
            <td>
                <a href="productos.php?editar=<?php echo $prod['id']; ?>">Editar</a> |
                <a href="productos.php?borrar=<?php echo $prod['id']; ?>" 
                   onclick="return confirm('¿Seguro que deseas borrar este producto?');">
                   Borrar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
