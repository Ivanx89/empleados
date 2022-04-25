<?php
/**
 * @author Iván Alcolea
 */

require_once "../../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"]) || $_SESSION["nivel"] < NIVEL_USUARIO_BASICO) {
    header("Location:../../index.php");
    exit;
}


$radiofiltro = recoge("filtrar");

if ($radiofiltro == "") {
    header("location:listar.php");
}elseif ($radiofiltro == "semana") {
    header("location:listar.php?filtro=semana");
}elseif ($radiofiltro == "mes") {
    header("location: listar.php?filtro=mes");
}elseif ($radiofiltro == "ano") {
    header("location:listar.php?filtro=ano");
}elseif ($radiofiltro == "todo") {
    header("location:listar.php");
}else {
    header("location:listar.php");
}


echo $radiofiltro;