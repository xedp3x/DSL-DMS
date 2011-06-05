<form method="post">
	Debug Meldungen <input type="submit" value="erneuern" /> oder <input name="reset" type="submit" value="l&ouml;schen" />
</form>
<?php
if ($_GET["reset"]){
	$_SESSIO["debug"]=array();
}
if (count($_SESSION["debug"]) > 0){
	$set = $_SESSION["debug"];
	echo "<table border='1'>";
	while( list ( $key, $val ) = each ( $set ) ){
			echo "<tr><td>".array2br($val)."</td></tr>"; 
		}
	echo "</table>";
}else{
	echo "<br />Keine Debugmeldungen vorhanden";
}