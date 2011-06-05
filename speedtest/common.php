<?php
###############################################################################
## Fancy Speed Test - Easily measure your upload and download speeds
## Home Page:   http://www.brandonchecketts.com/speedtest/
## Author:      Brandon Checketts
## File:        common.php
## Version:     1.1
## Date:        2006-02-06
## Purpose:     Common functions for this application
###############################################################################


## Read through the config file and assign items to the global $config variable
function ReadConfig($config_file) {
    global $config;
    $lines = file($config_file);
    foreach ($lines as $line_num => $line) {
        $line = rtrim(preg_replace("/#.*/","",$line));
        if(preg_match("/\[.*\]/", $line, $parts)) {
            $section = $parts[0];
            $section = preg_replace("/[\[\]]/","",$section);
        } elseif (preg_match("/=/",$line)) {
            list($var,$value) = split('=',$line);
            $var = preg_replace('/ $/','',$var);
            $value = preg_replace('/^ +/','',$value);
            $config->{$section}->{$var} = $value;
        }
    }
}

## Write to log if debugging is on
function Debug($message) {
    global $config;
    if($config->{'general'}->{'debug'}) {
        BCLog($message);
    }
}

## Write to the log file
function BCLog($message) {
    global $config;
    $logfile = $config->{'general'}->{'logfile'};
    if(! $logfile) {
        return;
    }
    $timestamp = date("Y z H:i:s");

    $LOG=fopen($logfile,"a");
    $string="$timestamp $message\n";
    fwrite($LOG,$string);
    fclose($LOG);
}



?>
