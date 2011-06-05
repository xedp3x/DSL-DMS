<?php

include "lib/lib.php";
include_once 'gui/user.php';
if ($show_body){
	die('');
}

if (! isset($_GET["modul"])){
	error("E100","Kein API-Modul wurde geladen");
	die('');
}
if (! file_exists("modul/".$_GET["modul"].".php")){
	error("E100","Das Modul wurde nicht Gefunden");
}

include_once "modul/".$_GET["modul"].".php"; 

?>