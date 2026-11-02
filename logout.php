
<?php

// Iniciamos la sesión
session_start();

// Eliminamos todas las variables de sesión
$_SESSION = [];

// Eliminamos la cookie de sesión del navegador actual
if (ini_get("session.use_cookies")) {

    $parametros = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $parametros["path"],
        $parametros["domain"],
        $parametros["secure"],
        $parametros["httponly"]
    );
}

// Destruimos la sesión actual
session_destroy();

// Volvemos al inicio
header("Location: index.php");
exit;

?>
