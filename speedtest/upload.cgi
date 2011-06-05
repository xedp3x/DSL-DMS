#!/usr/bin/perl -w
###############################################################################
## Fancy Speed Test - Easily measure your upload and download speeds
## Home Page:   http://www.brandonchecketts.com/speedtest/
## Author:      Brandon Checketts
## File:        upload.cgi
## Version:     1.1
## Date:        2006-02-06
## Purpose:     Time the upload progress, forward to results.php (or back to
##              download.php if using auto_size)
###############################################################################

use strict;
use File::Basename;         ## for dirname();
use Time::HiRes qw(gettimeofday tv_interval);
## Time::HiRes should be included with most Perl distributions
## use "perl -MCPAN -e install Time::HiRes" if it isn't installed

my $dirname = dirname($0);
my $config_file = "$dirname/speedtest.cfg";
## NOTE: 
## If you have to move upload.cgi to another directory (ie: /cgi-bin) then
## you will have to change the path for $config_file to point to the
## correct directory.  
# my $config_file = "/home/brandonchecketts.com/speedtest/speedtest.cfg";



## Make sure we read in the config file
my $config = &ReadConfig($config_file);
DieNicely("Unable to read configuration settings") if(! $config);

## This is what PHP can't do .......
my $content_length = $ENV{'CONTENT_LENGTH'} ? $ENV{'CONTENT_LENGTH'} : 1;

## Don't buffer output
$|=1;

## Start the timer
Debug("Starting to read");
Debug("len is $content_length");
my $t0 = [gettimeofday];

## Read all of the STDIN (HTTP POST data)
my $bytes_read = 0;
while (read (STDIN ,my $line, 4096) && $bytes_read < $content_length ) {
	$bytes_read += length($line);
}
## Stop the timer
my $t1 = [gettimeofday];
Debug("Done reading");



## Calculate the speed
my $elapsed = sprintf("%.2f",tv_interval ( $t0, $t1 ));

my $upload_speed;
if($elapsed != 0) {
    $upload_speed = sprintf("%.2f",$bytes_read / $elapsed * 8 / 1024);
} else {
    $upload_speed="undefined";
}
my $uploadsize = $bytes_read / 1024;

Debug("\$upload_size is $uploadsize");
Debug("\$bytes_read is $bytes_read");
Debug("\$elapsed is $elapsed");
Debug("\$upload_speed is $upload_speed");


## If we're using auto_size, then forward back to download.php with the
## speed values from out initial test.  
## Otherwise forward to result.php to display the results

my $url;
if( $ENV{'QUERY_STRING'} =~ m/auto_size=1/) {
    $url = $config->{'general'}->{'base_url'} . "download.php?".$ENV{'QUERY_STRING'}."&uptime=$elapsed&upsize=$uploadsize&upspeed=$upload_speed";
} else {
    $url = $config->{'general'}->{'base_url'} . "results.php?".$ENV{'QUERY_STRING'}."&uploadtime=$elapsed&uploadsize=$uploadsize&upspeed=$upload_speed";
}

Log("Redirecting to $url");
print "Location: $url\n\n";


## My standard Log() function
sub Log {
    my $message = shift;
    my $logfile = $config->{'general'}->{'logfile'};
    return if(! $logfile);

    (my $sec, my $min, my $hour, my $year, my $jdate)=(localtime())[0,1,2,5,7];
    if($sec < 10) {
        $sec="0$sec";
    }
    if($min < 10) {
        $min="0$min";
    }
    if($hour < 10) {
        $hour="0$hour";
    }

    if($jdate < 100) {
        if($jdate < 10) {
            $jdate="0$jdate";
        }
        $jdate="0$jdate";
    }

    $year=$year+1900;
    if($jdate == "365" || ($jdate == "364" && ($year % 4 == 0))) {
        $jdate++;
    }
    open(LOGFILE,">> $logfile") ||
        DieNicely("Couldn't open $logfile");
    print LOGFILE "$year $jdate $hour:$min:$sec $message\n";
    close(LOGFILE);
}

## Display a nice error message if we have to die
sub DieNicely {
    my $message = shift();
    print "Content-type: text/html\n\n<h1>A critical error occurred: $message</h1>\n";
    Log("Dying: $message");
    exit 1;
}

## Debug to the log file if the config file says to
sub Debug {
    Log(@_) if($config->{'general'}->{'debug'});
}



## Read in the configuration file into our $config variable
sub ReadConfig() {
    my $config;
    $config_file = shift;
    open(CONF,"<$config_file") ||
        DieNicely("Unable to read configuration file: $config_file\n\n");
    my $section;
    while(<CONF>) {
        chomp($_);
        s/\r//g;        ## Remove DOS EOL Symbols
        s/\#.*//g;      ## Remove comments
        next if(! $_);

        if (substr($_,0,1) eq "[") {
            $section = $_;
            $section =~ s/\[//g;
            $section =~ s/\]//g;
            next;
        }
        next if($_ !~ m/=/);
        
        if($section) {
            my ($item,$value) = split('=',$_);
            ## Remove un-necessary speces
            $item =~ s/ +$//g;
            $value =~ s/^ $//g;
            $config->{$section}->{$item} = $value;
        }
    }
    close(CONF);
    return $config;
}






