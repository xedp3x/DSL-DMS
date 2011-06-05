<?php
/*
 * L�d alle Bibliotheken
 */

define("lib",true);
include "config/system.php";
if (! debug){error_reporting(1);}

include_once 'lib/error.php';
include_once 'lib/sql.php';
include_once 'lib/converter.php';
include_once 'lib/rs232.php';

include_once 'lib/schuler.php';
include_once 'lib/platz.php';

include_once 'lib/dns.php';
include_once 'lib/mail.php';
include_once 'lib/voip.php';
include_once 'lib/ppp.php';

include_once 'lib/dslam.php';
include_once 'lib/eth.php';


include_once 'lib/tool.php';

include 'Net/TELN.php';