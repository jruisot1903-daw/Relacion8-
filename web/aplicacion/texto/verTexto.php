<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");


if (!$acceso->hayUsuario()) {
    header("Location: /aplicacion/acceso/login.php");
    exit;
}
// CONTROLADOR

$barraUbi = [
    ["TEXTO" => "Inicio",  "LINK" => "/index.php"],
    ["TEXTO" => "Textos",  "LINK" => ""],
];

// Al inicio recogemos los textos que haya en la sesión
$textos = $_SESSION['texto'] ?? [];

$errores = [];
$texto   = '';

// REGISTRAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    $texto = trim($_POST['texto'] ?? '');

    if ($texto === '') {
        $errores['texto'] = "Debes escribir un texto";
    } elseif (mb_strlen($texto) > 200) {
        $errores['texto'] = "El texto no puede superar los 200 caracteres";
    } else {
        $textos[] = new RegistroTexto($texto);
        $_SESSION['texto'] = $textos; // actualizamos la sesión

        // Redirigimos para que F5 no vuelva a registrar el mismo texto
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// LIMPIAR: solo se vacían los textos, NO se destruye la sesión
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['limpiar'])) {
    $textos = [];
    $_SESSION['texto'] = $textos;

    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// VISTA

inicioCabecera("Textos");
finCabecera();

inicioCuerpo("Textos", $barraUbi);
cuerpo($errores, $textos, $texto);
finCuerpo();

function cuerpo(array $errores, array $textos, string $texto)
{
?>
    <form action="" method="post">
        <label for="texto">Texto:</label>
        <input type="text" name="texto" id="texto" value="<?= htmlspecialchars($texto) ?>">
        <span style="color:red"><?= $errores['texto'] ?? '' ?></span>
        <button type="submit" name="registrar">Registrar</button>
    </form>

    <h3>Textos registrados:</h3>
    <textarea rows="10" cols="60" readonly><?php foreach ($textos as $t) echo htmlspecialchars($t->__toString()) . "\n"; ?></textarea>

    <form action="" method="post">
        <button type="submit" name="limpiar">Limpiar</button>
    </form>
<?php
}