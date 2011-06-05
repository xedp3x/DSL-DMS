<div id='vor'>

	<input type="button" onclick="document.getElementById('log').value='- Start -';
	gruppenbefehl(Array(<?php
		$raum = RAUM_get_platz($platz["raum"]);
		while( list ( $key, $val ) = each ( $raum ) ){
			$out .= 
				"'type=eth&name=".$val["name"]."&port=".$val["eth1"]."',".
				"'type=eth&name=".$val["name"]."&port=".$val["eth2"]."',".
				"'type=dsl&name=".$val["name"]."&port=".$val["dsl1"]."',".
				"'type=dsl&name=".$val["name"]."&port=".$val["dsl2"]."',\n"				
			;
		}
		echo substr($out, 0, -2);
		?>),'api.php?modul=reset&')" value="start" />
</div>
</center>
<div id='bei' style='display: none;'>
	<div style="position: relative; width:100%; background-color: #C0C0C0; border: solid 1px #000000;">
		<span id="counter3" style="position: absolute; width: 100%; z-index: 3; text-align: center; font-weight: bold;">0%</span> 
		<div id="status3" style="position: relative; background-color: #00FF00; width:0px; height: 22px; border-right: solid 1px #000000; z-index: 2;">&thinsp;</div>
	</div>	
</div>
<center>
<div id='nach' style='display: none;'>
	Fertig
</div>

<br /> 

<textarea id='log' cols='80' rows='32' readonly>
Es werden alle Anschl&uuml;sse in diesem Raum resettet

Die DSL-Ports werden mit 16+ konfiguriert und mit dem BB-RAR verbunden.

Alle VLANs werden gel&ouml;scht. Die Ethernetports werden mit den Dafaulteinstellungen versehn. 
</textarea>