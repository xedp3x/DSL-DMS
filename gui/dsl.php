<?php

if (($_GET["port"] <> $platz["dsl1"]) AND ($_GET["port"] <> $platz["dsl2"]) AND $platz["type"] <> "admin" ){
	die ("Falscher aufruf <!-- Zugriff auf verbotenen DSL-Port -->");
}
if ($_GET["html"]<>"false"){
if ($_GET["port"] == $platz["dsl1"]){
	echo "Linker DSL-Port";
}elseif ($_GET["port"] == $platz["dsl2"]){
	echo "Rechter DSL-Port";
}else{
	echo "Port ".$_GET["port"];
}
echo "
<center><form>
	<input type='hidden' name='view' value='dsl'>
	<input type='hidden' name='port' value='{$_GET["port"]}'>
	<input name='Aktion' type='submit' title='Reset' value='Reset' />
	<input name='Aktion' type='submit' title='Messen' onclick='loading()' value='Messen' />
	<input name='Aktion' type='submit' title='Spektrum' value='Spektrum' />
</form></center>";}

switch ($_GET["Aktion"]){
	case "Reset":
		if (! $_GET["RESET"] == "True"){
			echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=dsl&port='.$_GET["port"].'&RESET=True&Aktion=Reset">
				Daten werden &Uuml;bertragen <br /> <br />
				<img src="img/permit.gif"> <br /> <br />
				Bitte Warten';
		}else{
			if (insel){
				echo "<font color='red'> Inselmodus daher nicht geladen </font><br />";
				sleep(10);
			}else{
				DSLAM_reset($_GET["port"]);
			}
			echo '<meta http-equiv="refresh" content="5; URL='.$_SERVER['SCRIPT_NAME'].'?view=dsl&port='.$_GET["port"].'&Aktion=Messen">
				Fast Fertig :-) <br /> <br />
				<img src="img/wait.gif">'; 
		}
	break;
	
	case "Messen":
		$messwert = DSLAM_status($_GET["port"]);
		
		echo'<meta http-equiv="refresh" content="10">'.
			"<table border='1'>
				<tr>
					<td>
						<p align='left'>
							<pre>$messwert</pre>
						</p>
					</td>
				</tr>
			</table>".
			date('d.m.Y H:i:s')."<br />
			Hilf ist <a href='http://faq.server.osz/?action=search&search=DSL-Messen'>hier</a> hinterlegt";;
	break;
//------------------------------------------------------------------------
	case "Spektrum":
		echo "
			Export als 
				<a href='#".date('d.m.Y-H:i:s')."' onclick=\"window.open('{$_SERVER['SCRIPT_NAME']}?view=dsl&port={$_GET["port"]}&Aktion=Spektrum-Export&html=false');\">Exel</a>
				<a href='#".date('d.m.Y-H:i:s')."' onclick=\"window.open('api?modul=dsl_spek&port={$_GET["port"]}&export=.svg');\">SVG</a>
			<br />
			<object data='api?modul=dsl_spek&port={$_GET["port"]}' width='612' height='300' type='image/svg+xml'>
				API ist nich erreichbar <br />
				<img src='img/sorry.png' />
			</object>
		";	
	break;
//------------------------------------------------------------------------
	case "Spektrum-Export":
		$messwerte = DSLAM_spektrum($_GET["port"]);
	
		header( "Content-Type: application/ms-excel" ); 
		header( "Content-Disposition: attachment; filename=Spektrum_".date('Y-m-d_H-i').".csv"); 
		header( "Content-Description: Exel-CSV Datei" ); 
		header( "Pragma: no-cache" ); 
		header( "Expires: 0" ); 	
		
		echo"Traeger,Frequenz,Type,Bit\r\n";
		for($i = 1; $i < 512; $i++)
			{
			  echo"$i,{$messwerte[$i]["freq"]},{$messwerte[$i]["type"]},{$messwerte[$i]["bit"]}\r\n";
			}
	break;
//------------------------------------------------------------------------
	default:


if ($_GET["DSLAM_set"]){
	DSLAM_load($_GET["port"]);
	echo '<meta http-equiv="refresh" content="2; URL='.$_SERVER['SCRIPT_NAME'].'?view=dsl&port='.$_GET["port"].'&Aktion=Messen">
		Fast Fertig :-) <br /> <br />
		<img src="img/wait.gif">'; 
}elseif ($_POST["Speichern"]){
	unset ($_POST["Speichern"]);
	DSLAM_set($_GET["port"], $_POST);
	echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=dsl&port='.$_GET["port"].'&DSLAM_set=True">
	Daten werden &Uuml;bertragen <br /> <br />
	<img src="img/permit.gif"> <br /> <br />
	Bitte Warten'; 
}elseif ($_GET["port"]){
	$port = DSLAM_get($_GET["port"]);
	echo "
<form method='post'>
	<table>
		<tr><td>
			<table border='1'>
				<tr onmouseover='showWMTT(\"rate_mode\")' 	onmouseout='hideWMTT()'><td>Anpassung	</td><td>".array2select(array('init','manual','dynamic')			,'rate_mode','style="width: 100px"','', $port["rate_mode"])."</td></tr>
				<tr onmouseover='showWMTT(\"latency\")' 	onmouseout='hideWMTT()'><td>Latenz		</td><td>".array2select(array('interleaved','fast')					,'latecy'   ,'style="width: 100px"','', $port["latecy"])."</td></tr>
				<tr onmouseover='showWMTT(\"mode\")' 		onmouseout='hideWMTT()'><td>Modus		</td><td>".array2select(array('dmt','adsl2','adsl2plus','multimode'),'mode'  	,'style="width: 100px"','', $port["mode"])."</td></tr>
				<tr onmouseover='showWMTT(\"link\")' 		onmouseout='hideWMTT()'><td>Link ID		</td><td>{$port["link_id"]}</td></tr>			
			</table>
		</td><td>	
			<input style='width: 90px' name='Speichern' type='submit' value='Speichern' /> <br />
			<input style='width: 90px' type='button' value='Default' onmouseover='showWMTT(\"default\")' 	onmouseout='hideWMTT()'
			onclick='".'	
				this.form.rate_mode.selectedIndex	= "0";
				this.form.latecy.selectedIndex		= "0";
				this.form.mode.selectedIndex		= "3";
				
				this.form.up_max.value 				= "2048";
				this.form.down_max.value 			= "20480";
				
				this.form.up_min.value 				= "32";
				this.form.down_min.value 			= "32";
				
				this.form.up_delay.value 			= "16";
				this.form.down_delay.value 			= "16";
				
				this.form.up_noise_max.value 		= "31";
				this.form.down_noise_max.value 		= "31";
				
				this.form.up_noise_min.value 		= "0";
				this.form.down_noise_min.value 		= "0";
				
				this.form.up_noise_target.value 	= "16";
				this.form.down_noise_target.value 	= "8";
			'."' />
		</td></tr>
	</table>
	<table border='1' onmouseover='showWMTT(\"Profile\")' onmouseout='hideWMTT()'>
		<tr>
			<th>Eigenschaft</th>
			<th>Upload</th>
			<th>Download</th>
		</tr>
		<tr>
			<td>Max. Speed</td>
			<td><input name='up_max'			value='{$port["up_max"]}'			size='9' /></td> 
			<td><input name='down_max'			value='{$port["down_max"]}'			size='10' /></td>
		</tr>
		<tr>
			<td>Min. Speed</td>
			<td><input name='up_min'			value='{$port["up_min"]}'			size='9' /></td>
			<td><input name='down_min'			value='{$port["down_min"]}'			size='10' /></td>
		</tr>	
		<tr>
			<td>Delay</td>
			<td><input name='up_delay'			value='{$port["up_delay"]}'			size='9' /></td>
			<td><input name='down_delay'		value='{$port["down_delay"]}'		size='10' /></td>
		</tr>
		<tr>
			<td>Max. Nois</td>
			<td><input name='up_noise_max'		value='{$port["up_noise_max"]}'		size='9' /></td>
			<td><input name='down_noise_max'	value='{$port["down_noise_max"]}'	size='10' /></td>
		</tr>		
		<tr>
			<td>Min. Nois</td>
			<td><input name='up_noise_min'		value='{$port["up_noise_min"]}'		size='9' /></td>
			<td><input name='down_noise_min'	value='{$port["down_noise_min"]}'	size='10' /></td>
		</tr>
		<tr>
			<td>Nois target</td>
			<td><input name='up_noise_target'	value='{$port["up_noise_target"]}'	size='9' /></td>
			<td><input name='down_noise_target'	value='{$port["down_noise_target"]}'size='10' /></td>
		</tr>
	</tabel>
	<table>
		<tr><td>
			<div id='rate_mode' style='display: none;'>
				<b>rate-adaptive-mode</b> specifies whether the port <br />
				will adapt its rate to downstream line conditions. <br /> <br />
				&nbsp;&nbsp;&nbsp;&nbsp;<b>manual</b> - Manually selected at startup<br />
				&nbsp;&nbsp;&nbsp;&nbsp;<b>init</b> - Automatically selected at startup<br />
				&nbsp;&nbsp;&nbsp;&nbsp;<b>dynamic</b> - Automatically selected at run time<br />
			</div>
			<div id='latency' style='display: none;'>
				<b>latency</b>  specifies whether an interleave buffer is used.<br /> <br />
				&nbsp;&nbsp;&nbsp;&nbsp;<b>fast</b>  - K&uuml;rzer Ping aber geringere Bandbreite <br />
				&nbsp;&nbsp;&nbsp;&nbsp;<b>interleaved</b> - Durch den Interleave-Buffer k&ouml;nnen Fehler koregiert werden<br />
			</div>
			<div id='Profile' style='display: none;'>
				<table>
					<tr><td>Max. Speed</td><td>- Maximale Geschwindigkeit in KBit </td></tr>
					<tr><td>Min. Speed</td><td>- Minimale Geschwindigkeit in KBit </td></tr>
					<tr><td>&nbsp;</td><td>
						<table>
							<tr><td>Upstream:</td><td>32-2048</td></tr>
							<tr><td>Downstream:</td><td>32-20480</td></tr>
						</table></td></tr>
					<tr><td>Delay</td><td>- Interleave-Buffer Gr&ouml;&szlig;e in ms</td></tr>
					<tr><td>Max. Nois</td><td>- Maximaler Signal-Rausch-Abstand in dB</td></tr>
					<tr><td>Min. Nois</td><td>- Minimaler Signal-Rausch-Abstand in dB</td></tr>
					<tr><td>Nois target</td><td>- Signal-Rausch Anpassung in dB</td></tr>
				</table>
			</div>
			<div id='link' style='display: none;'>
				Eine Link-ID ist eine Kennung um Verbindungen zwischen mehreren Punkten herzustellen <br />
				<br />
				Die ID wird in ein VLAN umgewandelt und dann in die Hardware eingespielt
			</div>
			<div id='mode' style='display: none;'>
				Definit den &Uuml;bertragungsmodus
			</div>		
			<div id='default' style='display: none;'>
				Setzt alle Werte f&uuml;r eine Standart 16'000 DSL Anschluss<br />
				<br />
				Verbindung bitte noch &uuml;ber Ethernet&gt;Verbindung&gt;DSL Setzen
			</div>		
		</td></tr>
	</table>
</form>
";
	}
}