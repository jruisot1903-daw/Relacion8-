<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

// CONTROLADOR

$barraUbi = [
    ["TEXTO" => "Inicio", "LINK" => "/index.php"],
    ["TEXTO" => "Login",  "LINK" => ""],
];

// Si ya está validado, no tiene sentido mostrar el login
if ($acceso->hayUsuario()) {
    header("Location: /index.php");
    exit;
}

$errores = [];
$nick    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entrar'])) {
    $nick       = trim($_POST['nick'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if ($nick === '') {
        $errores['nick'] = "Indica tu nick";
    }
    if ($contrasena === '') {
        $errores['contrasena'] = "Indica tu contraseña";
    }

    if (empty($errores)) {
        // 1. Se comprueba con la ACL
        if ($acl->esValido($nick, $contrasena)) {
            $cod = $acl->getCodUsuario($nick);

            // 2. Se guarda el usuario validado con la clase Acceso
            session_regenerate_id(true); // nuevo id al validarse (evita la fijación de sesión)
            $acceso->registrarUsuario($nick, $acl->getNombre($cod), $acl->getPermisos($cod));

            header("Location: /index.php");
            exit;
        }
        // Mensaje genérico: no se dice si falló el nick o la contraseña
        $errores['login'] = "Nick o contraseña incorrectos";
    }
}

// VISTA

inicioCabecera("Login");
finCabecera();

inicioCuerpo("Acceso", $barraUbi);
cuerpo($errores, $nick);
finCuerpo();

function cuerpo(array $errores, string $nick)
{
?>
    <form action="" method="post">
        <?php if (isset($errores['login'])) { ?>
            <p style="color:red"><?= htmlspecialchars($errores['login']) ?></p>
        <?php } ?>

        <p>
            <label for="nick">Nick:</label>
            <input type="text" name="nick" id="nick" value="<?= htmlspecialchars($nick) ?>">
            <span style="color:red"><?= $errores['nick'] ?? '' ?></span>
        </p>
        <p>
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena">
            <span style="color:red"><?= $errores['contrasena'] ?? '' ?></span>
        </p>
        <button type="submit" name="entrar">Entrar</button>
    </form>
<?php
}
