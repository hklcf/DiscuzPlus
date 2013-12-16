<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
require $discuz_root.'./include/discuzcode.php';

$discuz_action = 21;

$total = 0;
//FIX:公告定位問題 BY PK
$query = $db->query("SELECT id, endtime FROM $table_announcements ORDER BY starttime DESC, id DESC"); 

while($announce = $db->fetch_array($query)) {
	if($timestamp >= $announce[starttime] && ($timestamp <= $announce[endtime] || !$announce[endtime])) {
		$total++;
		if($announce[id] == $id) {
			$page = ceil($total / $ppp);
		}
	}
}
if($total != $db->num_rows($query)) {
	$db->query("DELETE FROM $table_announcements WHERE endtime<>'0' AND endtime<'$timestamp'");
	require $discuz_root.'./include/cache.php';
	updatecache('announcements');
}

if(!$total) {
	showmessage('announcement_nonexistence');
}

if (!$page && !$id) {
	$page = 1;
	$start = 0;
} else {
	$start = ($page - 1) * $ppp;
}
$multipage = multi($total, $ppp, $page, 'announcement.php?p=1'); //FIX: BY PK 公告翻頁錯誤


$announcelist = array();
$query = $db->query("SELECT * FROM $table_announcements WHERE endtime='0' OR endtime>'$timestamp' ORDER BY starttime DESC, id DESC LIMIT $start, $ppp");
while($announce = $db->fetch_array($query)) {
	$announce[authorenc] = rawurlencode($announce[author]);
	$announce[starttime] = gmdate($dateformat, $announce[starttime] + $timeoffset * 3600);
	$announce[endtime] = $announce[endtime] ? gmdate($dateformat, $announce[endtime] + $timeoffset * 3600) : NULL;
	$announce[message] = postify($announce[message], 0, 0, 0, 1, 1, 1, 1);

	$announcelist[] = $announce;
}

include template('announcement');

?>