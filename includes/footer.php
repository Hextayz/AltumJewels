<?php

?>
<footer>
    <h2>Contacto</h2>
    <form action="procesar_contacto.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="mensaje">Mensaje:</label>
        <textarea name="mensaje" id="mensaje" rows="3" required></textarea>

        <button type="submit">Enviar</button>
    </form>
    <p>© 2025 Altum Jewels</p>
</footer>
</body>
</html>
