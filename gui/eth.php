<?php

if (($_GET["port"] <> $platz["eth1"]) AND ($_GET["port"] <> $platz["eth2"]) AND	
	($_GET["dsl"] <> $platz["dsl1"]) AND ($_GET["dsl"] <> $platz["dsl2"]) AND
	($_GET["dsl"] <> '') AND ($_GET["port"] <> '')
	AND $platz["type"] <> "admin" ){
	die ("Falscher aufruf <!-- Zugriff auf verbotenen DSL-Port -->");
}

if (($_GET["port"] == $platz["eth1"])or ($_GET["dsl"] == $platz["dsl1"])){
	echo "Linker Ethernet-Port <br />";
}elseif (($_GET["port"] == $platz["eth2"])or ($_GET["dsl"] == $platz["dsl2"])){
	echo "Rechter Ethernet-Port<br />";
}else{
	if (($_GET["dsl"] <> '') OR ($_GET["port"] <> '')){
		echo "Port :".$_GET["port"].$_GET["dsl"]."<br />";
	}
}


if ($_GET["port"]){
	$messwert = ETH_status($_GET["port"]);     
	$link = ETH_link_find(array($_GET["port"])); 
	echo "Link-ID : ".$link[''];
	echo'<meta http-equiv="refresh" content="10">'."
		<table border='1'>
			<tr>
				<td>
					<p align='left'>
						<pre>$messwert</pre>
					</p>
				</td>
			</tr>
		</table>".
		date('d.m.Y H:i:s');
}elseif($_GET["dsl"]){																		// DSL
	switch ($_GET["Aktion"]){
		case "next":
			echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=eth&dsl='.$_GET["dsl"].'&Aktion=set&mode='.$_GET["mode"].'">
				Daten werden &Uuml;bertragen <br /> <br />
				<img src="img/permit.gif"> <br /> <br />
				Bitte Warten';  
			break;
				
		case "set":
			ETH_DSL($_GET["dsl"], strtolower($_GET["mode"]), $sitzungs_id);
			echo '<meta http-equiv="refresh" content="2; URL='.$_SERVER['SCRIPT_NAME'].'?view=dsl&port='.$_GET["dsl"].'&Aktion=Messen">
				Fast Fertig :-) <br /> <br />
				<img src="img/wait.gif">'; 
			break;
			
		default:
			echo "
				<center><form>
					<input type='hidden' name='view' 	value='eth' />
					<input type='hidden' name='dsl' 	value='{$_GET["dsl"]}' />
					<input type='hidden' name='Aktion' 	value='next' />
					Den Port mit dem
					".array2select(array('PAP','CHAP'),'mode')."
					Server verbinden.<br />
				<input type='submit' value='Los' /><br />
				</form></center>";
	}	
}else{
	if ($_GET["Aktion"] == "linken"){
		if ($_GET["dsl1"]){$dsl[1] = $_GET["dsl1"];}
		if ($_GET["dsl2"]){$dsl[2] = $_GET["dsl2"];}
		if ($_GET["eth1"]){$eth[1] = $_GET["eth1"];}
		if ($_GET["eth2"]){$eth[2] = $_GET["eth2"];}
		if (!isset($dsl)){$dsl = false;}
		$link_id = ETH_link($sitzungs_id, $eth, $_GET["mode"], $dsl);
		echo "Ports sind verbunden. <br />
		Die neue Link-ID ist $link_id";
	}
	if ($_GET["link"]){
		if (isset($_POST["mode"])){
			if ($_POST["dsl1"]){$dsl[1] = $_POST["dsl1"]; $get .= "&dsl1=".$_POST["dsl1"];}
			if ($_POST["dsl2"]){$dsl[2] = $_POST["dsl2"]; $get .= "&dsl2=".$_POST["dsl2"];}
			if ($_POST["eth1"]){$eth[1] = $_POST["eth1"]; $get .= "&eth1=".$_POST["eth1"];}
			if ($_POST["eth2"]){$eth[2] = $_POST["eth2"]; $get .= "&eth2=".$_POST["eth2"];}
			$mode	= $_POST["mode"]; $get .= "&mode=".$_POST["mode"];
						
			if (! (isset($dsl) or isset($eth) )){ Echo "Bitte einen Port w&auml;hlen";}
			elseif((count($dsl) + count($eth) )== 1 and ($mode == "self")){echo "Mit was denn nun verbinden?";}
			elseif(isset($dsl) and ($_POST["mode"] == "chap" or $mode == "pap")){echo "Verbindung vom DSL zum PPPoE Server Bitte &uuml;ber Ethernet&gt;Verbindung&gt;DSL erstellen";}
			elseif(isset($dsl) and ($_POST["mode"] <> "self")){echo "DSL-Ports sind nur mit Allein oder PPPoE Kominirbar";}
			elseif(!isset($eth)){echo "Es muss mindestesn 1 ETH port ausgew&auml;ht werden";}
			else{// Alles OK
								
				echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=eth&Aktion=linken'.$get.'">
				Daten werden &Uuml;bertragen <br /> <br />
				<img src="img/permit.gif"> <br /> <br />
				Bitte Warten';
				$ok = true;
			}
						
		}
		if (! $ok){
		echo "<form method='post'>
				<table>
				<tr><td>
					<p onmouseover='showWMTT(\"tae\")'  onmouseout='hideWMTT()'>
						<input type='checkbox' name='dsl1' value='{$platz["dsl1"]}' />DSL links<br />
						<input type='checkbox' name='dsl2' value='{$platz["dsl2"]}' />DSL rechts<br />
					</p>
					<p onmouseover='showWMTT(\"uae\")'  onmouseout='hideWMTT()'>
						<input type='checkbox' name='eth1' value='{$platz["eth1"]}' />ETH links<br />
						<input type='checkbox' name='eth2' value='{$platz["eth2"]}' />ETH rechts<br />
					</p>
				</td><td>
					<a onmouseover='showWMTT(\"aleine\")' onmouseout='hideWMTT()'><input type='radio' name='mode' value='self' checked /> <i> Alleine </i> </a> <br /> 
					<a onmouseover='showWMTT(\"pap\")'    onmouseout='hideWMTT()'><input type='radio' name='mode' value='pap'	/> 	PPPoE Server (PAP)</a> <br /> 
					<a onmouseover='showWMTT(\"chap\")'   onmouseout='hideWMTT()'><input type='radio' name='mode' value='chap' 	/> 	PPPoE Server (CHAP)</a> <br /> 
					<a onmouseover='showWMTT(\"user\")'   onmouseout='hideWMTT()'><input type='radio' name='mode' value='user' 	/> 	DHCP oder &quot;Feste Server-IP&quot;</a> <br /> 
					<a onmouseover='showWMTT(\"osz\")'    onmouseout='hideWMTT()'><input type='radio' name='mode' value='osz'  	/> 	OSZ Netz</a> <br /> 
				</td></tr>
				</tabel>
				<input type='submit' value='Verbinden' />";
		if ($platz["type"] == 'admin'){
			// Liste aller Ports im Raum
			echo "<table border='1'>
				<tr>
					<th>ID</th>
					<th>Platz-Name</th>
					<th>DSL</th>
					<th>ETH</th>
				</tr>";
			
			$set 	= $raum;
			while( list ( $key, $val ) = each ( $set ) ){
				echo "<tr>
						<td> <center> {$val["platz_id"]} </center> </td>
						<td> {$val["name"]} </td>
						
						<td>
							<input type='checkbox' onmouseover='showWMTT(\"links\")'  onmouseout='hideWMTT()' name='dsl_{$val["dsl1"]}' value='{$val["dsl1"]}' />
							<input type='checkbox' onmouseover='showWMTT(\"rechts\")' onmouseout='hideWMTT()' name='dsl_{$val["dsl2"]}' value='{$val["dsl2"]}' />
						</td>
						
						<td>
							<input type='checkbox' onmouseover='showWMTT(\"links\")'  onmouseout='hideWMTT()' name='eth_{$val["eth1"]}' value='{$val["eth1"]}' />
							<input type='checkbox' onmouseover='showWMTT(\"rechts\")' onmouseout='hideWMTT()' name='eth_{$val["eth2"]}' value='{$val["eth2"]}' />
						</td>
						
				</tr>";
			}
			echo "</table>";
		}
		echo "</form>
			<table><tr><td>
			<div id='tae' style='display: none;'>
				<img src='img/tae.jpg' width='142' height='142' />
			</div>
			<div id='uae' style='display: none;'>
				<img src='img/uae.jpg' width='142' height='142' />
			</div>
			<div id='aleine' style='display: none;'>
				Es wird nur eine Verbindung zwischen den Ausgew&auml;lten Ports aufgabuet
			</div>
			<div id='pap' style='display: none;'>
				Zu der Verbindung geh&ouml;rt noch ein PPPoE Server <br />
				<br />
					Das Password Authentication Protocol (PAP) ist ein Verfahren<br />
					zur Authentifizierung &uuml;ber das Point-to-Point Protocol (PPP) und<br />
					ist in RFC 1334 beschrieben. Es wurde h&auml;ufig f&uuml;r die Einwahl mit<br />
					Modems zu Netzwerkbetreibern (ISP) verwendet.
			</div>
			<div id='chap' style='display: none;'>
				Zu der Verbindung geh&ouml;rt noch ein PPPoE Server <br />
				<br />
					Das Challenge Handshake Authentication Protocol (CHAP) ist ein<br />
					Authentifizierungsprotokoll, das im Rahmen von Point-to-Point Protocol (PPP)<br />
					eingesetzt wird. PPP ist auf der Sicherungsschicht in der<br />
					Internetprotokollfamilie angesiedelt. CHAP ist im RFC 1994<br />
					spezifiziert. Im Gegensatz zum Vorl&auml;ufer Password Authentication<br />
					Protocol (PAP) wird beim CHAP mehr Wert auf die Sicherheit bei<br />
					der &Uuml;bertragung der Passw&ouml;rter gelegt.
			</div>
			<div id='user' style='display: none;'>
				Das Netz hat die Gr&ouml;&szlig;e von 10.31.0.0/16 <br />
				Ein DHCP-Server verteilt Adressen automatisch <br />
				<br />
				Feste IP's k&ouml;nnem im Bereich 10.31.128.0 bis 10.31.191.255 <br />
				frei gew&auml;hlt werden. <br />
				<br />
				andere IP's werden <b>nur</b> vom DMS vergeben<br />
				<br />
				<pre>
					Gateway		:  10. 31.  0.  1
					DNS-Server	:  10.  1.  1.  1
					Netzmaske	: 255.255.  0.  0
					
					Kein Proxy !
				</pre>				 
			</div>
			<div id='osz' style='display: none;'>
				Das normale OSZ-Netz<br />
				<br />
				<pre>
					Gateway		: 192.168.100.  3
					DNS-Server	: 192.168.100.  3
					Netzmaske	: 255.255.255.  0
					
					Proxy		: 192.168.100.  3
					Port		: 8080
				</pre>	
			</div>
			
			
			<div id='links' style='display: none;'>
				Links
			</div>
			
			<div id='rechts' style='display: none;'>
				Rechts
			</div>
			</td></tr></tabel>
		";
		} // Neue Verbindung
	}else{
		$links = ETH_link_find(array($platz["eth1"], $platz["eth2"]),array($platz["dsl1"], $platz["dsl2"]));
		
		$set = $links;	
		echo "
		<form>
			<input type='hidden' name='view' value='eth'>
			<input name='link' type='submit' value='Neuer Link erstellen' />
		</form>
		<table border='1'>
			<tr>
				<th>Link-ID</th>
				<th onmouseover='showWMTT(\"eth\")' onmouseout='hideWMTT()'>Ethernet</th>
				<th onmouseover='showWMTT(\"dsl\")' onmouseout='hideWMTT()'>DSL Ports</th>
			</tr>";
		while( list ( $key, $val ) = each ( $set ) ){
			$link	= ETH_link_info($val);
			echo "<tr onmouseover='showWMTT(\"link$val\")' onmouseout='hideWMTT()'>
					<td>$val</td>
					<td>{$link["eth"]}</td>
					<td>{$link["dsl"]}</td>
				</tr>";
			$info .= "<div id='link$val' style='display: none;'>
						<table border='0'>
							<tr><td> Link ID </td><td> $val</td></tr>
							<tr><td> VLAN </td><td> {$link["vlan"]}</td></tr>
							<tr><td> Modus</td><td> {$link["mode"]}</td></tr>
						</table>	
					</div>
					";
		}
		echo "</table>
			$info
			<div id='eth' style='display: none;'>
				Anzahl der Verbundenen Ethernet Anschl&uuml;sse <br />
				<img src='img/uae.jpg' width='142' height='142' /> 	
			</div>
			<div id='dsl' style='display: none;'>
				Anzahl der Verbundenen DSL Anschl&uuml;sse <br />
				<img src='img/tae.jpg' width='142' height='142' /> 	
			</div>
		";
	}
}