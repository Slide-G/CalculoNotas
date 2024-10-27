<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesador de Notas</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h1 class="mb-4">Procesador de Notas</h1>

<form method="POST" action="controller.php">
    <div class="form-group">
        <label for="jsonInput">Ingresa el JSON:</label>
        <textarea class="form-control" name="jsonInput" id="jsonInput" rows="10"><?= isset($jsonInput) ? $jsonInput : '' ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Enviar</button>
</form>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger mt-3">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($resultado): ?>
    <h2 class="mt-4">Resultados</h2>
    <table class="table table-bordered mt-3">
        <thead>
        <tr>
            <th>Módulo</th>
            <th>Nota Media</th>
            <th>Suspensos</th>
            <th>Aprobados</th>
            <th>Nota Máxima</th>
            <th>Mejor Alumno</th>
            <th>Nota Mínima</th>
            <th>Peor Alumno</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($resultado as $module => $stats): ?>
            <tr>
                <td><?= $module ?></td>
                <td><?= round($stats['nota_media'], 2) ?></td>
                <td><?= $stats['num_suspensos'] ?></td>
                <td><?= $stats['num_aprobados'] ?></td>
                <td><?= $stats['nota_max'] ?></td>
                <td><?= $stats['mejor_alumno'] ?></td>
                <td><?= $stats['nota_min'] ?></td>
                <td><?= $stats['peor_alumno'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Listado de alumnos en categorías -->
    <h2 class="mt-4">Listados de Alumnos</h2>
    <div class="alert alert-success mt-3">
        <strong>Alumnos que han aprobado todo:</strong>
        <ul>
            <?php foreach (array_unique($approvedStudents) as $student): ?>
                <li><?= $student ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="alert alert-warning mt-3">
        <strong>Alumnos que han suspendido al menos una asignatura:</strong>
        <ul>
            <?php foreach (array_unique($failedStudents) as $student): ?>
                <li><?= $student ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="alert alert-info mt-3">
        <strong>Alumnos que promocionan:</strong>
        <ul>
            <?php foreach (array_unique($promotedStudents) as $student): ?>
                <li><?= $student ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="alert alert-danger mt-3">
        <strong>Alumnos que no promocionan:</strong>
        <ul>
            <?php foreach (array_unique($notPromotedStudents) as $student): ?>
                <li><?= $student ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
</body>
</html>
