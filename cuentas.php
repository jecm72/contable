<?php
include 'config/database.php';
// Verificar sesión y permisos
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
                        <h5>Plan de Cuentas</h5>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCuenta">
                            <i class="fas fa-plus"></i> Nueva Cuenta
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query("SELECT * FROM cuentas ORDER BY codigo");
                                while ($cuenta = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                                            <td>{$cuenta['codigo']}</td>
                                            <td>{$cuenta['nombre']}</td>
                                            <td>{$cuenta['tipo']}</td>
                                            <td>
                                                <a href='accion_cuenta.php?editar={$cuenta['id_cuenta']}' class='btn btn-sm btn-warning'><i class='fas fa-edit'>Editar</i></a>
                                                <a href='accion_cuenta.php?eliminar={$cuenta['id_cuenta']}' class='btn btn-sm btn-danger'><i class='fas fa-trash'>Eliminar</i></a>
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

    <!-- Modal para Cuentas -->
    <div class="modal fade" id="modalCuenta">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="accion_cuenta.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Cuenta</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_cuenta" id="id_cuenta">
                        <div class="mb-3">
                            <label>Código</label>
                            <input type="text" name="codigo" class="form-control" placeholder="Ej: 1.1.1" required>
                        </div>
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tipo</label>
                            <select name="tipo" class="form-select" required>
                                <option value="">Seleccionar...</option>
                                <option value="Activo">Activo</option>
                                <option value="Pasivo">Pasivo</option>
                                <option value="Patrimonio">Patrimonio</option>
                                <option value="Ingreso">Ingreso</option>
                                <option value="Gasto">Gasto</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="guardar_cuenta" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>