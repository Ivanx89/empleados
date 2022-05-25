<?php
/**
 * @author Iván Alcolea
 */

require_once "comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();





cabecera("Inicio", MENU_PRINCIPAL, PROFUNDIDAD_0);

pie();
