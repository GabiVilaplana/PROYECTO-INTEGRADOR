<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';

$infoMensaje = "";

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head><meta charset="UTF-8"><title>No autorizado</title></head>
    <body style="font-family:Arial,sans-serif; text-align:center; margin-top:50px;">
    <h2>⚠️ Debes iniciar sesión para crear un servicio</h2>
    <a href="../login.php">Ir al login</a>
    </body>
    </html>';
    exit;
}

// Usuario logueado
$usuarioID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $duracion = trim($_POST['duracion'] ?? '');
    $unidadDuracion = trim($_POST['unidad_duracion'] ?? '');
    $precio = trim($_POST['precio'] ?? '');
    $idCategoria = trim($_POST['id_categoria'] ?? '');
    $imagen = $_FILES['imagen']['name'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($descripcion) || empty($duracion) || empty($unidadDuracion) || empty($precio) || empty($idCategoria)) {
        $infoMensaje = "⚠️ Todos los campos son obligatorios.";
    } elseif (!is_numeric($precio) || $precio < 0) {
        $infoMensaje = "⚠️ El precio debe ser un número positivo.";
    } else {

        $data = json_get_data('db.json') ?? ['Servicios' => []];
        $servicios = $data['Servicios'] ?? [];

        // Generar ID autoincremental
        $ultimoID = 1000;
        foreach ($servicios as $ser) {
            if (isset($ser['IDServicio']) && is_numeric($ser['IDServicio'])) {
                $ultimoID = max($ultimoID, (int)$ser['IDServicio']);
            }
        }
        $nuevoID = $ultimoID + 1;

        // Procesar imagen
        if (!empty($imagen) && is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $rutaDestino = '../../frontend/IMG/servicios/' . basename($imagen);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino);
        } else {
            $rutaDestino = '../../frontend/IMG/categorias/default.png'; // imagen por defecto
        }

        // Crear servicio
        $nuevoServicio = [
            "IDServicio" => (string)$nuevoID,
            "Nombre" => $nombre,
            "FechaCreacion" => date('Y-m-d H:i:s'),
            "Descripcion" => $descripcion,
            "DuracionEstimada" => $duracion,
            "UnidadDuracion" => $unidadDuracion,
            "Precio" => number_format((float)$precio, 2, '.', ''),
            "IDCategoria" => $idCategoria,
            "IDUsuarioCreacion" => $usuarioID,
            "IDImagen" => $rutaDestino,
            "Estado" => "activo"
        ];

        $servicios[] = $nuevoServicio;
        $data['Servicios'] = $servicios;

        if (json_save_data('db.json', $data)) {
            header('Location: ./servicios.php');
            exit;
        } else {
            $infoMensaje = "❌ Error al guardar el servicio. Inténtalo más tarde.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../../frontend/HTML/CSS/contacto.css" type="text/css" />
<title>Crear Servicio - TaskLink</title>
</head>
<body>
<div class="contenedor">
    <aside class="sidebar">
        <section class="opcionesContacto">
            <div class="OtrosContactos">
                <a href="../../frontend/index.php" class="btn-volver">← Volver</a>
                <h3>Opciones</h3>
            </div>
        </section>
    </aside>

    <form method="POST" enctype="multipart/form-data" class="formulario">
        <div class="contactoh2">
            <img src="../../frontend/IMG/logo.png" alt="Logo">
            <h2>Crear Servicio</h2>
        </div>

        <div class="contenedorNombre">
            <span>Nombre del Servicio</span><br>
            <input class="nombre" type="text" name="nombre" required>
        </div>

        <div class="contenedorMensaje">
            <span>Descripción</span><br>
            <textarea class="mensaje" name="descripcion" rows="6" placeholder="Describe tu servicio" required></textarea>
        </div>

        <div class="contenedorDuracion">
            <span>Duración Estimada</span><br>
            <input type="number" name="duracion" min="1" style="width:30%;" required>
            <select name="unidad_duracion" style="width:65%;" required>
                <option value="días">Días</option>
                <option value="semanas">Semanas</option>
                <option value="años">Años</option>
            </select>
        </div>

        <div class="contenedorPrecio">
            <span>Precio (€)</span><br>
            <input type="number" step="0.01" min="0" name="precio" required>
        </div>

        <div class="contenedorCategoria">
            <span>Categoría</span><br>
            <select name="id_categoria" required>
                <option value="1">Diseño</option>
                <option value="2">Desarrollo</option>
                <option value="3">Marketing</option>
            </select>
        </div>

        <div class="contenedorImagen">
            <span>Imagen (opcional)</span><br>
            <input type="file" name="imagen" accept="image/*">
        </div>

        <div class="contenedorEnviar">
            <button class="btnEnviar">Crear Servicio</button>
        </div>

        <?php if($infoMensaje): ?>
            <p class="mensaje-info"><?= $infoMensaje ?></p>
        <?php endif; ?>
    </form>
</div>
</body>
</html>
