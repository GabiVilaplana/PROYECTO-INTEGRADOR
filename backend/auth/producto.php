<?php
session_start();

require_once __DIR__ . '/../includes/json_connect.php';

// 1. Gestión de Usuario logueado
$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
$usuario = null;
$data = json_get_data('db.json');

if ($user_id) {
    $usuarios = $data['Usuarios'] ?? [];
    foreach ($usuarios as $u) {
        if (isset($u['IDUsuario']) && $u['IDUsuario'] === $user_id) {
            $usuario = $u;
            break;
        }
    }
}

// 2. Obtener el Servicio actual
$id_actual = $_GET['id'] ?? null; 
$servicio_actual = null;

if ($id_actual) {
    foreach ($data['Servicio'] as $s) {
        if ($s['IDServicio'] == $id_actual) {
            $servicio_actual = $s;
            break;
        }
    }
}

if (!$servicio_actual) {
    die("Servicio no encontrado.");
}

// 2.1 Obtener datos del Anfitrión (Dueño del servicio)
$usuario_host = null;
foreach ($data['Usuarios'] as $u) {
    if ($u['IDUsuario'] === $servicio_actual['IDUsuarioCreacion']) {
        $usuario_host = $u;
        break;
    }
}

// 3. Lógica de Valoraciones
$comentarios_totales = $data['Comentarios'] ?? [];
$comentarios_servicio = array_filter($comentarios_totales, function($c) use ($id_actual) {
    return $c['IDServicio'] == $id_actual;
});

$total_resenas = count($comentarios_servicio);
$media = 0;
if ($total_resenas > 0) {
    $suma = array_sum(array_column($comentarios_servicio, 'Puntuacion'));
    $media = round($suma / $total_resenas, 1);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($servicio_actual['Nombre']) ?> - TaskLink</title>
  <link rel="stylesheet" href="../../frontend/HTML/CSS/index.css">
  <style>
    :root {
        --primary-color: #007bff;
        --text-dark: #222;
        --text-gray: #6a6a6a;
        --border-color: #dddddd;
    }

    .airbnb-layout {
        max-width: 1120px;
        margin: 0 auto;
        padding: 0 24px;
    }

    .product-header { padding: 24px 0; }
    .product-header h1 { font-size: 26px; font-weight: 600; margin-bottom: 8px; }

    /* Galería */
    .photo-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        grid-template-rows: 200px 200px;
        gap: 8px;
        height: 400px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 32px;
    }
    .photo-grid img { width: 100%; height: 100%; object-fit: cover; }
    .main-photo { grid-row: span 2; }

    /* Layout Columnas */
    .content-wrapper { display: flex; gap: 80px; position: relative; }
    .main-column { flex: 2; }

    .sidebar-column { 
        flex: 0 0 320px; /* Le damos un ancho fijo de base para que no se deforme */
    }

    .host-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .host-profile-img {
        width: 70px !important;
        height: 70px !important;
        border-radius: 50% !important;
        object-fit: cover !important;
        flex-shrink: 0;
        border: 1px solid #ddd;
    }

    /* Espaciado solicitado */
    .description-section {
        padding: 32px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .features-list {
        padding: 32px 0; /* Espacio antes de Opiniones de clientes */
    }

    .section-title { font-size: 18px; font-weight: 700; margin-bottom: 16px; color: var(--text-dark); }

    /* Estrellas e Items de opinión */
    .review-item-feature {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f0f0f0;
    }
    .review-avatar {
        width: 40px; height: 40px; background: #eee; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-weight: bold; color: #666; flex-shrink: 0;
    }
    .review-body .stars { color: #ff385c; font-size: 12px; margin-bottom: 4px; display: block; }

    /* Widget Reserva */
    .booking-widget {
        position: sticky; 
        top: 100px;
        border: 1px solid var(--border-color);
        border-radius: 12px; 
        padding: 24px;
        box-shadow: rgba(0, 0, 0, 0.12) 0px 6px 16px;
        background: white;
        box-sizing: border-box; /* ESENCIAL: para que el padding no estire el cuadro */
        width: 100%;
    }

    /* Mejora de la fila del Total */
    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid #eee;
        font-size: 16px;
    }

    .total-row b {
        font-size: 18px;
        white-space: nowrap; /* Evita que el símbolo € se baje a otra línea */
    }

    /* Reducimos un poco el gap si la pantalla no es muy ancha */
    @media (max-width: 1100px) {
        .content-wrapper { gap: 40px; }
    }

    .widget-btn {
        width: 100%; background: var(--primary-color); color: white;
        border: none; padding: 14px; border-radius: 8px; font-weight: 600; cursor: pointer; margin-top: 16px;
    }

    /* Formulario */
    .rating-form { display: flex; flex-direction: row-reverse; justify-content: flex-end; margin-bottom: 15px; }
    .rating-form input { display: none !important; }
    .rating-form label { font-size: 32px; color: #ddd; cursor: pointer; padding: 0 2px; }
    .rating-form label:hover, .rating-form label:hover ~ label, .rating-form input:checked ~ label { color: #ff385c; }
    .form-comentario-container textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; margin: 10px 0; }

    @media (max-width: 950px) { .content-wrapper { flex-direction: column; } .sidebar-column { order: -1; } }
  </style>
</head>

<body>
  <header>
    <div class="logo-container">
        <a href="../../frontend/index.php"><img src="../../frontend/IMG/logo.png" alt="Logo" class="logo-icon"></a>
    </div>
    <div class="right-header">
      <?php if ($usuario): ?>
        <span class="texto-servicios"><?= htmlspecialchars($usuario['Nombre']) ?></span>
        <div class="icono-perfil"><img src="../../frontend/IMG/imagenPerfilRedonda.png" class="profile-icon"></div>
      <?php else: ?>
        <span class="texto-servicios"><a href="login.php">Iniciar Sesión</a></span>
      <?php endif; ?>
    </div>
  </header>

  <main class="airbnb-layout">
    <div class="product-header">
        <h1><?= htmlspecialchars($servicio_actual['Nombre']) ?></h1>
        <p>⭐ <?= $media ?> · <?= $total_resenas ?> reseñas · Sevilla, España</p>
    </div>

    <section class="photo-grid">
        <div class="main-photo">
            <img src="../../frontend/IMG/image<?= $servicio_actual['IDCategoria'] ?>.jpg" alt="Imagen principal">
        </div>
        <img src="../../frontend/IMG/image<?= $servicio_actual['IDCategoria'] ?>.jpg" alt="Foto 2">
        <img src="../../frontend/IMG/image<?= $servicio_actual['IDCategoria'] ?>.jpg" alt="Foto 3">
        <img src="../../frontend/IMG/image<?= $servicio_actual['IDCategoria'] ?>.jpg" alt="Foto 4">
        <img src="../../frontend/IMG/image<?= $servicio_actual['IDCategoria'] ?>.jpg" alt="Foto 5">
    </section>

    <div class="content-wrapper">
        <div class="main-column">
            <!-- INFO ANFITRIÓN -->
            <div class="host-info">
                <div>
                    <h2>Servicio ofrecido por <?= htmlspecialchars($usuario_host['Nombre'] ?? 'el anfitrión') ?></h2>
                    <p>Experiencia verificada en TaskLink</p>
                </div>
              <img src="../../frontend/IMG/imagenPerfilRedonda.png" class="host-profile-img" alt="Foto del anfitrión">
            </div>

            <!-- SECCIÓN DESCRIPCIÓN (SUBIDA) -->
            <div class="description-section">
                <div class="section-title">Sobre este servicio</div>
                <p><?= nl2br(htmlspecialchars($servicio_actual['Descripcion'])) ?></p>
            </div>

            <!-- OPINIONES DE CLIENTES -->
            <div class="features-list">
                <div class="section-title">Opiniones de clientes</div>
                
                <?php if (count($comentarios_servicio) > 0): ?>
                    <?php foreach ($comentarios_servicio as $com): ?>
                        <div class="review-item-feature">
                            <div class="review-avatar"><?= strtoupper(substr($com['NombreUsuario'], 0, 1)) ?></div>
                            <div class="review-body">
                                <b><?= htmlspecialchars($com['NombreUsuario']) ?></b>
                                <span class="stars"><?= str_repeat('★', $com['Puntuacion']) ?></span>
                                <p><?= htmlspecialchars($com['Comentario']) ?></p>
                                <span style="font-size: 12px; color: #999;"><?= $com['Fecha'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="feature-item">
                        <div class="feature-text">
                            <b>Aún no hay reseñas</b>
                            <span>Sé el primero en probar este servicio y compartir tu experiencia.</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- FORMULARIO DE VALORACIÓN -->
            <div class="form-comentario-container">
                <?php 
                $usuario_ya_ha_comentado = false;
                if ($usuario) {
                    foreach ($comentarios_servicio as $com) {
                        if ($com['IDUsuario'] === $user_id) { $usuario_ya_ha_comentado = true; break; }
                    }
                }
                ?>

                <?php if ($usuario && !$usuario_ya_ha_comentado): ?>
                    <div class="section-title">Deja tu valoración</div>
                    <form action="add_comment.php" method="POST">
                        <input type="hidden" name="id_servicio" value="<?= $id_actual ?>">
                        <div class="rating-form">
                            <input type="radio" name="rating" value="5" id="s5"><label for="s5">★</label>
                            <input type="radio" name="rating" value="4" id="s4"><label for="s4">★</label>
                            <input type="radio" name="rating" value="3" id="s3" checked><label for="s3">★</label>
                            <input type="radio" name="rating" value="2" id="s2"><label for="s2">★</label>
                            <input type="radio" name="rating" value="1" id="s1"><label for="s1">★</label>
                        </div>
                        <textarea name="comentario" rows="3" placeholder="Escribe aquí tu opinión..." required></textarea>
                        <button type="submit" class="btn-enviar">Publicar valoración</button>
                    </form>
                <?php elseif ($usuario && $usuario_ya_ha_comentado): ?>
                    <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; border: 1px solid #bde0ff;">
                        <p style="margin: 0; color: #0056b3;">✅ Gracias por tu valoración. Ya has opinado sobre este servicio.</p>
                    </div>
                <?php else: ?>
                    <p style="color: #666; font-style: italic;">Debes <a href="login.php" style="color: #ff385c; font-weight:bold;">iniciar sesión</a> para dejar una reseña.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA DERECHA (Widget) -->
        <div class="sidebar-column">
    <div class="booking-widget">
        <div class="widget-price">
            <?= number_format($servicio_actual['Precio'], 2) ?> € <span style="font-weight: normal; font-size: 16px;">/ sesión</span>
        </div>
        
        <div style="border: 1px solid #ccc; border-radius: 8px; margin-bottom: 15px; overflow: hidden;">
            <div style="padding: 10px; border-bottom: 1px solid #ccc; font-size: 12px;">
                <b>FECHA</b><br>
                <span style="color: #666;">Seleccionar fecha</span>
            </div>
            <div style="padding: 10px; font-size: 12px;">
                <b>PERSONAS</b><br>
                <span style="color: #666;">1 persona</span>
            </div>
        </div>

        <button class="widget-btn">Reservar ahora</button>
        
        <p style="text-align: center; font-size: 12px; margin-top: 10px; color: #666;">No se te cobrará nada aún</p>
        
        <!-- Fila de Total corregida -->
        <div class="total-row">
            <span>Total</span>
            <b><?= number_format($servicio_actual['Precio'], 2) ?> €</b>
        </div>
    </div>
</div>
    </div>
</main>

  <footer>
    <div class="footer-bottom">
      Español (España) © 2025 TaskLink from Alex&Gabi
    </div>
  </footer>

  <script>
    document.querySelector('.icono-perfil')?.addEventListener('click', () => {
      document.getElementById('user-dropdown').classList.toggle('active');
    });
  </script>
</body>
</html>