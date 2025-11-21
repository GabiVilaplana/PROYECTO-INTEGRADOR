<?php
$nom = "";
$email = "";
$telefon = "";
$consentimiento = false;
$cicle = "";
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $nom = trim($_POST["nom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefon = trim($_POST["telefon"] ?? "");
    $consentimiento = !empty($_POST["consentimiento"]); // checkbox: true si marcado
    $cicle = trim($_POST["cicle"] ?? "");

    if (empty($nom)) {
        $errores["errorNombre"] = "No introducido ningún nombre";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores["errorEmail"] = "No introducido ningún Email o este es incorrecto";
    }

    if (empty($telefon) || strlen($telefon) !== 9 || !ctype_digit($telefon)) {
        $errores["errorTelefon"] = "Teléfono que ha introducido es incorrecto";
    }

    if (empty($cicle)) {
        $errores["errorCicle"] = "Ciclo introducido es nulo";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Proyecto Intermodular</title>
    <link href="../../frontend/HTML/CSS/index.css" rel="stylesheet" type="text/css">
</head>
<body>
    <h1>Página Contactos</h1>

    <form method="POST" action="">
        <label><strong>Nom:</strong></label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>" required><br><br>

        <?php if (!empty($errores["errorNombre"])): ?>
            <p class="datosIncorrectos"><?php echo htmlspecialchars($errores["errorNombre"]); ?></p><br>
        <?php endif; ?>

        <label><strong>Email:</strong></label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

        <?php if (!empty($errores["errorEmail"])): ?>
            <p class="datosIncorrectos"><?php echo htmlspecialchars($errores["errorEmail"]); ?></p><br>
        <?php endif; ?>

        <label for="cicle"><strong>Cicle Formatiu:</strong></label>
        <select id="cicle" name="cicle">
            <option value="">-- Selecciona --</option>
            <option value="ASIX" <?php echo ($cicle === 'ASIX') ? 'selected' : ''; ?>>ASIX</option>
            <option value="DAM" <?php echo ($cicle === 'DAM') ? 'selected' : ''; ?>>DAM</option>
            <option value="DAW" <?php echo ($cicle === 'DAW') ? 'selected' : ''; ?>>DAW</option>
        </select><br><br>

        <?php if (!empty($errores["errorCicle"])): ?>
            <p class="datosIncorrectos"><?php echo htmlspecialchars($errores["errorCicle"]); ?></p><br>
        <?php endif; ?>

        <label><strong>Telèfon:</strong></label>
        <input type="tel" id="telefon" name="telefon" value="<?php echo htmlspecialchars($telefon); ?>" required><br><br>

        <?php if (!empty($errores["errorTelefon"])): ?>
            <p class="datosIncorrectos"><?php echo htmlspecialchars($errores["errorTelefon"]); ?></p><br>
        <?php endif; ?>

        <label>
            <input type="checkbox" id="consentimiento" name="consentimiento" <?php echo $consentimiento ? 'checked' : ''; ?> required>
            Al enviar este formulario, aceptas el tratamiento de tus datos personales conforme a nuestra Política de Privacidad.
        </label><br><br>

        <a href="../../frontend/HTML/index.html">Volver</a>
    </form>
</body>
</html>