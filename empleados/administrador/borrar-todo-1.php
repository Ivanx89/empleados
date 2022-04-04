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

cabecera("Administrador - Borrar todo 1", MENU_ADMINISTRADOR, PROFUNDIDAD_1);

print "    <form action=\"borrar-todo-2.php\" method=\"$cfg[formMethod]\">\n";
print "      <p>¿Está seguro?</p>\n";
print "\n";
print "      <p>\n";
print "        <input type=\"submit\" name=\"borrar\" value=\"Sí\">\n";
print "        <input type=\"submit\" name=\"borrar\" value=\"No\">\n";
print "      </p>\n";
print "    </form>\n";

pie();
