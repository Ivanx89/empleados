<?php
/**
 * @author Iván Alcolea
 */

require_once "../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (!isset($_SESSION["conectado"]) || $_SESSION["nivel"] < NIVEL_ADMINISTRADOR) {
    header("Location:../index.php");
    exit;
}

$borrar = recoge("borrar");

if ($borrar != "Sí") {
    header("Location:index.php");
    exit;
}

$pdo = conectaDb();

cabecera("Administrador - Borrar todo 2", MENU_ADMINISTRADOR, PROFUNDIDAD_1);

borraTodo();

$pdo = null;

pie();
