<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

// Discuz! �����s���եε{�� whatsnew.php
// ���{�ǨϥΤ�k�� utilities/whatsnew.txt

error_reporting(E_ERROR | E_WARNING | E_PARSE);

$num = 10;				// ��ܦh�ֽg�׾·s�峹
$forumurl = 'http://localhost/discuz';	// �׾� URL �a�}
$length = 50;				// ���D��̤ܳj����(�r����)
$smdir = 'images/smilies';		// Smilies �۹�׾¸��|
$pre = '��-';				// ���D�e�r���A�p��ܬ��峹�ϥܡA�г]�m�� "icon"

require './config.php';

mysql_connect($dbhost, $dbuser, $dbpw);
mysql_select_db($dbname);

$fidin = $HTTP_GET_VARS['fidin'];
$fidout = $HTTP_GET_VARS['fidout'];

$pre = addslashes($pre);

$forumadd = '';
$and = '';
$or = '';

if(strtolower($fidin) != 'all') {
	$fidind = explode('_', $fidin);
	$fidoutd = explode('_', $fidout);
	foreach($fidind as $fid) {
		if(trim($fid)) {
			$forumadd .= "$or fid='$fid'";
			$or = ' OR ';
			$and = ' AND ';
		}
	}

	if($forumadd) {
		$forumadd = "( $forumadd )";
	}

	foreach($fidoutd as $fid) {
		if(trim($fid)) {
			$forumadd .= "$and fid<>'$fid'";
			$and = " AND ";
		}
	}
}

if($forumadd) {
	$forumadd = "AND $forumadd";
}	


$query = mysql_query("SELECT subject, tid, icon FROM $tablepre"."threads WHERE closed NOT LIKE 'moved|%' $forumadd ORDER BY lastpost DESC LIMIT 0, $num") or die(mysql_error());
while($threads = mysql_fetch_array($query)) {
	$threads[subject] = htmlspecialchars(wordscut($threads[subject], $length));
	if($pre == "icon") {
		if($threads[icon]) {
			$icon = "<img src='$forumurl/$smdir/$threads[icon]' valign='absmiddle' border='0'>";
		} else {
			$icon = "";
		}
		echo"document.write(\"$icon <a href=$forumurl/viewthread.php?tid=$threads[tid] target=_blank>$threads[subject]</a><br>\");\n";
	} else {
		echo"document.write(\"<a href=$forumurl/viewthread.php?tid=$threads[tid] target=_blank>$pre$threads[subject]</a><br>\");\n";
	}
}

function wordscut($string, $length) {
	if(strlen($string) > $length) {
		for($i = 0; $i < $length - 3; $i++) {
			if(ord($string[$i]) > 127) {
				$wordscut .= $string[$i].$string[$i + 1];
				$i++;
			} else {
				$wordscut .= $string[$i];
			}
		}
		return $wordscut.' ...';
	}
	return $string;
}

?>