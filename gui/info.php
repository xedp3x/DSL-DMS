<table>
<tr><td>
<?php
switch ($_GET["info"]){
	case "impressum":
		?>			
			<center>
				<b>Impressum</b>
				<br />
				Willkommen im DMS vom OSZ-Teltow <br />
			</center>
			<br />
			<center>
			Das Device-Management-System ist zur Konfiguration und<br />
			Hilfestellung im zusammenhand mit dem DSL-Projekt gedacht.
			</center>
			<br /> <br /> <br />
			<center>
				<img src="img/impressum.png" />
			</center>
		<?php 
		break;
		
	case "willkommen":
		?>
			<center>
				<b>Willkommen</b>
				<br />
				<br />
				Willkommen im DMS vom OSZ-Teltow <br />
			</center>
		<?php 
		break;	
		
	case "user-ip":
		?>
			Dieser PC konnte nicht im DMS gefunden werden !<br />
			<br />
			Das aufrufen ist nur &uuml;ber die festen PC's an den Pl&auml;tzen m&ouml;glich. <br />
			Es muss der <b>DMS-Webclient</b> verwendet werden <br />
			Die IP und Proxy-Einstellungen m&uuml;ssen korrekt sein<br />
			<br />
			Mehr infos im <a href='http://faq.server.osz/?action=search&search=DMS-Webclient'> FAQ </a>
			<br /><br />
			
			Sitzungsinfo:<br />
		<?php 
		echo array2list($_SERVER); 
		break;	
		
	default:
		echo "Konnte leider '".$_GET["info"]."' nicht finden. <br />
		bitte noch in gui/info erg&auml;zen.";
}
?>
</td></tr>
</tbody>
</table>