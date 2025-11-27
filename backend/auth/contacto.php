<?php
session_start();

require_once __DIR__ . '/../includes/json_connect.php';

$infoMensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['correo'] ?? '');
    $asunto = trim($_POST['titulo'] ?? '');
    $nombreUsuario = trim($_POST['nombre'] ?? '');
    $mensaje = trim($_POST['mensaje'] ?? '');


    if (empty($email) || empty($asunto) || empty($mensaje) || empty($nombreUsuario)) {
        $infoMensaje = "⚠️ Todos los campos son obligatorios.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $infoMensaje = "⚠️ El correo electrónico no es válido.";
    } else {

        $data = json_get_data('db.json')?? ['Mensajes' => []];
        $mensajes = $data['Mensajes'] ?? [];

            $ultimID = 2000;
            foreach ($mensajes as $men) {
                if (isset($men['idMensaje']) && is_numeric($men['idMensaje'])) {
                    $ultimID = max($ultimID, (int) $men['idMensaje']);
                }

            }

            $nouID = $ultimID + 1;
            $nuevoMensaje = [
                "idMensaje" => (string) $nouID,
                "nombre" => $nombreUsuario,
                "email" => $email,
                "asunto" => $asunto,
                "mensaje" => $mensaje,
                "fecha_envio" => date('Y-m-d H:i:s'),
                "leido" => 0
            ];

            $mensajes[] = $nuevoMensaje;
            $data["Mensajes"]=$mensajes;


            if(json_save_data('db.json', $data)){
                header('Location: ./contacto.php');
                exit;
            }else{
                $infoMensaje ="❌ Error al guardar los datos. Inténtalo más tarde.";
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
    <title>Pagina Contacto TaskLink</title>
</head>

<body>
    <div class="contenedor">
        <aside class="sidebar">
            <section class="opcionesContacto">
                <div class="OtrosContactos">
                    <a href="../../frontend/index.php" class="btn-volver">← Volver</a>
                    <h3>Otras Formas de Contacto</h3>
                </div>
                <div class="contactoInsta" id="contacto">
                    <img src="../../frontend/IMG/Instagram.png">
                    <span>Instagram</span>
                </div>
                <div class="contactoFace" id="contacto">
                    <img src="../../frontend/IMG/facebook.png">
                    <span>Facebook</span>
                </div>
                <div class="contactoWhats" id="contacto">
                    <img src="../../frontend/IMG/WhatsApp.png">
                    <span>WhatsApp</span>
                </div>
                <div class="contactoTel" id="contacto">
                    <img src="../../frontend/IMG/llamada.png">
                    <span>Telefono</span>
                </div>
            </section>
        </aside>

        <form method="POST" class="formulario">
            <div class="contactoh2">
                <img src="../../frontend/IMG/logo.png">
                <h2>Contactanos</h2>
            </div>
            <div class="contenedorNombre">
                <span>Nombre Usuario</span><br>
                <input class="nombre" type="text" name="nombre" alt="Nombre Usuario" required>
            </div>
            <div class="contenedorCorreo">
                <span>Correo Electrónico</span><br>
                <input class="correo" type="email" name="correo" alt="Correo Usuario" required>
            </div>
            <div class="contenedorTitulo">
                <span>Asunto</span><br>
                <input class="titulo" type="text" name="titulo" alt="Titulo" required>
            </div>
            <div class="contenedorMensaje">
                <span>Mensaje</span><br>
                <textarea class="mensaje" name="mensaje" rows="6" cols="85" placeholder="Escriba su mensaje" required></textarea>
            </div>
            <div class="contenedorEnviar">
                <button class="btnEnviar">Enviar</button>
            </div>
        </form>
    </div>


</body>

</html>