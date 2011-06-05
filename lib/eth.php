<?php
/*
 * API für den Zugriff auf dem Switch
 */

if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}

function ETH_link_remove($link_id){
	global $config;
	$link_eth 	= SQL_select_as_array("eth"		,"link_id = $link_id");
	$link_dsl 	= SQL_select_as_array("dslam"	,"link_id = $link_id");
	$link_platz	= SQL_select_as_array("eth,platz,dslam",
		"(eth.link_id 	= $link_id AND ((eth.eth_id = platz.eth1) 	OR (eth.eth_id = platz.eth2))) OR ".
		"(dslam.link_id = $link_id AND ((dslam.dsl_id = platz.dsl1) OR (dslam.dsl_id = platz.dsl2)))",
		"platz.ip AS ip",false, "ip");
	
	SQL_update("eth", 	"link_id = $link_id", "link_id = NULL");
	SQL_update("dslam",	"link_id = $link_id", "link_id = NULL");
	
	$set = $link_dsl;
	while( list ( $key, $val ) = each ( $set ) ){
		DSLAM_load($val["dsl_id"]);
	}
	
	$set = $link_eth;
	while( list ( $key, $val ) = each ( $set ) ){
		if (! insel){
			$set = $sql; // VLAN's in die Hardware
			$teln = new Net_TELN($val["name"].".sec.osz",23);
			
		 	$teln->send($config["switch"]["login"]."\r\n");// login Password
		 	$teln->send("enable\r\n");
		 	$teln->send($config["switch"]["enable"]."\r\n");// Admin Password

		 	$teln->send("configure terminal\r\n");
			$teln->send("interface fastEthernet 0/".$val["port"]."\r\n");
			$teln->send("shutdown\r\n");
			$teln->send("switchport access vlan ".$val["default"]."\r\n");
			$teln->send("no shutdown\r\n");
				
			$teln->send("exit\r\n");
			$teln->send("exit\r\n");
			$teln->send("exit\r\n"); 	
			}
		}
	
	$set = $link_platz;
	while( list ( $key, $val ) = each ( $set ) ){
		if ($val["ip"] <> $_SERVER["REMOTE_ADDR"]){
			dmesg(	"Einer oder mehrere Ports wurden \r\n".
					"aus einem Verbund getrennt. Daher \r\n".
					"sind sie auf Standarteinstellung \r\n".
					"gestellt wordem.", 'info', $val["ip"]);
		}
	}
}
	
function ETH_DSL($DSL_port, $type, $sitzungs_id){
	switch($type){
		case "chap": 
			$vlan = 300 + $DSL_port;
			break;
		case "pap": 
			$vlan = 400 + $DSL_port;
			break;
		default:
			error("ETH_03", "DSL-VLAN-Type nicht Vorhanden");
	}
	
	$link_id = SQL_insert("link", array(
			"mode"			=> $type,
			"vlan"			=> $vlan,
			"sitzungs_id"	=> $sitzungs_id
		));
	
	DSLAM_set($DSL_port, array("link_id" => $link_id));
	DSLAM_load($DSL_port);
	
	return true;	
}

function ETH_link_add($sitzungs_id, $eth, $link_id, $pass){
	
	echo "lib/eth ETH_link_add fehlt <br />";
}

function ETH_link($sitzungs_id, $eth = Array (), $type = "slef", $DSL_port = false){
	global $config;
	
	$set = $eth;
	while( list ( $key, $val ) = each ( $set ) ){
		$where .= "eth_id = $val OR ";
	}
	$where = substr($where,0,-4);
	$sql = SQL_select_as_array("eth", $where);
	
	
	switch($type){
		case "self": 
			$vlan = $sql[0]["res_vlan"];
			break;
		case "osz": 
			$type = "inter";
		case "inter": 
			$vlan = 5;
			break;
		case "chap": 
			$vlan = 300;
			break;
		case "pap": 
			$vlan = 400;
			break;
		case "user": 
			$vlan = 200;
			break;
		default:
			error("ETH_01", "VLAN kann nicht gebucht werden da Type nicht vorhanden");
	}	
	
	
	$set = $sql; // Alte VLAN's löschen
	while( list ( $key, $val ) = each ( $set ) ){
		if ($val["link_id"] <> null){
			ETH_link_remove($val["link_id"]);
		}
	}
		
	$link_id = SQL_insert("link", array(
				"mode"			=> $type,
				"vlan"			=> $vlan,
				"sitzungs_id"	=> $sitzungs_id
			));
			
	SQL_update("eth", $where, "link_id = $link_id");
	
	if (! insel){
		$set = $sql; // VLAN's in die Hardware
			while( list ( $key, $val ) = each ( $set ) ){
		 		$teln = new Net_TELN($val["name"].".sec.osz",23);
		 	
				$teln->send($config["switch"]["login"]."\r\n");// login Password
		 		$teln->send("enable\r\n");
		 		$teln->send($config["switch"]["enable"]."\r\n");// Admin Password
		 	
		 		$teln->send("configure terminal\r\n");
				$teln->send("interface fastEthernet 0/".$val["port"]."\r\n");
				$teln->send("shutdown\r\n");
		 		$teln->send("switchport access vlan $vlan\r\n");
		 		$teln->send("no shutdown\r\n");
		 		
				$teln->send("exit\r\n");
				$teln->send("exit\r\n");
				$teln->send("exit\r\n"); 	
		}
	}else{
		echo "<font color='red'> Inselmodus daher nicht geladen </font><br />";
	}
	
	if ($DSL_port){
		$set = $DSL_port; // VLAN's in die Hardware
		while( list ( $key, $val ) = each ( $set ) ){
			DSLAM_set($val, array("link_id" => $link_id));
			DSLAM_load($val);
		}
	}
	
	return $link_id;		
};

function ETH_status($port){
	global $config;
	$sql =  SQL_select_one("eth", "eth_id = $port");
	if (! insel){
		
	 	$teln = new Net_TELN($sql["name"].".sec.osz",23);
	 	
		$teln->send($config["switch"]["login"]."\r\n");// login Password
		$teln->send("enable\r\n");
	 //	$teln->send($config["switch"]["enable"]."\r\n");// Admin Password
	
	 	$teln->send("show interfaces fastEthernet 0/".$sql["port"]."\r\n\r\n\r\n\r\n\r");
		
		$teln->send("exit\r\n"); 	
		return str_replace('--More--          ','',array2str($teln->recv(),6,34));
	}else{
		echo "<font color='red'> Inselmodus daher nicht geladen </font><br />";
		return 'FastEthernet0/XX is down, line protocol is down (disabled)
  Hardware is Lance, address is 0000.0000.0000 (bia 0000.0000.0000)
 BW 100000 Kbit, DLY 1000 usec,
     reliability 255/255, txload 1/255, rxload 1/255
  Encapsulation ARPA, loopback not set
  Keepalive set (10 sec)
  Half-duplex, 100Mb/s
  input flow-control is off, output flow-control is off
  ARP type: ARPA, ARP Timeout 04:00:00
  Last input 00:00:00, output 00:00:00, output hang never
  Last clearing of "show interface" counters never
  Input queue: 0/75/0/0 (size/max/drops/flushes); Total output drops: 0
  Queueing strategy: fifo
  Output queue :0/40 (size/max)
  5 minute input rate 0 bits/sec, 0 packets/sec
  5 minute output rate 0 bits/sec, 0 packets/sec
     0 packets input, 0 bytes, 0 no buffer
     Received 0 broadcasts, 0 runts, 0 giants, 0 throttles
     0 input errors, 0 CRC, 0 frame, 0 overrun, 0 ignored, 0 abort
     0 watchdog, 0 multicast, 0 pause input
     0 input packets with dribble condition detected
     0 packets output, 0 bytes, 0 underruns
     0 output errors, 0 collisions, 10 interface resets
     0 babbles, 0 late collision, 0 deferred
     0 lost carrier, 0 no carrier
     0 output buffer failures, 0 output buffers swapped out';
	}
}

function ETH_link_find($eth, $dsl = False){
	$set = $eth;
	while( list ( $key, $val ) = each ( $set ) ){
		$where .= "eth_id = $val OR ";
	}$where = substr($where,0,-4);
	$out_eth = SQL_select_as_array("eth, link", "(".$where.") AND eth.link_id = link.link_id", "link.link_id AS link_id");
	
	$out_dsl = array();
	if ($dsl){
		$set = $dsl;
		$where 	= "";
		while( list ( $key, $val ) = each ( $set ) ){
			$where .= "dsl_id = $val OR ";
		}$where = substr($where,0,-4);
		$out_dsl = SQL_select_as_array("dslam, link", "(".$where.") AND dslam.link_id = link.link_id", "link.link_id AS link_id");
	}
	
	if ($out_eth == null){
		$out_eth = Array();
	}
	if ($out_dsl == null){
		$out_dsl = Array();
	}
	$set = array_merge($out_eth, $out_dsl);
	while( list ( $key, $val ) = each ( $set ) ){
		$out[$i++] = $val["link_id"];
	}
	$out = array_unique($out);
	return $out;	
}

function ETH_link_info($link_id){
	
	$link = SQL_select_one("link,
		(SELECT count(*) AS eth FROM link, eth WHERE eth.link_id = link.link_id AND link.link_id = $link_id) AS eth_anz,
		(SELECT count(*) AS dsl FROM link, dslam WHERE dslam.link_id = link.link_id AND link.link_id = $link_id) AS dsl_anz", "link_id = $link_id");
	
	return $link;	
}