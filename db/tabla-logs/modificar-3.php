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

cabecera("Empleados - Modificar 3", MENU_empleados, PROFUNDIDAD_2);

$nombre    = recoge("nombre");
$apellidos = recoge("apellidos");
$RFID       = recoge("RFID");
$Hora       = recoge("Hora");
$id        = recoge("id");

$nombreOk    = false;
$apellidosOk = false;
$HoraOk   = false;
$RFIDOk        = false;
$idOk        = false;

if (mb_strlen($Hora, "UTF-8") > $cfg["dblogsTamHora"]) {
    print "    <p class=\"aviso\">La hora no puede tener más de $cfg[dblogsTamHora] caracteres.</p>\n";
    print "\n";
} else {
    $HoraOk = true;
}

if (mb_strlen($RFID, "UTF-8") > $cfg["dbempleadosTamRFID"]) {
    print "    <p class=\"aviso\">El nombre no puede tener más de $cfg[dbempleadosTamRFID] caracteres.</p>\n";
    print "\n";
} else {
    $RFIDOk = true;
}

if (mb_strlen($nombre, "UTF-8") > $cfg["dbempleadosTamNombre"]) {
    print "    <p class=\"aviso\">El nombre no puede tener más de $cfg[dbempleadosTamNombre] caracteres.</p>\n";
    print "\n";
} else {
    $nombreOk = true;
}

if (mb_strlen($apellidos, "UTF-8") > $cfg["dbempleadosTamApellidos"]) {
    print "    <p class=\"aviso\">Los apellidos no pueden tener más de $cfg[dbempleadosTamApellidos] caracteres.</p>\n";
    print "\n";
} else {
    $apellidosOk = true;
}
if ($nombre == "" && $apellidos == "" && $RFIDOk == "" && $HoraOk == "") {
    print "    <p class=\"aviso\">Hay que rellenar al menos uno de los campos. No se ha guardado el registro.</p>\n";
    print "\n";
    $nombreOk = $apellidosOk = $RFIDOk = $HoraOk = false;
}

if ($id == "") {
    print "    <p class=\"aviso\">No se ha seleccionado ningún registro.</p>\n";
} else {
    $idOk = true;
}

if ($nombreOk && $apellidosOk && $idOk && $RFIDOk && $HoraOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[dblogsTabla]
                 WHERE id = :id";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":id" => $id])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() == 0) {
        print "    <p class=\"aviso\">Registro no encontrado.</p>\n";
    } else {
        // La consulta cuenta los registros con un id diferente porque MySQL no distingue
        // mayúsculas de minúsculas y si en un registro sólo se cambian mayúsculas por
        // minúsculas MySQL diría que ya hay un registro como el que se quiere guardar.
        $consulta = "SELECT COUNT(*) FROM $cfg[dblogsTabla]
                     WHERE nombre = :nombre
                     AND apellidos = :apellidos
                     AND RFID = :RFID
                     AND Hora = :Hora
                     AND id <> :id";

        $resultado = $pdo->prepare($consulta);
        if (!$resultado) {
            print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":RFID" => $RFID, ":id" => $id, ":Hora" => $Hora])) {
            print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif ($resultado->fetchColumn() > 0) {
            print "    <p class=\"aviso\">Ya existe un registro con esos mismos valores. "
                . "No se ha guardado la modificación.</p>\n";
        } else {
            $consulta = "UPDATE $cfg[dblogsTabla]
                         SET nombre = :nombre, apellidos = :apellidos,
                             RFID = :RFID, Hora = :Hora
                         WHERE id = :id";

            $resultado = $pdo->prepare($consulta);
            if (!$resultado) {
                print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":id" => $id, ":RFID" => $RFID, ":Hora" => $Hora])) {
                print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p>Registro modificado correctamente.</p>\n";
            }
        }
    }
}

$pdo = null;

pie();
