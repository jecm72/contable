<?php
include 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
echo "<pre>";
print_r($_POST);
echo "</pre>";
if (isset($_POST['guardar_asiento'])) {
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];
    $cuentas = $_POST['cuentas'] ?? [];
    $montos = $_POST['montos'] ?? [];
    $tipos = $_POST['tipos'] ?? [];

    try {
        // Validaciones básicas (corregido el paréntesis)
        if (empty($cuentas)) throw new Exception("Debe agregar al menos una transacción");
        if (count($cuentas) !== count($montos) || count($montos) !== count($tipos)) {
            throw new Exception("Datos de transacciones incompletos");
        }

        $pdo->beginTransaction();

        // Insertar asiento
        $stmt = $pdo->prepare("INSERT INTO asientos_contables (fecha, descripcion) VALUES (?, ?)");
        if (!$stmt->execute([$fecha, $descripcion])) {
            throw new Exception("Error al crear el asiento");
        }
        $id_asiento = $pdo->lastInsertId();

        $total_debe = 0;
        $total_haber = 0;
        
        // Insertar transacciones
        foreach ($cuentas as $i => $id_cuenta) {
            $monto = (float) $montos[$i];
            $tipo = $tipos[$i];

            if ($monto < 0) throw new Exception("Monto inválido en la línea " . ($i + 1));
            if (!in_array($tipo, ['Debe', 'Haber'])) throw new Exception("Tipo inválido en la línea " . ($i + 1));

            $stmt = $pdo->prepare("
                INSERT INTO transacciones (id_asiento, id_cuenta, monto, tipo)
                VALUES (?, ?, ?, ?)
            ");
            if (!$stmt->execute([$id_asiento, $id_cuenta, $monto, $tipo])) {
                throw new Exception("Error al guardar la transacción");
            }

            // Sumar totales
            ($tipo === 'Debe') ? $total_debe += $monto : $total_haber += $monto;
        }

        // Validar partida doble
        if (abs($total_debe - $total_haber) > 0.001) {
            throw new Exception("Error: Débito ($" . number_format($total_debe, 2) . ") ≠ Crédito ($" . number_format($total_haber, 2) . ")");
        }

        $pdo->commit();
        $_SESSION['mensaje'] = "Asiento registrado exitosamente!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = $e->getMessage();
        error_log("Error en asiento: " . $e->getMessage()); // Registrar error en servidor
    }
    header('Location: asientos.php');
    exit;
}

// Resto del código...



// Eliminar Asiento
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    try {
        $pdo->beginTransaction();
        $pdo->prepare("DELETE FROM transacciones WHERE id_asiento = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM asientos_contables WHERE id_asiento = ?")->execute([$id]);
        $pdo->commit();
        $_SESSION['mensaje'] = "Asiento eliminado!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Error al eliminar: " . $e->getMessage();
    }
    header('Location: asientos.php');
    exit;
}