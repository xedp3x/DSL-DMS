<textarea id='log' cols='80' rows='33' readonly>
Klicken Sie auf 'Start' um den Test zu beginnen.
</textarea>

<div id='vor'>

	<input type="button" onclick="document.getElementById('log').value='';
	gruppenbefehl(Array(<?php
		$tag = TAGS("ping");
		while( list ( $key, $val ) = each ( $tag ) ){
			$tt = explode(",", $val["wert"]);
			$out .="'check&type=ping&ip=".$tt[0]."&name=".$tt[1]."',\n";
		}
		
		$out.= "'dummy&out=',\n";
		
		$tag = TAGS("tcp");
		while( list ( $key, $val ) = each ( $tag ) ){
			$tt = explode(",", $val["wert"]);
			$out .="'check&type=tcp&ip=".$tt[0]."&port=".$tt[1]."&name=".$tt[2]."',\n";
		}

		$out.= "'dummy&out=',\n";
		
		echo substr($out, 0, -2);
		?>),'api.php?modul=')" value="Start" />
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
</div>