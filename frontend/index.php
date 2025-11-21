<?php 
session_start();
require_once __DIR__ . '/../backend/includes/json_connect.php';

$user_id = $_COOKIE['user_id'] ?? $_SESSION['user_id'] ?? null;
$usuario = null;
if ($user_id) {
    $data = json_get_data('db.json');
    $usuarios = $data['Usuarios'] ?? [];

    foreach ($usuarios as $u) {
        // Comparació estricta de strings (IDUsuario és string!)
        if (isset($u['IDUsuario']) && $u['IDUsuario'] === $user_id) {
            $usuario = $u;
            
            // Sincronitzar sessió (per coherència)
            $_SESSION['user_id'] = $u['IDUsuario'];
            $_SESSION['email'] = $u['Correo'];
            $_SESSION['nombre'] = $u['Nombre'];
            break;
        }
    }
}

if ($user_id && !$usuario) {
    header('Location: logout.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TaskLink</title>
    <link rel="stylesheet" href="./HTML/CSS/index.css">
    
</head>

<body>

    <header>
        <div class="logo-container">
            <img src="./IMG/logo.png" alt="TaskLink Logo" class="logo-icon">
        </div>

        <div class="right-header">
            <span class="texto-servicios"><?=  htmlspecialchars($_SESSION["nombre"])?></span>
            <div class="icono-perfil" onclick="toggleDropdown()">
                <img src="./IMG/imagenPerfilRedonda.png" class="profile-icon"></div>
            </div>
        </div>

        <div id="user-dropdown" class="user-dropdown">
        <ul class="dropdown-menu">
            <h2>Mi cuenta</h2>
            <li class="divider"><hr></li>
            <li><a href="../backend/auth/profile.php"><span>👤</span> Perfil</a></li>
            <li><a href="like.php"><span>❤️</span> Favoritos</a></li>
            <li><a href="mensajes.php"><span>💬</span> Mensajes</a></li>
            <li class="divider"><hr></li>
            <li><a href="configuracion.php"><span>⚙️</span> Configuración de la cuenta</a></li>
            <li><a href="moneda.php"><span>🌐</span> Idiomas y moneda</a></li>
            <li><a href="ayuda.php"><span>❓</span> Centro de ayuda</a></li>
        </ul>
    </div>
    </header>

    <section class="hero">
        <h2>Conecta el talento con las oportunidades</h2>
        <div class="search-container">
            <div class="search-bar">
                <input type="text" placeholder="¿Que servicio necesitas?">
                <button>Buscar</button>
            </div>
        </div>
    </section>

    <div class="container">
        <h2>OFERTAS ACTUALES</h2>
        <section class="courses">
            <div class="course">
                <img src="./IMG/image1.png" alt="Diseñador">
                <h3>Diseñador</h3>
                <p>Busco diseñador gráfico para proyecto web.</p>
                <div class="course-footer">
                    <span class="price">8€</span>
                    <button>Reservar</button>
                </div>
            </div>
            <div class="course">
                <img src="./IMG/img2.png" alt="Limpieza">
                <h3>Limpieza</h3>
                <p>Se necesita personal para limpieza de oficinas.</p>
                <div class="course-footer">
                    <span class="price">6€/h</span>
                    <button>Reservar</button>
                </div>
            </div>
            <div class="course">
                <img src="./IMG/img3.png" alt="Diseñador">
                <h3>Diseñador</h3>
                <p>Busco diseñador gráfico para proyecto web.</p>
                <div class="course-footer">
                    <span class="price">47€/h</span>
                    <button>Reservar</button>
                </div>
            </div>
        </section>

        <h3>Servicios destacados</h3>
        <section class="courses">
            <div class="course">
                <img src="./IMG/img4.png" alt="Peluquero">
                <h3>Peluquero</h3>
                <p>Peluquero 24h pide cita cuando mas falta te haga.</p>
                <div class="course-footer">
                    <span class="price">15€</span>
                    <button>Reservar</button>
                </div>
            </div>
            <div class="course">
                <img src="./IMG/img5.png" alt="Profesor">
                <h3>Profesor</h3>
                <p>Profesor particular para cualquier tipo de asignatura.</p>
                <div class="course-footer">
                    <span class="price">Negociable</span>
                    <button>Reservar</button>
                </div>
            </div>
            <div class="course">
                <img src="./IMG/img6.png" alt="Mecanico">
                <h3>Mecánico</h3>
                <p>Mecánico con mucha experiéncia para todas las necesidades.</p>
                <div class="course-footer">
                    <span class="price">50€/h</span>
                    <button>Reservar</button>
                </div>
            </div>
        </section>

        <h3>¡Tendencias ahora!</h3>
        <p><i>Proximamente...</i></p>
    </div>
    <footer>
        Español (España) © 2025 TaskLink from Alex&Gabi
    </footer>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            const isActive = dropdown.classList.contains('active');

            // Cerrar todos los dropdowns primero
            document.querySelectorAll('.user-dropdown').forEach(el => {
                el.classList.remove('active');
            });

            // Si no estaba activo, abrirlo
            if (!isActive) {
                dropdown.classList.add('active');
                
                // Cerrar al hacer clic fuera
                document.addEventListener('click', closeDropdownOnClickOutside);
            }
        }

        function closeDropdownOnClickOutside(event) {
            const dropdown = document.getElementById('user-dropdown');
            const profileIcon = document.querySelector('.icono-perfil');

            // Si el clic fue fuera del dropdown Y fuera del ícono de perfil → cerrar
            if (!dropdown.contains(event.target) && !profileIcon.contains(event.target)) {
                dropdown.classList.remove('active');
                document.removeEventListener('click', closeDropdownOnClickOutside);
            }
        }
</script>
</body>
</html>