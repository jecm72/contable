<?php
include 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Obtener lista de usuarios
$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();

include 'includes/header.php';
?>

<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Usuarios</h5>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalUsuario">Nuevo Usuario</button>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= $u['nombre'] ?></td>
                    <td><?= $u['username'] ?></td>
                    <td><?= $u['rol'] ?></td>
                    <td>
                        <a href="accion_usuario.php?editar=<?= $u['id_usuario'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="accion_usuario.php?eliminar=<?= $u['id_usuario'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para agregar/editar -->
<div class="modal fade" id="modalUsuario">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="accion_usuario.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title"><?= isset($_GET['editar']) ? 'Editar' : 'Nuevo' ?> Usuario</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_usuario" id="id_usuario">
                    <div class="mb-3">
                        <label>Nombre completo</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nombre de usuario</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Contraseña</label>
                        <input type="password" name="password" class="form-control" placeholder="<?= isset($_GET['editar']) ? 'Dejar en blanco para no cambiar' : 'Obligatorio' ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="guardar" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<!-- Script para prellenar el modal de edición -->
<script>
$(document).ready(function() {
    // Manejar clic en editar
    $('a[href*="editar="]').click(function(e) {
        e.preventDefault();
        $.get($(this).attr('href'), function(data) {
            const usuario = JSON.parse(data);
            $('#modalUsuario input[name="nombre"]').val(usuario.nombre);
            $('#modalUsuario input[name="username"]').val(usuario.username);
            $('#modalUsuario').modal('show');
        });
    });
});
</script>
