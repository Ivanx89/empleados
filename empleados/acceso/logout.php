<?php
/**
 * @author Iván Alcolea
 */

require_once "../comunes/biblioteca.php";

session_name($cfg["sessionName"]);
session_start();

session_destroy();

header("location:../index.php");
