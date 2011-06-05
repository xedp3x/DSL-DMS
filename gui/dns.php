<?php

if (isset($_GET["ptr"])){
	
	$ppp = PPP_state($_GET["ptr"]);
	list ($ip1, $ip2, $ip3, $ip4) = split('[.]', $ppp["ip_client"]);
	if ($s_id <> $ppp["s_id"]){error("DNS_10","Fehler in der PPP Kennung");}
	$dns = DNS_get("$ip4.$ip3.$ip2.$ip1.in-addr.arpa.","PTR");
	
	if (! $dns){
		DNS_set("PTR","$ip4.$ip3.$ip2.$ip1.in-addr.arpa.", '', $s_id,False,$_GET["ptr"]);
	}
	
	echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=dns&edit='."$ip4.$ip3.$ip2.$ip1.in-addr.arpa.".'&type=PTR">
		Moment Bitte <br /> <br />
		<img src="img/wait.gif">'; 
	
	
}elseif (isset($_GET["edit"])){
	if (! isset($_GET["type"])){
		error("DNS_06","Defekte URL");
	}
	
	$alt = DNS_get($_GET["edit"],$_GET["type"]);
	
	
	if ($_POST["save"]){
		if ($alt["s_id"] <> $s_id){ error("DNS_13","Fehler beim Auffinden der Domain");}
		if ($alt["ppp_username"]){
			$ppp_ref = $alt["ppp_username"];
		}else{
			$ppp_ref = False;
		}
		DNS_set($_GET["type"], $_GET["edit"], $_POST["data"], $s_id, False, $ppp_ref);
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=dns">
			Daten werden &Uuml;bertragen <br /> <br />
			<img src="img/permit.gif"> <br /> <br />
			Bitte Warten';
		
	}else{
		echo "<form method='post'>
			<table border='1'>
				<tr><td>Domain</td><td>".$alt["name"]."</td></tr>
				<tr><td>Type</td><td>".$alt["type"]."</td></tr>";
				
			if ($alt["ppp_username"]){
				echo "<tr><td>Link</td><td>".$alt["ppp_username"]."</td></tr>";
			}
			if ($alt["type"] == "PTR"){
				echo "<tr><td>Hinwei&szlig;</td><td><textarea cols='45' rows='1' readonly>Bei PTR Eintr&auml;gen muss als Ziel eine Domain mit Punk am Ende eingetragen werden</textarea></td></tr>";
			}elseif (($alt["type"] == "A") or ($alt["type"] == "MX")){
				echo "<tr><td>Hinwei&szlig;</td><td><textarea cols='45' rows='1' readonly>Ales Ziel muss ein eine g&uuml;ltige IP eingegeben werden</textarea></td></tr>";
			}
			
			echo"<tr><td>Ziel</td><td><input name='data' type='text' value='".$alt["data"]."' /></td></tr>
			</table>
			
			<input name='save' type='submit' value='speichern' />
				
		</form>";
	}
}elseif (isset($_GET["domain"]) or $_GET["neu"]){
	if ($_GET["domain"]){
		$alt = DNS_get($_GET["name"],$_GET["type"]);
		if (isset($alt["s_id"]) and $alt["s_id"] <> $s_id ){echo "Domain ist schon an Jemand anders vergeben";}
		elseif($_GET["host"] == ''){echo "Bitte einen Host angeben";}
		elseif($_GET["ip"] == ''){echo "Bitte eine IP angeben";}
		else{
			$ok = true;
			
			DNS_set($_GET["type"], $_GET["host"].".".$_GET["domain"], $_GET["ip"], $s_id ,False);
			echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=dns">
			Moment Bitte <br /> <br />
			<img src="img/wait.gif">'; 
		}
	}
	if (! $ok){
	echo "<form>
		<input type='hidden' name='view' value='dns'>
		
		Type : ".array2select(array('A','MX'), "type", '', '', $_GET["type"])."<br />
		<input type='text' name='host' value='{$_GET["host"]}' size='9' /> . ".array2select(array('user.osz','schuler.osz'), "domain", '', '', $_GET["domain"])." <br />
		IP: <input type='text' name='ip' value='{$_GET["ip"]}' /> <br /> 
		
		<input type='submit' value='Speichern' />
	</form>";
	}
}elseif (isset($_GET["del"])){
	if(isset($_POST["wahl"])){
		if ($_POST["wahl"] == "Ja"){
			DNS_del($_GET["del"], $_GET["type"], $s_id);
		}
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=dns">
			Moment Bitte <br /> <br />
			<img src="img/wait.gif">'; 
	}else{
		echo "<form method='post'>
			Wirklich L&ouml;schen <br />
			<input type='submit' name='wahl' value='Ja' />
			<input type='submit' name='wahl' value='Nein' />
		</form>";
	}
}else{
	
	echo"<form>
		<input type='hidden' name='view' value='dns'>
		<input type='submit' name='neu' value='Neuer DNS-Eintrag' />
	</form>
	<table border='1'>
		<tr>
			<th>Domain</th>
			<th>Verwei&szlig;</th>
			<th>Type</th>
			<th>Aktion</th>
		</tr>";
	$dns_list	= DNS_get_list($s_id);
	while( list ( $key, $val ) = each ( $dns_list ) ){
		echo "<tr>
			<td>{$val["name"]}</td>
			<td>{$val["data"]}</td>
			<td>{$val["type"]}</td>
			<td>
				<a href='{$_SERVER['SCRIPT_NAME']}?view=tool&l&tool=nslookup&zeil={$val["name"]}' class='weis'><img src='img/star.gif' title='Testen'></a>
				<a href='{$_SERVER['SCRIPT_NAME']}?view=dns&del={$val["name"]}&type={$val["type"]}' class='weis'><img src='img/x.gif' title='L&ouml;schen'></a>
				<a href='{$_SERVER['SCRIPT_NAME']}?view=dns&edit={$val["name"]}&type={$val["type"]}' class='weis'><img src='img/edit.png' title='L&ouml;schen'></a>
			</td>
		</tr>";
	}	
	echo "</table>";
}