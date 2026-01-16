<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $idServicio = $_POST['id_servicio'];
    $user_id = $_SESSION['user_id'];
    $puntuacion = (int)$_POST['rating'];
    $comentarioTexto = htmlspecialchars($_POST['comentario']);
    
    // Cargamos los datos actuales
    $data = json_get_data('db.json');
    $comentarios = $data['Comentarios'] ?? [];

    // --- NUEVA VALIDACIÓN: COMPROBAR SI YA EXISTE ---
    foreach ($comentarios as $c) {
        if ($c['IDUsuario'] === $user_id && $c['IDServicio'] === $idServicio) {
            // El usuario ya ha comentado, lo mandamos de vuelta con un aviso
            header("Location: producto.php?id=" . $idServicio . "&error=ya_valorado");
            exit;
        }
    }

    // Creamos el nuevo objeto comentario
    $nuevoComentario = [
        "IDComentario" => uniqid(),
        "IDServicio" => $idServicio,
        "IDUsuario" => $_SESSION['user_id'],
        "NombreUsuario" => $_SESSION['nombre'] ?? 'Usuario',
        "Puntuacion" => $puntuacion,
        "Comentario" => $comentarioTexto,
        "Fecha" => date('d/m/Y')
    ];

    // Añadimos y guardamos
    $comentarios[] = $nuevoComentario;
    $data['Comentarios'] = $comentarios;

    if (json_save_data('db.json', $data)) {
        // Volvemos a la página del producto
        header("Location: producto.php?id=" . $idServicio);
        exit;
    } else {
        echo "Error crítico: No se pudo escribir en db.json";
    }
} else {
    header("Location: ../../index.php");
}