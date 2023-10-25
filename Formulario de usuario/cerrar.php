<?php

session_start();

$_SESSION=array();

Session_destroy();

header("Location: http://localhost/Formulario%20de%20usuario/inicio.html");
exit();
?>