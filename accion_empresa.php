<?php
include 'config/database.php';

// Crear o Editar Empresa
if (isset($_POST['guardar_empresa'])) {
    $id_empresa = $_POST['id_empresa'];
    $nombre = $_POST['nombre'];
    $rif = $_POST['rif'];

    if (empty($id_empresa)) {
        // Crear nueva empresa
        $stmt = $pdo->prepare("INSERT INTO empresas (nombre, rif) VALUES (?, ?)");
        $stmt->execute([$nombre, $rif]);
        $_SESSION['mensaje'] = "Empresa creada exitosamente!";
    } else {
        // Editar empresa existente
        $stmt = $pdo->prepare("UPDATE empresas SET nombre = ?, rif = ? WHERE id_empresa = ?");
        $stmt->execute([$nombre, $rif, $id_empresa]);
        $_SESSION['mensaje'] = "Empresa actualizada!";
    }
    header('Location: empresas.php');
    exit;
}

// Eliminar Empresa
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM empresas WHERE id_empresa = ?");
    $stmt->execute([$id]);
    $_SESSION['mensaje'] = "Empresa eliminada!";
    header('Location: empresas.php');
    exit;
}

// Cargar datos para editar
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM empresas WHERE id_empresa = ?");
    $stmt->execute([$id]);
    $empresa = $stmt->fetch();
    
    // Pasar datos a JSON para prellenar el modal
    echo json_encode($empresa);
    exit;
}
?>