<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['contrasenya'] ?? '';
  $repeat = $_POST['repetircontrasenya'] ?? '';
  $nombreUsuario = $_POST["nombre"] ?? "";
  $apellidos = $_POST["apellido"] ?? "";

  // Validacions bàsiques
  if (empty($email) || empty($password) || empty($repeat)) {
    $mensaje = "⚠️ Todos los campos son obligatorios.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $mensaje = "⚠️ El correo electrónico no es válido.";
  } elseif ($password !== $repeat) {
    $mensaje = "⚠️ Las contraseñas no coinciden.";
  } else {
    // Llegir dades actuals
    $data = json_get_data('db.json');
    $usuarios = $data['Usuarios'] ?? [];

    // Comprovar si ja existeix l'email
    foreach ($usuarios as $u) {
      if (isset($u['Correo']) && strtolower($u['Correo']) === strtolower($email)) {
        $mensaje = "❌ Ya existe una cuenta con este correo.";
        break;
      }
    }

    if (!$mensaje) {

      $ultimID = 100;
      foreach ($usuarios as $u) {
        if (isset($u['IDUsuario']) && is_numeric($u['IDUsuario'])) {
          $ultimID = max($ultimID, (int) $u['IDUsuario']);
        }
      }
      $nouID = $ultimID + 1;

      $nouUsuari = [
        "IDUsuario" => (string) $nouID,
        "Nombre" => $nombreUsuario,
        "Apellidos" => $apellidos,
        "Telefono" => "",
        "Correo" => $email,
        "Password" => password_hash($password, PASSWORD_DEFAULT), // 📝 Text pla (coherent amb el teu JSON)
        "Valoracion" => null
      ];

      // Afegir a la llista
      $usuarios[] = $nouUsuari;
      $data['Usuarios'] = $usuarios;

      // Desar
      if (json_save_data('db.json', $data)) {

        setcookie('user_id', '', time() - 3600, '/');
        unset($_COOKIE['user_id']);

        $_SESSION['user_id'] = $nouUsuari['IDUsuario'];
        $_SESSION['email'] = $nouUsuari['Correo'];
        $_SESSION['nombre'] = $nouUsuari['Nombre'];

        header('Location: ../../frontend/index.php');
        exit;
      } else {
        $mensaje = "❌ Error al guardar los datos. Inténtalo más tarde.";
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
  <title>Registro - TaskLink</title>
  <link rel="stylesheet" href="../../frontend/HTML/CSS/register.css" type="text/css" />
</head>

<body>
  <header>
    <img src="../../frontend/IMG/Registrarse.png" alt="Imagen Superior" />
  </header>

  <div class="contenedor">
    <section class="datos">
      <!-- ✅ Canviat action a register.php i method a POST -->
      <form method="POST" class="contenedorInicio">
        <p class="textRegistrarse">
          Empieza a usar <strong>TaskLink</strong>
        </p>

        <!-- 📩 Mostra missatge -->
        <?php if ($mensaje): ?>
          <div class="mensaje <?= str_contains($mensaje, '✅') ? 'mensaje-exito' : 'mensaje-error' ?>">
            <?= htmlspecialchars($mensaje) ?>
          </div>
        <?php endif; ?>

        <div class="contenedorEmail">
          <input type="text" id="nombre" name="nombre" class="inputNombre" placeholder="Nombre Usuario" required
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
        </div>

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

        <div class="contenedorContrasenya">
          <img src="../../frontend/SVG/contrasenya.svg" class="iconoContrasnya" />
          <input type="password" id="repetircontrasenya" name="repetircontrasenya" class="inputContra"
            placeholder="Repite la Contraseña" required />
        </div>

        <input type="submit" value="Crear cuenta" class="btnEntrar" />

        <div class="contenedorOlvidarContra">
          <a href="login.php">← ¿Ya tienes cuenta? Inicia sesión</a>
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
      </div>

      <div class="logo">
        <img src="../../frontend/IMG/logo.png" alt="logo TaskLink" />
        <p>También puede <a href="#">denunciar ofertas</a> sin iniciar sesión</p>
      </div>
      <footer>Español (España) © 2025 TaskLink from Alex&Gabi</footer>
    </section>
  </div>
</body>

</html>