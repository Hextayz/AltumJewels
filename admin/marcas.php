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
    $stmtDel = $pdo->prepare("DELETE FROM marcas WHERE id = :id");
    $stmtDel->execute([':id' => $idBorrar]);
    header("Location: marcas.php");
    exit;
}


if (isset($_POST['action']) && $_POST['action'] === 'crearMarca') {
    $nombreNueva = trim($_POST['nombre']);
    $pdo->prepare("INSERT INTO marcas (nombre) VALUES (:n)")
        ->execute([':n' => $nombreNueva]);
    header("Location: marcas.php");
    exit;
}
if (isset($_POST['action']) && $_POST['action'] === 'editarMarca') {
    $idMarca = intval($_POST['id']);
    $nuevoNombre = trim($_POST['nombre']);
    $pdo->prepare("UPDATE marcas SET nombre=:nom WHERE id=:id")
        ->execute([':nom' => $nuevoNombre, ':id' => $idMarca]);
    header("Location: marcas.php");
    exit;
}


$idEditar = isset($_GET['editar']) ? intval($_GET['editar']) : 0;
$marcaEditar = null;
if ($idEditar > 0) {
    $stmtE = $pdo->prepare("SELECT * FROM marcas WHERE id = :id");
    $stmtE->execute([':id' => $idEditar]);
    $marcaEditar = $stmtE->fetch(PDO::FETCH_ASSOC);
}


$stmtM = $pdo->query("SELECT * FROM marcas");
$marcas = $stmtM->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Marcas - Altum Jewels</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<h1 class="admin-header">Gestionar Marcas</h1>
<div class="admin-nav">
    <a href="dashboard.php">Volver al Panel</a>
    <a href="../index.php">Ir a la Tienda</a>
</div>

<?php if ($marcaEditar): ?>

    <div class="formulario-admin">
        <h2>Editar Marca (ID: <?php echo $marcaEditar['id']; ?>)</h2>
        <form method="POST">
            <input type="hidden" name="action" value="editarMarca">
            <input type="hidden" name="id" value="<?php echo $marcaEditar['id']; ?>">

            <label for="nombreEditar">Nombre de la Marca:</label>
            <input 
                type="text" 
                id="nombreEditar" 
                name="nombre" 
                value="<?php echo htmlspecialchars($marcaEditar['nombre']); ?>" 
                required
            >

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
<?php else: ?>
 
    <div class="formulario-admin">
        <h2>Nueva Marca</h2>
        <form method="POST">
            <input type="hidden" name="action" value="crearMarca">

            <label for="nombreNueva">Nombre de la Marca:</label>
            <input type="text" id="nombreNueva" name="nombre" required>

            <button type="submit">Crear Marca</button>
        </form>
    </div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Marca</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($marcas as $m): ?>
        <tr>
            <td><?php echo $m['id']; ?></td>
            <td><?php echo htmlspecialchars($m['nombre']); ?></td>
            <td>
                <a href="marcas.php?editar=<?php echo $m['id']; ?>">Editar</a> 
                |
                <a href="marcas.php?borrar=<?php echo $m['id']; ?>"
                   onclick="return confirm('¿Seguro que deseas borrar esta marca?');">
                   Borrar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
