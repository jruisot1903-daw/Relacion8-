<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

// CONTROLADOR

$barraUbi = [
    ["TEXTO" => "Inicio",       "LINK" => "/index.php"],
    ["TEXTO" => "Personalizar", "LINK" => ""],
];

// Colores guardados en cookies. Nunca nos fiamos de la cookie:
// si no existe o no es un color permitido, usamos el valor por defecto.
$fondo = $_COOKIE['colorFondo'] ?? 'blanco';
$texto = $_COOKIE['colorTexto'] ?? 'negro';
if (!is_string($fondo) || !array_key_exists($fondo, COLORESFONDO)) {
    $fondo = 'blanco';
}
if (!is_string($texto) || !array_key_exists($texto, COLORESTEXTO)) {
    $texto = 'negro';
}

// Contador de visitas guardado en una cookie
$visitas = max(0, (int)($_COOKIE['visitas'] ?? 0)) + 1;
setcookie('visitas', (string)$visitas, [
    'expires'  => time() + 30 * 24 * 3600,
    'path'     => '/',
    'httponly' => true,
    'samesite' => 'Lax',
]);

// VISTA

inicioCabecera("Personalizar");
cabecera($fondo, $texto);
finCabecera();

inicioCuerpo("Personalizar", $barraUbi); 
cuerpo($visitas);
finCuerpo();

function cabecera(string $fondo, string $texto)
{
    echo "<style>#documento { background-color: " . COLORESFONDO[$fondo] .
         "; color: " . COLORESTEXTO[$texto] . "; }</style>";
}

function cuerpo(int $visitas)
{
?>
    <div id="documento">
        <h2>Página de ejemplo</h2>
        <p>Este texto se muestra con los colores que hayas configurado.</p>
        <p>Has accedido a esta página <strong><?= $visitas ?></strong> veces.</p>
        <p><a href="personalizar.php">Cambiar colores</a></p>
    </div>
<?php
}