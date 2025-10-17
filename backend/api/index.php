<?php
$nom = "";
$email = "";
$telefon = "";
$consentimiento = false;
$cicle = "";
$errores = [];


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $nom = $_POST["nom"] ?? "";
    $email = $_POST["email"] ?? "";
    $telefon = $_POST["telefon"] ?? "";
    $consentimiento = $_POST["consentimiento"] ?? "";
    $cicle = $_POST["cicle"] ?? "";



    if (empty($nombre)) {
        $errores["errorNombre"] = "No introducido ningún nombre";
    }

    if (empty($email)) {
        $errores["errorEmail"] = "No introducido ningún Email o este es incorrecto";
    }

    if (empty($telefon) && strlen(trim($telefon)) !== 9) {
        $errores["errorTelefon"] = "Telefono que ha introducido es incorrecto";
    }

    if (empty($cicle)) {
        $errores["erroreCicle"] = "Ciclo Introducido es nulo";
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf8">
    <title>Proyecto Intermodular</title>
    <meta name="description" content="Ejercicio de Javascript">
    <meta name="author" content="Gabi y Alex">
    <meta name="keywords" content="Venta, Articulos, e-commerce">
    <link href="../../frontend/HTML/CSS/index.css" rel="stylesheet" type="text/css">
</head>
</head>

<body>
    <h1>Pagina Contactos</h1>

    <label><strong>Nom:</strong></label>
    <input type="text" id="nom" name="nom" required><br><br>

    <?php if ((strlen(htmlspecialchars($errores["errorNombre"])) > 0)): ?>
        <p id="datosIncorrectos"><?php htmlspecialchars($errores["errorNombre"]) ?></p><br>
    <?php endif; ?>

    <label><strong>Email: </label>
    <input type="email" id="email" name="email" required><br><br>

    <?php if ((strlen(htmlspecialchars($errores["errorEmail"])) > 0)): ?>
        <p id="datosIncorrectos"><?php htmlspecialchars($errores["errorEmail"]) ?></p><br>
    <?php endif; ?>

    <label for="cicle"><strong>Cicle Formatiu</strong> </label>
    <select id="cicle" name="cicle">
        <option vlaue="ASIX"></option>
        <option vlaue="DAM"></option>
        <option vlaue="DAW"></option>
    </select><br>
    <p>

        <?php if ((strlen(htmlspecialchars($errores["errorCicle"])) > 0)): ?>
        <p id="datosIncorrectos"><?php htmlspecialchars($errores["errorCicle"]) ?></p><br>
    <?php endif; ?>

    <label><strong>Telefon:</strong></label>
    <input type="number" id="telefon" name="telefon" required><br><br>

    <?php if ((strlen(htmlspecialchars($errores["errorTelefon"])) > 0)): ?>
        <p id="datosIncorrectos"><?php htmlspecialchars($errores["errorTelefon"]) ?></p><br>
    <?php endif; ?>

    <label><strong>Al enviar este formulario, aceptas el tratamiento de tus datos personales conforme a nuestra Política
            de Privacidad.</strong></label>
    <input type="checkbox" id="consentimiento" name="consentimiento" required><br>

    <input href="../../frontend/HTML/index.html" type="submit" id="button" name="button" value="Volver">
</body>
</form>
</body>

</html>