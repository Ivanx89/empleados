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

cabecera("Logs - Modificar 2", MENU_empleados, PROFUNDIDAD_2);

$id = recoge("id");

if ($id == "") {
    print "    <p class=\"aviso\">No se ha seleccionado ningún registro.</p>\n";
} else {
    $consulta = "SELECT * FROM $cfg[dblogsTabla]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!($registro = $resultado->fetch())) {
        print "    <p class=\"aviso\">Registro no encontrado.</p>\n";
    } else {
        print "    <form action=\"modificar-3.php\" method=\"$cfg[formMethod]\">\n";
        print "      <p>Modifique los campos que desee:</p>\n";
        print "\n";
        print "      <table>\n";
        print "        <tbody>\n";
        print "          <tr>\n";
        print "            <td>Nombre:</td>\n";
        print "            <td><input type=\"text\" name=\"nombre\" size=\"$cfg[formempleadosTamNombre]\" maxlength=\"$cfg[formempleadosTamNombre]\" value=\"$registro[nombre]\" autofocus></td>\n";
        print "          </tr>\n";
        print "          <tr>\n";
        print "            <td>Apellidos:</td>\n";
        print "            <td><input type=\"text\" name=\"apellidos\" size=\"$cfg[formempleadosTamApellidos]\" maxlength=\"$cfg[formempleadosTamApellidos]\" value=\"$registro[apellidos]\"></td>\n";
        print "          </tr>\n";
        print "          <tr>\n";
        print "            <td>RFID:</td>\n";
        print "            <td><input type=\"text\" name=\"RFID\" size=\"$cfg[formempleadosTamRFID]\" maxlength=\"$cfg[formempleadosTamRFID]\" value=\"$registro[RFID]\"></td>\n";
        print "          </tr>\n";
        print "          <tr>\n";
        print "            <td>Hora:</td>\n";
        print "            <td><input type=\"time\" name=\"Hora\" size=\"$cfg[formlogsTamHora]\" maxlength=\"$cfg[formlogsTamHora]\" value=\"$registro[Hora]\"></td>\n";
        print "          </tr>\n";
        print "          <tr>\n";
        print "            <td>Fecha:</td>\n";
        print "            <td><input type=\"date\" name=\"Fecha\" value=\"$registro[Fecha]\"></td>\n";
        print "          </tr>\n";
        print "        </tbody>\n";
        print "      </table>\n";
        print "\n";
        print "      <p>\n";
        print "        <input type=\"hidden\" name=\"id\" value=\"$id\">\n";
        print "        <input type=\"submit\" value=\"Actualizar\">\n";
        print "        <input type=\"reset\" value=\"Reiniciar formulario\">\n";
        print "      </p>\n";
        print "    </form>\n";
    }
}

$pdo = null;

pie();
