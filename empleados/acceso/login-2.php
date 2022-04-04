<?php
/**
 * @author Iván Alcolea
 */

require_once "../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

if (isset($_SESSION["conectado"])) {
    header("Location:../index.php");
    exit;
}

$pdo = conectaDb();

$usuario  = recoge("usuario");
$password = recoge("password");

if (!$usuario) {
    header("Location:login-1.php?aviso=Error: Nombre de usuario no permitido");
    exit;
}

$consulta = "SELECT * FROM $cfg[dbUsuariosTabla]
             WHERE usuario = :usuario
             AND password = :password";

$resultado = $pdo->prepare($consulta);
if (!$resultado) {
    header("Location:login-1.php?aviso=Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}");
    exit;
}

$resultado->execute([":usuario" => $usuario, ":password" => encripta($password)]);
if (!$resultado) {
    header("Location:login-1.php?aviso=Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}");
    exit;
}

$registro = $resultado->fetch();
if (!is_array($registro)) {
    header("Location:login-1.php?aviso=Error: Nombre de usuario y/o contraseña incorrectos");
    exit;
}

$_SESSION["conectado"] = true;
$_SESSION["nivel"]     = $registro["nivel"];

$pdo = null;

header("Location:../index.php");
