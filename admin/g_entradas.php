<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    include '../config/conexion.php';

    // Verifica la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Obtén los datos del formulario
    $categoria = $_POST["categoria"];
    $producto = $_POST["producto"];
    $stock = $_POST["stock"];

    // Verificar si ya existe un registro con la misma categoría y producto
    $sql_check = "SELECT * FROM entradas WHERE categoria = ? AND producto = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $categoria, $producto);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Ya existe un registro con la misma categoría y producto, actualiza el stock
        $sql_update = "UPDATE entradas SET stock = stock + ? WHERE categoria = ? AND producto = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iss", $stock, $categoria, $producto);

        if ($stmt_update->execute()) {
            // Redirigir a 'entradas.php' con un mensaje de éxito
            header("Location: entradas.php?success=true");
            exit();
        } else {
            // Redirigir a 'entradas.php' con un mensaje de error
            header("Location: entradas.php?success=false&error_message=" . urlencode("Error al actualizar el stock: " . $stmt_update->error));
            exit();
        }
    } else {
        // No existe un registro con la misma categoría y producto, inserta uno nuevo
        $sql_insert = "INSERT INTO entradas (categoria, producto, stock) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssi", $categoria, $producto, $stock);

        if ($stmt_insert->execute()) {
            // Redirigir a 'entradas.php' con un mensaje de éxito
            header("Location: entradas.php?success=true");
            exit();
        } else {
            // Redirigir a 'entradas.php' con un mensaje de error
            header("Location: entradas.php?success=false&error_message=" . urlencode("Error al guardar los datos: " . $stmt_insert->error));
            exit();
        }
    }

    // Cierra las sentencias y la conexión
    $stmt_check->close();
    $stmt_update->close();
    $stmt_insert->close();
    $conn->close();
}
?>