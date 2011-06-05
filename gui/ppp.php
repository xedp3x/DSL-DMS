<?php

if (isset($_GET["del"])){
	if ($_POST["ok"]=="Ja"){
		PPP_del($_GET["del"], $s_id);
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=ppp">
			Daten werden &Uuml;bertragen <br /> <br />
			<img src="img/permit.gif"> <br /> <br />
			Bitte Warten';
	}elseif ($_POST["ok"]=="Nein"){
		echo '<meta http-equiv="refresh" content="0; URL='.$_SERVER['SCRIPT_NAME'].'?view=ppp">
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
}elseif (isset($_POST["add"])){
	switch ($_POST["label"]){
		case "Ohne":
			if ($_POST["type"]== "Feste IP"){
				$ip = PPP_ip_get();
				$username = $ip."@osz";
			}else{
				$ip = "*";
				$username = "dyn_".mt_rand(100000, 999999)."@osz";
			}
			
			$password = mt_rand(100000, 999999);
			break;
			
		case "T-Systems":

			for ($i = 0; $i < 12 ; $i++){
				$a = mt_rand(0, 35);
				if ($a > 9 ){
					$a = $a + 55;
				}else{
					$a = $a + 48;
				}
				$nname .= chr($a);
			}
			if ($_POST["type"]== "Feste IP"){
				$ip = PPP_ip_get();
				$username = "feste-ip".mt_rand(0,9)."/".$nname."@t-online-com.de ";
			}else{
				$ip = "*";
				$username = "t-online-com/".$nname."@t-online-com.de ";
			}		
			$password = mt_rand(100000, 999999);
			break;
			
		case  "T-Online":
			if ($_POST["type"]== "Feste IP"){
				echo "T-Online und Feste IP ist nicht m&ouml;glich <br /> <br />";
				break;
			}
			$ip = "*";
			$ken1 = mt_rand(100000, 999999).mt_rand(100000, 999999);
			$ken2 = mt_rand(100000, 999999).mt_rand(100000, 999999);
			$password = mt_rand(100000, 999999);
			$username = $ken1.$ken2."#0001@t-online.de";
			echo "<table border='0'>
				<tr><td>Anschlusskennung	</td><td>$ken1</td></tr>
				<tr><td>T-Onlinenummer		</td><td>$ken2</td></tr>
				<tr><td>Mitbenutzer-Sufix	</td><td>0001 </td></tr>
				</table> <br />";
			break;
			
		default:
				error("ppp_51","Falsches Label");
	}
	if ($ip <> ''){
		PPP_set($s_id,$username,$password,$ip); 
		echo "Ihre neuen Kennungen sind:<br /> <br />
		<table border='0'>
		<tr><td>IP</td><td>$ip</td></tr>
		<tr><td>Username:</td><td>$username</td></tr>
		<tr><td>Password:</td><td>$password</td></tr>
		</table> <br />";
	}
}
if (! (isset ($ip) or isset($_GET["del"])) ){
echo "<form method='post'>
	<table border='0'>
	<tr><td>Type:</td><td>".array2select(array("Standard","Feste IP"),"type",'style="width: 100px"')."</td></tr>
	<tr><td>Label:</td><td>".array2select(array("T-Online","T-Systems","Ohne"),"label",'style="width: 100px"')."</td></tr>
	</table>
	<input name='add' type='submit' value='Neue Kennung holen' />
	</form>";
}


echo"<table border='1'>
<tr>
	<th>Username</th>
	<th>Password</th>
	<th>Client-IP</th>
	<th>Status</th>
</tr>";
$ppp_list	= PPP_get($s_id);
while( list ( $key1, $val ) = each ( $ppp_list ) ){
	echo "<tr>
	<td>{$val["username"]}</td>
	<td>{$val["password"]}</td>
	<td>{$val["ip_client"]}</td>
	<td>";
		if ($val["ip_client"] <> '*'){
			echo "<a href='{$_SERVER['SCRIPT_NAME']}?view=tool&tool=ping&zeil={$val["ip_client"]}' onclick='loading()' class='weis'>";
		}else{
			echo "<a href='javascript:alert(\"Von einer dynamischen Kennung ist die Aktuelle IP nicht bekannt\")' class='weis'>";
		}
		if ($val["status"] == 1){
			echo "<img src='img/online.gif' title='online'></a>";
		}else{
			echo "<img src='img/offline.gif' title='offline'></a>";
		};
		if ($val["ip_client"] == '*'){
			echo"<a href='javascript:alert(\"Auf eine Dynamische-IP kann kein PTR Eintrag erstellt werden !\")' class='weis'><img src='img/RTP.png' title='Resource Record'></a>";
		}else{
			echo"<a href='{$_SERVER['SCRIPT_NAME']}?view=dns&ptr=".str_replace('#','%23',$val["username"])."' class='weis'><img src='img/RTP.png' title='Resource Record'></a>";	
		}
		echo"	 <a href='{$_SERVER['SCRIPT_NAME']}?view=ppp&del=".str_replace('#','%23',$val["username"])."' class='weis'><img src='img/x.gif' title='L&ouml;schen'></a>
		</td>
	</tr>";
}	
echo"</tabel>";