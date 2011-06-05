<?php
echo "Installatio gestartet <br >";

include 'lib/lib.php';

KLASSE_set("root","systemklasse");
SCHULER_set("root","system","","besitzer aller Systemeintraege");

// Mailserver
mysql_query("INSERT INTO `mail_domains` VALUES ('mail.osz');");
mysql_query("INSERT INTO `mail_forwardings` VALUES('info@mail.osz', 'test@mail.osz');");
MAIL_user_set("test@mail.osz","",1);

// TLD einrichten
mysql_query("INSERT INTO `dns_soa` (`origin`) VALUES
('.'),
('osz.'),
('sec.osz.'),
('int.osz.'),
('rar.osz.'),
('ip.osz.'),
('server.osz.'),
('user.osz.'),
('schuler.osz.'),
('in-addr.arpa.'),
('192.in-addr.arpa.'),
('10.in-addr.arpa.'),
('1.10.in-addr.arpa.'),
('1.1.10.in-addr.arpa.');");

// alle DNS Einträge der Server
DNS_set("A","main.server.osz", 	"10.1.1.1"	,1,true);
DNS_set("A","dns.server.osz", 	"10.1.1.101",1,true);
DNS_set("A","faq.server.osz", 	"10.1.1.103",1,true);
DNS_set("A","sip.server.osz",	"10.1.1.131",1,true);
DNS_set("A","stun.server.osz",	"10.1.1.132",1,true);
DNS_set("A","samba.server.osz",	"10.1.1.133",1,true);

DNS_set("A","www.mail.osz", 	"10.1.1.1",1,true);
DNS_set("A","mail.osz", 		"10.1.1.134",1,true);
DNS_set("MX","mail.osz", 		"10.1.1.134",1,false);
DNS_set("A","smtp.mail.osz",	"10.1.1.134",1,false);
DNS_set("A","imap.mail.osz",	"10.1.1.134",1,false);
DNS_set("A","pop3.mail.osz",	"10.1.1.134",1,false);

DNS_set("A","rar.server.osz",	"10.1.1.250",1,true);

DNS_set("A","proxy.rar.osz",	"10.0.0.1",	1,true);
DNS_set("A","31.rar.osz",		"10.31.0.1",1,true);
DNS_set("A","64.rar.osz",		"10.64.0.1",1,true);
DNS_set("A","out.rar.osz",	"192.168.100.208",1,true);

DNS_set("A","main.sec.osz",		"10.10.10.1",1,true);
DNS_set("A","dslam.sec.osz",	"10.10.10.10",1,true);
DNS_set("A","sw01.sec.osz",		"10.10.10.101",1,true);
DNS_set("A","rar.sec.osz",		"10.10.10.254",1,true);

DNS_set("A","sw111.sec.osz",	"10.10.10.111",1,true);

DNS_set("A","proxy.int.osz",	"192.168.100.3",1,true);
DNS_set("A","dns.int.osz",		"192.168.100.3",1,false);
DNS_set("A","router.int.osz",	"192.168.100.1",1,true);

for ($i = 1; $i <= 48; $i++){
	DSLAM_set($i, $set);
}

$sql = "INSERT INTO `eth` (`eth_id`, `port`, `name`, `res_vlan`, `link_id`) VALUES";
for ($i = 1; $i <= 16; $i++){
	$sql.="
		(".($i + 0) .", $i, 'sw101', ".($i + 500) .", NULL),
		(".($i +16) .", $i, 'sw111', ".($i + 516) .", NULL),
		(".($i +32) .", $i, 'sw113', ".($i + 532) .", NULL),";		
}
mysql_query(substr($sql,0,-1).";");

echo "Installation fertig <br>";
