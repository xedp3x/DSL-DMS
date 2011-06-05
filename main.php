<?php
include "lib/lib.php";
if (! (($_GET["view"] == "info") and (substr($_GET["info"],0,4) == "user")))  {
	include_once 'gui/user.php';
}else{
	if (! isset($_GET["menu"])){
		$_GET["menu"] = "false";
	}
}

if ($show_body){
	$_GET["view"] = "dummy";
}elseif ($_GET["view"] == "user"){
	die("");
}

if ($_GET["html"] <> "false"){
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<style type="text/css">
a.link {
 color: blue;
}
a.weis {
 color: #FFFFFF;
}
</style>
<script language="JavaScript">
function showWMTT(id) {
  wmtt = document.getElementById(id);
  wmtt.style.display = "block"

  wmtte = document.getElementById("show-else");
  wmtte.style.display = "none";
}
 
function updateWMTT(e) {
  if (wmtt != null && wmtt.style.display == "block") {
    x = (e.pageX ? e.pageX : window.event.x) + wmtt.offsetParent.scrollLeft - wmtt.offsetParent.offsetLeft;
    y = (e.pageY ? e.pageY : window.event.y) + wmtt.offsetParent.scrollTop - wmtt.offsetParent.offsetTop;
    wmtt.style.left = (x + 20) + "px";
    wmtt.style.top   = (y + 20) + "px";
  }
}
function hideWMTT() {
  wmtt.style.display = "none";
  wmtte.style.display = "block";
}

function loading(){
	load = document.getElementById("onloading");
	load.style.display = "block"

	load = document.getElementById("inhalt");
	load.style.display = "none"
}

window.onload = function() {
	  window.resizeTo(700,700);
	  
	  load = document.getElementById("onloading");
	  load.style.display = "none"

	  load = document.getElementById("inhalt");
	  load.style.display = "block"
	}

var xmlhttp=false;
if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	try {
		xmlhttp = new XMLHttpRequest();
	} catch (e) {
		xmlhttp=false;
	}
}
if (!xmlhttp && window.createRequest) {
	try {
		xmlhttp = window.createRequest();
	} catch (e) {
		xmlhttp=false;
	}
}


function mach(){
	xmlhttp.open("GET", command+list[x] ,true);
	 xmlhttp.onreadystatechange=function() {
	  if (xmlhttp.readyState==4) {
		 document.getElementById("log").value = xmlhttp.responseText+'\n'+document.getElementById("log").value;
		 x +=1;
		 if (x < list.length) {
			 mach();
			 document.getElementById("status3").style.width = ((x/list.length*100)) + "%";
			 document.getElementById("counter3").innerHTML = Math.round(((x/list.length*100) - 1))+" %";
		 }else{
			 document.getElementById('bei').style.display = 'none';
			 document.getElementById('nach').style.display = 'block';
			 var ende  = new Date();
			 document.getElementById("log").value = ' - Fertig nach '+Math.round((ende.getTime() - start.getTime()) / 1000)+' Sekunden - \n'+document.getElementById("log").value;
		 }
		 //document.getElementById("log").scrollTop = document.getElementById("log").scrollHeight;
	  }
	 }
	 xmlhttp.send(null);	
}

var list;
var command;
var x;
var start;
alert(jetzt.getTime());
function gruppenbefehl(IN,IN2){
	list 	= IN;
	command = IN2;
	x 		= 0;
	start 	= new Date();
	mach();
	document.getElementById('vor').style.display = 'none';
	document.getElementById('bei').style.display = 'block';
	return false;	
}


function bin_show(){
	if ((xmlhttp.readyState == 3 || xmlhttp.readyState == 4) && (document.getElementById("bin_out").value != xmlhttp.responseText)){
		if (xmlhttp.status != 200){
			alert('Die Verbindung zum DMS ist fehlgeschlagen (Fehler '+xmlhttp.status+')');
		}else{
			document.getElementById("bin_out").value = xmlhttp.responseText;
			document.getElementById("bin_out").scrollTop = document.getElementById("bin_out").scrollHeight;
		}
	}
	if (xmlhttp.readyState != 3){
		document.getElementById('bei').style.display = 'none';
		document.getElementById('vor').style.display = 'block';
	}else{
		document.getElementById('bei').style.display = 'block';
		document.getElementById('vor').style.display = 'none';
		setTimeout("bin_show()",100);
	}
}

function bin_call(IN){
	xmlhttp.open("GET", "bin/"+IN,true);
	xmlhttp.send(null);
	document.getElementById('bei').style.display = 'block';
	document.getElementById('vor').style.display = 'none';
	setTimeout("bin_show()",500); 
} 

function bin_abort(){
	xmlhttp.abort();
}	
              

</script>
<head>
	<title>Device-Management-System vom OSZ Teltow &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; v3.4</title>
	<link rel="shortcut icon" href="img/favicon.gif" >
    <link rel="icon" type="image/gif" href="img/favicon.gif" >
	<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
	<!-- SmartMenus 6 config and script core files -->
		<script type="text/javascript" src="c_config.js"></script>
		<script type="text/javascript" src="c_smartmenus.js"></script>
	<!-- SmartMenus 6 config and script core files -->
</head>
<body background="img/bg.jpg">
	<?php if (($_GET["view"] <> "dummy") and ($_GET["menu"] <> 'false')){include_once"gui/menu.php";} ?>
<br /> <br /> <br />
<center>
	<div id='inhalt'>
	<?php 
}// HTML <> false
		if (!isset($_GET["view"])){
			$_GET["view"] = "info";
			$_GET["info"] = "willkommen";
		}
		include_once "gui/".$_GET["view"].".php";
		
if ($_GET["html"] <> "false"){
	echo "</div>";
	if (! strpos($_SERVER["HTTP_USER_AGENT"],"Prism")) {
		echo "<br /> <br /> <big>! ! ! WARUNG ! ! !</big> <br />
		Bitte verwenden sie den <a href='http://faq.server.osz/?action=search&search=DMS-Webclient'>DMS-Webclient</a>";
	}
	if (!$_SERVER['HTTPS']) {
		echo "<div style='position:absolute; left:0; top:0'>
			<font color='red' align='right'>
				<big> ! ! Kein SSL verwendet ! ! </big>
			</font>
		</div>";
	}
	?>
	
	<div id='onloading'>
		<br /> <br />
		<img src="img/wait.gif" />
	</div>
</center>
</body>
</html>
<?php }