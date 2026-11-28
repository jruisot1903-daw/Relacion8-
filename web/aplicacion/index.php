<?php
// index.php de la raíz: es la única página a la que se puede entrar sin validarse
include_once(dirname(__FILE__) . "/cabecera.php");

// CONTROLADOR

$barraUbi = [
    ["TEXTO" => "Inicio", "LINK" => ""],
];

// VISTA

inicioCabecera("Inicio");
finCabecera();

inicioCuerpo("Inicio", $barraUbi);
cuerpo($acceso);
finCuerpo();

function cuerpo(Acceso $acceso)
{
?>
    <h2>Bienvenido</h2>
    <?php if ($acceso->hayUsuario()) { ?>
        <p>Hola, <strong><?= htmlspecialchars($acceso->getNombre()) ?></strong>. Ya puedes usar las opciones del menú.</p>
    <?php } else { ?>
        <p>Para acceder al resto de páginas tienes que <a href="/aplicacion/acceso/login.php">iniciar sesión</a>.</p>
    <?php } ?>
<?php
}