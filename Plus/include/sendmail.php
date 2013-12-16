<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

require $discuz_root.'./mail_config.php';
@include language('emails');

error_reporting($sendmail_silent ? E_CORE_ERROR : E_ERROR | E_WARNING | E_PARSE);

if(isset($language[$subject])) {
	eval("\$subject = \"".$language[$subject]."\";");
}
if(isset($language[$message])) {
	eval("\$message = \"".$language[$message]."\";");
}

$subject = str_replace("\r", '', str_replace("\n", '', $subject));
$message = str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $message)))));

if(!$from) {
	$from = "$bbname <$adminemail>";
}

if($mailsend == 1) {

	if(strpos($to, ',')) {
		mail('Discuz! User <me@localhost>', $subject, $message, "From: $from\r\nBcc: $to");
	} else {
		mail($to, $subject, $message, "From: $from");
	}

} elseif($mailsend == 2) {

	$fp = fsockopen($mailcfg['server'], $mailcfg['port'], &$errno, &$errstr, 30);
	if($mailcfg['auth']) {
		$from = $mailcfg['from'];
		fputs($fp, "EHLO discuz \r\n");
		fputs($fp, "AUTH LOGIN \r\n");
		fputs($fp, base64_encode($mailcfg['auth_username'])." \r\n");
		fputs($fp, base64_encode($mailcfg['auth_password'])." \r\n");
	} else {
		fputs($fp, "HELO discuz \r\n");
	}

	fputs($fp, "MAIL FROM: $from\r\n");

	foreach(explode(',', $to) as $touser) {
		$touser = trim($touser);
		if($touser) {
			fputs($fp, "RCPT TO: $touser\r\n");
		}
	}

	fputs($fp, "DATA\r\n");
	$tosend  = "From: $mailcfg[from]\r\n";
	$tosend .= "To: Discuz Users <info@discuz.net>\r\n";
	$tosend .= 'Subject: '.str_replace("\n", ' ', $subject)."\r\n\r\n$message\r\n.\r\n"; 
	fputs($fp, $tosend);

	fputs($fp, "QUIT\r\n");
	echo nl2br(fread($fp, 10000));	//debug
	fclose($fp);

} elseif($mailsend == 3) {

	ini_set('SMTP', $mailcfg['server']);
	ini_set('smtp_port', $mailcfg['port']);
	ini_set('sendmail_from', $from);

	foreach(explode(',', $to) as $touser) {
		$touser = trim($touser);
		if($touser) {
			mail($touser, $subject, $message, "From: $from");
		}
	}

}

error_reporting(E_ERROR | E_WARNING | E_PARSE);

?>