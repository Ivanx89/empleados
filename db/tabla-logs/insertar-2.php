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
$RFID      = recoge("RFID");
$Hora      = recoge("Hora");

$nombreOk    = false;
$apellidosOk = false;
$RFIDOk    = false;
$HoraOk = false;

if (mb_strlen($Hora, "UTF-8") > $cfg["dblogsTamHora"]) {
    print "    <p class=\"aviso\">La hora no puede tener más de $cfg[dblogsTamHora] caracteres.</p>\n";
    print "\n";
} else {
    $HoraOk = true;
}

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

if ($nombre == "" && $apellidos == "" && $RFID == "" && $Hora == "") {
    print "    <p class=\"aviso\">Hay que rellenar al menos uno de los campos. No se ha guardado el registro.</p>\n";
    print "\n";
    $nombreOk = $apellidosOk = $HoraOk = $RFIDOk = false;
}

if ($nombreOk && $apellidosOk && $RFIDOk && $HoraOk) {
    $consulta = "SELECT COUNT(*) FROM $cfg[dblogsTabla]
                 WHERE nombre = :nombre
                 AND apellidos = :apellidos
                 AND Hora   =  :Hora
                 AND RFID = :RFID";

    $resultado = $pdo->prepare($consulta);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":RFID" => $RFID, ":Hora" => $Hora])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif ($resultado->fetchColumn() > 0) {
        print "    <p class=\"aviso\">El registro ya existe.</p>\n";
    } else {
        $consulta = "SELECT COUNT(*) FROM $cfg[dblogsTabla]";

        $resultado = $pdo->query($consulta);
        if (!$resultado) {
            print "    <p class=\"aviso\">Error en la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
        } elseif ($resultado->fetchColumn() >= $cfg["dblogsMaxReg"]) {
            print "    <p class=\"aviso\">Se ha alcanzado el número máximo de registros que se pueden guardar.</p>\n";
            print "\n";
            print "    <p class=\"aviso\">Por favor, borre algún registro antes de insertar un nuevo registro.</p>\n";
        } else {
            $consulta = "INSERT INTO $cfg[dblogsTabla]
                         (nombre, apellidos, RFID, Hora)
                         VALUES (:nombre, :apellidos, :RFID, :Hora)";

            $resultado = $pdo->prepare($consulta);
            if (!$resultado) {
                print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } elseif (!$resultado->execute([":nombre" => $nombre, ":apellidos" => $apellidos, ":RFID" => $RFID, ":Hora" => $Hora])) {
                print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
            } else {
                print "    <p>Registro creado correctamente.</p>\n";
            }
        }
    }
}

$pdo = null;

pie();
