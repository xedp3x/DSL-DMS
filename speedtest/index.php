<?php
###############################################################################
## Fancy Speed Test - Easily measure your upload and download speeds
## Home Page:   http://www.brandonchecketts.com/speedtest/
## Author:      Brandon Checketts
## File:        index.php
## Version:     1.1
## Date:        2006-02-06
## Purpose:     Display a welcome page, or redirect straight to the 
##              download test if auto_start is enabled
###############################################################################

require("common.php");
ReadConfig("speedtest.cfg");

## Redirect immediately to download.php if auto_start = 1
if($config->{'general'}->{'auto_start'}) {
    Header("Location: ".$config->{'general'}->{'base_url'}."/download.php");
    exit;
}

?>
<html>
<head>
<title><?php print $config->{'general'}->{'page_title'}; ?> - Fancy Speed Test</title>
<meta http-equiv="Expires" CONTENT="Fri, Jan 1 1980 00:00:00 GMT" /> 
<meta http-equiv="Pragma" CONTENT="no-cache" /> 
<meta http-equiv="Cache-Control" CONTENT="no-cache" />  
<link rel="stylesheet" href="style.css" />
</head>
<body>

<?php
if(file_exists("header.html")) {
    ## Include "header.html" for a custom header, if the file exists
    include("header.html");
} else {
    ## Else just print a plain header
    print "<center>\n";
}
?>
<div id="speedtest_content">


<?php 
if(file_exists("welcome.html")) {
    ## Include "welcome.html" for a custom welcome page, if the file exists
    include("welcome.html");
} else { 
    ## Else print a standard welcome message
?>
<center>
<h2>Speed Test</h2>
<div style="width: 400px;">
Den Speedtest gibt es unter der Adresse <br />
<b>http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']?></b>
</div>
</center>
<?php } ?>

<center><h2><a class="start_test" title="Begin Speed Test" href="<?php echo $config->{'general'}->{'base_url'}; ?>/download.php">Start Test</a></h2></center>
<div id="speedtest_credits">
    Powered by <a title="Brandon Checketts Fancy Source Speedtest" href="http://www.brandonchecketts.com/speedtest/" target="_new">Fancy Speed Test</a>
</div>
</div>
<?php
if(file_exists("footer.html")) {
    ## Include "footer.html" for a custom footer, if the file exists
    include("footer.html");
} else {
    ## Else just print a plain footer
    print "</center>\n";
}
?>

    



</body>
</html>

