<?php
/*
 * Datenbank der VoIP Konten
 * Daten müssen aber noch in
 * Asterisk geladen werden !
 */

if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}


function VOIP_get_nummern($s_id){
	$SQL = SQL_select_as_array("voip"," `s_id` = '$s_id'");
	return $SQL;
}
function VOIP_get_info($telnum){
	$SQL = SQL_select_as_array("voip"," `name` = '$telnum'");
	while( list ( $key, $val ) = each ( $SQL[0] ) ){
		if ($val <> ''){
			$out[$key] = $val;
		}
	}
	return $out;
}
function VOIP_get_list($s_id){
	$get 	= VOIP_get_nummern($s_id);
	while( list ( $key, $val ) = each ( $get ) ){
		$out.= $val["name"]."<br>";
	}
	return $out;
}
function VOIP_set($s_id,$telnum,$pass){
	$sql = array (
		"name"	=> $telnum,
		"s_id"	=> $s_id,
		"secret"=> $pass
	);
	SQL_insert_update("voip",$sql);
}
function VOIP_del($telnum, $s_id){
	SQL_delete("voip","name = $telnum AND s_id = $s_id");
}

/*
function VOIP_load(){
	$sql	= SQL_select_as_array("voip");
	$sip	= 	"[general]\n".
				"port = 5060\n".
				"bindaddr = 0.0.0.0\n".
				"context = sonstige\n\n";
	$ext	=	"[sonstige]\n\n".
				"[meine-telefone]\n\n".
				"exten => 1234,1,Playback(demo-echotest)\n".
				"exten => 1234,n,Echo\n".           
				"exten => 1234,n,Playback(demo-echodone)\n".
				"exten => 1234,n,Goto(s,6)\n\n";	
	while( list ( $key, $val ) = each ( $sql ) ){
	$sip.= 	"[{$val["telnum"]}]\n".
			"type=friend\n".
			"context=meine-telefone\n".
			"secret={$val["pass"]}\n".
			"host=dynamic\n\n";
	$ext.= 	"exten => {$val["telnum"]},1,Dial(SIP/{$val["telnum"]})\n";
	}
	if (insel){
		echo "<font color='red'> Inselmodus daher nicht geladen </font>
		<a onmouseover='showWMTT(\"debug\")' onmouseout='hideWMTT()'> info </a> 
		</center>
		<div id='debug' style='display: none;'>
		<pre>				
		SIP:
		$sip
		
		
		EXT:
		$ext
		</pre>
		</div>
		<center><br />";
	}else{
		include('Net/SFTP.php');
		
		$sftp = new Net_SFTP('10.1.1.13');
		if (!$sftp->login('root', 'Pass001')) {exit('Login Failed');}
		
		$sftp->put('/etc/asterisk/sip.conf'			, $sip);
		$sftp->put('/etc/asterisk/extensions.conf'	, $ext);
	
		$ret = $sftp->exec("asterisk -r -x 'reload'");
		if ($ret <> ""){
			error("E201",$ret);
		}
	}
	return true;
}
*/