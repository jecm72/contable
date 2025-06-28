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
    <?php include 'includes/header.php'; ?> <!-- Incluye navbar y estilos -->
</head>
<body>
    <!-- Contenido del dashboard -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                
            </div>
            <div class="col-md-30">
                <!-- Tarjeta de empresas -->
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Gestión de Empresas</h5>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEmpresa">
                            <i class="fas fa-plus"></i> Nueva Empresa
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>NIT</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Obtener empresas
                                $stmt = $pdo->query("SELECT * FROM empresas");
                                while ($empresa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                                            <td>{$empresa['id_empresa']}</td>
                                            <td>{$empresa['nombre']}</td>
                                            <td>{$empresa['rif']}</td>
                                            <td>
                                                <a href='accion_empresa.php?editar={$empresa['id_empresa']}' class='btn btn-sm btn-warning'><i class='fas fa-edit'>Editar</i></a>
                                                <a href='accion_empresa.php?eliminar={$empresa['id_empresa']}' class='btn btn-sm btn-danger'><i class='fas fa-trash'>Eliminar</i></a>
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

    <!-- Modal para agregar/editar empresa -->
    <div class="modal fade" id="modalEmpresa">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="accion_empresa.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar Empresa</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_empresa" id="id_empresa">
                        <div class="mb-3">
                            <label>Nombre de la empresa</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>NIT </label>
                            <input type="text" name="rif" class="form-control" placeholder="Ej: 2515592-8" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="guardar_empresa" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?> <!-- Scripts JS -->
</body>
</html>