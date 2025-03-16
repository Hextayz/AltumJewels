<?php
// auth/logout.php
require_once "../inc/funciones.php";
iniciarSesion();

// Destruir la sesión y redirigir
session_destroy();
header("Location: ../index.php");
exit;
