<?php

if (($_GET["pass"] <> 'Pass001') and (isset($_GET["pass"]))){
	die ("<center>Passward ist falsch</center>");
}
if($_GET["aktion"]){
	include('Net/SSH2.php');

	if ($_GET["rar"]){
    	$rar = new Net_SSH2('10.1.1.250');
    	if ($rar->login('root', 'Pass001')) {
    		$rar->exec($_GET["aktion"]);
    	}
    	$rar->disconnect();
    }

	if ($_GET["serv01"]){
    	$rar = new Net_SSH2('10.1.1.13');
    	if ($rar->login('root', 'Pass001')) {
    		$rar->exec($_GET["aktion"]);
    	}
    	$rar->disconnect();
    }

	if ($_GET["main"]){
    	$rar = new Net_SSH2('127.0.0.1');
    	if ($rar->login('root', 'Pass001')) {
    		$rar->exec($_GET["aktion"]);
    	}
    	$rar->disconnect();
    }    

    die("Alles erledigt ! ! !");

}elseif($_GET["reload"]){
	die("Durch debug gesperrt");
}
?>
<center>
	<table border="1">
		<tr>
			<td>
				<form action="halt.php">
					Password : <input type="password" name="pass"><br />
					Server Aktion
					<select name="aktion">
						<option>reboot</option>
						<option>halt</option>
					</select>				
					<br /><br />
					<center>
						<input selected="selected" type="checkbox" name="main" 	value="true" /> Main
						<input selected="selected" type="checkbox" name="rar" 	value="true" /> BB-RAR
						<input selected="selected" type="checkbox" name="serv01"value="true" /> Serv01
						<br />				
						<input type="submit" value="LOS !" />
					</center>  
				</form>
			</td><td>
				<form action="halt.php">
					Password :<input type="password" name="pass"><br />
					Restart von:
						<center>
							<input type="submit" name="reload" value="DSLAM">
							<input type="submit" name="reload" value="Core_Switch" />
							<br />
							<input type="submit" name="reload" value="Switch110" />
							<input type="submit" name="reload" value="Switch111" />
							<input type="submit" name="reload" value="Switch113" />
						</center>				
					<br />
				</form>
			</td>
		</tr>
	</table>
</center>