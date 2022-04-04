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
$telefono  = recoge("telefono");
$correo    = recoge("correo");
$RFID       = recoge("RFID");
$id        = recoge("id");

$nombreOk    = false;
$apellidosOk = false;
$telefonoOk  = false;
$correoOk    = false;
$RFIDOk        = false;
$idOk        = false;


if (mb_strlen($nombre, "UTF-8") > $cfg["dbempleadosTamRFID"]) {
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

if (mb_strlen($telefono, "UTF-8") > $cfg["dbempleadosTamTelefono"]) {
    print "    <p class=\"aviso\">El teléfono no puede tener más de $cfg[dbempleadosTamTelefono] caracteres.</p>\n";
    print "\n";
} else {
    $telefonoOk = true;
}

if (mb_strlen($correo, "UTF-8") > $cfg["dbempleadosTamCorreo"]) {
    print "    <p class=\"aviso\">El correo no puede tener más de $cfg[dbempleadosTamCorreo] caracteres.</p>\n";
    print "\n";
} else {
    $correoOk = true;
}

if ($nombre == "" && $apellidos == "" && $telefono == "" && $correo == "" && $RFIDOk == "") {
    print "    <p class=\"aviso\">Hay que rellenar al menos uno de los campos. No se ha guardado el registro.</p>\n";
    print "\n";
    $nombreOk = $apellidosOk = $telefonoOk = $correoOk = $RFIDOk = false;
}

if ($id == "") {
    print "    <p class=\"aviso\">No se ha seleccionado ningún registro.</p>\n";
} else {
    $idOk = true;
}

if ($nombreOk && $apellidosOk && $telefonoOk && $correoOk && $idOk $RFIDOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[dbempleadosTabla]
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
        $consulta = "SELECT COUNT(*) FROM $cfg[dbempleadosTabla]
                     WHERE nombre = :nombre
                     AND apellidos = :apellidos
                     AND telefono = :telefono
                     AND correo = :correo
                     AND RFID = :RFID
                     AND id <> :id";

        $resultado = $pdo->prepare($consulta);
        if (!$resultado) {
            print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos,  ":telefono" => $telefono, ":correo" => $correo, ":RFID" => $RFID, ":id" => $id])) {
            print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif ($resultado->fetchColumn() > 0) {
            print "    <p class=\"aviso\">Ya existe un registro con esos mismos valores. "
                . "No se ha guardado la modificación.</p>\n";
        } else {
            $consulta = "UPDATE $cfg[dbempleadosTabla]
                         SET nombre = :nombre, apellidos = :apellidos,
                             telefono = :telefono, correo = :correo, RFID = :RFID
                         WHERE id = :id";

            $resultado = $pdo->prepare($consulta);
            if (!$resultado) {
                print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos,  ":telefono" => $telefono, ":correo" => $correo, ":id" => $id, ":RFID" => $RFID])) {
                print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p>Registro modificado correctamente.</p>\n";
            }
        }
    }
}

$pdo = null;

pie();
