<?php
/*
 * Managet alle Fehlermeldungen
 */

function logging($message, $from = "DMS"){
	$exec =	str_replace('(','[',
			str_replace(')',']',
			str_replace('<br />','',
			str_replace("\r",'',
			str_replace("\n",'',
				"bash -c 'logger -t $from $message'"
			)))));
	exec($exec);
}

function error($ID, $Fehler){
	echo "Der Fehler $ID ist aufgetreten :-(<br /><br />
	<img src='img/sorry.png' /> <br /> <br />
	Der Fehler Lautet <br />
	$Fehler";
		
	logging("Error $ID - $Fehler");
	die ("<br /> Ablauf wurde gestoppt");
}

function DebMsg($msg){
	global $_SESSION;
	$_SESSION["debug"][(count($_SESSION["debug"]) + 1)] = $msg;
}

function dmesg($text, $type = 'info', $host = false, $timeout = 600){
	if (! $host){$host = $_SERVER["REMOTE_ADDR"];}
	$exec = "bash -c 'zenity --text=\"$text\" --$type --timeout=$timeout --display=$host:0 > /dev/null 2>&1 &'";
	exec($exec);
}
