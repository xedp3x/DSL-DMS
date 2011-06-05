<?php

// Debugmodus
define("debug",true);
define("insel",true);

//Grundkonfiguration
$config = array(
"mysql"		=> array (
	"host"		=> "127.0.0.1",
	"database"	=> "dsl",
	"username"	=> "dsl",
	"password"	=> "Pass001"
	),
"dslam"		=> array(
	"login"		=> "YWRtaW46",	 // Userneme:Password in Base64
	"ip"		=> "10.10.10.10",
	"username"	=> "admin",
	"pass1"		=> "",
	"pass2"		=> ""
	),
"switch"	=> array(
	"login"		=> "Pass001",
	"enable"	=> "Pass001"
	)
);