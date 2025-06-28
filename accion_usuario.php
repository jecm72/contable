<?php
include 'config/database.php';

// Crear o Editar
if (isset($_POST['guardar'])) {
    $id = $_POST['id_usuario'] ?? null;
    $nombre = $_POST['nombre'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    try {
        if ($id) {
            // Editar
            if ($password) {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, username = ?, password_hash = ? WHERE id_usuario = ?");
                $stmt->execute([$nombre, $username, $password, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, username = ? WHERE id_usuario = ?");
                $stmt->execute([$nombre, $username, $id]);
            }
            $_SESSION['mensaje'] = "Usuario actualizado!";
        } else {
            // Crear
            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, username, password_hash) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $username, $password]);
            $_SESSION['mensaje'] = "Usuario creado!";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    header('Location: usuarios.php');
    exit;
}

// Cargar datos para editar
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();
    echo json_encode($usuario);
    exit;
}

// Resto del código...