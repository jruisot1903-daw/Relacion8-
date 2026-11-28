<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");


if (!$acceso->puedePermiso(1)) {
    paginaError("No tienes permiso para acceder a esta página");
    exit;
}
// CONTROLADOR

$barraUbi = [
    ["TEXTO" => "Inicio",       "LINK" => "/index.php"],
    ["TEXTO" => "Personalizar", "LINK" => "index.php"],
    ["TEXTO" => "Colores",      "LINK" => ""],
];

$errores = [];
$mensaje = '';

// Colores guardados en cookies (validados: no nos fiamos de la cookie)
$fondo = $_COOKIE['colorFondo'] ?? 'blanco';
$texto = $_COOKIE['colorTexto'] ?? 'negro';
if (!is_string($fondo) || !array_key_exists($fondo, COLORESFONDO)) {
    $fondo = 'blanco';
}
if (!is_string($texto) || !array_key_exists($texto, COLORESTEXTO)) {
    $texto = 'negro';
}

// Valores que aparecen seleccionados en los combos
$datos = ['fondo' => $fondo, 'texto' => $texto];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $datos['fondo'] = $_POST['fondo'] ?? '';
    $datos['texto'] = $_POST['texto'] ?? '';

    // Solo se aceptan claves que existan en las constantes
    if (!is_string($datos['fondo']) || !array_key_exists($datos['fondo'], COLORESFONDO)) {
        $errores['fondo'] = "Selecciona un color de fondo válido";
    }
    if (!is_string($datos['texto']) || !array_key_exists($datos['texto'], COLORESTEXTO)) {
        $errores['texto'] = "Selecciona un color de texto válido";
    }

    // Texto y fondo con el mismo color harían la página ilegible
    if (empty($errores) && COLORESFONDO[$datos['fondo']] === COLORESTEXTO[$datos['texto']]) {
        $errores['texto'] = "El texto no puede ser del mismo color que el fondo";
    }

    if (empty($errores)) {
        $opciones = [
            'expires'  => time() + 30 * 24 * 3600,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ];
        setcookie('colorFondo', $datos['fondo'], $opciones);
        setcookie('colorTexto', $datos['texto'], $opciones);

        // setcookie no actualiza $_COOKIE en esta petición: usamos ya los nuevos colores
        $fondo = $datos['fondo'];
        $texto = $datos['texto'];
        $mensaje = "Colores guardados correctamente";
    }
    // Con errores, la página sigue viéndose con los colores ya guardados
}

// VISTA

inicioCabecera("Personalizar colores");
cabecera($fondo, $texto);
finCabecera();

inicioCuerpo("Personalizar colores", $barraUbi); // ajusta los parámetros a la firma de tu plantilla
cuerpo($errores, $mensaje, $datos);
finCuerpo();

function cabecera(string $fondo, string $texto)
{
    echo "<style>#documento { background-color: " . COLORESFONDO[$fondo] .
         "; color: " . COLORESTEXTO[$texto] . "; }</style>";
}

function cuerpo(array $errores = [], string $mensaje = '', array $datos = [])
{
?>
    <div id="documento">
        <h2>Personalizar colores</h2>

        <?php if ($mensaje !== '') { ?>
            <p><strong><?= htmlspecialchars($mensaje) ?></strong></p>
        <?php } ?>

        <form action="personalizar.php" method="post">
            <p>
                <label for="fondo">Color de fondo:</label>
                <select name="fondo" id="fondo">
                    <?php foreach (COLORESFONDO as $clave => $css) { ?>
                        <option value="<?= $clave ?>" <?= (($datos['fondo'] ?? '') === $clave) ? 'selected' : '' ?>>
                            <?= ucfirst($clave) ?>
                        </option>
                    <?php } ?>
                </select>
                <span style="color:red"><?= $errores['fondo'] ?? '' ?></span>
            </p>

            <p>
                <label for="texto">Color del texto:</label>
                <select name="texto" id="texto">
                    <?php foreach (COLORESTEXTO as $clave => $css) { ?>
                        <option value="<?= $clave ?>" <?= (($datos['texto'] ?? '') === $clave) ? 'selected' : '' ?>>
                            <?= ucfirst($clave) ?>
                        </option>
                    <?php } ?>
                </select>
                <span style="color:red"><?= $errores['texto'] ?? '' ?></span>
            </p>

            <button type="submit" name="guardar">Guardar</button>
        </form>

        <p><a href="index.php">Volver</a></p>
    </div>
<?php
}