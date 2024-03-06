<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
  <link rel="stylesheet" href="css/menu.css">
  <!-- Logo -->
  <link rel="shortcut icon" type="image/x-icon" href="ico/logo.png">

  <style>
    /* Estilos para la imagen de login */
nav a img {
  width: 25px; /* Ancho deseado */
  height: 25px; /* Altura deseada */
}

/* Estilo adicional si deseas cambiar el cursor al pasar el ratón sobre la imagen */
nav a:hover {
  cursor: pointer;
}

  </style>

</head>
<body>
  <header>
    <img class="logo" src="img/logo.png" alt="Logo de Distribuidora">
    
    <nav>
      <a href="login.php">Inicio</a>
        <img src="img/usuario.png" alt="Login">
      </a>

    </nav>

    <!-- Icono de hamburguesa -->
    <div class="burger-icon" onclick="toggleNav()">
      <img src="fonts/menu.png" alt="Menú">
    </div>
  </header>

  <!-- Contenido de la página aquí -->

  <script>
    function toggleNav() {
      var nav = document.querySelector('nav');
      nav.style.display = (nav.style.display === 'none' || nav.style.display === '') ? 'flex' : 'none';
    }
  </script>
</body>
</html>
