<?php
/*
 * API für Kontenverwaltung vom Mailserver
 * 
 */


if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}


function MAIL_user_set($adress,$pass,$s_id){
	$split	= split('@',$adress);
	
	$sql = array (
		"email"		=> $adress,
		"s_id"		=> $s_id,
		"domain"	=> $split[1],
		"pass"		=> "ENCRYPT('$pass')",
		"quota"		=> 10485760
	);
	SQL_insert_update("mail_user",$sql);
}

function MAIL_gpg($adress,$gpg,$s_id){
	$sqL = SQL_update("mail_user", "email = '$adress' AND s_id = $s_id", "gpg = '$gpg'");
	return $sql;
}

function MAIL_get_list($s_id){
	$SQL = SQL_select_as_array("mail_user"," `s_id` = '$s_id'");
	return $SQL;
}

function MAIL_get($adress){
	$SQL = SQL_select_one("mail_user", "email = '$adress'");
	return $SQL;
}

function MAIL_user_del($adress, $s_id){
	return SQL_delete("mail_user", "email = '$adress' AND s_id = $s_id");
}