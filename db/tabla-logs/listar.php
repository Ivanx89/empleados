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

cabecera("LOGS - Listar", MENU_empleados, PROFUNDIDAD_2);

$ordena = recogeValores("ordena", $cfg["dblogsColumnasOrden"], "HoraEntrada DESC");

$filtro = $_GET["filtro"];

if ($filtro == "") {
    $consulta = "SELECT * FROM $cfg[dblogsTabla]
             ORDER BY $ordena";
}elseif ($filtro == "semana") {
    $consulta = "SELECT * FROM $cfg[dblogsTabla] WHERE Fecha >= CURDATE() - INTERVAL 7 DAY ORDER BY $ordena;";
}elseif ($filtro == "mes") {
    $consulta = "SELECT * FROM $cfg[dblogsTabla] WHERE Fecha >= CURDATE() - INTERVAL 1 MONTH ORDER BY $ordena;";;
}elseif ($filtro == "ano") {
    $consulta = "SELECT * FROM $cfg[dblogsTabla] WHERE Fecha >= CURDATE() - INTERVAL 1 YEAR ORDER BY $ordena;";;
}

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif (!count($registros = $resultado->fetchAll())) {
    print "    <p class=\"aviso\">No se ha creado todavía ningún registro.</p>\n";
} else {
    print " <form action=\"filtrar.php\" method=\"post\"> ";
    print " <label>Año<label/>";
    print " <input type=\"radio\" name=\"filtrar\" value=\"ano\"/><br>";
    print " <label>Semana<label/>";
    print " <input type=\"radio\" name=\"filtrar\" value=\"semana\"/><br>";
    print " <label>Mes<label/>";
    print " <input type=\"radio\" name=\"filtrar\" value=\"mes\"/><br>";
    print " <label>Todo<label/>";
    print " <input type=\"radio\" name=\"filtrar\" value=\"todo\"/><br>";
    print " <input type=\"submit\" name=\"boton\" value=\"Listar\"/>";
    print "</form>";

    print "    <p>Listado completo de registros:</p>\n";
    print "\n";
    print "    <form action=\"$_SERVER[PHP_SELF]\" method=\"$cfg[formMethod]\">\n";
    print "      <table class=\"conborde franjas\">\n";
    print "        <thead>\n";
    print "          <tr>\n";
    print "            <th>\n";
    print "              <button name=\"ordena\" value=\"nombre ASC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/abajo.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "              Nombre\n";
    print "              <button name=\"ordena\" value=\"nombre DESC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/arriba.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "            </th>\n";
    print "            <th>\n";
    print "              <button name=\"ordena\" value=\"apellidos ASC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/abajo.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "              Apellidos\n";
    print "              <button name=\"ordena\" value=\"apellidos DESC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/arriba.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "            </th>\n";
    print "            <th>\n";
    print "              <button name=\"ordena\" value=\"RFID ASC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/abajo.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "              RFID\n";
    print "              <button name=\"ordena\" value=\"RFID DESC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/arriba.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "            </th>\n";
    print "            <th>\n";
    print "              <button name=\"ordena\" value=\"HoraEntrada ASC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/abajo.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "              Hora Entrada\n";
    print "              <button name=\"ordena\" value=\"HoraEntrada DESC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/arriba.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "            </th>\n";
    print "            <th>\n";
    print "              <button name=\"ordena\" value=\"HoraSalida ASC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/abajo.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "              Hora Salida\n";
    print "              <button name=\"ordena\" value=\"HoraSalida DESC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/arriba.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "            </th>\n";
    print "            <th>\n";
    print "              <button name=\"ordena\" value=\"Fecha ASC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/abajo.svg\" alt=\"A-Z\" title=\"A-Z\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "              Fecha\n";
    print "              <button name=\"ordena\" value=\"Fecha DESC\" class=\"boton-invisible\">\n";
    print "                <img src=\"../../img/arriba.svg\" alt=\"Z-A\" title=\"Z-A\" width=\"15\" height=\"12\">\n";
    print "              </button>\n";
    print "            </th>\n";
    print "          </tr>\n";
    print "        </thead>\n";
    print "        <tbody>\n";
    foreach ($registros as $registro) {
        print "          <tr>\n";
        print "            <td>$registro[nombre]</td>\n";
        print "            <td>$registro[apellidos]</td>\n";
        print "            <td>$registro[RFID]</td>\n";
        print "            <td>$registro[HoraEntrada]</td>\n";
        print "            <td>$registro[HoraSalida]</td>\n";
        print "            <td>$registro[Fecha]</td>\n";
        print "          </tr>\n";
    }
    print "        </tbody>\n";
    print "      </table>\n";
    print "    </form>\n";

    print " <form action=\"export.php\" method=\"post\"> ";
    print " <input type=\"submit\" name=\"export\" class=\"excel\"value=\"Exportar a Excel\"/>";
    print "</form>";
}

$pdo = null;

pie();
