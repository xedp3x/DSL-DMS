<?php

if (isset($_GET["del"])){																		// DEL
	if ($_POST["ok"]=="Ja"){
		MAIL_user_del($_GET["del"], $s_id);
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=mail">
			Daten werden &Uuml;bertragen <br /> <br />
			<img src="img/permit.gif"> <br /> <br />
			Bitte Warten';
	}elseif ($_POST["ok"]=="Nein"){
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=mail">
		Moment Bitte <br /> <br />
		<img src="img/wait.gif">'; 
	}else{
		echo "M&ouml;chten sie die Kennung wirklich l&ouml;schen ? <br />
			Wiederherstellen ist nicht m&ouml;glich ! ! !
			<form method='post'>
				<input name='ok' type='submit' value='Ja' />
				<input name='ok' type='submit' value='Nein' />
			</form>";		
	}
}elseif($_GET["gpg"]){																				// GPG
	if (isset($_POST["key"])){
		 MAIL_gpg($_GET["gpg"],$_POST["key"],$s_id);
		 echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=mail">
				Daten werden &Uuml;bertragen <br /> <br />
				<img src="img/permit.gif"> <br /> <br />
				Bitte Warten';
	}else{
		$mail = MAIL_get($_GET["gpg"]);
		echo "<form method='post'>";
		if ($mail["s_id"]== $s_id) { 
			echo"<textarea name='key' cols='80' rows='30'>{$mail["gpg"]}</textarea>
			<br /><input name='add' type='submit' value='Speichern' />";
		}else{
			echo"<textarea name='key' cols='80' rows='30' readonly>{$mail["gpg"]}</textarea>";
		}
		echo" </form>";
	}
}elseif($_GET["key"]){																				// KEY
	if ($_POST["pass1"]){
		if ($_POST["pass1"] <> $_POST["pass2"]){ echo "Die Passwordwiederholung ist falsch ";}
		elseif (strlen($_POST["pass1"]) < 5 ){ echo "Password ist zu Kurz! mindestens 5 Zeichen";}
		else{
			MAIL_user_set($_GET["key"],$_POST["pass1"],$s_id);
			echo "&Auml;derung ist beauftragt <br /> <br />
				Es kann bis zu einer Minute dauern bis dies Aktiv ist <br /> <br />";
				$ok = True;
			}
			echo "<br /><br /><br />";
		}
		if (! $ok){
	    	echo "<center>
	    	<form  method='post'>
	    		<table border='1'> 
	    			<tr><td>Password 	</td><td><input type='password' name='pass1' /> </td></tr> 
	    			<tr><td>Wiederholen </td><td><input type='password' name='pass2' /> </td></tr>
	    		</tabele>
	    		<input type='submit' value='Speichern' />    		
	    	</form></center>";	
		}
}elseif (isset($_POST["domain"])){																		// ADD
	if ($_POST["pass1"]){
			if ($_POST["pass1"] <> $_POST["pass2"]){ echo "Die Passwordwiederholung ist falsch ";}
			elseif (strlen($_POST["pass1"]) < 5 ){ echo "Password ist zu Kurz! mindestens 5 Zeichen";}
			else{
				$username = mt_rand(100000, 999999)."@".$_POST["domain"];
				MAIL_user_set($username,$_POST["pass1"],$s_id);
				echo "Ihre neue Kennungen ist:<br /> <br />
				<table border='0'>
				<tr><td>Username:</td><td>$username</td></tr>
				</table> <br /> <br />
				Es kann bis zu einer Minute dauern bis dies Aktiv ist <br /> <br />";
				$ok = True;
			}
			echo "<br /><br /><br />";
		}
		if (! $ok){
	    	echo "<center>
	    	Das Password Merken !
	    	<form  method='post'>
	    		<table border='1'> 
	    			<tr><td>Password 	</td><td><input type='password' name='pass1' /> </td></tr> 
	    			<tr><td>Wiederholen </td><td><input type='password' name='pass2' /> </td></tr>
	    		</tabele>
	    		<input type='hidden' name='domain' value='{$_POST["domain"]}'>
	    		<input type='submit' value='Senden' />    		
	    	</form></center>";	
		}
}else{
echo "<table><tr><td>
		<form method='post'>
			<table border='0' width='150'>
			<tr><td></td><td>".array2select(array("mail.osz"),"domain",'style="width: 120px"')."</td></tr>
			<tr><td></td><td><input type='submit' value='Neue Adresse' style='width: 120px' /></td></tr>
			</table>
		</form>
	</td><td>
		<form>
			<input type='hidden' name='view' value='mail'>
			Nach GPG-Schl&uuml;ssel suchen<br />
			E-Mail: <input type='text' name='gpg'/> <br />
			<input type='submit' value='suchen' />
		</from>
	</td></tr></table>";
}


if(! isset($_GET["gpg"])){
echo"<table border='1'>
<tr>
	<th>Mailadresse</th>
	<th>Aktion</th>
</tr>";
$mail_list	= MAIL_get_list($s_id);
while( list ( $key, $val ) = each ( $mail_list ) ){
	echo "<tr>
		<td>{$val["email"]}</td>
		<td>
			<a href='http://www.mail.osz/?_user={$val["email"]}' class='weis'>			   <img src='img/mail.png' title='Webmailer'></a>
			<a href='{$_SERVER['SCRIPT_NAME']}?view=mail&key={$val["email"]}' class='weis'><img src='img/key.gif' title='neues Password'></a>
			<a href='{$_SERVER['SCRIPT_NAME']}?view=mail&gpg={$val["email"]}' class='weis'><img src='img/schloss.gif' title='GPG Schl&uuml;ssel'></a>
			<a href='{$_SERVER['SCRIPT_NAME']}?view=mail&del={$val["email"]}' class='weis'><img src='img/x.gif' title='L&ouml;schen'></a>
		</td>
	</tr>";
}	
echo"</tabel><br />
Einstellungen sind im <a href='http://faq.server.osz/?action=search&search=mail'>FAQ</a> hinterlegt";
}