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

        // Validar si hay entrada de JSON
        if (empty($jsonInput)) {
            $errors[] = "El campo JSON no puede estar vacío.";
        } else {
            // Decodificar JSON y verificar si es válido
            $data = json_decode($jsonInput, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $errors[] = "El texto ingresado no es un JSON válido.";
            } else {
                // Validar estructura del JSON
                if (is_array($data) && validateJsonStructure($data)) {
                    // Procesar el JSON y calcular los resultados
                    $resultado = processGrades($data);
                } else {
                    $errors[] = "El JSON no sigue el formato requerido.";
                }
            }
        }
    }

