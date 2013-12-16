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

$visitor['agent'] = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
$visitor['month'] = gmdate('Ym', $timestamp + $timeoffset * 3600);
$visitor['week'] = gmdate('w', $timestamp + $timeoffset * 3600);
$visitor['hour'] = gmdate('H', $timestamp + $timeoffset * 3600);

if(!$sessionexists) {
	if(strpos($visitor['agent'], 'MSIE')) {
		$visitor['browser'] = 'MSIE';
	} elseif(strpos($visitor['agent'], 'Netscape')) {
		$visitor['browser'] = 'Netscape';
	} elseif(strpos($visitor['agent'], 'Lynx')) {
		$visitor['browser'] = 'Lynx';
	} elseif(strpos($visitor['agent'], 'Opera')) {
		$visitor['browser'] = 'Opera';
	} elseif(strpos($visitor['agent'], 'Konqueror')) {
		$visitor['browser'] = 'Konqueror';
	} elseif(substr('Mozilla/5.0 (compatible; MSIE 5.0)', 0, 7) == 'Mozilla') {
		$visitor['browser'] = 'Mozilla';
	} else {
		$visitor['browser'] = 'Other';
	}

	if(strpos($visitor['agent'], 'Win')) {
		$visitor['os'] = 'Windows';
	} elseif(strpos($visitor['agent'], 'Mac')) {
		$visitor['os'] = 'Mac';
	} elseif(strpos($visitor['agent'], 'Linux')) {
		$visitor['os'] = 'Linux';
	} elseif(strpos($visitor['agent'], 'FreeBSD')) {
		$visitor['os'] = 'FreeBSD';
	} elseif(strpos($visitor['agent'], 'SunOS')) {
		$visitor['os'] = 'SunOS';
	} elseif(strpos($visitor['agent'], 'BeOS')) {
		$visitor['os'] = 'BeOS';
	} elseif(strpos($visitor['agent'], 'OS/2')) {
		$visitor['os'] = 'OS/2';
	} elseif(strpos($visitor['agent'], 'AIX')) {
		$visitor['os'] = 'AIX';
	} else {
		$visitor['os'] = 'Other';
	}
	$visitorsadd = "OR (type='browser' AND var='$visitor[browser]') OR (type='os' AND var='$visitor[os]')";
	$visitorsadd .= $discuz_user ? " OR (type='total' AND var='members')" : " OR (type='total' AND var='guests')";
	$updatedrows = 7;
} else {
	$visitorsadd = '';
	$updatedrows = 4;
}

$db->query("UPDATE $table_stats SET count=count+1 WHERE (type='total' AND var='hits') $visitorsadd OR (type='month' AND var='$visitor[month]') OR (type='week' AND var='$visitor[week]') OR (type='hour' AND var='$visitor[hour]')");

if($updatedrows > $db->affected_rows()) {
	$db->query("INSERT INTO $table_stats (type, var, count)
		VALUES ('month', '$visitor[month]', '1')");
}

?>