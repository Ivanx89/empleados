<?php
/**
 * @author Iván Alcolea
 */

require_once "./comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

$card = recoge("card"); 
$_SESSION['scan'] = $card;

$pdo = conectaDb();

$consulta = "SELECT * FROM $cfg[dbempleadosTabla] WHERE RFID = '$card'";

$resultado = $pdo->query($consulta);
if (!count($registros = $resultado->fetchAll())) {
   echo "Sent to admin $_SESSION[scan]" ;
} else {
    print "    <p>Ya existía ese registro $card.</p>\n";

    $servername = "localhost";
    $username = "root";
    $password = "";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    echo "Connected successfully";

    $sql = "INSERT INTO empleados.logs (nombre, apellidos, RFID, Hora) SELECT nombre, apellidos, RFID, CURRENT_TIMESTAMP FROM empleados.empleados WHERE empleados.RFID = '$card';";

    if (mysqli_query($conn, $sql)) {
    echo "New record created successfully";
    } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
$pdo = null;