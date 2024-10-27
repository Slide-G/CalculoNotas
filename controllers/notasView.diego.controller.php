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
    function validateJsonStructure($data)
    {
        foreach ($data as $module) {
            if (!isset($module['nombre']) || !isset($module['alumnos']) || !is_array($module['alumnos'])) {
                return false;
            }
            foreach ($module['alumnos'] as $student) {
                if (!isset($student['nombre']) || !isset($student['notas']) || !is_array($student['notas'])) {
                    return false;
                }
            }
        }
        return true;
    }
    // Función para procesar notas y calcular los resultados
    function processGrades($data) {
        $result = [];
        // Inicializamos contadores y variables
        $approvedStudents = [];
        $failedStudents = [];
        $promotedStudents = [];
        $notPromotedStudents = [];

        foreach ($data as $module) {
            $moduleName = $module['nombre'];
            $result[$moduleName] = [
                'nota_media' => 0,
                'num_suspensos' => 0,
                'num_aprobados' => 0,
                'nota_max' => 0,
                'mejor_alumno' => '',
                'nota_min' => PHP_INT_MAX,
                'peor_alumno' => '',
            ];

            $totalGrades = 0;
            $totalScore = 0;

            foreach ($module['alumnos'] as $student) {
                $studentName = $student['nombre'];
                $grades = $student['notas'];
                $totalGrades += count($grades);
                $totalScore += array_sum($grades);

                $maxGrade = max($grades);
                $minGrade = min($grades);
                $avgGrade = array_sum($grades) / count($grades);

                if ($maxGrade > $result[$moduleName]['nota_max']) {
                    $result[$moduleName]['nota_max'] = $maxGrade;
                    $result[$moduleName]['mejor_alumno'] = $studentName;
                }
                if ($minGrade < $result[$moduleName]['nota_min']) {
                    $result[$moduleName]['nota_min'] = $minGrade;
                    $result[$moduleName]['peor_alumno'] = $studentName;
                }

                if ($avgGrade >= 5) {
                    $result[$moduleName]['num_aprobados']++;
                } else {
                    $result[$moduleName]['num_suspensos']++;
                }
            }



