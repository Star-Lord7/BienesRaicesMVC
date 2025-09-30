<?php

    // if(!isset($_SESSION['login'])){ //Si el usuario no ha iniciado sesion
    //     session_start(); //Iniciar la sesion
    // // }

    $auth = $_SESSION['login'] ?? false; //Si la variable de sesion 'login' existe, asigna su valor a $auth, si no, asigna false

    if(!isset($inicio)){
        $inicio = false; //Si la variable $inicio no está definida, la definimos como false
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienes Raices</title>
    <link rel="stylesheet" href="../build/css/app.css">
</head>
<body>
    <header class="header <?php echo $inicio ? 'inicio' : ''; ?>">
        <div class="contenedor contenido-header">
            <div class="barra">
                <a href="/">
                    <img src="/build/img/logo.svg" alt="Logotipo de Bienes Raices">
                </a>
                <div class="mobile-menu">
                    <img src="/build/img/barras.svg" alt="">
                </div>

                <div class="derecha">
                    <img class="dark-mode-boton" src="/build/img/dark-mode.svg" alt="Modo Oscuro">
                    <nav class="navegacion">
                        <a href="/nosotros">Nosotros</a>
                        <a href="/propiedades">Anuncios</a>
                        <a href="/blog">Blog</a>
                        <a href="/contacto">Contacto</a>
                        <?php if(!$auth): ?>
                            <a href="/login">Iniciar Sesión</a>
                        <?php else: ?>
                            <a href="/logout">Cerrar Sesión</a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div><!-- Cierre de la Barra -->

            <?php echo $inicio ? "<h1>Venta de Casas y Departamentos Exclusivos de Lujo</h1>" : ""; ?>
        </div>
    </header>

    <?php echo $contenido; ?>

    <footer class="footer seccion">
        <div class="contenedor contenedor-footer">
            <nav class="navegacion">
                <a href="nosotros.php">Nosotros</a>
                <a href="anuncios.php">Anuncios</a>
                <a href="blog.php">Blog</a>
                <a href="contacto.php">Contacto</a>
            </nav>
        </div>

        <?php
            $fecha = date('Y');
        ?>

        <p class="copyright">&copy; <?php echo $fecha; ?> Bienes Raices. Todos los derechos reservados.</p>
    </footer>

    <script src="../build/js/bundle.min.js"></script>
</body>
</html>