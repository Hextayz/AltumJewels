<?php

require_once "inc/funciones.php";
iniciarSesion();

// CASO 1: Usuario no logueado
if (!isset($_SESSION['user_id'])) {
    // Te lleva al login
    header("Location: auth/login.php");
    exit;
}

if ($_SESSION['user_tipo'] !== 'admin') {
    // Muestra mensaje
    echo "No tienes permisos para acceder a esta página.";
    exit;
}


header("Location: admin/dashboard.php");
exit;
