<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php'; // Ajusta la ruta según tu estructura

// 🔍 Identificar al usuario
$user_id = $_COOKIE['user_id'] ?? $_SESSION['user_id'] ?? null;
$usuario = null;

if ($user_id) {
    $data = json_get_data('db.json');
    $usuarios = $data['Usuarios'] ?? [];

    foreach ($usuarios as $u) {
        if (isset($u['IDUsuario']) && $u['IDUsuario'] === $user_id) {
            $usuario = $u;
            break;
        }
    }
}

// ❌ Si no hay usuario, redirigir al login
if (!$user_id || !$usuario) {
    header('Location: ./login.php');
    exit;
}

$mensaje = '';

// ✏️ Actualizar datos si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');

    // Validaciones básicas
    if (empty($nombre)) {
        $mensaje = "⚠️ El nombre es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "⚠️ El correo no es válido.";
    } else {
        // Verificar si el nuevo correo ya está en uso por otro usuario
        $correo_existe = false;
        foreach ($usuarios as $u) {
            if ($u['Correo'] === $correo && $u['IDUsuario'] !== $user_id) {
                $correo_existe = true;
                break;
            }
        }

        if ($correo_existe) {
            $mensaje = "❌ Ya existe una cuenta con este correo.";
        } else {
            // Actualizar datos en el array
            $usuario['Nombre'] = $nombre;
            $usuario['Telefono'] = $telefono;
            $usuario['Correo'] = $correo;

            // Buscar índice y actualizar en el array general
            foreach ($usuarios as $index => $u) {
                if ($u['IDUsuario'] === $user_id) {
                    $usuarios[$index] = $usuario;
                    break;
                }
            }

            // Guardar cambios
            $data['Usuarios'] = $usuarios;
            if (json_save_data('db.json', $data)) {
                $mensaje = "✅ Datos actualizados correctamente.";
                
                // Actualizar sesión
                $_SESSION['nombre'] = $nombre;
                $_SESSION['email'] = $correo;
            } else {
                $mensaje = "❌ Error al guardar los datos.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario - TaskLink</title>
    <link rel="stylesheet" href="../../frontend/HTML/CSS/profile.css">
</head>
<body>

    <header>
        <h1>Mi Perfil</h1>
        <nav>
            <a href="../../frontend/index.php">← Volver al inicio</a>
        </nav>
    </header>

    <main class="profile-container">
        <?php if ($mensaje): ?>
            <div class="mensaje <?= str_contains($mensaje, '✅') ? 'mensaje-exito' : 'mensaje-error' ?>">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="profile-form">
            <h2>Datos personales</h2>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['Nombre']) ?>" required>

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($usuario['Correo']) ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['Telefono']) ?>">

            <label for="id">ID de usuario:</label>
            <input type="text" id="id" value="<?= htmlspecialchars($usuario['IDUsuario']) ?>" disabled>

            <label for="valoracion">Valoración:</label>
            <input type="text" id="valoracion" value="<?= $usuario['Valoracion'] ? htmlspecialchars($usuario['Valoracion']) . ' ⭐' : 'Sin valoración' ?>" disabled>

            <button type="submit">Actualizar Datos</button>
        </form>
    </main>

</body>
</html>