<?php
if ($_GET["fertig"]){
	echo "Das Programm solte gestarte sein. <br /> <br />
		
		wenn nicht &uuml;berpr&uuml;fen sie ob 
		<a href='http://faq.server.osz/?action=search&search=xming' class='weis'>
			<img src='img/Xming.png' title='Xming' alt='Xming'/>
		</a>
		gestarte ist.";	
}elseif ($_GET["jetzt"]){
	if ($_GET["debug"]){
		echo "Das programm meldet : <pre>".shell_exec("src/".$_GET["start"]." ".$_SERVER["REMOTE_ADDR"])."</pre>";		
	}else{
		exec('bash -c "'."src/".$_GET["start"]." ".$_SERVER["REMOTE_ADDR"].' > /dev/null 2>&1 &"');
		echo '<meta http-equiv="refresh" content="5; URL='.$_SERVER['SCRIPT_NAME'].'?view=start&fertig=true" />
			Das Programm startet . . . <br /> <br />
			Das kann einnige Sekunden dauern <br /> <br />
			<img src="img/cone.gif" />';	
	}
}else{	
	echo '<meta http-equiv="refresh" content="2; URL='.$_SERVER['SCRIPT_NAME'].'?view=start&jetzt=true&start='.$_GET["start"].'" />';
	echo "Der Programmstart wird vorbereitet <br /> <br />
	<img src='img/permit.gif' /> ";
}