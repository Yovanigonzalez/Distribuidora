<?php include 'menu.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
    <style>
        /* Estilos para pantallas más grandes (md y superior) */
        @media (min-width: 768px) {
            .custom-card {
                max-width: 800px; /* Ancho máximo para la tarjeta en pantallas medianas y superiores */
            }

            .custom-image {
                max-width: 80%; /* Tamaño máximo de la imagen en pantallas medianas y superiores */
            }
        }

        /* Estilos para pantallas más pequeñas (sm y inferior) */
        @media (max-width: 767px) {
            .custom-image {
                max-width: 100%; /* La imagen ocupa el ancho completo en pantallas más pequeñas */
            }
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-md-6 d-flex align-items-center justify-content-center">
                            <img src="log/LOGO.png" alt="Imagen de fondo" class="img-fluid mb-3 mx-auto" style="max-width: 80%;">
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h2 class="card-title text-center">Login</h2>
                                <form action="login_process.php" method="post">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Usuario:</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña:</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
