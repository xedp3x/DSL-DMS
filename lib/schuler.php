<?php
/*
 * API zur verwaltung der Schuler und Klassen
 */

if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}


function KLASSE_get_list(){
	$set = SQL_select_as_array("klassen");	
	while( list ( $key, $val ) = each ( $set ) ){
		$out[$key] = $val["klasse"];
	}
	return $out;
}
function KLASSE_set($klasse, $lehrer){
	$sql = array (
		"klasse"	=> $klasse,
		"lehrer"	=> $lehrer,
	);
	SQL_insert_update("klassen",$sql);
}

function SCHULER_get($klasse, $schuler, $pass){
	$sql = SQL_select_one("schuler", "klasse = '$klasse' AND schuler='$schuler'");
	if ($sql["pass"] == $pass){
		return $sql["s_id"];
	}else{
		return false;
	}
}

function SCHULER_get_by_id($s_id){
	$sql = SQL_select_one("schuler", "s_id = '$s_id'");
	unset($sql["pass"]);
	return $sql;
}

function SCHULER_get_list($klasse = False){
	if (! $klasse) {error("E101","API Fehler");}
		
	$set = SQL_select_as_array("schuler","klasse = '$klasse'");
	while( list ( $key, $val ) = each ( $set ) ){
		$out[$key] = $val["schuler"];
	}
	return $out;
}
function SCHULER_set($klasse,$schuler,$pass,$komm){
	$sql = array (
		"klasse"	=> $klasse,
		"schuler"	=> $schuler,
		"kommentar"	=> $komm,
		"pass"		=> $pass
	);
	SQL_insert_update("schuler",$sql);
}

function SCHULER_update($s_id,$pass_alt, $pass_neu,$komm){
	return SQL_update("schuler", "s_id = $s_id AND pass = '$pass_alt'", "pass = '$pass_neu', kommentar = '$komm'");
}
function SCHULER_del($klasse,$schuler){
	return SQL_delete("schuler", "klasse = '$klasse' AND schuler = '$schuler'");
}



