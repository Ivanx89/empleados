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

cabecera("empleados - Añadir 2", MENU_empleados, PROFUNDIDAD_2);

$nombre    = recoge("nombre");
$apellidos = recoge("apellidos");
$telefono  = recoge("telefono");
$correo    = recoge("correo");
$RFID    = recoge("RFID");

$nombreOk    = false;
$apellidosOk = false;
$telefonoOk  = false;
$correoOk    = false;
$RFIDOk    = false;

if (mb_strlen($RFID, "UTF-8") > $cfg["dbempleadosTamRFID"]) {
    print "    <p class=\"aviso\">El RFID no puede tener más de $cfg[dbempleadosTamRFID] caracteres.</p>\n";
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

if ($nombre == "" && $apellidos == "" && $telefono == "" && $correo == "" && $RFID == "") {
    print "    <p class=\"aviso\">Hay que rellenar al menos uno de los campos. No se ha guardado el registro.</p>\n";
    print "\n";
    $nombreOk = $apellidosOk = $telefonoOk = $correoOk = $RFIDOk = false;
}

if ($nombreOk && $apellidosOk && $telefonoOk && $correoOk && $RFIDOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[dbempleadosTabla]
                 WHERE nombre = :nombre
                 AND apellidos = :apellidos
                 AND telefono = :telefono
                 AND correo = :correo
                 AND RFID = :RFID";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":telefono" => $telefono, ":correo" => $correo,  ":RFID" => $RFID])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() > 0) {
        print "    <p class=\"aviso\">El registro ya existe.</p>\n";
    } else {
        $consulta = "SELECT COUNT(*) FROM $cfg[dbempleadosTabla]";

        $resultado = $pdo->query($consulta);
        if (!$resultado) {
            print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif ($resultado->fetchColumn() >= $cfg["dbempleadosMaxReg"]) {
            print "    <p class=\"aviso\">Se ha alcanzado el número máximo de registros que se pueden guardar.</p>\n";
            print "\n";
            print "    <p class=\"aviso\">Por favor, borre algún registro antes de insertar un nuevo registro.</p>\n";
        } else {
            $consulta = "INSERT INTO $cfg[dbempleadosTabla]
                         (nombre, apellidos, telefono, correo, RFID)
                         VALUES (:nombre, :apellidos, :telefono, :correo, :RFID)";

            $resultado = $pdo->prepare($consulta);
            if (!$resultado) {
                print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":telefono" => $telefono, ":correo" => $correo, ":RFID" => $RFID])) {
                print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p>Registro creado correctamente.</p>\n";
            }
        }
    }
}

$pdo = null;

pie();
