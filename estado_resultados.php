
<?php
include 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener fechas del período (ejemplo: mes actual)
$fecha_inicio = date('Y-m-01');
$fecha_fin = date('Y-m-t');


// Antes de ejecutar la consulta, muestra el query final
$query = "
    SELECT 
        c.id_cuenta,
        c.nombre,
        c.tipo,
        SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) AS debe,
        SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) AS haber,
        CASE 
            WHEN c.tipo = 'Ingreso' THEN (SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) - SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END))
            WHEN c.tipo = 'Gasto' THEN (SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) - SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END))
        END AS saldo
    FROM cuentas c
    LEFT JOIN transacciones t ON c.id_cuenta = t.id_cuenta
    LEFT JOIN asientos_contables a ON t.id_asiento = a.id_asiento
    WHERE c.tipo IN ('Ingreso', 'Gasto')
    AND a.fecha BETWEEN :fecha_inicio AND :fecha_fin
    GROUP BY c.id_cuenta
    ORDER BY c.tipo DESC, c.codigo
";

// Depurar el query y parámetros
error_log("Query ejecutado: " . $query);
error_log("Parámetros: fecha_inicio=$fecha_inicio, fecha_fin=$fecha_fin");

$stmt = $pdo->prepare($query);
$stmt->execute([':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);

// Consulta para Ingresos y Gastos
$query = "
   SELECT 
    c.id_cuenta,
    c.nombre,
    c.tipo,
    SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) AS debe,
    SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) AS haber,
    CASE 
        WHEN c.tipo = 'Ingreso' THEN (SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) - SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END))
        WHEN c.tipo = 'Gasto' THEN (SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) - SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END))
    END AS saldo
FROM cuentas c
LEFT JOIN transacciones t ON c.id_cuenta = t.id_cuenta
LEFT JOIN asientos_contables a 
       ON t.id_asiento = a.id_asiento
      AND a.fecha BETWEEN :fecha_inicio AND :fecha_fin
WHERE c.tipo IN ('Ingreso', 'Gasto')
GROUP BY c.id_cuenta
ORDER BY c.tipo DESC, c.codigo
";

$stmt = $pdo->prepare($query);
$stmt->execute([':fecha_inicio' => $fecha_inicio, ':fecha_fin' => $fecha_fin]);
$cuentas = $stmt->fetchAll();

// Calcular totales
$total_ingresos = 0;
$total_gastos = 0;

foreach ($cuentas as $c) {
    if ($c['tipo'] == 'Ingreso') $total_ingresos += $c['saldo'];
    if ($c['tipo'] == 'Gasto') $total_gastos += $c['saldo'];
}

$utilidad_neta = $total_ingresos - $total_gastos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?>
    <title>Estado de Resultados</title>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-30">
               
            </div>
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header">
                        <h5>Estado de Resultados <?= date('01/m/Y', strtotime($fecha_inicio)) ?> al <?= date('d/m/Y', strtotime($fecha_fin)) ?></h5>
                    </div>
                    <div class="card-body">
                        <!-- Filtro de fechas -->
                        <form method="GET" class="mb-4">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="date" name="fecha_inicio" class="form-control" value="<?= $fecha_inicio ?>">
                                </div>
                                <div class="col-md-5">
                                    <input type="date" name="fecha_fin" class="form-control" value="<?= $fecha_fin ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Filtrar</button>
                                </div>
                            </div>
                        </form>

                        <!-- Tabla de resultados -->
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Cuenta</th>
                                    <th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Ingresos -->
                                <tr class="table-success">
                                    <td colspan="2"><strong>INGRESOS</strong></td>
                                </tr>
                                <?php foreach ($cuentas as $c): ?>
                                    <?php if ($c['tipo'] == 'Ingreso' && $c['saldo'] != 0): ?>
                                        <tr>
                                            <td><?= $c['nombre'] ?></td>
                                            <td class="text-end">Q<?= number_format($c['saldo'], 2) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <tr class="table-active">
                                    <td><strong>Total Ingresos</strong></td>
                                    <td class="text-end"><strong>Q<?= number_format($total_ingresos, 2) ?></strong></td>
                                </tr>

                                <!-- Gastos -->
                                <tr class="table-danger">
                                    <td colspan="2"><strong>GASTOS</strong></td>
                                </tr>
                                <?php foreach ($cuentas as $c): ?>
                                    <?php if ($c['tipo'] == 'Gasto' && $c['saldo'] != 0): ?>
                                        <tr>
                                            <td><?= $c['nombre'] ?></td>
                                            <td class="text-end">Q<?= number_format($c['saldo'], 2) ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <tr class="table-active">
                                    <td><strong>Total Gastos</strong></td>
                                    <td class="text-end"><strong>Q<?= number_format($total_gastos, 2) ?></strong></td>
                                </tr>

                                <!-- Utilidad Neta -->
                                <tr class="<?= ($utilidad_neta >= 0) ? 'table-success' : 'table-danger' ?>">
                                    <td><strong>UTILIDAD NETA</strong></td>
                                    <td class="text-end"><strong>Q<?= number_format(abs($utilidad_neta), 2) ?></strong></td>
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