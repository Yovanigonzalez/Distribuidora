<?php
include '../config/conexion.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $sql = "SELECT id, nombre FROM productos WHERE nombre LIKE '%$query%'";
    $result = $conn->query($sql);

    $productos = array();

    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    echo json_encode($productos);
}
?>
