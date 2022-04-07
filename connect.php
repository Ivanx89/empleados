<?php
/* Configuración de la Base de Datos */
	$servername = "localhost";
    $username = "root";		//phpmyadmin password (root)
    $password = "";			//if your phpmyadmin has a password put it here.(default is "root")
    $dbname = "empleados";
    
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
        die("No se ha podido conectar a la base de datos: " . $conn->connect_error);
    }
?>
