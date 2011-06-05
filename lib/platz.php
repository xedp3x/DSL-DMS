<?php

if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}


function RAUM_get_platz($raum){
	$out = SQL_select_as_array("platz", "raum = '$raum' AND type = 'user'");
	return $out;
} 
