<!DOCTYPE html>
<html lang="en">

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
            <div class="contenedorTitulo">
                <span>Titulo</span><br>
                <input class="titulo" type="text" alt="Titulo">
            </div>
            <div class="contenedorNombre">
                <span>Nombre Usuario</span><br>
                <input class="nombre" type="text" alt="Nombre Usuario">
            </div>
            <div class="contenedorCorreo">
                <span>Correo Electrónico</span><br>
                <input class="correo" type="email" alt="Correo Usuario">
            </div>
            <div class="contenedorMensaje">
                <span>Mensaje</span><br>
                <textarea class="mensaje" name="mensaje" rows="6" cols="85" placeholder="Escriba su mensaje"></textarea>
            </div>
            <div class="contenedorEnviar">
                <button class="btnEnviar">Enviar</button>
            </div>
        </form>
    </div>


</body>

</html>