<?php
session_start();
require_once __DIR__ . '/../includes/json_connect.php';

// 1. Verificar sesión (Si no está logueado, redirigir al login)
$user_id_sesion = $_SESSION['user_id'] ?? $_COOKIE['user_id'] ?? null;
if (!$user_id_sesion) {
    header('Location: login.php?id=' . ($_GET['id'] ?? ''));
    exit;
}

// 2. Obtener datos del servicio actual
$id_servicio = $_GET['id'] ?? null;
if (!$id_servicio) {
    header('Location: ../../frontend/index.php');
    exit;
}

$data = json_get_data('db.json');
$servicio = null;
foreach ($data['Servicio'] as $s) {
    if ($s['IDServicio'] === (string)$id_servicio) {
        $servicio = $s;
        break;
    }
}

if (!$servicio) { 
    echo "Servicio no encontrado."; 
    exit; 
}

// 3. Cálculos de precio
$precio_base = (float)$servicio['Precio'];
$comision = 4.50;
$total = $precio_base + $comision;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar y pagar - TaskLink</title>
    <!-- CSS General y específico -->
    <link rel="stylesheet" href="../../frontend/HTML/CSS/index.css">
    <link rel="stylesheet" href="../../frontend/HTML/CSS/payment.css">
</head>
<body>

<header class="header-simple">
    <div class="logo-container">
        <a href="../../frontend/index.php">
            <img src="../../frontend/IMG/logo.png" alt="TaskLink Logo" class="logo-icon">
        </a>
    </div>
    <div class="header-title">Confirmar y pagar</div>
</header>

<main class="container-payment">
    <div class="payment-layout">
        
        <!-- COLUMNA IZQUIERDA: FORMULARIO DE PAGO -->
        <section class="payment-details">
            <div class="section-block">
                <h3>Tu Servicio</h3>
                <div class="info-row">
                    <div>
                        <strong>Fecha estimada</strong>
                        <p><?= date('d/m/Y') ?> al <?= date('d/m/Y', strtotime('+'.$servicio['DuracionEstimada'].' days')) ?></p>
                    </div>
                    <a href="producto.php?id=<?= $id_servicio ?>" class="edit-btn">Editar</a>
                </div>
            </div>

            <hr class="divider">

            <div class="section-block">
                <h3>Pagar con</h3>
                <div class="payment-methods-container">
                    
                    <!-- Opción 1: Tarjeta -->
                    <div class="method-item active">
                        <div class="method-header">
                            <label>
                                <input type="radio" name="payment" value="card" checked>
                                <span>Tarjeta de crédito o débito</span>
                            </label>
                            <div class="method-icons">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard">
                            </div>
                        </div>
                        <div class="card-inputs">
                            <input type="text" placeholder="Número de tarjeta" class="full-width">
                            <div class="input-row">
                                <input type="text" placeholder="Caducidad (MM/AA)">
                                <input type="text" placeholder="CVV">
                            </div>
                            <input type="text" placeholder="Código postal" class="full-width">
                        </div>
                    </div>

                    <!-- Opción 2: PayPal -->
                    <div class="method-item">
                        <div class="method-header">
                            <label>
                                <input type="radio" name="payment" value="paypal">
                                <span>PayPal</span>
                            </label>
                            <div class="method-icons">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal">
                            </div>
                        </div>
                    </div>

                    <!-- Opción 3: Bizum -->
                    <div class="method-item">
                        <div class="method-header">
                            <label>
                                <input type="radio" name="payment" value="bizum">
                                <span>Bizum</span>
                            </label>
                            <div class="method-icons">
                                <img src="../../frontend/IMG/Bizum.png" alt="Bizum">
                            </div>
                        </div>
                    </div>

                    <!-- Opción 4: Google Pay -->
                    <div class="method-item">
                        <div class="method-header">
                            <label>
                                <input type="radio" name="payment" value="mobile_pay">
                                <span>Google Pay</span>
                            </label>
                            <div class="method-icons">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Google_Pay_Logo.svg" alt="Google Pay">
                            </div>
                        </div>
                    </div>

                    <!-- Opción 5: Apple Pay -->
                    <div class="method-item">
                        <div class="method-header">
                            <label>
                                <input type="radio" name="payment" value="mobile_pay">
                                <span>Apple Pay</span>
                            </label>
                            <div class="method-icons">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/b/b0/Apple_Pay_logo.svg" alt="Apple Pay">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <hr class="divider">

            <div class="section-block">
                <h3>Política de cancelación</h3>
                <p class="text-small">Cancelación gratuita durante las primeras 24 horas. Si el profesional ya ha comenzado la tarea, la comisión de servicio no será reembolsable.</p>
            </div>

            <button class="btn-confirm-pay">Confirmar y pagar</button>
        </section>

        <!-- COLUMNA DERECHA: RESUMEN DEL PEDIDO -->
        <aside class="payment-summary">
            <div class="summary-card">
                <div class="service-preview">
                    <!-- Imagen pequeña del servicio -->
                    <img src="../../frontend/IMG/image<?= $servicio['IDCategoria'] ?>.jpg" alt="Servicio">
                    <div class="preview-text">
                        <span class="category-small">Garantía TaskLink</span>
                        <p class="service-name"><?= htmlspecialchars($servicio['Nombre']) ?></p>
                        <span class="rating">★ 4.90 (Reseñas verificadas)</span>
                    </div>
                </div>

                <hr class="divider">

                <h3>Detalles del precio</h3>
                <div class="price-row">
                    <span>Precio del servicio</span>
                    <span><?= number_format($precio_base, 2) ?>€</span>
                </div>
                <div class="price-row">
                    <span>Gastos de gestión</span>
                    <span><?= number_format($comision, 2) ?>€</span>
                </div>

                <hr class="divider">

                <div class="price-row total">
                    <span>Total (EUR)</span>
                    <span><?= number_format($total, 2) ?>€</span>
                </div>
            </div>
        </aside>

    </div>
</main>

<footer class="footer-simple">
    <p>© 2025 TaskLink · Privacidad · Condiciones · Ayuda</p>
</footer>

</body>
</html>