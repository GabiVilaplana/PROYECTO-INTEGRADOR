<?php
$nom = "";
$email = "";
$telefon = "";
$consentimiento = false;
$cicle = "";
$errores = [];


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $nom = $_POST["nom"]?? "";
    $email = $_POST["email"]?? "";
    $telefon = $_POST["telefon"]?? "";
    $consentimiento = $_POST["consentimiento"]?? "";
    $cicle = $_POST["cicle"] ?? "";



    if (empty($nombre)) {
        $errores["errorNombre"] = "No introducido ningún nombre";
    }

    if (empty($email)) {
        $errores["errorEmail"] = "No introducido ningún Email o este es incorrecto";
    }

    if (empty($telefon)) {
        $errores["errorTelefon"] = "Telefono que ha introducido es incorrecto";
    }

    if (empty($cicle)) {
        $errores["erroreCicle"] = "Ciclo Introducido es nulo";
    }

}
?>