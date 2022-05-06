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
    echo "Connected successfully<br>";

    $sql = "SELECT * FROM empleados.logs WHERE RFID = '$card' AND Fecha = CURRENT_DATE;";

    if ($result = mysqli_query($conn, $sql)) {
        $rowcount = mysqli_num_rows( $result );
        printf("Registro Nuevo", $rowcount);
        if ($rowcount == 0) {
            $sql = "INSERT INTO empleados.logs (nombre, apellidos, RFID, HoraEntrada, HoraSalida, Fecha) SELECT nombre, apellidos, RFID, CURRENT_TIME, NULL, CURRENT_DATE FROM empleados.empleados WHERE empleados.RFID = '$card';";
            if ($result = mysqli_query($conn, $sql)) {
                $rowcount = mysqli_num_rows( $result );
            printf("Insert hecho");
            } else {
                printf("Insert Error");
            }
        }if ($rowcount > 0) {
            $sql = "SELECT * FROM empleados.logs WHERE RFID = '$card' AND Fecha = CURRENT_DATE AND HoraSalida IS NULL;";
            if ($result = mysqli_query($conn, $sql)) {
                $rowcount = mysqli_num_rows( $result );
                if ($rowcount > 0) {
                    $sql = "UPDATE empleados.logs SET HoraSalida = CURRENT_TIME WHERE HoraSalida IS NULL;";
                    if ($result = mysqli_query($conn, $sql)) {
                        echo "Update HoraSalida hecho";
                    }
                } else {
                    $sql = "INSERT INTO empleados.logs (nombre, apellidos, RFID, HoraEntrada, HoraSalida, Fecha) SELECT nombre, apellidos, RFID, CURRENT_TIME, NULL, CURRENT_DATE FROM empleados.empleados WHERE empleados.RFID = '$card';";
                    if ($result = mysqli_query($conn, $sql)) {
                        $rowcount = mysqli_num_rows( $result );
                    printf("Insert Nuevo hecho");
                    } else {
                        printf("Insert Nuevo Error");
                    }
                }
            }

        }else{
                echo "INSERTADO NUEVO";

    }
        
    } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
$pdo = null;