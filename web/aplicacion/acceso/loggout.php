<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

// Solo quita el registro del usuario (no destruye la sesión) y vuelve a index.php
$acceso->quitarRegistroUsuario();
session_regenerate_id(true);

header("Location: /index.php");
exit;
