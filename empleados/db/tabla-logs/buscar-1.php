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

cabecera("Logs - Buscar 1", MENU_empleados, PROFUNDIDAD_2);

$consulta = "SELECT COUNT(*) FROM $cfg[dblogsTabla]";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif ($resultado->fetchColumn() == 0) {
    print "    <p class=\"aviso\">No se ha creado todavía ningún registro.</p>\n";
} else {
    print "    <form action=\"buscar-2.php\" method=\"$cfg[formMethod]\">\n";
    print "      <p>Escriba el criterio de búsqueda (caracteres o números):</p>\n";
    print "\n";
    print "      <table>\n";
    print "        <tbody>\n";
    print "          <tr>\n";
    print "            <td>Nombre:</td>\n";
    print "            <td><input type=\"text\" name=\"nombre\" size=\"$cfg[formempleadosTamNombre]\" maxlength=\"$cfg[formempleadosTamNombre]\" autofocus></td>\n";
    print "          </tr>\n";
    print "          <tr>\n";
    print "            <td>Apellidos:</td>\n";
    print "            <td><input type=\"text\" name=\"apellidos\" size=\"$cfg[formempleadosTamApellidos]\" maxlength=\"$cfg[formempleadosTamApellidos]\"></td>\n";
    print "          </tr>\n";
    print "          <tr>\n";
    print "            <td>RFID:</td>\n";
    print "            <td><input type=\"text\" name=\"RFID\" size=\"$cfg[formempleadosTamRFID]\" maxlength=\"$cfg[formempleadosTamRFID]\"></td>\n";
    print "          </tr>\n";
    print "          <tr>\n";
    print "            <td>Hora:</td>\n";
    print "            <td><input type=\"time\" name=\"Hora\" size=\"$cfg[formlogsTamHora]\" maxlength=\"$cfg[formlogsTamHora]\"></td>\n";
    print "          </tr>\n";
    print "        </tbody>\n";
    print "      </table>\n";
    print "\n";
    print "      <p>\n";
    print "        <input type=\"submit\" value=\"Buscar\">\n";
    print "        <input type=\"reset\" value=\"Reiniciar formulario\">\n";
    print "      </p>\n";
    print "    </form>\n";
}

$pdo = null;

pie();
