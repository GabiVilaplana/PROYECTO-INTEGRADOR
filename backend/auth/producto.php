<?php
session_start();

require_once __DIR__ . '/../includes/json_connect.php';

$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
$usuario = null;
if ($user_id) {
  $data = json_get_data('db.json');
  $usuarios = $data['Usuarios'] ?? [];

  foreach ($usuarios as $u) {
    if (isset($u['IDUsuario']) && $u['IDUsuario'] === $user_id) {
      $usuario = $u;
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
  <title>Paseo fotográfico por el casco antiguo - TaskLink</title>
  <link rel="stylesheet" href="../../frontend/HTML/CSS/index.css">
  <style>
    /* Estilos específicos para la página tipo Airbnb */
    .airbnb-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 24px;
      background: white;
      box-shadow: 0 0 20px rgba(0,0,0,0.05);
      border-radius: 12px;
      margin-top: 20px;
      margin-bottom: 40px;
    }

    .airbnb-container h1 {
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 8px;
      color: #222;
    }

    .airbnb-subtitle {
      color: #666;
      font-size: 16px;
      margin-bottom: 24px;
    }

    .price-card {
      background: white;
      border: 1px solid #ddd;
      border-radius: 12px;
      padding: 16px;
      margin: 24px 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .price {
      font-size: 24px;
      font-weight: 700;
      color: #ff385c;
    }

    .duration {
      color: #666;
      font-size: 14px;
    }

    .section-title {
      font-size: 18px;
      font-weight: 700;
      margin: 28px 0 12px 0;
      color: #222;
    }

    p {
      margin-bottom: 16px;
      line-height: 1.6;
    }

    .ig-link {
      color: #007aff;
      text-decoration: none;
    }

    .ig-link:hover {
      text-decoration: underline;
    }

    .rating {
      color: #ff385c;
      font-weight: 700;
      font-size: 16px;
    }

    .footer-note {
      font-size: 12px;
      color: #888;
      margin-top: 32px;
      padding-top: 16px;
      border-top: 1px solid #eee;
    }

    @media (max-width: 600px) {
      .airbnb-container {
        padding: 16px;
      }
      .price-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
      }
    }
  </style>
</head>

<body>

  <header>
    <div class="logo-container">
      <img src="./IMG/logo.png" alt="TaskLink Logo" class="logo-icon">
    </div>

    <div class="right-header" tabindex="1">
      <?php if ($usuario): ?>
        <span class="texto-servicios"><?= htmlspecialchars($usuario['Nombre']) ?></span>
        <div class="icono-perfil">
          <img src="../frontend/IMG/imagenPerfilRedonda.png" class="profile-icon">
        </div>
      <?php else: ?>
        <span class="texto-servicios"><a href="./backend/auth/login.php">Iniciar Sesión</a></span>
      <?php endif; ?>
    </div>

    <div id="user-dropdown" class="user-dropdown">
      <ul class="dropdown-menu">
        <h2>Mi cuenta</h2>
        <li class="divider"><hr></li>
        <li><a href="backend/auth/profile.php"><span>👤</span> Perfil</a></li>
        <li><a href="like.php"><span>❤️</span> Favoritos</a></li>
        <li><a href="mensajes.php"><span>💬</span> Mensajes</a></li>
        <li><a href="backend/auth/crearServicio.php"><span>➕🛠️</span> Añadir Servicio</a></li>
        <li class="divider"><hr></li>
        <li><a href="ayuda.php"><span>❓</span> Centro de ayuda</a></li>
        <li class="divider"><hr></li>
        <li><a href="backend/auth/logout.php"><span>➜</span> Cerrar sesión</a></li>
      </ul>
    </div>
  </header>

  <!-- Contenido tipo Airbnb -->
  <div class="airbnb-container">
    <h1>Paseo fotográfico por el casco antiguo con Lucía</h1>
    <p class="airbnb-subtitle">Captura recuerdos únicos en rincones secretos de Sevilla, sin multitudes.</p>

    <div class="price-card">
      <div class="price">75 € por persona</div>
      <div class="duration">1.5 h</div>
    </div>

    <p>Disfruta de un paseo íntimo por las calles más auténticas de Sevilla mientras te tomo fotos profesionales con luz natural. Evitamos zonas turísticas masificadas y buscamos ángulos únicos que reflejen tu esencia.</p>

    <p>Instagram: 
      <a href="https://instagram.com/lucia.photos" class="ig-link">@lucia.photos</a><br>
      IG: 
      <a href="https://instagram.com/sevilla_moments" class="ig-link">@sevilla_moments</a>
    </p>

    <p>Puedes escribir a Lucía para ajustar la ruta, horario o tipo de sesión (pareja, familia, solista, etc.).</p>

    <div class="section-title">Mis cualificaciones</div>
    <p><strong>Fotógrafa profesional con 5 años de experiencia en sesiones callejeras.</strong><br>
       He trabajado con cientos de viajeros creando recuerdos visuales auténticos.</p>

    <p><strong>Colaboradora en revistas de viaje como National Geographic Travel.</strong><br>
       Mis fotos han aparecido en editoriales internacionales sobre cultura y turismo.</p>

    <p><strong>Especializada en luz natural y composiciones auténticas.</strong><br>
       No uso flashes ni poses forzadas: busco momentos reales y espontáneos.</p>

    <p><em>Para proteger tus pagos, utiliza siempre plataformas seguras para reservar y comunicarte con anfitriones.</em></p>

    <div class="section-title">Mi portfolio</div>
    <p>(Galería de fotos disponible al reservar o contactar directamente)</p>

    <div class="section-title">Valoración</div>
    <p class="rating">⭐ 4.98 (42 reseñas)</p>

    <div class="section-title">Dónde iremos</div>
    <p>41004, Sevilla, Andalucía</p>

    <div class="section-title">Qué debes saber</div>

    <p><strong>Requisitos para los viajeros</strong><br>
       Solo pueden apuntarse viajeros mayores de 3 años, hasta un máximo de 4 personas.</p>

    <p><strong>Accesibilidad</strong><br>
       El recorrido incluye calles empedradas. Consulta con Lucía si necesitas adaptaciones.</p>

    <p><strong>Política de cancelación</strong><br>
       Cancela hasta 24 horas antes del inicio para obtener un reembolso completo.</p>

    <p><strong>Lucía ejerce su actividad de anfitriona como particular</strong><br>
       Es la persona responsable de este servicio.</p>

    <div class="price-card">
      <div class="price">Desde 75 € por persona</div>
      <div class="duration">Cancelación gratuita</div>
    </div>

    <p><strong>Servicio de fotografía con garantía de calidad</strong><br>
       Evaluamos a los anfitriones en base a su experiencia, portfolio y feedback de viajeros.</p>

    <div class="footer-note">
      ¿Has detectado un problema? Reporta contenido inapropiado.
    </div>
  </div>

  <!-- Footer original -->
  <footer>
    <div class="footer-contenedor">
      <div class="footer-asistencia">
        <h2>Sobre Nosotros</h2>
        <p>Esta página web va dirigida a la generación de servicios.<br>
          Puedes tanto contratar servicios como crear servicios para otras personas.</p>
      </div>

      <div class="footer-ofertas">
        <h2>Enlaces Rápidos</h2>
        <a href="backend/auth/contacto.php">Centro de ayuda</a><br>
        <a href="#">Crear oferta</a><br>
        <a href="#">Solicitar Oferta</a>
      </div>

      <div class="footer-acercade">
        <h2>TaskLink</h2>
        <a href="#">Lanzamiento TaskLink</a><br>
        <a href="#">Empleo</a><br>
        <a href="#">Inversores</a>
      </div>
    </div>

    <div class="footer-bottom">
      Español (España) © 2025 TaskLink from Alex&Gabi
    </div>
  </footer>

  <!-- Scripts (opcional: mantener si usas dropdown JS) -->
  <script>
    // Si tienes lógica JS para el dropdown, añádela aquí
    document.querySelector('.icono-perfil')?.addEventListener('click', () => {
      const dropdown = document.getElementById('user-dropdown');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
  </script>

</body>
</html>