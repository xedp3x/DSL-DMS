<?php
/*
 * Beinhaltet kleine Funktioen die etwas
 * ändern oder Beschädigen können.
 * 
 * Sie liefern immer nur einen wert zurück
 */


function TOOL_ping($dest){
	exec("ping -c 5 -i 0.2 \"$dest\"",$back);
	return array2br($back);
}

function TOOL_nslookup($dest){
	exec("nslookup $dest",$back);
	return array2br($back);
}
