<?php
/*
 * API für den Zugriff auf dem DSLAM
 */

if (!defined('lib')) { die ("E100 direkter aufruf der API-Komponente nicht erlaubt");}

function DSLAM_get($port){
	return SQL_select_one("dslam", "dsl_id = $port");
}

function DSLAM_set($port, $set){
	$set["dsl_id"] = $port;
	SQL_insert_update("dslam", $set);
}

function DSLAM_load($port){
	global $config;
	$set  =	SQL_select_one("dslam, link", "dsl_id = $port AND dslam.link_id = link.link_id","dslam . * , link.vlan AS vlan");
	
	if ($set == null) { // Kein Link
		$set  =	SQL_select_one("dslam", "dsl_id = $port");
		$set["vlan"] = 300 + $port;
	}
	
	$command = Array(
//		"configure interface dsl $port vlan pvid {$set["vlan"]}",
		
		"configure interface dsl-profile-line latency {$set["latecy"]} prof$port",
		
		"configure interface dsl-profile-line max-speed-downstream {$set["down_max"]} prof$port",
		"configure interface dsl-profile-line min-speed-downstream {$set["down_min"]} prof$port",
		"configure interface dsl-profile-line max-interleave-delay-downstream {$set["down_delay"]} prof$port",
		"configure interface dsl-profile-line max-noise-margin-downstream {$set["down_noise_max"]} prof$port",
		"configure interface dsl-profile-line min-noise-margin-downstream {$set["down_noise_min"]} prof$port",
		"configure interface dsl-profile-line target-noise-margin-downstream {$set["down_noise_target"]} prof$port",
		"configure interface dsl-profile-line rate-adaptive-downstream {$set["rate_mode"]} prof$port",
		
		"configure interface dsl-profile-line max-speed-upstream {$set["up_max"]} prof$port",
		"configure interface dsl-profile-line min-speed-upstream {$set["up_min"]} prof$port",
		"configure interface dsl-profile-line max-interleave-delay-upstream {$set["up_delay"]} prof$port",
		"configure interface dsl-profile-line max-noise-margin-upstream {$set["up_noise_max"]} prof$port",
		"configure interface dsl-profile-line min-noise-margin-upstream {$set["up_noise_min"]} prof$port",
		"configure interface dsl-profile-line target-noise-margin-upstream {$set["up_noise_target"]} prof$port",
		"configure interface dsl-profile-line rate-adaptive-upstream {$set["rate_mode"]} prof$port",
		
		"configure interface dsl-profile-line activate prof$port $port",
		"configure interface dsl $port line-mode {$set["mode"]}"
	);
	DebMsg($command);
	if (insel){
		echo "<font color='red'> Inselmodus daher nicht geladen </font>";
		sleep(1);
	}else{
		
	 	$teln = new Net_TELN($config["dslam"]["ip"],23);
	 	
	 	$teln->send($config["dslam"]["username"]."\r\n");
	 	$teln->send($config["dslam"]["pass1"]."\r\n");// login Password
	 	$teln->send("privilege\r\n");
	 	$teln->send($config["dslam"]["pass2"]."\r\n");// Admin Password
	 			
		$teln->send("configure interface dsl $port state disable\r\n");
	 	sleep(2);
		while( list ( $key, $val ) = each ( $command ) )
			{$teln->send($val."\r\n");}  	
		sleep(2);
		$teln->send("configure interface dsl $port state enable\r\n");
		$teln->send("exit\r\n");
	}
}
function DSLAM_reset($port){
	global $config;
	if (insel){
		echo "<font color='red'> Inselmodus daher nicht geladen </font><br />";
		sleep(10);
	}else{
		
	 	$teln = new Net_TELN($config["dslam"]["ip"],23);
	 	
	 	$teln->send($config["dslam"]["username"]."\r\n");
	 	$teln->send($config["dslam"]["pass1"]."\r\n");// login Password
	 	$teln->send("privilege\r\n");
	 	$teln->send($config["dslam"]["pass2"]."\r\n");// Admin Password
	 	
		$teln->send("configure interface dsl $port state disable\r\n");
		sleep(10);
		$teln->send("configure interface dsl $port state enable\r\n");
		$teln->send("exit\r\n");
	} 	
}

function DSLAM_spektrum($port){
	global $config;
	if (insel){
		sleep(5);
		for ($i = 38; $i < 510; $i++){
			if (($i > 470)){
				$out[$i]["type"] 	= "Downstream";	
				$out[$i]["bit"] 	= ceil(12 -(($i-470)/3) + Rand (0,2));
			}elseif (($i > 130)){
				$out[$i]["type"] 	= "Downstream";	
				$out[$i]["bit"] 	= ceil((sin(($i - 40)/20)*2)+10 + Rand (0,2));
			}elseif(($i > 110)){
				$out[$i]["type"] 	= "Downstream";	
				$out[$i]["bit"] 	= ceil((($i-110)/2) + Rand (0,3));	
			}elseif(($i < 93)){
				$out[$i]["type"] 	= "Upstream";	
				$out[$i]["bit"] 	= ceil((sin($i/10 - 5)*5)+6 + Rand (0,2));				
			}
			$out[$i]["tone"] 	= $i;
			$out[$i]["freq"] 	= ceil($i*4.3125);
			if ( ! ($out[$i]["bit"] > 0 )){ unset ($out[$i]);};
		}
		return $out;
	}else{
		$teln = new Net_TELN($config["dslam"]["ip"],80);
		$mess = $teln->exec("GET /status/adsl_performance.html/OpStatusAction?ADSLport=$port&bitsScreen=true HTTP/1.1\r\n".
	   						"Host: {$config["dslam"]["ip"]}\r\n".
							"Authorization: Basic {$config["dslam"]["login"]}\r\n".
	 						"Connection: Close\r\n\r\n");
		
		$ll = explode("<hr>",array2str($mess));
		$ll = explode("<tr class='tblrow'>",$ll[1]);
		
		$out = array();
		
		list ( $key, $val ) = each ( $ll ); // erster nicht
		while( list ( $key, $val ) = each ( $ll ) ){
				$x = explode("<td> Tone=",$val);
				
				$x = explode("</td><td>",$x[1]);
				
				$out[$x[0]]["tone"] = $x[0];
				$out[$x[0]]["freq"] = $x[1];
				$out[$x[0]]["type"] = $x[2];
				
				$e = explode("\r\n",substr($x[3],0,-5));
				$out[$x[0]]["bit"] = str_replace('</td>','',$e[0]);
			}
	}
	unset($out[$x[0]]);
	return $out;
}

function DSLAM_status($port){
	global $config;
	if (insel){
		sleep(1);
		echo "<font color='red'> Inselmodus daher nicht geladen </font><br />";
		return 	"link status			Up \r\n".
				"link up time			ddd hh:mm:ss \r\n".
				"transmission mode		dmt \r\n".
				"latency	interleaved	\r\n".
				"near end alarm state		OK\r\n".
				"far end alarm state		OK\r\n".
				"\r\n".		
				"\r\n".		
				"ADSL status:			Near End	Far End\r\n".
				"rate (Kbps)			xxxxx		xxxxx\r\n".
				"attainable rate (Kbps)		xxxxx		xxxxx\r\n".
				"previous rate			xxxxxx		xxxxxx\r\n".
				"margin (dB)			xx		xx\r\n".
				"attenuation (dB)		xx		xx\r\n".		
				"current transmit power (dB)	xx		xx \r\n".
				"ADSL line init attempts		xxx";
	}else{
		
	 	$teln = new Net_TELN($config["dslam"]["ip"],23);
	 	
	 	$teln->send($config["dslam"]["username"]."\r\n");
	 	$teln->send($config["dslam"]["pass1"]."\r\n");// login Password
	 //	$teln->send("privilege\r\n");
	 //	$teln->send($config["dslam"]["pass2"]."\r\n");// Admin Password
	 	
		$teln->send("show interface dsl $port status\r\n");
	
		$teln->send("exit\r\n"); 
		$teln->send("y\r\n"); 	
		return array2str($teln->recv(),8,26);
	}
}