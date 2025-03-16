<?php
// auth/register.php
require_once "../inc/db.php";
require_once "../inc/funciones.php";
iniciarSesion();

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if ($password !== $confirmPassword) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Puedes agregar validaciones adicionales de email o contraseña aquí.
        $hash = password_hash($password, PASSWORD_DEFAULT);
        // Insertamos el usuario con tipo "user" por defecto.
        $stmt = $pdo->prepare("INSERT INTO usuarios (email, password, tipo) VALUES (:e, :p, 'user')");
        try {
            $stmt->execute([':e' => $email, ':p' => $hash]);
            header("Location: login.php?registro=ok");
            exit;
        } catch (PDOException $ex) {
            $error = "Error al registrar el usuario: " . $ex->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Altum Jewels</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>



<div class="formulario-registro">
    <h2>Registrarse</h2>
    <?php if ($error !== ""): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="email">Correo:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Confirmar Contraseña:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <button type="submit">Registrarse</button>
    </form>

    <p style="text-align: center; margin-top: 1em;">
        ¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a>
    </p>
    <p style="text-align: center;">
        <a href="../index.php">Volver a la Tienda</a>
    </p>
</div>



</body>
</html>
