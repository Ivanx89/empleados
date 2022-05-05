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

cabecera("Logs - Borrar 1", MENU_empleados, PROFUNDIDAD_2);

$ordena = recogeValores("ordena", $cfg["dblogsColumnasOrden"], "nombre ASC");
$id     = recoge("id", []);

$consulta = "SELECT * FROM $cfg[dblogsTabla]
             ORDER BY $ordena";

$resultado = $pdo->query($consulta);
if (!$resultado) {
    print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
} elseif (!count($registros = $resultado->fetchAll())) {
    print "    <p class=\"aviso\">No se ha creado todavía ningún registro.</p>\n";
} else {
    print "    <form action=\"$_SERVER[PHP_SELF]\" method=\"$cfg[formMethod]\">\n";
    print "      <p>Marque los registros que quiera borrar:</p>\n";
    print "\n";
    print "      <table class=\"conborde franjas\">\n";
    print "        <thead>\n";
    print "          <tr>\n";
    print "            <th>Borrar</th>\n";
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
        if (isset($id[$registro["id"]])) {
            print "            <td class=\"centrado\"><input type=\"checkbox\" name=\"id[$registro[id]]\" checked></td>\n";
        } else {
            print "            <td class=\"centrado\"><input type=\"checkbox\" name=\"id[$registro[id]]\"></td>\n";
        }
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
    print "\n";
    print "      <p>\n";
    print "        <input type=\"submit\" value=\"Borrar registro\" formaction=\"borrar-2.php\">\n";
    print "        <input type=\"reset\" value=\"Reiniciar formulario\">\n";
    print "      </p>\n";
    print "    </form>\n";
}

$pdo = null;

pie();
