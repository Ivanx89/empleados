<?php
/**
 * @author Iván Alcolea
 */

// OPCIONES DISPONIBLES PARA EL ADMINISTRADOR DE LA APLICACIÓN

// Nombre de sesión

$cfg["sessionName"] = "empleados";       // Nombre de sesión

// Base de datos utilizada por la aplicación

$cfg["dbMotor"] = MYSQL;                                   // Valores posibles: MYSQL o SQLITE

// Configuración para SQLite

$cfg["sqliteDatabase"] = "/tmp/mclibre-base-datos-3-b-0.sqlite";    // Ubicación de la base de datos

// Configuración para MySQL

$cfg["mysqlHost"]     = "mysql:host=localhost";             // Nombre de host
$cfg["mysqlUser"]     = "root";         // Nombre de usuario
$cfg["mysqlPassword"] = "";                                 // Contraseña de usuario
$cfg["mysqlDatabase"] = "empleados";         // Nombre de la base de datos

// Tamaño de los campos en la tabla Usuarios

$cfg["dbUsuariosTamUsuario"]  = 20;                         // Tamaño de la columna Usuarios > Nombre de usuario
$cfg["dbUsuariosTamPassword"] = 64;                         // Tamaño de la columna Usuarios > Contraseña de usuario (cifrada)

// Tamaño de los controles en los formularios

$cfg["formUsuariosTamUsuario"]  = $cfg["dbUsuariosTamUsuario"];     // Tamaño de la caja de texto Usuario > Nombre de usuario
$cfg["formUsuariosTamPassword"] = 20;                               // Tamaño de la caja de texto Usuario > Contraseña

// Tamaño de los campos en la tabla empleados

$cfg["dbempleadosTamNombre"]    = 40;                        // Tamaño de la columna empleados > Nombre
$cfg["dbempleadosTamApellidos"] = 60;                        // Tamaño de la columna empleados > Apellidos
$cfg["dbempleadosTamTelefono"]  = 10;                        // Tamaño de la columna empleados > Teléfono
$cfg["dbempleadosTamCorreo"]    = 50;                        // Tamaño de la columna empleados > Correo
$cfg["dbempleadosTamRFID"]    = 50;                        // Tamaño de la columna empleados > Correo
$cfg["dblogsTamHora"]    = 6;                        // Tamaño de la columna empleados > Correo

// Tamaño de los controles en los formularios

$cfg["formempleadosTamNombre"]    = $cfg["dbempleadosTamNombre"];     // Tamaño de la caja de texto empleados > Nombre
$cfg["formempleadosTamApellidos"] = $cfg["dbempleadosTamApellidos"];  // Tamaño de la caja de texto empleados > Apellidos
$cfg["formempleadosTamTelefono"]  = $cfg["dbempleadosTamTelefono"];   // Tamaño de la caja de texto empleados > Teléfono
$cfg["formempleadosTamCorreo"]    = $cfg["dbempleadosTamCorreo"];     // Tamaño de la caja de texto empleados > Correo
$cfg["formempleadosTamRFID"]      = $cfg["dbempleadosTamRFID"];     // Tamaño de la caja de texto empleados > Correo
$cfg["formlogsTamHora"]           = $cfg["dblogsTamHora"];     // Tamaño de la caja de texto empleados > Correo

// Número máximo de registros en las tablas

$cfg["dbUsuariosMaxReg"] = 20;                              // Número máximo de registros en la tabla Usuarios
$cfg["dbempleadosMaxReg"] = 20;                              // Número máximo de registros en la tabla empleados
$cfg["dblogsMaxReg"] = 20;                              // Número máximo de registros en la tabla empleados

// Usuario Administrador de la aplicación

$cfg["rootName"]      = "root";                             // Nombre del Usuario Administrador de la aplicación
$cfg["rootPassword"]  = "4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2";  // Contraseña encriptada del Usuario Administrador de la aplicación
$cfg["hashAlgorithm"] = "sha256";                           // Algoritmo hash para encriptar la contraseña de usuario
                                                            // Los posibles algoritmos son https://www.php.net/manual/en/function.hash-algos.php
$cfg["rootPasswordModificable"] = false;                    // Contraseña del usuario Administrador se puede cambiar o no

// Método de envío de formularios

$cfg["formMethod"] = "get";                                 // Valores posibles: get o post
