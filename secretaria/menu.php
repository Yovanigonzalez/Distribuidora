<?php
session_start();
// Resto del código de login.php

if (empty($_SERVER['HTTP_REFERER'])) {
    // El acceso se está realizando directamente desde la URL
    header('Location: error.php');
    exit();
}
?>

<?php
include '../config/conexion.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--Logo-->
  <link rel="shortcut icon" type="image/x-icon" href="../log/ico.png">

  <!-- Enlaces a los archivos CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
  <!-- Icono de la página -->
  <link rel="icon" href="../log/logo2.jpg" type="image/jpeg">

  <!-- Enlaces a los archivos JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>

      <!-- Agregar enlaces a los archivos CSS de Bootstrap y Krub font -->
      <link href="https://fonts.googleapis.com/css2?family=Krub:wght@400;700&display=swap" rel="stylesheet">

<!-- Agregar estilo personalizado para la fuente -->
<style>
    body {
        font-family: 'Krub', sans-serif;
    }

    .form-label, .form-control, .btn {
        font-family: 'Krub', sans-serif;
    }
</style>

</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Barra de navegación superior -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Barra de navegación a la izquierda -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
          </a>
        </li>
      </ul>

      <!-- Botones de la barra de navegación a la derecha -->
      <ul class="navbar-nav ml-auto">

      <style>
    .center-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }


    .rounded-circle-container {
        width: 100px;
        height: 100px;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .rounded-circle-container img {
        max-width: 70px;
        max-height: 70px;
        border-radius: 50%;
    }
</style>

        <style>
    .nav-icon {
        margin-right: 20px; /* Puedes ajustar el valor según tus preferencias */
    }
</style>

<?php
if (isset($_SESSION['nombre_usuario'])) {
    echo '<div class="nav-icon position-relative text-dark"><i class="fa fa-user-circle"></i> Hola! Bienvenido, ' . $_SESSION['nombre_usuario'] . '</div>';
    echo '<a class="nav-icon position-relative text-decoration-none" href="logout.php">';
    echo '<i class="fa fa-fw fa-sign-out-alt text-dark"></i> Cerrar Sesión</a>';

} else {
    echo '<a class="nav-icon position-relative text-decoration-none" href="login.php">';
    echo '<i class="fa fa-fw fa-user text-dark"></i> Iniciar Sesión</a>';
}
?>
        
      </ul>
    </nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Logo -->
  <a href="inicio.php" class="brand-link">
    <span class="brand-text font-weight-light">Panel de caja</span>
    <div class="center-container">
    <div class="rounded-circle-container">
        <img src="../log/logo2.jpg" class="img-fluid" alt="Login Image">
    </div>
</div>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Menú -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        
      <!-- Agregar Usuarios-->

        
      <li class="nav-item">
    <a href="ventas.php" class="nav-link">
        <i class="nav-icon fas fa-cash-register"></i> <!-- Cambiado a un icono relacionado con ventas -->
        <p>Ventas</p>
    </a>
</li>

<li class="nav-item">
    <a href="pedidos.php" class="nav-link"> <!-- Cambiado a "pedidos.php" en lugar de "logout.php" -->
        <i class="nav-icon fas fa-list-alt"></i> <!-- Cambiado a un icono relacionado con pedidos -->
        <p>Pedidos</p>
    </a>
</li>

<li class="nav-item">
    <a href="cajas.php" class="nav-link">
        <i class="nav-icon fas fa-cash-register"></i> <!-- Puedes ajustar este icono según lo que represente "Cajas" en tu contexto -->
        <p>Cajas</p>
    </a>
</li>

<li class="nav-item">
    <a href="nota.php" class="nav-link">
        <i class="nav-icon fas fa-file-alt"></i> <!-- Cambiado a un icono relacionado con notas o documentos -->
        <p>Notas</p>
    </a>
</li>

<li class="nav-item">
    <a href="localizador.php" class="nav-link">
        <i class="nav-icon fas fa-file-alt"></i> <!-- Cambiado a un icono relacionado con notas o documentos -->
        <p>Localizar Notas</p>
    </a>
</li>

<li class="nav-item">
    <a href="logout.php" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i> <!-- Cambiado a un icono de cerrar sesión -->
        <p>Cerrar sesión</p>
    </a>
</li>

  </aside>