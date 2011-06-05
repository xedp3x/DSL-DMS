<?php
if ($_GET["set"]){
	//VOIP_load();
	echo '<meta http-equiv="refresh" content="1; URL='.$_SERVER['SCRIPT_NAME'].'?view=voip">
		Moment Bitte <br /> <br />
		<img src="img/wait.gif">';
}elseif (isset($_GET["del"])){
	if ($_POST["ok"]=="Ja"){
		VOIP_del($_GET["del"], $s_id);
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=voip&set=true">
			Daten werden &Uuml;bertragen <br /> <br />
			<img src="img/permit.gif"> <br /> <br />
			Bitte Warten';
	}elseif ($_POST["ok"]=="Nein"){
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=voip">
		Moment Bitte <br /> <br />
		<img src="img/wait.gif">'; 
	}else{
		echo "M&ouml;chten sie die Nummer wirklich l&ouml;schen ? <br />
			Wiederherstellen ist nicht m&ouml;glich ! ! !
			<form method='post'>
				<input name='ok' type='submit' value='Ja' />
				<input name='ok' type='submit' value='Nein' />
			</form>";		
	}
}elseif (isset($_GET["add"])){
	if(! $_GET["jetzt"]){
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=voip&add=true&jetzt=true">
			Daten werden &Uuml;bertragen <br /> <br />
			<img src="img/permit.gif"> <br /> <br />
			Bitte Warten';
	}else{
		$telnum = "1".mt_rand(100000, 999999);
		$pass 	= mt_rand(1000000, 9999999);
		VOIP_set($s_id,$telnum,$pass);
		//VOIP_load();
		echo "Ihre neuen Daten sind:<br /> <br />
		<table border='0'>
		<tr><td>Nummer:</td><td>$telnum</td></tr>
		<tr><td>Password:</td><td>$pass</td></tr>
		</table> <br />
		Das Aktiviren kann ca. 1 Minute dauern ! ! ! <br /> <br />";
	}
}else{
echo "<form method='get'>
		<input name='view' value='voip' type='hidden' />
		<input name='add' type='submit' value='Eine Nummer mehr Bitte' />
	</form>";
}

if (isset($_GET["info"])){
	echo "<table border='0'><tr><td>";
}
echo"<table border='1'>
<tr>
	<th>Rufnummer</th>
	<th>Password</th>
	<th>L&ouml;schen</th>
</tr>";
$voip_list	= VOIP_get_nummern($s_id);
while( list ( $key, $val ) = each ( $voip_list ) ){
	echo "<tr>
		<td>{$val["name"]}</td>
		<td>{$val["secret"]}</td>
		<td>
			<a href='{$_SERVER['SCRIPT_NAME']}?view=voip&info={$val["name"]}' class='weis'>";
				if ($val["port"] <> 0){
					echo "<img src='img/online.gif' title='online'></a>";
				}else{
					echo "<img src='img/offline.gif' title='offline'></a>";
				};	
echo"		<a href='{$_SERVER['SCRIPT_NAME']}?view=voip&del={$val["name"]}' class='weis'><img src='img/x.gif' title='L&ouml;schen'></a>		
		</td>
	</tr>";
}	
echo"</table>";
if (isset($_GET["info"])){
	$info = VOIP_get_info($_GET["info"]);
	echo "</td><td>";
	unset($info["s_id"]);
	
	echo array2list($info, true);
	echo "</td></tr></table>";
}
echo"<br />
Einstellungen sind im <a href='http://faq.server.osz/?action=search&search=VoIP'>FAQ</a> hinterlegt";