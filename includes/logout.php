<?php
    //Inicializamos la sesión actual
    session_start();

    //Limpiamos las variables de sesión
    session_unset();

    //Destruimos la sesión completamente
    session_destroy();

    //Redirigimos al usuario a la página de inicio
    header("Location: ../index.php");

    //Evitamos que se ejecute el código posterior
    exit;
?>