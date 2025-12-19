<?php
session_start();
require_once __DIR__ . '/../backend/includes/json_connect.php';

$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
$usuario = null;
if ($user_id) {
  $data = json_get_data('db.json');
  $usuarios = $data['Usuarios'] ?? [];

  foreach ($usuarios as $u) {
    // Comparació estricta de strings (IDUsuario és string

    if (isset($u['IDUsuario']) && $u['IDUsuario'] === $user_id) {
      $usuario = $u;

      // Sincronitzar sessió (per coherència)
      $_SESSION['user_id'] = $u['IDUsuario'];
      $_SESSION['email'] = $u['Correo'];
      $_SESSION['nombre'] = $u['Nombre'];
      break;
    }
  }
}

if ($user_id && !$usuario) {
  header('Location: logout.php');
  exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TaskLink</title>
  <link rel="stylesheet" href="./HTML/CSS/index.css">

</head>

<body>

  <header>
    <div class="logo-container">
      <img src="./IMG/logo.png" alt="TaskLink Logo" class="logo-icon">
    </div>

    <div class="right-header">
      <?php if ($usuario): ?>
        <span class="texto-servicios"><?= htmlspecialchars($usuario['Nombre']) ?></span>
        <div class="icono-perfil">
          <img src="./IMG/imagenPerfilRedonda.png" class="profile-icon">
        </div>
      <?php else: ?>
        <span class="texto-servicios"><a href="../backend/auth/login.php">Iniciar Sesión</a></span>
        <!-- No mostrar el ícono de perfil si no hay usuario -->
      <?php endif; ?>
    </div>

    <div id="user-dropdown" class="user-dropdown">
      <ul class="dropdown-menu">
        <h2>Mi cuenta</h2>
        <li class="divider">
          <hr>
        </li>
        <li><a href="../backend/auth/profile.php"><span>👤</span> Perfil</a></li>
        <li><a href="like.php"><span>❤️</span> Favoritos</a></li>
        <li><a href="mensajes.php"><span>💬</span> Mensajes</a></li>
        <li><a href="../backend/auth/crearServicio.php"><span>➕🛠️</span> Añadir Servicio</a></li>
        <li class="divider">
          <hr>
        </li>
        <!--<li><a href="moneda.php"><span>🌐</span> Idiomas y moneda</a></li>-->
        <li><a href="ayuda.php"><span>❓</span> Centro de ayuda</a></li>
        <li class="divider">
          <hr>
        </li>
        <li><a href="../backend/auth/logout.php"><span>➜]</span> Cerrar sesión</a></li>
      </ul>
    </div>
  </header>

  <section class="hero">
    <video class="hero-video" autoplay muted loop>
      <source src="./MP4/prueba2.mp4" type="video/mp4" />
      Tu navegador no soporta videos.
    </video>
    <div class="hero-content">
      <h2>Conecta el talento con las oportunidades</h2>
      <p>
        Conectamos tus talentos con las oportunidades<br />
        que te ofrecen otras personas
      </p>
      <div class="search-container">
        <div class="search-bar">
          <input type="text" placeholder="¿Qué servicio necesitas?" />
          <button class="btnSearch-bar">Buscar</button>
        </div>
      </div>
    </div>
  </section>

  <div class="container">

    <div class="carousel-wrapper">
      <h2>Categorias</h2>
      <button class="carousel-btn left" id="btn-left">‹</button>
      <button class="carousel-btn right" id="btn-right">›</button>
      <section class="courses carousel-track" id="datosCategoria">
        <!-- aquí JS mete las cards -->
      </section>


    </div>
    <h2>Servicios Disponibles</h2>
    <div id="contenedorcardsCategoria"></div>
    <div id="contenedorCategoria"></div>
    <h3>¡Tendencias ahora!</h3>
    <div class="proximamente">
      <p><i>Proximamente...</i></p>
    </div>
  </div>
  <footer>
    <div class="footer-contenedor">

      <div class="footer-asistencia">
        <h2>Sobre Nosotros</h2>
        <div>
          <p>Esta página web va dirigida a la generación de servicios.<br>
            Puedes tanto contratar servicios como crear servicios para otras personas.</p>
        </div>
      </div>

      <div class="footer-ofertas">
        <h2>Enlaces Rápidos</h2>
        <div>
          <a href="../backend/auth/contacto.php">Centro de ayuda</a>
          <a href="#">Crear oferta</a>
          <a href="#">Solicitar Oferta</a>
        </div>
      </div>

      <div class="footer-acercade">
        <h2>TaskLink</h2>
        <div>
          <a href="#">Lanzamiento TaskLink</a>
          <a href="#">Empleo</a>
          <a href="#">Inversores</a>
        </div>
      </div>

    </div>

    <div class="footer-bottom">
      Español (España) © 2025 TaskLink from Alex&Gabi
    </div>
  </footer>

  <script type="module">
    import Controller from './JAVAScript/controllers/indexController.class.js';

    document.addEventListener("DOMContentLoaded", () => {
      const myController = new Controller();
      myController.init();
    })

  </script>

</body>

</html>