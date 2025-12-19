<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';

// 1. Obtener ID del servicio de la URL
$id_servicio = $_GET['id'] ?? null;

if (!$id_servicio) {
    header('Location: ../../frontend/index.php');
    exit;
}

// 2. Cargar datos
$data = json_get_data('db.json');
$servicios = $data['Servicio'] ?? [];
$usuarios = $data['Usuarios'] ?? [];
$categorias = $data['Categorias'] ?? [];

// 3. Buscar el servicio específico
$servicio = null;
foreach ($servicios as $s) {
    if ($s['IDServicio'] === (string)$id_servicio) {
        $servicio = $s;
        break;
    }
}

if (!$servicio) {
    echo "Servicio no encontrado.";
    exit;
}

// 4. Buscar información del Anfitrión (dueño del servicio)
$anfitrion = null;
foreach ($usuarios as $u) {
    if ($u['IDUsuario'] === $servicio['IDUsuarioCreacion']) {
        $anfitrion = $u;
        break;
    }
}

// 5. Buscar nombre de la categoría
$nombreCategoria = "General";
foreach ($categorias as $c) {
    if ($c['IDCategoria'] === $servicio['IDCategoria']) {
        $nombreCategoria = $c['Nombre'];
        break;
    }
}

// 6. Usuario logueado (para permitir comentarios)
$user_id_sesion = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
$usuario_logueado = null;
if ($user_id_sesion) {
    foreach ($usuarios as $u) {
        if ($u['IDUsuario'] === $user_id_sesion) {
            $usuario_logueado = $u;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($servicio['Nombre']) ?> - TaskLink</title>
    <!-- Rutas ajustadas a tu estructura de carpetas -->
    <link rel="stylesheet" href="../../frontend/HTML/CSS/index.css">
    <link rel="stylesheet" href="../../frontend/HTML/CSS/detailService.css">
</head>
<body>

<header>
    <div class="logo-container">
        <a href="../../frontend/index.php">
            <img src="../../frontend/IMG/logo.png" alt="TaskLink Logo" class="logo-icon">
        </a>
    </div>

    <div class="right-header">
      <?php if ($usuario_logueado): ?>
        <span class="texto-servicios"><?= htmlspecialchars($usuario_logueado['Nombre']) ?></span>
        <div class="icono-perfil">
          <img src="../../frontend/IMG/imagenPerfilRedonda.png" class="profile-icon">
        </div>
      <?php else: ?>
        <span class="texto-servicios"><a href="./login.php">Iniciar Sesión</a></span>
      <?php endif; ?>
    </div>
</header>

<main class="container-detail">
    <!-- Título y categoría -->
    <section class="detail-header">
        <h1><?= htmlspecialchars($servicio['Nombre']) ?></h1>
        <div class="header-info">
            <span>★ <?= $anfitrion['Valoracion'] ?? 'Nuevo' ?></span> · 
            <span class="category-link"><?= htmlspecialchars($nombreCategoria) ?></span> ·
            <span class="location">Madrid, España</span>
        </div>
    </section>

    <!-- Galería estilo Airbnb -->
    <section class="image-gallery">
        <div class="main-image">
            <img src="<?= !empty($servicio['IDImagen']) ? $servicio['IDImagen'] : '../../frontend/IMG/categorias/default.png' ?>" alt="Servicio">
        </div>
        <div class="side-images">
            <!-- Relleno para mantener el diseño grid de Airbnb -->
            <img src="../../frontend/IMG/categorias/default.png" alt="Detail">
            <img src="../../frontend/IMG/categorias/default.png" alt="Detail">
            <img src="../../frontend/IMG/categorias/default.png" alt="Detail">
            <img src="../../frontend/IMG/categorias/default.png" alt="Detail">
        </div>
    </section>

    <div class="content-wrapper">
        <!-- Columna Izquierda: Info detallada -->
        <div class="info-column">
            <div class="host-info">
                <div>
                    <h2>Servicio prestado por <?= htmlspecialchars($anfitrion['Nombre']) ?></h2>
                    <p>Estimación: <?= $servicio['DuracionEstimada'] ?> <?= $servicio['UnidadDuracion'] ?? 'unidades' ?></p>
                </div>
                <img src="../../frontend/IMG/imagenPerfilRedonda.png" alt="Anfitrión" class="host-avatar">
            </div>

            <hr class="divider">

            <div class="description">
                <h3>Acerca de este servicio</h3>
                <p><?= nl2br(htmlspecialchars($servicio['Descripcion'])) ?></p>
            </div>

            <hr class="divider">

            <!-- Sección de Reseñas / Comentarios -->
            <section class="reviews-section">
                <h3>Reseñas</h3>
                <?php if ($usuario_logueado): ?>
                    <div class="comment-box">
                        <textarea placeholder="¿Qué te ha parecido este servicio?"></textarea>
                        <button class="btn-primary">Enviar valoración</button>
                    </div>
                <?php else: ?>
                    <div class="login-warning">
                        <p>Solo los usuarios registrados pueden dejar una valoración.</p>
                        <a href="./login.php" class="btn-link">Iniciar sesión ahora</a>
                    </div>
                <?php endif; ?>
                <p class="no-reviews">No hay reseñas para mostrar todavía.</p>
            </section>
        </div>

        <!-- Columna Derecha: Tarjeta de Reserva (Sticky) -->
        <aside class="booking-sidebar">
            <div class="booking-card">
                <div class="price-header">
                    <span class="price-val"><?= number_format($servicio['Precio'], 2) ?>€</span>
                </div>

                <div class="booking-form">
                    <div class="input-group">
                        <label>FECHA DE INICIO</label>
                        <input type="date" value="<?= date('Y-m-d') ?>">
                    </div>
                </div>

                <button class="btn-reserve">Contratar Servicio</button>
                <p class="disclaimer">No se realizará ningún cargo todavía</p>

                <div class="total-calc">
                    <div class="row">
                        <span>Precio base</span>
                        <span><?= $servicio['Precio'] ?>€</span>
                    </div>
                    <div class="row">
                        <span>Comisión TaskLink</span>
                        <span>4.50€</span>
                    </div>
                    <hr>
                    <div class="row total">
                        <span>Total</span>
                        <span><?= $servicio['Precio'] + 4.50 ?>€</span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>

<footer>
    <div class="footer-bottom">
      Español (España) © 2025 TaskLink from Alex&Gabi
    </div>
</footer>

</body>
</html>