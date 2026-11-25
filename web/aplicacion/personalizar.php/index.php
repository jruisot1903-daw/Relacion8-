<?php
include_once(dirname(__FILE__) . "/../../cabecera.php");

$barraUbi = [
    ["TEXTO" => "Inicio", "LINK" => "/index.php"],
    ["TEXTO" => "Principal", "LINK" => "/aplicacion/principal/index.php"],
];

//dibuja la plantilla de la vista
inicioCabecera("Relación 8");
finCabecera();
inicioCuerpo("Relación 8", $barraUbi);
cuerpo();  //llamo a la vista
finCuerpo();
// **********************************************************

//vista
function cuerpo()
{

}