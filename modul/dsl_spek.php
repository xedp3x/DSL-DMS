<?php
$messwerte = DSLAM_spektrum($_GET["port"]);
	
header( "Content-Type: image/svg+xml" ); 

if($_GET["export"]){ 
	header( "Content-Disposition: attachment; filename=Spektrum_".date('Y-m-d_H-i').".svg"); 
	header( "Content-Description: Scalable Vector Graphics" ); 
	header( "Pragma: no-cache" ); 
	header( "Expires: 0" ); 	
}

echo "<?xml version='1.0' standalone='no'?>
<!DOCTYPE svg PUBLIC '-//W3C//DTD SVG 1.1//EN' 'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'>
<svg width='612' height='300' version='1.1' xmlns='http://www.w3.org/2000/svg'>"; 
if ($_GET["export"]<> "true"){?>
<script language="JavaScript">
function showWMTT(id) {
	  wmtt = document.getElementById('info_'+id);
	  wmtt.style.display = "block";

	  wmll = document.getElementById('line_'+id);
	  wmll.setAttribute("style", "stroke:rgb(255,0,0)");
	}
	 
function hideWMTT(id) {
	  wmtt.style.display = "none";
	  wmll.setAttribute("style", "stroke:"+id);
	}
</script>
<?php 
} // Export

for($i = 1; $i <= 16; $i++){
	echo"<line x1='80' y1='".($i * 10)."' x2='592' y2='".($i * 10)."' style='stroke:rgb(99,99,99);stroke-width:1'/>
		 <text x='75' y='".($i * 10 + 4)."' style='font-size:11px' text-anchor='end'>".( 16 - $i )."</text>";
}
for($i = 0; $i <= 16; $i++){
	echo"<line x1='".($i * 32 + 80)."' y1='9' x2='".($i * 32 + 80)."' y2='160' style='stroke:rgb(99,99,99);stroke-width:1'/>
		 <text x='".($i * 32 + 80)."' y='175' style='font-size:11px' text-anchor='middle'>".( $i * 32 )."</text>
		 <text x='".($i * 32 + 80)."' y='190' style='font-size:10px' text-anchor='middle'>".( ($i * 32) * 4.3125 )."</text>";
}

if (count($messwerte) == 0){
 echo '<text style="font-size:50px" x="150" y="100" >Keine Messwerte</text>';
}else{
	for($i = 1; $i < 512; $i++)
	{
	  if (isset ($messwerte[$i]["type"])){
		  if ($messwerte[$i]["type"] == "Downstream"){
		  		$color = "rgb(0,0,255)";
		  }else{
		  		$color = "rgb(0,255,0)";
		  }
		  echo "<line id='line_$i' onmouseover='showWMTT(\"$i\")' onmouseout='hideWMTT(\"$color\")' x1='".($i + 80)."' y1='".(160 - ($messwerte[$i]["bit"]*10))."' x2='".($i + 80)."' y2='160' style='stroke:$color;stroke-width:1'/>";
		  if ($_GET["export"]<> "true"){
		  echo"
		  	<g id='info_$i' style='display: none;'>
		  		<text x='580' y='220' text-anchor='end' > $i</text>
		  		<text x='580' y='240' text-anchor='end' >".$messwerte[$i]["bit"]."</text>
		  		<text x='580' y='260' text-anchor='end' >".$messwerte[$i]["freq"]." kHz</text>
		  		<text x='580' y='280' text-anchor='end' >".$messwerte[$i]["type"]."</text>
		  	</g>
		  	";
		  }//export
	  }
	}
}
?>
<text style="font-size:12px" x="35" y="20" text-anchor="middle" >Bits je</text>
<text style="font-size:12px" x="35" y="40" text-anchor="middle" >Träger</text>

<text style="font-size:12px" x="70" y="177" text-anchor="end" >Träger</text>
<text style="font-size:11px" x="70" y="190" text-anchor="end" >Frequenz</text>

<rect x="100" y="210" width="20" height="20" style="fill:rgb(0,255,0);stroke-width:1;stroke:rgb(0,0,0)"/>
<text x="130" y="230" style="font-size:20px">Upstream</text>

<rect x="100" y="240" width="20" height="20" style="fill:rgb(0,0,255);stroke-width:1;stroke:rgb(0,0,0)"/>
<text x="130" y="260" style="font-size:20px">Downstream</text>

<text x="100" y="300" style="font-size:15px"><?php echo date('d.m.Y H:i:s'); ?></text>

<?php if ($_GET["export"]<> "true"){?>
	<text x="450" y="220" text-anchor="end" >Träger</text>
	<text x="450" y="240" text-anchor="end" >Bits</text>
	<text x="450" y="260" text-anchor="end" >Frequens</text>
	<text x="450" y="280" text-anchor="end" >Richtung</text>
<?php } // Export ?>
</svg>