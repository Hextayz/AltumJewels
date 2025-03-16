<?php

require_once "../inc/db.php";
require_once "../inc/funciones.php";
iniciarSesion();


if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración - Altum Jewels</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1 class="admin-header">Panel de Administración</h1>
    <div class="admin-nav">
        <a href="marcas.php" class="admin-btn">Gestionar Marcas</a>
        <a href="productos.php" class="admin-btn">Gestionar Productos</a>
        <a href="mensajes_contacto.php" class="admin-btn">Mensajes de Contacto</a>
        <a href="../index.php" class="admin-btn">Volver a la Tienda</a>
    </div>
    <p style="text-align:center;">Bienvenido al Panel de Administración.</p>
</body>
</html>
