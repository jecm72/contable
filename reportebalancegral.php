<?php
include 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se solicita exportar a Excel
if(isset($_GET['exportar_excel'])) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="balance_general.xls"');
    header('Cache-Control: max-age=0');
    
    $query = "
        SELECT 
            c.id_cuenta,
            c.codigo,
            c.nombre,
            c.tipo,
            SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) AS debe,
            SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) AS haber,
            CASE 
                WHEN c.tipo = 'Activo' THEN (SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) - SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END))
                ELSE (SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) - SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END))
            END AS saldo
        FROM cuentas c
        LEFT JOIN transacciones t ON c.id_cuenta = t.id_cuenta
        GROUP BY c.id_cuenta
        ORDER BY c.codigo
    ";

    $stmt = $pdo->query($query);
    $cuentas = $stmt->fetchAll();

    $activos = array_filter($cuentas, fn($c) => $c['tipo'] == 'Activo');
    $pasivos = array_filter($cuentas, fn($c) => $c['tipo'] == 'Pasivo');
    $patrimonio = array_filter($cuentas, fn($c) => $c['tipo'] == 'Patrimonio');

    $total_activo = array_sum(array_column($activos, 'saldo'));
    $total_pasivo = array_sum(array_column($pasivos, 'saldo'));
    $total_patrimonio = array_sum(array_column($patrimonio, 'saldo'));

    // Generar contenido Excel
    echo '<table border="1">';
    
    // Encabezado
    echo '<tr style="background:#e6ffe6;"><th colspan="3">BALANCE GENERAL</th></tr>';
    
    // Activos
    echo '<tr><th colspan="3" style="background:#f0f0f0;">ACTIVOS</th></tr>';
    foreach($activos as $a) {
        echo '<tr>';
        echo '<td>'.$a['codigo'].'</td>';
        echo '<td>'.$a['nombre'].'</td>';
        echo '<td align="right">Q'.number_format($a['saldo'], 2).'</td>';
        echo '</tr>';
    }
    echo '<tr style="background:#f0f0f0;">';
    echo '<td colspan="2"><strong>TOTAL ACTIVO</strong></td>';
    echo '<td align="right"><strong>Q'.number_format($total_activo, 2).'</strong></td>';
    echo '</tr>';
    
    // Pasivos
    echo '<tr><th colspan="3" style="background:#f0f0f0;">PASIVOS</th></tr>';
    foreach($pasivos as $p) {
        echo '<tr>';
        echo '<td>'.$p['codigo'].'</td>';
        echo '<td>'.$p['nombre'].'</td>';
        echo '<td align="right">Q'.number_format($p['saldo'], 2).'</td>';
        echo '</tr>';
    }
    
    // Patrimonio
    echo '<tr><th colspan="3" style="background:#f0f0f0;">PATRIMONIO</th></tr>';
    foreach($patrimonio as $pat) {
        echo '<tr>';
        echo '<td>'.$pat['codigo'].'</td>';
        echo '<td>'.$pat['nombre'].'</td>';
        echo '<td align="right">Q'.number_format($pat['saldo'], 2).'</td>';
        echo '</tr>';
    }
    
    // Totales
    echo '<tr style="background:#f0f0f0;">';
    echo '<td colspan="2"><strong>TOTAL PASIVO + PATRIMONIO</strong></td>';
    echo '<td align="right"><strong>Q'.number_format(($total_pasivo + $total_patrimonio), 2).'</strong></td>';
    echo '</tr>';
    
    // Validación ecuación
    echo '<tr>';
    echo '<td colspan="3" style="text-align:center;background:'.($total_activo == ($total_pasivo + $total_patrimonio) ? '#e6ffe6' : '#ffe6e6').'">';
    echo ($total_activo == ($total_pasivo + $total_patrimonio)) ? 
         '✅ Ecuación contable equilibrada' : 
         '⚠️ Desbalance: Activo ≠ Pasivo + Patrimonio';
    echo '</td></tr>';
    
    echo '</table>';
    exit;
}

// Código original para mostrar en HTML
$query = "
    SELECT 
        c.id_cuenta,
        c.codigo,
        c.nombre,
        c.tipo,
        SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) AS debe,
        SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) AS haber,
        CASE 
            WHEN c.tipo = 'Activo' THEN (SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) - SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END))
            ELSE (SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) - SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END))
        END AS saldo
    FROM cuentas c
    LEFT JOIN transacciones t ON c.id_cuenta = t.id_cuenta
    GROUP BY c.id_cuenta
    ORDER BY c.codigo
";

$stmt = $pdo->query($query);
$cuentas = $stmt->fetchAll();

$activos = array_filter($cuentas, fn($c) => $c['tipo'] == 'Activo');
$pasivos = array_filter($cuentas, fn($c) => $c['tipo'] == 'Pasivo');
$patrimonio = array_filter($cuentas, fn($c) => $c['tipo'] == 'Patrimonio');

$total_activo = array_sum(array_column($activos, 'saldo'));
$total_pasivo = array_sum(array_column($pasivos, 'saldo'));
$total_patrimonio = array_sum(array_column($patrimonio, 'saldo'));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?>
    <title>Balance General</title>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Balance General al <?= date('d/m/Y') ?></h5>
                        <a href="?exportar_excel=1" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Exportar a Excel
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Activos -->
                        <div class="mb-4">
                            <h6 class="fw-bold">ACTIVOS</h6>
                            <table class="table table-sm">
                                <tbody>
                                    <?php foreach ($activos as $a): ?>
                                    <tr>
                                        <td><?= $a['codigo'] ?></td>
                                        <td><?= $a['nombre'] ?></td>
                                        <td class="text-end">Q<?= number_format($a['saldo'], 2) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-active">
                                        <td colspan="2" class="fw-bold">TOTAL ACTIVO</td>
                                        <td class="text-end fw-bold">Q<?= number_format($total_activo, 2) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pasivos y Patrimonio -->
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">PASIVOS</h6>
                                <table class="table table-sm">
                                    <tbody>
                                        <?php foreach ($pasivos as $p): ?>
                                        <tr>
                                            <td><?= $p['codigo'] ?></td>
                                            <td><?= $p['nombre'] ?></td>
                                            <td class="text-end">Q<?= number_format($p['saldo'], 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">PATRIMONIO</h6>
                                <table class="table table-sm">
                                    <tbody>
                                        <?php foreach ($patrimonio as $pat): ?>
                                        <tr>
                                            <td><?= $pat['codigo'] ?></td>
                                            <td><?= $pat['nombre'] ?></td>
                                            <td class="text-end">Q<?= number_format($pat['saldo'], 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <tr class="table-active">
                                            <td colspan="2" class="fw-bold">TOTAL PASIVO + PATRIMONIO</td>
                                            <td class="text-end fw-bold">Q<?= number_format($total_pasivo + $total_patrimonio, 2) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Validación de ecuación contable -->
                        <div class="alert <?= ($total_activo == ($total_pasivo + $total_patrimonio)) ? 'alert-success' : 'alert-danger' ?>">
                            <?php if ($total_activo == ($total_pasivo + $total_patrimonio)): ?>
                                ✔️ La ecuación contable se cumple: Activo (Q<?= number_format($total_activo, 2) ?>) = Pasivo + Patrimonio (Q<?= number_format(($total_pasivo + $total_patrimonio), 2) ?>)
                            <?php else: ?>
                                ⚠️ Desbalance detectado: Activo (Q<?= number_format($total_activo, 2) ?>) ≠ Pasivo + Patrimonio (Q<?= number_format(($total_pasivo + $total_patrimonio), 2) ?>)
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>