<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['contrasenya'] ?? '';

  if (empty($email) || empty($password)) {
    $mensaje = "⚠️ Correo y contraseña son obligatorios.";
  } else {
    // Llegir dades del json-server (suposant 'db.json')
    $data = json_get_data('db.json');
    $usuarios = $data['Usuarios'] ?? [];

    $user = null;
    foreach ($usuarios as $u) {
      if (isset($u['Correo']) && strtolower($u['Correo']) === strtolower($email)) {
        $user = $u;
        break;
      }
    }

    if (!$user) {
      $mensaje = "❌ Usuario no encontrado.";
    } else {
      // 🔑 Comparació EN TEXT PLA (perquè 'Password' és en clar al JSON)
      // ⚠️ Només acceptable en entorns educatius / locals.
      if (isset($user['Password']) && $password === $user['Password']) {
        // ✅ Login correcte
        $_SESSION['user_id'] = $user['IDUsuario'];
        $_SESSION['email'] = $user['Correo'];
        $_SESSION['nombre'] = $user['Nombre'] ?? '';


        // 🍪 Cookie com demanat
        setcookie('user_id', $user['IDUsuario'], [
          'expires' => time() + 3600,
          'path' => '/',
          'secure' => false,
          'httponly' => true,
          'samesite' => 'Lax'
        ]);

        header('Location: ../../frontend/index.php');
        exit;
      } else {
        $mensaje = "❌ Contraseña incorrecta.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inicio Sesión TaskLink</title>
  <link rel="stylesheet" href="../../frontend/HTML/CSS/login.css" type="text/css" />
</head>

<body>
  <div class="contenedor">
    <aside class="sidebar">
      <img src="../../frontend/IMG/InicioSesion.png" alt="Imagen Lateral" />
    </aside>

    <section class="datos">
      <form method="POST" class="contenedorInicio">
        <p class="textInicioSesion">Iniciar Sesión en <strong>TaskLink</strong></p>

        <?php if ($mensaje): ?>
          <div class="mensaje-error"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <div class="contenedorEmail">
          <img src="../../frontend/SVG/usuario.svg" class="iconoEmail" />
          <input type="email" id="email" name="email" class="inputMail" placeholder="Correo Electrónico" required
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
        </div>

        <div class="contenedorContrasenya">
          <img src="../../frontend/SVG/contrasenya.svg" class="iconoContrasnya" />
          <input type="password" id="contrasenya" name="contrasenya" class="inputContra" placeholder="Contraseña"
            required />
        </div>

        <input type="submit" value="Entrar" class="btnEntrar" />

        <div class="contenedorOlvidarContra">
          <strong><a href="#">¿Has olvidado la contraseña?</a></strong>
        </div>
      </form>

      <div class="lineaSeparacion">
        <hr class="lineaPrincipio" /> O
        <hr class="lineaFinal" />
      </div>

      <div class="btnInicios">
        <div class="contenedorGoogle">
          <img src="../../frontend/SVG/google.svg" class="iconoGoogle" />
          <button type="button" class="btnGoogle" disabled>Continuar con Google</button>
        </div>
        <div class="contenedorApple">
          <img src="../../frontend/SVG/apple.svg" class="iconoContrasnya" />
          <button type="button" class="btnApple" disabled>Continuar con Apple</button>
        </div>
        <div class="contenedorRegistrarse">
          <a href="./register.php" class="btnCrearCuenta">Crear una Cuenta</a>
        </div>
      </div>

      <div class="logo">
        <img src="../../frontend/IMG/logo.png" alt="logo TaskLink" />
        <p>También puede <a href="#">denunciar ofertas</a> sin iniciar sesión</p>
      </div>
      <footer>Español (España) © 2025 TaskLink</footer>
    </section>
  </div>
</body>

</html>