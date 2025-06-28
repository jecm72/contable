<?php
include 'config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
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
                <a class="nav-link" href="estado_resultados.php">Estado de Resultados</a>
                <a class="nav-link" href="estados_financieros.php">Estados de Financieros</a>
                
                <a class="nav-link text-danger" href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    <div class="row">
            <div class="col-md-30">
                <div class="card shadow">
            <?php $nombre = $_SESSION['nombre'] ;  $apellido = $_SESSION['apellido'] ; ?>
                <!-- Contenido dinámico según la página -->
           
                <h3>Bienvenido, <?php  echo  $nombre; echo" ",$apellido; ?></h3>
                </div>
                </div>
            </div>
            </div>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-20">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title">Menú</h5>
                        <ul class="list-group">
                            <li class="list-group-item"><a href="crud.php">Usuarios</a></li>
                            <li class="list-group-item"><a href="empresas.php">Empresas</a></li>
                            <li class="list-group-item"><a href="cuentas.php">Cuentas</a></li>
                           
                            <li class="list-group-item"><a href="asientos.php">Asientos </a></li>
                            <li class="list-group-item"><a href="balance_general.php">Balance General</a></li>
                            <li class="list-group-item"><a href="estado_resultados.php">Estado de Resultados</a></li>
                            <li class="list-group-item"><a href="estados_financieros.php">Estado de Financieros</a></li>
                            <li class="list-group-item"><a href="logout.php">Salir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container mt-4">
       
        </div>
    </div>
    <br>
<br>
<br>
<br>
<br>
<br>
<br>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            
           
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            
           
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            
           
        </div>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            
           
        </div>
    </nav>
</body>
</html>