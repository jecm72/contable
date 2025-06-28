<?php if (isset($_SESSION['mensaje'])): ?>
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <?= $_SESSION['mensaje'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php unset($_SESSION['mensaje']); endif; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
 <!-- Navbar -->
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Sistema Contable UMG</a>
            <div class="navbar-nav">
          
                <a class="nav-link" href="crud.php">Usuarios</a>
                <a class="nav-link" href="empresas.php">Empresas</a>
                 <a class="nav-link" href="cuentas.php">Cuentas</a>
                
                <a class="nav-link" href="asientos.php">Asientos</a>
                <a class="nav-link" href="balance_general.php">Balance General</a>
               
                <a  class="nav-link" href="estado_resultados.php">Estado de Resultados</a>
                <a class="nav-link" href="estados_financieros.php">Estados de Financieros</a>
                
                <a class="nav-link text-danger" href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    </body>