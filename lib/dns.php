<?php
/*
 * API für die DNS einträge
 * 
 * CHATCH beachten ! ! !
 */


if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}


function DNS_get($domain,$type = 'A'){
	if ($domain[strlen($domain)-1] <> "."){$domain = $domain.".";}
	$SQL = SQL_select_one("dns_rr","type = '$type' AND name = '$domain'");
	return $SQL;
}
function DNS_get_tld($domain){
	if ($domain[strlen($domain)-1] <> "."){$domain = $domain.".";}
	
	$split	= split('[.]',$domain);
	$num 	= 0;
	$max	= count($split)-2;
	
	do{
		$tld = '';
		for($i = $num; $i <= $max; $i++)
		{
			$tld = $tld.$split[$i].'.';
		}		
		$sql = SQL_select_one("dns_soa","origin = '$tld'","id");
		$num++;
	}WHILE ((!isset($sql["id"])) AND ($num <= $max));
	return $sql["id"];	
}
function DNS_set($type, $domain, $data, $s_id ,$ptr = False, $ppp_ref = False){
	if ($domain[strlen($domain)-1] <> "."){$domain = $domain.".";}
	$sql = array (
		"data"		=> $data,
		"s_id"		=> $s_id,
		"name"		=> $domain,
		"type"		=> $type,
		"zone"		=> DNS_get_tld($domain)
	);
	if ($ppp_ref){
		$sql["ppp_username"] = $ppp_ref;
	}
	SQL_insert_update("dns_rr",$sql);
	
	if ($ptr)
	{
		if (($type <> "PTR") and ($type <> "ptr")){
			list ($ip1, $ip2, $ip3, $ip4) = split('[.]', $data);
			DNS_set("PTR","$ip4.$ip3.$ip2.$ip1.in-addr.arpa.", $domain, $s_id,False);
		}else{
			error("dns_52","automarischer RTP aus ein PTR ist nicht möglich");
		}
	}
}
function DNS_get_list($s_id){
	$out = SQL_select_as_array("dns_rr","s_id = '$s_id'");	
	return $out;
}
function DNS_del($name, $type, $s_id){
	SQL_delete("dns_rr","name = '$name' AND type = '$type' AND s_id = $s_id");
}