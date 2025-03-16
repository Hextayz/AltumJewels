<?php

require_once "inc/funciones.php";
iniciarSesion();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Altum Jewels - Joyería para Hombres</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>

    <img src="imagenes/altum.png" alt="Logo Altum Jewels" style="height: 60px;">
    <h1>Altum Jewels</h1>
    

    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="carrito.php">Carrito</a></li>

       
            <li><a href="admin.php">Admin</a></li>

            <?php if (!isset($_SESSION['user_id'])): ?>
            
                <li><a href="auth/login.php">Login</a></li>
            <?php else: ?>
             
                <li><a href="auth/logout.php">Cerrar Sesión</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
