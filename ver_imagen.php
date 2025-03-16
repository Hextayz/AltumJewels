<?php

require_once "inc/db.php"; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    exit("ID inválido");
}

$stmt = $pdo->prepare("SELECT imagen_blob, imagen_tipo FROM productos WHERE id = :id");
$stmt->execute([':id' => $id]);
$prod = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prod || !$prod['imagen_blob']) {
    exit("Sin imagen");
}

header("Content-Type: " . $prod['imagen_tipo']);
echo $prod['imagen_blob'];
exit;
