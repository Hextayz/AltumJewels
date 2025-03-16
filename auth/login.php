<?php
// auth/login.php
require_once "../inc/db.php";
require_once "../inc/funciones.php";
iniciarSesion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    // Buscar usuario en tu tabla "usuarios" (asegúrate que la columna se llama "tipo")
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :e LIMIT 1");
    $stmt->execute([':e' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($pass, $usuario['password'])) {
        // Login correcto
        $_SESSION['user_id']    = $usuario['id'];
        $_SESSION['user_email'] = $usuario['email'];
        $_SESSION['user_tipo']  = $usuario['tipo']; // Por ejemplo: 'admin' o 'user'

        header("Location: ../index.php");
        exit;
    } else {
        $error = "Usuario o contraseña inválidos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Altum Jewels</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="formulario-login">
    <h2>Iniciar Sesión</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label for="email">Correo:</label>
        <input type="email" name="email" id="email" required>

        <label for="pass">Contraseña:</label>
        <input type="password" name="password" id="pass" required>

        <button type="submit">Entrar</button>
    </form>

    <p style="text-align:center; margin-top:1em;">
        <a href="register.php">¿No tienes cuenta? Regístrate</a>
    </p>
    <p style="text-align:center;">
        <a href="../index.php">Volver a la tienda</a>
    </p>
</div>

</body>
</html>
