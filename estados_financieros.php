<?php
include 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener períodos para filtro
$periodos = $pdo->query("SELECT * FROM periodos")->fetchAll();

// Filtrar por período si se selecciona
$id_periodo = $_GET['periodo'] ?? null;
$where = $id_periodo ? " WHERE id_periodo = " . (int)$id_periodo : "";

// Obtener estados financieros
$estados = $pdo->query("
    SELECT e.*, p.nombre as periodo 
    FROM estados_financieros e
    LEFT JOIN periodos p ON e.id_periodo = p.id_periodo
    $where
    ORDER BY e.id_periodo DESC, e.tipo
")->fetchAll();

// Exportar a Excel
if(isset($_GET['exportar'])) {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="estados_financieros.xls"');
    header('Cache-Control: max-age=0');
    
    echo '<table border="1">';
    foreach($estados as $e) {
        $datos = json_decode($e['datos'], true);
        if(json_last_error() !== JSON_ERROR_NONE) continue;
        
        echo '<tr><th colspan="2" style="background:#ccc;">'.$e['tipo'].' - '.$e['periodo'].'</th></tr>';
        
        if($e['tipo'] == 'Balance General') {
            // Encabezados
            echo '<tr>
                    <th style="background:#e6ffe6;">Activos</th>
                    <th style="background:#ffe6e6;">Pasivos y Patrimonio</th>
                  </tr>';
            
            // Datos
            $max_rows = max(
                count($datos['activos']), 
                count($datos['pasivos']) + count($datos['patrimonio'])
            );
            
            $activos = array_values($datos['activos']);
            $pasivos = array_merge(
                array_values($datos['pasivos']), 
                array_values($datos['patrimonio'])
            );
            
            for($i = 0; $i < $max_rows; $i++) {
                echo '<tr>';
                echo '<td>';
                if(isset($activos[$i])) {
                    $key = array_keys($datos['activos'])[$i];
                    echo $key . ' Q' . number_format($activos[$i], 2);
                }
                echo '</td>';
                
                echo '<td>';
                if(isset($pasivos[$i])) {
                    $keys = array_merge(
                        array_keys($datos['pasivos']), 
                        array_keys($datos['patrimonio'])
                    );
                    echo $keys[$i] . ' Q' . number_format($pasivos[$i], 2);
                }
                echo '</td>';
                echo '</tr>';
            }
        } else {
            // Estado de Resultados
            echo '<tr><th style="background:#e6ffe6;">Ingresos</th><th style="background:#ffe6e6;">Gastos</th></tr>';
            
            $max_rows = max(
                count($datos['ingresos']), 
                count($datos['gastos'])
            );
            
            $ingresos = array_values($datos['ingresos']);
            $gastos = array_values($datos['gastos']);
            
            for($i = 0; $i < $max_rows; $i++) {
                echo '<tr>';
                echo '<td>';
                if(isset($ingresos[$i])) {
                    $key = array_keys($datos['ingresos'])[$i];
                    echo $key . ' Q' . number_format($ingresos[$i], 2);
                }
                echo '</td>';
                
                echo '<td>';
                if(isset($gastos[$i])) {
                    $key = array_keys($datos['gastos'])[$i];
                    echo $key . ' Q' . number_format($gastos[$i], 2);
                }
                echo '</td>';
                echo '</tr>';
            }
            
            // Utilidad Neta
            echo '<tr style="background:#f0f0f0;">';
            echo '<td colspan="2" style="text-align:center;font-weight:bold;">';
            echo 'UTILIDAD NETA: Q' . number_format($datos['utilidad_neta'], 2);
            echo '</td></tr>';
        }
        echo '<tr><td colspan="2">&nbsp;</td></tr>'; // Espacio entre estados
    }
    echo '</table>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'includes/header.php'; ?>
    <title>Estados Financieros</title>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Estados Financieros</h5>
                        <div class="d-flex gap-2">
                            <form method="GET" class="d-flex gap-2">
                                <select name="periodo" class="form-select" onchange="this.form.submit()">
                                    <option value="">Todos los períodos</option>
                                    <?php foreach($periodos as $p): ?>
                                        <option value="<?= $p['id_periodo'] ?>" <?= $id_periodo == $p['id_periodo'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="exportar" value="1">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Exportar Excel
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
              
            </div>
            
            <div class="col-md-30">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Estados Financieros</h5>
                        <form method="GET" class="d-flex gap-2">
                            <select name="periodo" class="form-select" onchange="this.form.submit()">
                                <option value="">Todos los períodos</option>
                                <?php foreach($periodos as $p): ?>
                                    <option value="<?= $p['id_periodo'] ?>" <?= $id_periodo == $p['id_periodo'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                    
                    <div class="card-body">
                        <?php if(empty($estados)): ?>
                            <div class="alert alert-info">No se encontraron estados financieros</div>
                        <?php else: ?>
                            <?php foreach($estados as $e): 
                                $datos = json_decode($e['datos'], true);
                                if(json_last_error() !== JSON_ERROR_NONE) continue;
                            ?>
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5><?= htmlspecialchars($e['tipo']) ?> - <?= htmlspecialchars($e['periodo']) ?></h5>
                                    </div>
                                    
                                    <div class="card-body">
                                        <?php if($e['tipo'] == 'Balance General'): ?>
                                            <!-- Balance General -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-success">ACTIVOS</h6>
                                                    <ul class="list-group">
                                                        <?php foreach($datos['activos'] as $nombre => $monto): ?>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <?= htmlspecialchars($nombre) ?>
                                                                <span>Q<?= number_format($monto, 2) ?></span>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-4">
                                                        <h6 class="text-danger">PASIVOS</h6>
                                                        <ul class="list-group">
                                                            <?php foreach($datos['pasivos'] as $nombre => $monto): ?>
                                                                <li class="list-group-item d-flex justify-content-between">
                                                                    <?= htmlspecialchars($nombre) ?>
                                                                    <span>Q<?= number_format($monto, 2) ?></span>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                    
                                                    <div>
                                                        <h6 class="text-primary">PATRIMONIO</h6>
                                                        <ul class="list-group">
                                                            <?php foreach($datos['patrimonio'] as $nombre => $monto): ?>
                                                                <li class="list-group-item d-flex justify-content-between">
                                                                    <?= htmlspecialchars($nombre) ?>
                                                                    <span>Q<?= number_format($monto, 2) ?></span>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        
                                        <?php else: ?>
                                            <!-- Estado de Resultados -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-success">INGRESOS</h6>
                                                    <ul class="list-group">
                                                        <?php foreach($datos['ingresos'] as $nombre => $monto): ?>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <?= htmlspecialchars($nombre) ?>
                                                                <span>Q<?= number_format($monto, 2) ?></span>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <h6 class="text-danger">GASTOS</h6>
                                                    <ul class="list-group">
                                                        <?php foreach($datos['gastos'] as $nombre => $monto): ?>
                                                            <li class="list-group-item d-flex justify-content-between">
                                                                <?= htmlspecialchars($nombre) ?>
                                                                <span>Q<?= number_format($monto, 2) ?></span>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                    
                                                    <div class="mt-4 p-3 bg-light rounded">
                                                        <h5 class="mb-0">Utilidad Neta: 
                                                            <span class="float-end <?= $datos['utilidad_neta'] >= 0 ? 'text-success' : 'text-danger' ?>">
                                                                Q<?= number_format($datos['utilidad_neta'], 2) ?>
                                                            </span>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-footer text-muted small">

                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>