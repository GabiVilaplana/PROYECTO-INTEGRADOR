<?php
session_start();

require_once __DIR__ . '/../includes/json_connect.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Recuperar usuario
$user_id = $_SESSION['user_id'] ?? $_COOKIE['user_id'];
$data = json_get_data('db.json');
$usuarios = $data['Usuarios'] ?? [];

$usuario = null;
foreach ($usuarios as $u) {
    if (isset($u['IDUsuario']) && $u['IDUsuario'] === $user_id) {
        $usuario = $u;
        break;
    }
}

if (!$usuario) {
    header('Location: ../auth/logout.php');
    exit;
}

// Sincronizar sesión (opcional)
$_SESSION['user_id'] = $usuario['IDUsuario'];
$_SESSION['email'] = $usuario['Correo'];
$_SESSION['nombre'] = $usuario['Nombre'];

// ✅ Manejo de actualización de datos
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_nombre = trim($_POST['nombre'] ?? '');
    $nuevo_correo = trim($_POST['correo'] ?? '');
    $nueva_contrasena = $_POST['contrasena'] ?? '';
    $nuevo_telefono = trim($_POST['telefono'] ?? '');
    $nueva_direccion = trim($_POST['direccion'] ?? '');
    $nueva_biografia = trim($_POST['biografia'] ?? '');

    if (!empty($nuevo_nombre) && !empty($nuevo_correo)) {
        // Buscar y actualizar el usuario
        foreach ($usuarios as &$u) {
            if ($u['IDUsuario'] === $user_id) {
                $u['Nombre'] = $nuevo_nombre;
                $u['Correo'] = $nuevo_correo;
                $u['Telefono'] = $nuevo_telefono;
                $u['Direccion'] = $nueva_direccion;
                $u['Biografia'] = $nueva_biografia;
                if (!empty($nueva_contrasena)) {
                    $u['Contrasena'] = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
                }

                // ✅ Manejo de subida de imagen
                if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                    $nombre_foto = $_FILES['foto_perfil']['name'];
                    $tmp_foto = $_FILES['foto_perfil']['tmp_name'];
                    $ext = strtolower(pathinfo($nombre_foto, PATHINFO_EXTENSION));

                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $nombre_unico = 'perfil_' . $user_id . '_' . time() . '.' . $ext;
                        $ruta_destino = __DIR__ . '/../../frontend/IMG/' . $nombre_unico;

                        if (move_uploaded_file($tmp_foto, $ruta_destino)) {
                            $u['FotoPerfil'] = $nombre_unico;
                        }
                    }
                }
                break;
            }
        }

        // Guardar cambios en db.json
        $data['Usuarios'] = $usuarios;
        file_put_contents(__DIR__ . '/../../data/db.json', json_encode($data, JSON_PRETTY_PRINT));

        // Actualizar sesión
        $_SESSION['nombre'] = $nuevo_nombre;
        $_SESSION['email'] = $nuevo_correo;

        $mensaje = 'Perfil actualizado correctamente.';
    } else {
        $mensaje = 'Nombre y correo son obligatorios.';
    }
}

// Obtener la ruta de la foto de perfil
$foto_perfil = $usuario['FotoPerfil'] ?? 'imagenPerfilRedonda.png';
$ruta_foto = '../../frontend/IMG/' . $foto_perfil;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - TaskLink</title>
    <link rel="stylesheet" href="../../frontend/HTML/CSS/index.css">
</head>

<body>

    <!-- Header (igual que en index.php) -->
    <header>
        <div class="logo-container">
            <img src="../../frontend/IMG/logo.png" alt="TaskLink Logo" class="logo-icon">
        </div>

        <div class="right-header">
            <!-- Botón de volver atrás -->
            <a href="../../frontend/index.php" style="margin-right: 20px; color: #007BFF; text-decoration: none; font-weight: 500;">← Volver</a>
        </div>
    </header>

    <!-- Contenido del perfil -->
    <section class="hero" style="background-color: #f5f7fa; padding: 30px 20px;">
        <h2>Mi Perfil</h2>

        <?php if ($mensaje): ?>
            <div class="mensaje mensaje-exito" style="padding: 12px 16px; margin-bottom: 20px; border-radius: 6px; font-weight: 500; background-color: #e8f5e9; color: #2e7d32; border-left: 4px solid #4caf50;">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <div class="profile-container" style="max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <!-- Contenedor centrado para foto y botón -->
            <div style="text-align: center; padding: 20px; background: #f9f9f9; border-radius: 10px; margin-bottom: 20px; border: 1px solid #ddd;">
                <img id="preview-foto" src="<?= htmlspecialchars($ruta_foto) ?>" alt="Foto de perfil" class="profile-pic" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #007BFF; margin-bottom: 10px;">
                <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" style="display: none;" onchange="previewImage(event)">
                <button type="button" onclick="document.getElementById('foto_perfil').click()" style="background-color: #007BFF; color: white; border: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; cursor: pointer; min-width: 100px; text-align: center;">
                    Cambiar Foto
                </button>
            </div>

            <h3><?= htmlspecialchars($usuario['Nombre']) ?></h3>

            <!-- Formulario estilizado -->
            <form method="POST" enctype="multipart/form-data" style="margin-top: 20px; display: flex; flex-direction: column; gap: 15px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="nombre" style="font-weight: 500; color: #555; min-width: 100px;">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($usuario['Nombre']) ?>" 
                        style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; background: white;"
                        required>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="correo" style="font-weight: 500; color: #555; min-width: 100px;">Correo:</label>
                    <input type="email" name="correo" id="correo" value="<?= htmlspecialchars($usuario['Correo']) ?>" 
                        style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; background: white;"
                        required>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="telefono" style="font-weight: 500; color: #555; min-width: 100px;">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono" value="<?= htmlspecialchars($usuario['Telefono'] ?? '') ?>" 
                        style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; background: white;">
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="direccion" style="font-weight: 500; color: #555; min-width: 100px;">Dirección:</label>
                    <input type="text" name="direccion" id="direccion" value="<?= htmlspecialchars($usuario['Direccion'] ?? '') ?>" 
                        style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; background: white;">
                </div>

                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <label for="biografia" style="font-weight: 500; color: #555; min-width: 100px;">Biografía:</label>
                    <textarea name="biografia" id="biografia" rows="3" style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; background: white; resize: vertical;"><?= htmlspecialchars($usuario['Biografia'] ?? '') ?></textarea>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <label for="contrasena" style="font-weight: 500; color: #555; min-width: 100px;">Nueva Contraseña:</label>
                    <input type="password" name="contrasena" id="contrasena" placeholder="Dejar vacío para mantener la actual" 
                        style="flex: 1; padding: 10px 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 15px; background: white;">
                </div>

                <button type="submit" 
                        style="background-color: #007BFF; color: white; border: none; padding: 12px; border-radius: 6px; font-size: 16px; cursor: pointer; margin-top: 10px; width: 100%; max-width: 200px; align-self: center;">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        Español (España) © 2025 TaskLink from Alex&Gabi
    </footer>
</body>
</html>