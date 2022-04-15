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


$pdo = conectaDb();

cabecera("Empleados - Scan", MENU_empleados, PROFUNDIDAD_2);

print "    <form action=\"insertar-2.php\" method=\"$cfg[formMethod]\">\n";
print "      <p>Por favor pase la tarjeta por el lector y pulse continuar...</p>\n";
print "\n";
print "      <table>\n";
print "        <tbody>\n";
print "          <tr>\n";
print "            <td><input type=\"hidden\" name=\"RFID\" size=\"$cfg[formempleadosTamRFID]\" maxlength=\"$cfg[formempleadosTamRFID]\" value=\"$_SESSION[scan]\" autofocus></td>\n";
print "          </tr>\n";
print "        </tbody>\n";
print "      </table>\n";
print "\n";
print "      <p>\n";
print "        <input type=\"submit\" value=\"Añadir\">\n";
print "        <input type=\"reset\" value=\"Reiniciar formulario\">\n";
print "      </p>\n";
print "    </form>\n";


$pdo = null;

pie();
