<?php
include 'config/database.php';

// Crear o Editar Cuenta
if (isset($_POST['guardar_cuenta'])) {
    $id_cuenta = $_POST['id_cuenta'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];

    try {
        if (empty($id_cuenta)) {
            $stmt = $pdo->prepare("INSERT INTO cuentas (codigo, nombre, tipo) VALUES (?, ?, ?)");
            $stmt->execute([$codigo, $nombre, $tipo]);
            $_SESSION['mensaje'] = "¡Cuenta creada exitosamente!";
        } else {
            $stmt = $pdo->prepare("UPDATE cuentas SET codigo = ?, nombre = ?, tipo = ? WHERE id_cuenta = ?");
            $stmt->execute([$codigo, $nombre, $tipo, $id_cuenta]);
            $_SESSION['mensaje'] = "¡Cuenta actualizada!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: Código de cuenta ya existe o datos inválidos";
    }
    header('Location: cuentas.php');
    exit;
}

// Eliminar Cuenta
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $pdo->prepare("DELETE FROM cuentas WHERE id_cuenta = ?")->execute([$id]);
    $_SESSION['mensaje'] = "¡Cuenta eliminada!";
    header('Location: cuentas.php');
    exit;
}

// Cargar datos para editar
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM cuentas WHERE id_cuenta = ?");
    $stmt->execute([$id]);
    $cuenta = $stmt->fetch();
    echo json_encode($cuenta);
    exit;
}