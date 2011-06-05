<?php
session_start();

if (($_GET["start"]== "true")) {
	setcookie("user","",time() - 3600);
	$_SESSION = array();
	
	echo '<meta http-equiv="refresh" content="2; URL='.$_SERVER['SCRIPT_NAME'].'" />
			<center>
			Starte DMS <br /> <br />
			<img src="img/spinn.gif" />
			</center>';
	$show_body = true;
}elseif (isset($_COOKIE["user"])){
	switch ($_GET["user"]){
		case "logout":
			setcookie("user","",time() - 3600);
			echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?start=true">
				<center>
				Moment bitte <br /> <br />
				<img src="img/spinn.gif"> <br /> <br />
				Gleich geht es weiter. . . 
				</center>';
			$show_body = true;
			break;
			
		case "edit": 
			{
				$show_body = true;
				list($s_id, $platz_id, $sitzungs_id) = explode(":", $_COOKIE["user"]);
				if (isset($_POST["pass_alt"])){
					if ($_POST["pass_neu1"] <> ""){
						if (strlen($_POST["kommentar"]) < 8 ){ echo "Bei Mitglider müssen alle eure Namen voll ausgeschreiben stehen";}
						elseif ($_POST["pass_neu1"] <> $_POST["pass_neu2"]) { echo "Passw&ouml;rter passen nicht zusammen";}
						elseif (strlen($_POST["pass_neu1"]) < 5 ){ echo "Password ist zu Kurz! mindestens 5 Zeichen";}
						else{
							SCHULER_update($s_id,$_POST["pass_alt"], $_POST["pass_neu1"],$_POST["kommentar"]);
							echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=info&info=willkommen">
								<center>
									willkommen <br /> <br />
									<img src="img/spinn.gif"> <br /> <br />
									Gleich geht es weiter. . . 
								</center>';
						}
					}
	
				}else{
					$schuler = SCHULER_get_by_id($s_id);
					echo "<form method='post'>
						<table>
							<tr>
								<td>Altes Password</td>
								<td><input type='password' name='pass_alt'><td>
							</tr><tr>
								<td>Neues Password</td>
								<td><input type='password' name='pass_neu1'></td>
							</tr><tr>
								<td>Wiederholung</td>
								<td><input type='password' name='pass_neu2'></td>
							</tr><tr>
								<td>Mitglider</td>
								<td><textarea name='kommentar' rows='5' cols='20'>{$schuler["kommentar"]}</textarea></td>
							</td>
						</table>
						<input type='submit' value='Speichen'>
					</form>";
				}
			}
		break;
	}// kein Menu aufruf
		;
	list($s_id, $platz_id, $sitzungs_id) = explode(":", $_COOKIE["user"]);
	$sql = SQL_select_one('sitzung, platz', "platz.platz_id = sitzung.platz_id AND platz.ip = '{$_SERVER["REMOTE_ADDR"]}'" , "s_id, sitzungs_id, platz.platz_id AS platz_id", "ORDER BY sitzungs_id DESC");
	
	if (($sql["s_id"] <> $s_id) or
		($sql["platz_id"] <> $platz_id) or
		($sql["sitzungs_id"] <> $sitzungs_id)
	)
	{
		setcookie("user","",time() - 3600);
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=user">
			<center>
			Moment bitte <br /> <br />
			<img src="img/spinn.gif"> <br /> <br />
			Gleich geht es weiter. . . 
			</center>';
		$show_body = true;
	}else{// Cookie io
		SQL_update("sitzung", "sitzungs_id = $sitzungs_id", "`last_do` = CURRENT_TIMESTAMP");
		setcookie("user","$s_id:$platz_id:$sitzungs_id",time() + 3600);
		$platz	= SQL_select_one('platz', "platz_id = $platz_id");
		if ($platz["type"] == "admin"){
			$raum	= RAUM_get_platz($platz["raum"]);
		}
	}

}else{ // Kein Cookie
	
if (!$_SERVER['HTTPS']) { // erzwinge SSL login
//    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
//    header('Location: '.$redirect);
//    die(''); 
} 	
	
$klassen 	= KLASSE_get_list();
unset($klassen[0]); // loscht root aus der liste

switch ($_GET["user"]){
	case "registrieren":
		if (false){
			echo "Das Registreiren muss vom Leher aktivirt werden";
		}else{
			if ($_POST["klasse"]){
				if ($_POST["pass1"] <> $_POST["pass2"]){ echo "Die Passwordwiederholung ist falsch ";}
				elseif (strlen($_POST["pass1"]) < 5 ){ echo "Password ist zu Kurz! mindestens 5 Zeichen";}
				elseif (strlen($_POST["kommentar"]) < 8 ){ echo "Bei Mitglider müssen alle eure Namen voll ausgeschreiben stehen";}
				elseif (strlen($_POST["name"]) < 3 ){ echo "Username ist zu Kurz";}
				elseif ($_POST["klasse"] == 'Bitte Wählen'){ echo "Eure Klasse ist Bitte W&auml;hlen? Doch nicht wirklich... <br />evt. muss der Lehere noch euere Klasse erstellen";}
				else{
					SCHULER_set($_POST["klasse"],$_POST["name"],$_POST["pass1"],$_POST["kommentar"]);
					echo '<meta http-equiv="refresh" content="5; URL='.$_SERVER['SCRIPT_NAME'].'?view=user">
						<center>
						willkommen <br /> <br />
						<img src="img/spinn.gif"> <br /> <br />
						Gleich geht es weiter. . . 
						</center>';
					break; 
				}
				echo "<br /><br /><br />";
			}
	    	echo "<center>
	    	Als Username ist ein Name zu w&auml;hlern der f&uuml;r die ganze Gruppe zehlt <br />
	    	Das Password muss auch allen in der Gruppe bekant sein ! ! ! 
	    	<form  method='post'>
	    		<table border='1'>
	    			<tr><td>Klasse 		</td><td>". array2select($klassen,"klasse",$_POST["klasse"])."</td></tr> 
	    			<tr><td>Username	</td><td><input type='text' name='name' value='{$_POST["name"]}' /> </td></tr> 
	    			<tr><td>Password 	</td><td><input type='password' name='pass1' /> </td></tr> 
	    			<tr><td>Wiederholen </td><td><input type='password' name='pass2' /> </td></tr> 
	    			<tr><td>Mitglider	</td><td><textarea name='kommentar' rows='5' cols='20'>{$_POST["kommentar"]}</textarea> </td></tr>
	    		</tabele>
	    		<input type='submit' value='Senden' />
	    		<input type='hidden' name='view' value='user'>
	    		
	    	</form></center>";			
		}
        break;
        
    case "logout":  
    		setcookie("user","",time() - 3600);
			echo '<center>
				Sie wurden Ausgelogt <br />
				<br /> <br /> <br /> <br />
				</center>';
		// Kein Brake da gleich danach Login kommen soll        
	default:
		$platz_id 	= SQL_select_one("platz", "ip = '{$_SERVER["REMOTE_ADDR"]}'");
		if (! isset($platz_id["platz_id"])){
			Header("Location: https://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}?view=info&menu=false&info=user-ip"); 
			die();}
		$platz_id 	= $platz_id["platz_id"];
		if ($_POST["klasse"] and ($_POST["pass"] <> '')){
			$s_id = SCHULER_get($_POST["klasse"], $_POST["name"], $_POST["pass"]);
			if ($s_id) {
				SQL_insert("sitzung", array ("s_id" => $s_id, "Platz_id" => $platz_id));
				$sql 	  	= SQL_select_one('sitzung, platz', "platz.platz_id = sitzung.platz_id AND platz.ip = '{$_SERVER["REMOTE_ADDR"]}'" , "s_id, sitzungs_id, platz.platz_id", "ORDER BY sitzungs_id DESC");
				$sitzungs_id= $sql["sitzungs_id"];
				setcookie("user","$s_id:$platz_id:$sitzungs_id",time() + 3600);
				echo '<meta http-equiv="refresh" content="1; URL='.$_SERVER['SCRIPT_NAME'].'?view=info&info=willkommen">
					<center>
					willkommen <br /> <br />
					<img src="img/spinn.gif"> <br /> <br />
					Gleich geht es weiter. . . 
					</center>';
			}else{
				echo "<center>
				Klasse, Benutzername und Password passt nicht zusammen<br />
				<img src='img/14.gif'> <br /> <br /
				<form>
					<input type='hidden' name='view' value='user'>
					<input type='submit' value='Nochmal versuchen' />
				</form> <br />
				Der Lehere kann das Password auch zur&uuml;cksetzen
				</center>";
			}
		}else{
	    	echo "<center>
	    	<form  method='post'>
	    		<table>
	    			<tr><td>
	    				Klasse:<br />
	    				".array2select($klassen,"klasse", "size='15'")."
		    		</td><td>
		    			Username: <br />
		    			<input type='text' name='name' /> <br />
		    			<br />
			    		Password: <br />
			    		<input type='password' name='pass' /> <br />
			    		<br />
			    		<center><input type='submit' value='Login' /> </center><br />
		    		</td></tr>
	    		</table>
	    	</form>
	    	<br /> <br />
	    	<form>
	    		<input type='hidden' name='view' value='user'>
	    		<input type='submit' name='user' value='registrieren' />
	    	</form></center>";
		}
}

$show_body = true;
}