<?php
include 'config/database.php';
if (!isset($_GET['id']) || !isset($_SESSION['user_id'])) {
    header('Location: asientos.php');
    exit;
}

$id = $_GET['id'];
$asiento = $pdo->query("SELECT * FROM asientos_contables WHERE id_asiento = $id")->fetch();
$transacciones = $pdo->query("
    SELECT t.*, c.codigo, c.nombre 
    FROM transacciones t
    JOIN cuentas c ON t.id_cuenta = c.id_cuenta
    WHERE t.id_asiento = $id
");


$transacciones = $pdo->query("
    SELECT t.*, c.codigo, c.nombre 
    FROM transacciones t
    JOIN cuentas c ON t.id_cuenta = c.id_cuenta
    WHERE t.id_asiento = $id
");

// Convertir a array una sola vez
$transaccionesArray = $transacciones->fetchAll();

// Calcular totales
$totalDebe = 0;
$totalHaber = 0;

foreach ($transaccionesArray as $t) {
    if ($t['tipo'] == 'Debe') {
        $totalDebe += $t['monto'];
    } else {
        $totalHaber += $t['monto'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            
            <div class="col-md-30">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Detalle del Asiento #<?= $asiento['id_asiento'] ?></h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Fecha:</strong> <?= $asiento['fecha'] ?></p>
                        <p><strong>Descripción:</strong> <?= $asiento['descripcion'] ?></p>
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cuenta</th>
                                    <th>Débito</th>
                                    <th>Crédito</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transaccionesArray as $t): ?>
                                <tr>
                                    <td><?= $t['codigo'] ?> - <?= $t['nombre'] ?></td>
                                    <td><?= $t['tipo'] == 'Debe' ? number_format($t['monto'], 2) : '-' ?></td>
                                    <td><?= $t['tipo'] == 'Haber' ? number_format($t['monto'], 2) : '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-active">
                                    <td><strong>Totales</strong></td>
                                    <td><strong>Q<?= number_format($totalDebe, 2) ?></strong></td>
                                    <td><strong>Q<?= number_format($totalHaber, 2) ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>