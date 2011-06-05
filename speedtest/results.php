<?php
###############################################################################
## Fancy Speed Test - Easily measure your upload and download speeds
## Home Page:   http://www.brandonchecketts.com/speedtest/
## Author:      Brandon Checketts
## File:        results.php
## Version:     1.1
## Date:        2006-02-06
## Purpose:     Display the speed test results in a meaningful way
##              Save results to the database if enabled
###############################################################################

require("common.php");
ReadConfig("speedtest.cfg");


## Save the results of this speedtest to the database, if enabled
if($config->{'database'}->{'enable'}) {
    $ip_matches = $config->{'database'}->{'ip_matches'};
    if( (! $ip_matches) || ($ip_matches && preg_match("/$ip_matches/",$_SERVER['REMOTE_ADDR'])) ) {
        Debug("Saving to database");
        $dbh = mysql_connect(
            $config->{'database'}->{'host'},
            $config->{'database'}->{'user'},
            $config->{'database'}->{'password'}
        );
        $dbs = mysql_select_db( $config->{'database'}->{'database'}, $dbh);
        $table = $config->{'database'}->{'table'};
        $ip = $_SERVER['REMOTE_ADDR'];
        $upspeed = addslashes($_GET['upspeed']);
        $downspeed = addslashes($_GET['downspeed']);
        $sql = "
            INSERT INTO `$table`
            SET
                `ip_string` =  '$ip',
                `ip` = INET_ATON('$ip'),
                `timestamp` = NOW(),
                `upspeed` = '$upspeed',
                `downspeed` = '$downspeed'
        ";
        mysql_query($sql,$dbh);
    }
}



?>
<html>
<head>
<title><?php print $config->{'general'}->{'page_title'}; ?> - Fancy  Speed Test</title>
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
<div id="speedtest_contents">

<?php

    $bar_width = 400;

    $clean_down = CleanSpeed($_GET['downspeed']);
    $download_biggest = $_GET['downspeed'];
    print "<h2>Download Speed: $clean_down</h2>\n";
    ## Find the biggest value
    foreach($config->{'comparisons-download'} as $key=>$value) {
        if($value > $download_biggest) {
            $download_biggest = $value;
        }
    }
    ## Print a pretty table with a graph of the results
    print "<center><table>\n";
    foreach($config->{'comparisons-download'} as $key=>$value) {
        $this_bar_width = $bar_width / $download_biggest * $value;
        print "<tr><td>$key</td><td>".CleanSpeed($value)."</td><td width=\"400\">\n";
        print "<img src=\"". $config->{'general'}->{'image_path'};
        print "bar.gif\" height=\"10\" width=\"$this_bar_width\" alt=\"$value kbps\" /></td></tr>\n";
    }
    $this_bar_width = $bar_width / $download_biggest * $_GET['downspeed'];
    print "<tr><td><b>Your Speed</b></td>\n";
    print "<td>$clean_down</td><td width=\"400\"><img src=\"";
    print $config->{'general'}->{'image_path'} . "bar.gif\" height=\"10\" width=\"$this_bar_width\"></td></tr>\n";
    print "</table>\n";



    ## Don't display the upload stuff if we didn't get a speed to compare with
    if(isset($_GET['upspeed'])) {
        $clean_up = CleanSpeed($_GET['upspeed']);
        $upload_biggest = $_GET['upspeed'];
        print "<h2>Upload Speed: $clean_up</h2>\n";
        foreach($config->{'comparisons-upload'} as $key=>$value) {
            if($value > $upload_biggest) {
                $upload_biggest = $value;
            }
        }
        print "<table>\n";
        foreach($config->{'comparisons-upload'} as $key=>$value) {
            $this_bar_width = $bar_width / $upload_biggest * $value;
            print "<tr><td>$key</td><td>".CleanSpeed($value)."</td>\n";
            print "<td width='400'><img src=\"";
            print  $config->{'general'}->{'image_path'} ."bar.gif\" height=\"10\" width=\"$this_bar_width\" alt=\"$value kbps\" /></td></tr>\n";
        }
        $this_bar_width = $bar_width / $upload_biggest * $_GET['upspeed'];
        print "<tr><td><b>Your Speed</b></td><td>$clean_up</td><td width='400'>";
        print "<img src=\"". $config->{'general'}->{'image_path'} ."bar.gif\" height=\"10\" width=\"$this_bar_width\"></td></tr>\n";
        print "</table>\n";
        }
        
?>

<br /><br />
<h2><a class="start_test" href="<?php echo $config->{'general'}->{'base_url'}; ?>/download.php">Test Again</a></h2>
</center>
<div id="speedtest_credits">
    Powered by <a title="Brandon Checketts Fancy Speed Test" href="http://www.brandonchecketts.com/speedtest/" target="_new">Fancy Speed Test</a>
</div>
</div>

<?php if(file_exists("footer.html")) { include("footer.html"); } ?>

</body>
</html>

<?php
## Convert the raw speed value to a nicer value
function CleanSpeed($kbps) {
    if($kbps > 1024)   {
        $cleanspeed = round($kbps / 1024,2) . " Mbps";
    } else {
        $cleanspeed = round($kbps,2). " kbps";
    }
    return $cleanspeed;
}
?>

