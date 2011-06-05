<?php
/*
 * API zum zugriff auf sie PPPoE User
 */

if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}


function PPP_get($s_id){
	$SQL = SQL_select_as_array("ppp"," `s_id` = '$s_id'");
	return $SQL;
}
function PPP_state($username){
	return SQL_select_one("ppp", "username = '$username'");
}
function PPP_set($s_id,$usern,$pass,$ip_c = "*",$ip_s = "10.64.0.1"){
	$sql = array (
		"s_id"		=> $s_id,
		"username"	=> $usern,
		"password"	=> $pass,
		"ip_client"	=> $ip_c,
		"ip_server"	=> $ip_s,
		"status"	=> 0
	);
	SQL_insert_update("ppp",$sql);
}
function PPP_del($usern, $s_id){
	SQL_delete("ppp","username = '$usern' AND s_id = $s_id");
}

function PPP_ip_get(){
	do {
		$ip = "10.7".mt_rand(0,7).".".mt_rand(0,255).".".mt_rand(0,255);
		$SQL = SQL_select_as_array("ppp"," `ip_client` = '$ip'");
	} while (isset($SQL["s_id"]));
	return $ip;
}
