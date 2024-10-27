<?php
// controller.php

$errors = [];
$resultado = null;
$approvedStudents = [];
$failedStudents = [];
$promotedStudents = [];
$notPromotedStudents = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonInput = htmlspecialchars(trim($_POST['jsonInput']));

    if (empty($jsonInput)) {
        $errors[] = "El campo JSON no puede estar vacío.";
    } else {
        $data = json_decode($jsonInput, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $errors[] = "El texto ingresado no es un JSON válido.";
        } else {
            if (is_array($data) && validateJsonStructure($data)) {
                $resultado = processGrades($data);
            } else {
                $errors[] = "El JSON no sigue el formato requerido.";
            }
        }
    }
}

function validateJsonStructure($data) {
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

function processGrades($data) {
    global $approvedStudents, $failedStudents, $promotedStudents, $notPromotedStudents;
    $result = [];

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
            $fails = count(array_filter($grades, function ($grade) {
                return $grade < 5;
            }));

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

            if ($fails === 0) {
                $approvedStudents[] = $studentName;
            } elseif ($fails === 1) {
                $promotedStudents[] = $studentName;
            } elseif ($fails >= 2) {
                $notPromotedStudents[] = $studentName;
            } else {
                $failedStudents[] = $studentName;
            }
        }

        $result[$moduleName]['nota_media'] = $totalScore / $totalGrades;
    }

    return $result;
}
?>
