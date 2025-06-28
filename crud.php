<?php
include 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
// Verificar permisos de admin aquí

// Obtener lista de usuarios
$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll();

include 'includes/header.php';
?>


<h2>Usuarios</h2>

<?php
$host = 'localhost';
$dbname = 'sistema_contable';
$user = 'root'; // Cambiar según tu configuración
$pass = ''; // Cambiar según tu configuración

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("No se pudo conectar a la base de datos: " . $e->getMessage());
}

// Obtener lista de empresas para el select
$stmt_empresas = $pdo->query("SELECT id_empresa, nombre FROM empresas");
$empresas = $stmt_empresas->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $rol = $_POST['rol'];
        $id_empresa = $_POST['id_empresa'] ?: NULL;

        $stmt = $pdo->prepare("INSERT INTO usuarios (id_empresa, nombre, apellido, email, username, password_hash, rol) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_empresa, $nombre, $apellido, $email, $username, $password, $rol]);
    } elseif (isset($_POST['update'])) {
        $id_usuario = $_POST['id_usuario'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $_POST['current_password'];
        $rol = $_POST['rol'];
        $id_empresa = $_POST['id_empresa'] ?: NULL;

        $stmt = $pdo->prepare("UPDATE usuarios SET id_empresa=?, nombre=?, apellido=?, email=?, username=?, password_hash=?, rol=? WHERE id_usuario=?");
        $stmt->execute([$id_empresa, $nombre, $apellido, $email, $username, $password, $rol, $id_usuario]);
    } elseif (isset($_POST['delete'])) {
        $id_usuario = $_POST['id_usuario'];
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario=?");
        $stmt->execute([$id_usuario]);
    }
}

$stmt = $pdo->query("SELECT * FROM usuarios");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Crear Usuario</h3>
<form method="post">
    <div class="form-group">
        <label>Empresa:</label>
        <select name="id_empresa">
            <option value="">-- Seleccionar Empresa --</option>
            <?php foreach ($empresas as $empresa): ?>
                <option value="<?= $empresa['id_empresa'] ?>">
                    <?= htmlspecialchars($empresa['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
    </div>
    <div class="form-group">
        <label>Apellido:</label>
        <input type="text" name="apellido" required>
    </div>
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>Username:</label>
        <input type="text" name="username" required>
    </div>
    <div class="form-group">
        <label>Password:</label>
        <input type="password" name="password" required>
    </div>
    <div class="form-group">
        <label>Rol:</label>
        <select name="rol">
            <option value="admin">Admin</option>
            <option value="usuario">Usuario</option>
        </select>
    </div>
    <input type="submit" name="create" value="Crear">
</form>

<h3>Lista de Usuarios</h3>

<div class="card shadow">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Usuarios</h5>
        
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
        <th>ID</th>
        
        <th>Nombre</th>
        <th>Apellido</th>
        <th>Email</th>
        <th>Username</th>
        <th>Rol</th>
        <th>Fecha Registro</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($usuarios as $u): ?>
    <tr>
        
        <td><?= htmlspecialchars($u['id_empresa']) ?></td>
        <td><?= htmlspecialchars($u['nombre']) ?></td>
        <td><?= htmlspecialchars($u['apellido']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['rol']) ?></td>
        <td><?= htmlspecialchars($u['fecha_registro']) ?></td>
        <td>
            <form method="post" style="display:inline;">
                <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                <input type="submit" formaction="#editar" value="Editar">
            </form>
            <form method="post" style="display:inline;">
                <input class="btn btn-sm btn-warning" type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                <input class="btn btn-sm btn-danger" type="submit" name="delete" value="Eliminar" onclick="return confirm('¿Seguro?')">
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
</div>
<?php if (isset($_POST['id_usuario'])): 
    $id_usuario = $_POST['id_usuario'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario=?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<a name="editar"></a>
<h3>Editar Usuario</h3>
<form method="post">
    <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
    <input type="hidden" name="current_password" value="<?= $usuario['password_hash'] ?>">
    <div class="form-group">
        <label>Empresa:</label>
        <select name="id_empresa">
            <option value="">-- Seleccionar Empresa --</option>
            <?php foreach ($empresas as $empresa): ?>
                <option value="<?= $empresa['id_empresa'] ?>" <?= $empresa['id_empresa'] == $usuario['id_empresa'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($empresa['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
    </div>
    <div class="form-group">
        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
    </div>
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
    </div>
    <div class="form-group">
        <label>Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($usuario['username']) ?>" required>
    </div>
    <div class="form-group">
        <label>Nuevo Password (dejar vacío para mantener el actual):</label>
        <input type="password" name="password">
    </div>
    <div class="form-group">
        <label>Rol:</label>
        <select name="rol">
            <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="usuario" <?= $usuario['rol'] == 'usuario' ? 'selected' : '' ?>>Usuario</option>
        </select>
    </div>
    <input type="submit" name="update" value="Actualizar">
</form>
<?php endif; ?>

</body>
</html>