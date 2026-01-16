<?php
session_start();

require_once __DIR__ . '/../includes/json_connect.php';

// 1. Gestión de Usuario
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

// 2. Obtener el Servicio actual dinámicamente
$id_actual = $_GET['id'] ?? null; 
$servicio_actual = null;

if ($id_actual) {
    // IMPORTANTE: En tu JSON la clave es "Servicio" (en singular)
    foreach ($data['Servicio'] as $s) {
        if ($s['IDServicio'] == $id_actual) {
            $servicio_actual = $s;
            break;
        }
    }
}

// Si no existe el servicio, redirigimos al index o mostramos error
if (!$servicio_actual) {
    die("Servicio no encontrado.");
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
    /* Estilos Airbnb */
    .airbnb-container {
      max-width: 800px; margin: 20px auto 40px; padding: 24px;
      background: white; box-shadow: 0 0 20px rgba(0,0,0,0.05); border-radius: 12px;
    }
    .airbnb-container h1 { font-size: 28px; font-weight: 600; color: #222; }
    .price-card {
      background: white; border: 1px solid #ddd; border-radius: 12px;
      padding: 16px; margin: 24px 0; display: flex; justify-content: space-between; align-items: center;
    }
    .price { font-size: 24px; font-weight: 700; color: #ff385c; }
    .section-title { font-size: 18px; font-weight: 700; margin: 28px 0 12px 0; border-top: 1px solid #eee; padding-top: 20px; }
    .rating-big { color: #ff385c; font-weight: 700; font-size: 22px; margin-bottom: 20px;}

    /* Estilos Sistema Valoraciones */
    .rating-form { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
    .rating-form input { display: none; }
    .rating-form label { font-size: 35px; color: #ccc; cursor: pointer; }
    .rating-form label:hover, .rating-form label:hover ~ label, .rating-form input:checked ~ label { color: #ff385c; }
    
    .comentario-item { padding: 15px 0; border-bottom: 1px solid #f0f0f0; }
    .comentario-item:last-child { border-bottom: none; }
    .comentario-header { display: flex; justify-content: space-between; margin-bottom: 5px; }
    .user-name { font-weight: 600; color: #333; }
    .stars-color { color: #ff385c; }
    
    textarea { width: 100%; padding: 15px; border-radius: 8px; border: 1px solid #ddd; font-family: inherit; margin: 10px 0; }
    .btn-enviar { background: #ff385c; color: white; padding: 12px 25px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .btn-enviar:hover { background: #e31c5f; }
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

  <div class="airbnb-container">
    <!-- Datos dinámicos del Servicio -->
    <h1><?= htmlspecialchars($servicio_actual['Nombre']) ?></h1>
    <p class="airbnb-subtitle"><?= htmlspecialchars($servicio_actual['Descripcion']) ?></p>

    <div class="price-card">
      <div class="price"><?= number_format($servicio_actual['Precio'], 2) ?> €</div>
      <div class="duration">Duración: <?= $servicio_actual['DuracionEstimada'] ?> <?= $servicio_actual['UnidadDuracion'] ?? 'h' ?></div>
    </div>

    <!-- SECCIÓN DE VALORACIONES -->
    <div class="section-title">Valoraciones de la comunidad</div>
    <div class="rating-big">
        ★ <?= $media > 0 ? $media : 'Sin valoraciones' ?> 
        <span style="font-size: 14px; color: #666; font-weight: normal;">(<?= $total_resenas ?> reseñas)</span>
    </div>

    <!-- Lista de Comentarios -->
    <div class="comentarios-lista">
        <?php if ($total_resenas > 0): ?>
            <?php foreach ($comentarios_servicio as $com): ?>
                <div class="comentario-item">
                    <div class="comentario-header">
                        <span class="user-name"><?= htmlspecialchars($com['NombreUsuario']) ?></span>
                        <span class="stars-color"><?= str_repeat('★', $com['Puntuacion']) ?></span>
                    </div>
                    <p style="margin: 0; color: #444;"><?= htmlspecialchars($com['Comentario']) ?></p>
                    <small style="color: #999;"><?= $com['Fecha'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #888;">Nadie ha comentado todavía. ¡Sé el primero!</p>
        <?php endif; ?>
    </div>

    <!-- Formulario de envío (Solo si está logueado) -->
    <div class="section-title">¿Has probado este servicio?</div>
    <?php 
    // Comprobamos si el usuario actual ya ha dejado un comentario en este servicio
    $usuario_ya_ha_comentado = false;
    if ($usuario) {
        foreach ($comentarios_servicio as $com) {
            if ($com['IDUsuario'] === $user_id) {
                $usuario_ya_ha_comentado = true;
                break;
            }
        }
    }
    ?>

    <?php if ($usuario && !$usuario_ya_ha_comentado): ?>
        <!-- MOSTRAR FORMULARIO SOLO SI ESTÁ LOGUEADO Y NO HA COMENTADO AÚN -->
        <form action="add_comment.php" method="POST" style="margin-top: 15px;">
            <input type="hidden" name="id_servicio" value="<?= $id_actual ?>">
            
            <p style="margin-bottom: 5px; font-weight: 600;">Tu puntuación:</p>
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
        <!-- MENSAJE SI YA HA COMENTADO -->
        <div style="background: #e7f3ff; padding: 15px; border-radius: 8px; border: 1px solid #bde0ff;">
            <p style="margin: 0; color: #0056b3;">✅ Gracias por tu valoración. Ya has opinado sobre este servicio.</p>
        </div>

    <?php else: ?>
        <!-- MENSAJE SI NO ESTÁ LOGUEADO -->
        <div style="background: #f7f7f7; padding: 15px; border-radius: 8px; text-align: center;">
            <p>Para dejar un comentario debes <a href="login.php" style="color: #ff385c; font-weight: 700;">Iniciar Sesión</a></p>
        </div>
    <?php endif; ?>

    <!-- Opcional: Mostrar error de backend si intentan duplicar -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'ya_valorado'): ?>
        <script>alert("Ya has dejado una valoración para este servicio anteriormente.");</script>
    <?php endif; ?>

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