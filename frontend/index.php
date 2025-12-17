<?php
session_start();
require_once __DIR__ . '/../backend/includes/json_connect.php';

$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
$usuario = null;
if ($user_id) {
  $data = json_get_data('db.json');
  $usuarios = $data['Usuarios'] ?? [];

  foreach ($usuarios as $u) {
    // Comparació estricta de strings (IDUsuario és string!)
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
  <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        <div class="icono-perfil" onclick="toggleDropdown()">
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
    <h2>OFERTAS ACTUALES</h2>
    <div class="carousel-wrapper">
      <button class="carousel-btn left" id="btn-left">‹</button>

      <section class="courses carousel-track" id="carousel-courses">
        <!-- aquí JS mete las cards -->
      </section>

      <button class="carousel-btn right" id="btn-right">›</button>
    </div>

    <h3>Servicios destacados</h3>
    <section class="courses">
      <div class="course-completo category-peluquero">
        <div class="course">
          <img src="./IMG/img4.png" alt="Peluquero" />
          <h3>Peluquero</h3>
          <p>Peluquero 24h pide cita cuando mas falta te haga.</p>
          <div class="course-footer">
            <span class="price">15€</span>
          </div>
        </div>
        <div class="course-trasera">
          <h4>Información del Usuario</h4>
          <p>Nombre: Juan Pérez</p>
          <p>Valoración: 4/5</p>
          <p>Telefono: 637123487</p>
          <p>Email: juan@email.com</p>
          <div class="mapa-container">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12454.997924615587!2d-0.48873568354075925!3d38.70059844253098!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd61864e204bb377%3A0x3270bc5ab4510472!2sAlcoy%2C%20Alicante!5e0!3m2!1ses!2ses!4v1763423541393!5m2!1ses!2ses"
              allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
          <button class="btn-trasera">Contactar</button>
        </div>
      </div>

      <div class="course-completo category-profesor">
        <div class="course">
          <img src="./IMG/img5.png" alt="Profesor" />
          <h3>Profesor</h3>
          <p>Profesor particular para cualquier tipo de asignatura.</p>
          <div class="course-footer">
            <span class="price">Negociable</span>
          </div>
        </div>

        <div class="course-trasera">
          <h4>Información del Usuario</h4>
          <p>Nombre: Juan Pérez</p>
          <p>Valoración: 4/5</p>
          <p>Telefono: 637123487</p>
          <p>Email: juan@email.com</p>
          <div class="mapa-container">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12454.997924615587!2d-0.48873568354075925!3d38.70059844253098!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd61864e204bb377%3A0x3270bc5ab4510472!2sAlcoy%2C%20Alicante!5e0!3m2!1ses!2ses!4v1763423541393!5m2!1ses!2ses"
              allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
          <button class="btn-trasera">Contactar</button>
        </div>
      </div>

      <div class="course-completo category-mecanico">
        <div class="course">
          <img src="./IMG/img6.png" alt="Mecanico" />
          <h3>Mecánico</h3>
          <p>Mecánico con mucha experiéncia para todas las necesidades.</p>
          <div class="course-footer">
            <span class="price">50€/h</span>
          </div>
        </div>
        <div class="course-trasera">
          <h4>Información del Usuario</h4>
          <p>Nombre: Juan Pérez</p>
          <p>Valoración: 4/5</p>
          <p>Telefono: 637123487</p>
          <p>Email: juan@email.com</p>
          <div class="mapa-container">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12454.997924615587!2d-0.48873568354075925!3d38.70059844253098!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd61864e204bb377%3A0x3270bc5ab4510472!2sAlcoy%2C%20Alicante!5e0!3m2!1ses!2ses!4v1763423541393!5m2!1ses!2ses"
              allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
          <button class="btn-trasera">Contactar</button>
        </div>
      </div>
    </section>

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
  <!--<script>
    function toggleDropdown() {
      const dropdown = document.getElementById('user-dropdown');
      const isActive = dropdown.classList.contains('active');

      // Cerrar todos los dropdowns primero
      document.querySelectorAll('.user-dropdown').forEach(el => {
        el.classList.remove('active');
      });

      // Si no estaba activo, abrirlo
      if (!isActive) {
        dropdown.classList.add('active');

        // Cerrar al hacer clic fuera
        document.addEventListener('click', closeDropdownOnClickOutside);
      }
    }

    function closeDropdownOnClickOutside(event) {
      const dropdown = document.getElementById('user-dropdown');
      const profileIcon = document.querySelector('.icono-perfil');

      // Si el clic fue fuera del dropdown Y fuera del ícono de perfil → cerrar
      if (!dropdown.contains(event.target) && !profileIcon.contains(event.target)) {
        dropdown.classList.remove('active');
        document.removeEventListener('click', closeDropdownOnClickOutside);
      }
    }
  </script>-->
</body>

</html>