<?php
// controller.php

// Inicializamos variables para resultados y errores
$errors = [];
$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanear el JSON ingresado
    $jsonInput = htmlspecialchars(trim($_POST['jsonInput']));

// controller.php

// Inicializamos variables para resultados y errores
    $errors = [];
    $resultado = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener y sanear el JSON ingresado
        $jsonInput = htmlspecialchars(trim($_POST['jsonInput']));

