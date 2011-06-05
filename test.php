<?php
 openlog("myprogram", 0, LOG_LOCAL0);
syslog("My syslog message");
?>