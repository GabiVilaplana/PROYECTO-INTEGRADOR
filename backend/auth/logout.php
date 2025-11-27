<?php
session_start();

// 1. Limpiar todas las variables de sesión
$_SESSION = [];

// 2. Eliminar la cookie de sesión (si se usa)
if (ini_get("session.use_cookies")) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
}

// 3. ⭐️ Eliminar la cookie 'user_id' (la que causa el problema)
if (isset($_COOKIE['user_id'])) {
    setcookie('user_id', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => false, // Cambia a true si usas HTTPS
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

// 4. Destruir la sesión
session_destroy();

// 5. Redirigir con mensaje visual (opcional)
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sessió tancada</title>
    <meta charset="UTF-8">
</head>
<body>
    <p>Sessió tancada. Redirigint...</p>
    <script>
        setTimeout(function() {
            window.location.href = '../../frontend/index.php';
        }, 1000);
    </script>
</body>
</html>