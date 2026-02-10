<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';

$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$data = json_get_data('db.json');
$usuario = null;
foreach ($data['Usuarios'] as $u) {
    if ($u['IDUsuario'] === $user_id) {
        $usuario = $u;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Publicar Servicio - TaskLink</title>
  <!-- Cargamos primero el index para el header y luego el específico -->
  <link rel="stylesheet" href="../../frontend/HTML/CSS/index.css">
  <link rel="stylesheet" href="../../frontend/HTML/CSS/crearServicio.css">
</head>

<body class="body-crear-servicio">
  <header>
    <div class="logo-container">
        <a href="../../frontend/index.php">
            <img src="../../frontend/IMG/logo.png" alt="Logo" class="logo-icon">
        </a>
    </div>
    <div class="right-header">
        <span class="texto-servicios"><?= htmlspecialchars($usuario['Nombre']) ?></span>
        <div class="icono-perfil">
            <img src="../../frontend/IMG/imagenPerfilRedonda.png" class="profile-icon" alt="Perfil">
        </div>
    </div>
  </header>

  <main class="airbnb-layout">
    <div class="product-header">
        <h1>Anuncia tu talento en TaskLink</h1>
        <p>Crea una oferta atractiva para que los clientes te encuentren fácilmente.</p>
    </div>

    <!-- GRID DE IMÁGENES (IDENTIDAD) -->
    <section class="photo-upload-grid">
        <div class="main-photo-upload">
            <div class="upload-content">
                <span class="icon">📸</span>
                <b>Añadir foto principal</b>
                <p>O arrastra y suelta aquí</p>
                <input type="file" id="file-main" hidden>
            </div>
        </div>
        <div class="secondary-upload"><span>+</span></div>
        <div class="secondary-upload"><span>+</span></div>
        <div class="secondary-upload"><span>+</span></div>
        <div class="secondary-upload"><span>+</span></div>
    </section>

    <form id="form-crear-servicio" class="content-wrapper">
        <div class="main-column">
            <!-- INFO USUARIO -->
            <div class="host-info">
                <div>
                    <h2>Publicarás como <?= htmlspecialchars($usuario['Nombre']) ?></h2>
                    <p>Tu valoración actual es de ⭐ <?= $usuario['Valoracion'] ?? '5.0' ?></p>
                </div>
                <img src="../../frontend/IMG/imagenPerfilRedonda.png" class="host-profile-img" alt="Avatar">
            </div>

            <!-- FORMULARIO -->
            <div class="description-section">
                <div class="section-title">Título de tu servicio</div>
                <input type="text" name="nombre" class="input-minimal" placeholder="Ej: Diseño de logotipos modernos" required>
            </div>

            <div class="description-section">
                <div class="section-title">Categoría</div>
                <select name="idCategoria" id="idCategoria" class="input-minimal" required>
                    <!-- Se rellena vía JS con tu controlador -->
                </select>
            </div>

            <div class="description-section">
                <div class="section-title">Descripción detallada</div>
                <textarea name="descripcion" class="textarea-minimal" rows="6" placeholder="¿En qué consiste tu trabajo? ¿Qué herramientas usas? ¿Qué incluye el precio?" required></textarea>
            </div>
        </div>

        <!-- WIDGET LATERAL (ESTILO PRODUCTO) -->
        <div class="sidebar-column">
            <div class="publish-widget">
                <div class="section-title" style="margin-top:0">Precio del servicio</div>
                <div class="widget-price-row">
                    <input type="number" name="precio" id="price-input" placeholder="0.00" step="0.01" required>
                    <span>€ / sesión</span>
                </div>
                
                <div class="widget-details">
                    <div class="detail-row">
                        <span>Visibilidad</span>
                        <b>Máxima (Nueva)</b>
                    </div>
                    <div class="detail-row">
                        <span>Tasa de servicio</span>
                        <b>0.00 €</b>
                    </div>
                </div>

                <button type="submit" class="widget-btn">Publicar ahora</button>
                
                <div class="total-preview-row">
                    <span>Tú recibes</span>
                    <b id="price-preview">0.00 €</b>
                </div>
            </div>
        </div>
    </form>
  </main>

  <footer style="margin-top: 50px; padding: 20px; border-top: 1px solid #eee; text-align: center; color: #666;">
      Español (España) © 2025 TaskLink from Alex&Gabi
  </footer>

  <script type="module">
    import CrearServicioController from '../../JAVAScript/controllers/crearServicioController.class.js';
    document.addEventListener("DOMContentLoaded", () => {
        const controller = new CrearServicioController();
        controller.init();

        // Actualización dinámica del precio en el widget
        const input = document.getElementById('price-input');
        const preview = document.getElementById('price-preview');
        input.addEventListener('input', (e) => {
            preview.innerText = (e.target.value || "0.00") + " €";
        });
    });
  </script>
</body>
</html>