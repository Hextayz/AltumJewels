<?php
// admin/mensajes_contacto.php
require_once "../inc/db.php";
require_once "../inc/funciones.php";
iniciarSesion();

// Verificar que el usuario esté logueado y sea admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Si se recibe una petición para borrar un mensaje (?borrar=ID)
if (isset($_GET['borrar'])) {
    $idBorrar = intval($_GET['borrar']);
    $stmtDel = $pdo->prepare("DELETE FROM mensajes_contacto WHERE id = :id");
    $stmtDel->execute([':id' => $idBorrar]);
    header("Location: mensajes_contacto.php");
    exit;
}

// Obtener todos los mensajes de la tabla mensajes_contacto
$stmt = $pdo->query("SELECT * FROM mensajes_contacto ORDER BY fecha DESC");
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes de Contacto - Altum Jewels</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<!-- En este caso, en lugar de incluir dashboard.php, usamos un header sencillo -->


<h1 class="admin-header">Mensajes de Contacto</h1>

<div class="admin-nav">
    <!-- Aquí se dejan enlaces para volver al panel o a la tienda -->
    <a href="dashboard.php" class="admin-btn">Volver al Panel</a>
    <a href="../index.php" class="admin-btn">Ir a la Tienda</a>
</div>

<?php if (count($mensajes) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($mensajes as $mensaje): ?>
            <tr>
                <td><?php echo $mensaje['id']; ?></td>
                <td><?php echo htmlspecialchars($mensaje['nombre']); ?></td>
                <td><?php echo htmlspecialchars($mensaje['email']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($mensaje['mensaje'])); ?></td>
                <td><?php echo htmlspecialchars($mensaje['fecha']); ?></td>
                <td>
                    <a href="mensajes_contacto.php?borrar=<?php echo $mensaje['id']; ?>"
                       onclick="return confirm('¿Seguro que deseas borrar este mensaje?');">
                       Borrar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center;">No hay mensajes de contacto.</p>
<?php endif; ?>



</body>
</html>
