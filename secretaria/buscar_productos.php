<?php
include '../config/conexion.php';

if(isset($_POST['query'])){
    $query = $_POST['query'];

    // Escapa caracteres especiales para evitar inyecciones SQL
    $query = mysqli_real_escape_string($conexion, $query);

    // Realiza la consulta en la base de datos
    $sql = "SELECT * FROM productos WHERE nombre LIKE '%$query%'";
    $result = mysqli_query($conexion, $sql);

    // Muestra los resultados
    while($row = mysqli_fetch_assoc($result)){
        echo '<p>'.$row['nombre'].'</p>';
    }
}
?>
