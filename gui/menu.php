<ul id="Menu1" class="MM">

<?php if ($platz["type"] == "user") {?>
  <li><a>D S L</a>
    <ul>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=dsl&port=<?php echo $platz["dsl1"];?>">Linker Port</a></li>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=dsl&port=<?php echo $platz["dsl2"];?>">Rechter Port</a></li>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=ppp">Online Kennung</a></li>
    </ul>
  </li>
  
<?php }elseif ($platz["type"] == "admin") {?>
  <li><a>D S L</a>
    <ul>
    <?php if ($platz["dsl1"] <> 0) { ?>	<li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=dsl&port=<?php echo $platz["dsl1"];?>">Linker Port</a></li> <?php }?>
    <?php if ($platz["dsl2"] <> 0) { ?>	<li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=dsl&port=<?php echo $platz["dsl2"];?>">Rechter Port</a></li><?php }?>
      	
    <?php $set	= $raum;
	  while( list ( $key, $val ) = each ( $set ) ){?>
	  <li><a><?php echo $val["name"];?></a>
	  	<ul>
	      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=dsl&port=<?php echo $val["dsl1"];?>">Linker Port</a></li>
	      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=dsl&port=<?php echo $val["dsl2"];?>">Rechter Port</a></li>
	    </ul>
	   </li> 
     <?php }?>
     <br />
     <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=ppp">Online Kennung</a></li>
    </ul>
  </li>  
  
  
<?php }
if ($platz["type"] == "user") {?>
  <li><a>Ethernet</a>
    <ul>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&port=<?php echo $platz["eth1"];?>">Linker Port</a></li>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&port=<?php echo $platz["eth2"];?>">Rechter Port</a></li>
      <li><a>Verbindung</a>
      	<ul>
      		<li><a>DSL</a>
      		<ul>
      			<li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&dsl=<?php echo $platz["dsl1"];?>">Linker Port</a></li>
      			<li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&dsl=<?php echo $platz["dsl2"];?>">Rechter Port</a></li>
      		</ul>
      		</li>   
      		<li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth">Andere</a></li>   		
      	</ul>
      </li>
    </ul>
  </li>
<?php }elseif ($platz["type"] == "admin") {?>
  <li><a>Ethernet</a>
    <ul>
    <?php if ($platz["eth1"] <> 0) { ?>	<li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&port=<?php echo $platz["eth1"];?>">Linker Port</a></li> <?php }?>
    <?php if ($platz["eth2"] <> 0) { ?>	<li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&port=<?php echo $platz["eth2"];?>">Rechter Port</a></li> <?php }?>
    <?php $set	= $raum;
	  while( list ( $key, $val ) = each ( $set ) ){?>
	  <li><a><?php echo $val["name"];?></a>
	  	<ul>
	      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&port=<?php echo $val["eth1"];?>">Linker Port</a></li>
	      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&port=<?php echo $val["eth2"];?>">Rechter Port</a></li>
	    </ul>
	   </li> 
     <?php }?>
     <br />
     <li><a>Verbindung</a>
     	<ul>
      	 <li><a>DSL</a>
      		<ul>
      			<?php if ($platz["dsl1"] <> 0) { ?> <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&dsl=<?php echo $platz["dsl1"];?>">Linker Port</a></li><?php }?>
      			<?php if ($platz["dsl2"] <> 0) { ?> <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&dsl=<?php echo $platz["dsl2"];?>">Rechter Port</a></li><?php }?>
      			<?php $set	= $raum;
				while( list ( $key, $val ) = each ( $set ) ){?>
				  <li><a><?php echo $val["name"];?></a>
				  	<ul>
				      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&dsl=<?php echo $val["dsl1"];?>">Linker Port</a></li>
				      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth&dsl=<?php echo $val["dsl2"];?>">Rechter Port</a></li>
				    </ul>
				   </li> 
			     <?php }?>
      		</ul>
      	 </li>
      	 <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=eth">Andere</a></li>     		
      	</ul>
      </li>     
    </ul>    
  </li>    
<?php }
if (true) {?>  
   <li><a>Dienst</a>
    <ul>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=dns">DNS</a></li>
    </ul>
  </li>
<?php }
if (true) {?>  
   <li><a>Kommunikation</a>
    <ul>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=voip">VoIP</a></li>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=mail">E-Mail</a></li>
      <li><a href="http://www.mail.osz">Web-Mailer</a></li>
    </ul>
  </li>
<?php }
if (true) {?>  
  <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=tool">Toolbox</a></li>
<?php }
if (true) {?>  
  <li><a>Usersettings</a>
    <ul>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=user&user=logout">Logout</a></li>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=user&user=edit">&Auml;ndern</a></li>
    </ul>
  </li>
<?php }
if (true) {?>    
  <li><a>Info</a>
    <ul>
      <li><a href="http://faq.server.osz">FAQ</a></li>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=info&info=impressum">Impressum</a></li>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=self_test">Selftest</a></li>  
      <br />
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=bin&call=syslog">Live-Ticker</a></li>
      <li><a href="#" onclick="win=window.open('loganalyzer');loading();">SysLog</a></li>        
    </ul>
  </li>   
<?php }
if ($platz["type"] == "admin") {?>  
  <li><a>Admin</a>
    <ul>
      <li><a href="#" onclick="win=window.open('phpmyadmin');loading();">PHP-MyAdmin</a></li>
      <li><a href="halt.php">Server Ausschalten</a></li>
      <li><a href="<?php echo $_SERVER['SCRIPT_NAME']?>?view=raum_reset">Raum-Reset</a></li>        
    </ul>
  </li>
<?php } ?>
  <li><a href="javascript:window.print()">Drucken</a></li>
  <?php if (debug){?>
  	<li><a href="#" onclick="win=window.open('api.php?modul=debmsg');loading();">MSG</a></li>
  <?php }?>    
</ul>



