<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Asientos Contables  Nota: tiene que haber dos cuentas para que las agregue como partida contable</h5>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAsiento">
                            <i class="fas fa-plus"></i> Nuevo Asiento  
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Descripción</th>
                                    <th>Total Débito</th>
                                    <th>Total Crédito</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query("
                                    SELECT a.*, 
                                    SUM(CASE WHEN t.tipo = 'Debe' THEN t.monto ELSE 0 END) AS total_debe,
                                    SUM(CASE WHEN t.tipo = 'Haber' THEN t.monto ELSE 0 END) AS total_haber
                                    FROM asientos_contables a
                                    LEFT JOIN transacciones t ON a.id_asiento = t.id_asiento
                                    GROUP BY a.id_asiento
                                ");
                                while ($asiento = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                                            <td>{$asiento['fecha']}</td>
                                            <td>{$asiento['descripcion']}</td>
                                            <td>Q".number_format($asiento['total_debe'], 2)."</td>
                                            <td>Q".number_format($asiento['total_haber'], 2)."</td>
                                            <td>
                                                <a href='accion_asiento.php?eliminar={$asiento['id_asiento']}' class='btn btn-sm btn-danger'><i class='fas fa-trash'>Eliminar</i></a>
                                                <a href='ver_asiento.php?id={$asiento['id_asiento']}' class='btn btn-sm btn-info'><i class='fas fa-eye'>ver</i></a>
                                            </td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Asientos -->
    <div class="modal fade" id="modalAsiento">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="accion_asiento.php" method="POST" id="formAsiento">
               
                <input type="hidden" name="guardar_asiento" value="1"> 
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Asiento Contable</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Fecha</label>
                                <input type="date" name="fecha" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Descripción</label>
                                <input type="text" name="descripcion" class="form-control" required>
                            </div>
                        </div>
                        
                        <h6>Transacciones</h6>
                        <div id="transacciones">
                            <div class="transaccion row mb-2">
                                <div class="col-md-5">
                                    <select name="cuentas[]" class="form-select" required>
                                        <option value="">Seleccionar cuenta</option>
                                        <?php
                                        $cuentas = $pdo->query("SELECT * FROM cuentas");
                                        while ($c = $cuentas->fetch()) {
                                            echo "<option value='{$c['id_cuenta']}'>{$c['codigo']} - {$c['nombre']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="montos[]" step="0.01" class="form-control" placeholder="Monto" required>
                                </div>
                                <div class="col-md-3">
                                    <select name="tipos[]" class="form-select" required>
                                        <option value="Debe">Débito</option>
                                        <option value="Haber">Crédito</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarTransaccion(this)"><i class="fas fa-times">Eliminar</i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="agregarTransaccion()"><i class="fas fa-plus"></i> Agregar línea</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="guardar_asiento" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Funciones para manejar transacciones dinámicas
    function agregarTransaccion() {
        const clone = document.querySelector('.transaccion').cloneNode(true);
        clone.querySelectorAll('input').forEach(input => input.value = '');
        document.querySelector('#transacciones').appendChild(clone);
    }

    function eliminarTransaccion(btn) {
        if (document.querySelectorAll('.transaccion').length > 1) {
            btn.closest('.transaccion').remove();
        }
    }

    document.querySelector('#formAsiento').addEventListener('submit', function(e) {
    const cuentas = document.querySelectorAll('select[name="cuentas[]"]');
    if (cuentas.length === 0) {
        e.preventDefault();
        alert("Debe agregar al menos una transacción.");
    }
});
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>