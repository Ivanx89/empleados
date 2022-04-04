<?php
/**
 * @author Iván Alcolea
 */

require_once "./comunes/biblioteca.php";

session_name("getdata");
session_start();


$pdo = conectaDb();
$card = recoge("card"); 
$consulta = "SELECT * FROM $cfg[dbempleadosTabla] WHERE RFID = '$card'";

$resultado = $pdo->query($consulta);
if (!count($registros = $resultado->fetchAll())) {
    $insert = "INSERT INTO $cfg[dbempleadosTabla] (RFID) VALUES (:RFID)";
    
    print "    <p class=\"aviso\">INSERTADO</p>\n";

    $resultado = $pdo->prepare($insert);
    if (!$resultado) {
        print "    <p class=\"aviso\">Error al preparar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } elseif (!$resultado->execute([":RFID" => $card])) {
        print "    <p class=\"aviso\">Error al ejecutar la consulta. SQLSTATE[{$pdo->errorCode()}]: {$pdo->errorInfo()[2]}</p>\n";
    } else {
        print "    <p>Registro creado correctamente.</p>\n";
        
    }
    
} else {
    print "    <p>Ya existía ese registro $card.</p>\n";
}

session_destroy();
$pdo = null;