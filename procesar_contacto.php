<?php


require_once "inc/db.php";
require_once "inc/funciones.php";
iniciarSesion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $nombre  = trim($_POST['nombre'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');
    
   
    if (empty($nombre) || empty($email) || empty($mensaje)) {
       
        header("Location: index.php?contacto=error");
        exit;
    }
    
    
    $stmt = $pdo->prepare("INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (:nombre, :email, :mensaje)");
    
   
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mensaje', $mensaje);
    
    if ($stmt->execute()) {
        // Redirige con éxito
        header("Location: index.php?contacto=ok");
    } else {
        
        header("Location: index.php?contacto=error");
    }
    exit;
} else {
    
    header("Location: index.php");
    exit;
}
