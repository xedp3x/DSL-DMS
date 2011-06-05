<?php

switch ($_GET["type"]){
	case "tcp":
		$socket=@fsockopen($_GET["ip"],$_GET["port"]); 
		if ($socket != false) { 
			echo ":-)  gestartet ist ".$_GET["name"]; 
			fclose($socket); 
		} else {
			echo ":-C  Nicht gestartet ist ".$_GET["name"];	
		}
		break;

	case "ping":
		exec("ping ".$_GET["ip"]." -w 2 -c 1",$online);
		if (eregi("1 received", $online[4]))
		{ 
			echo ":-)  erreichbar ist ".$_GET["name"]; 
		 }else{
		 	echo ":-C  Nicht erreichbar ist ".$_GET["name"]; 
		 }
		 
		break;
	
	default:
		error("E100","Die Art und Weise des Test ist unbekant");
		break;
}

usleep(300000);