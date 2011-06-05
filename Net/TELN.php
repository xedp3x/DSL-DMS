<?php
/*
 * Telnet Bibliothek
 * @ pc-crack.eu
 * 
 * <?php
 *    include('TELN.php');
 *
 *    $teln = new Net_TELN('www.example.com',80);
 *
 *    echo $teln->exec(	"GET / HTTP/1.1\r\n".
 *   					"Host: www.example.com\r\n".
 *						"Connection: Close\r\n\r\n");
 * ?>
 */


class Net_TELN {
	var $fsock;
	
	function Net_TELN($host, $port = 21, $timeout = 30){
		$this->fsock = fsockopen($host, $port, $errno, $errstr, $timeout);
		if (!$this->fsock) {
    		error("E202","$errstr ($errno)<br />\n");
		}
    	return true;
	}
	
	function send($command){
		fwrite($this->fsock, $command);
	}
	
	function recv(){
		while (!feof($this->fsock)) {
        	//$out.= fgets($this->fsock, 128);
        	$out[$i++] = fgets($this->fsock);
    	}	
    	return $out;
	}

	function exec($command){
		$this->send($command."\r\n");
		return $this->recv();
	}
	
   function disconnect()
    {
         fclose($this->fsock);
    }

    function __destruct() {
        fclose($this->fsock);
    }
}