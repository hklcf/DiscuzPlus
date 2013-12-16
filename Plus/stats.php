<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require "./include/common.php";

$discuz_action = 131;

if(!$allowviewstats) {
	showmessage('group_nopermission');
}

if(!$type) {
	$vartype = "'total', 'month', 'hour'";
} elseif($type == "agent") {
	$vartype = "'os', 'browser'";
} else {
	$vartype = "'$type'";
}

$query = $db->query("SELECT * FROM $table_stats WHERE type IN ($vartype) ORDER BY type");
while($stats = $db->fetch_array($query)) {
	switch($stats['type']) {
		case total:
			$stats_total[$stats['var']] = $stats['count'];
			break;
		case os:
			$stats_os[$stats['var']] = $stats['count'];
			if($stats['count'] > $maxos) {
				$maxos = $stats['count'];
			}
			break;
		case browser:
			$stats_browser[$stats['var']] = $stats['count'];
			if($stats['count'] > $maxbrowser) {
				$maxbrowser = $stats['count'];
			}
			break;
		case month:
			$stats_month[$stats['var']] = $stats['count'];
			if($stats['count'] > $maxmonth) {
				$maxmonth = $stats['count'];
				$maxmonth_year = intval($stats['var'] / 100);
				$maxmonth_month = $stats['var'] - $maxmonth_year * 100;
			}
			break;
		case week:
			$stats_week[$stats['var']] = $stats['count'];
			if($stats['count'] > $maxweek) {
				$maxweek = $stats['count'];
				$maxweek_day = $stats['var'];
			}
			break;
		case hour:
			$stats_hour[$stats['var']] = $stats['count'];
			if($stats['count'] > $maxhour) {
				$maxhour = $stats['count'];
				$maxhourfrom = $stats['var'];
				$maxhourto = $maxhourfrom + 1;
			}
			break;
	}
}

if(!$type) {

	$query = $db->query("SELECT COUNT(*) FROM $table_forums WHERE type='forum' OR type='sub'");
	$forums = $db->result($query, 0);

	$query = $db->query("SELECT COUNT(*) FROM $table_threads");
	$threads = $db->result($query, 0);

	$query = $db->query("SELECT COUNT(*), (MAX(dateline)-MIN(dateline))/86400 FROM $table_posts");
	list($posts, $runtime) = $db->fetch_row($query);

	$query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE dateline>='".($timestamp - 86400)."'");
	$postsaddtoday = $db->result($query, 0);

	$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE regdate>='".($timestamp - 86400)."'");
	$membersaddtoday = $db->result($query, 0);

	$query = $db->query("SELECT COUNT(*) FROM $table_members");
	$members = $db->result($query, 0);

	$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE status='Admin' OR status='SuperMod' OR status='Moderator'");
	$admins = $db->result($query, 0);

	$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE postnum='0'");
	$memnonpost = $db->result($query, 0);
	$mempost = $members - $memnonpost;

	@$mempostavg = sprintf ("%01.2f", $posts / $members);
	@$threadreplyavg = sprintf ("%01.2f", ($posts - $threads) / $threads);
	@$mempostpercent = sprintf ("%01.2f", 100 * $mempost / $members);
	@$postsaddavg = round($posts / $runtime);
	@$membersaddavg = round($members / $runtime);

	$query = $db->query("SELECT author, COUNT(*) AS postnum FROM $table_posts WHERE dateline >= '".($timestamp - 86400)."' GROUP BY author ORDER BY postnum DESC LIMIT 0, 1");
	list($bestmem, $bestmempost) = $db->fetch_row($query);
	if($bestmem) {
		$bestmem = "<a href=\"viewpro.php?username=".rawurlencode($bestmem)."\"><span class=\"bold\">$bestmem</span></a>";
	} else {
		$bestmem = 'None';
		$bestmempost = 0;
	}

	$query = $db->query("SELECT posts, threads, fid, name FROM $table_forums ORDER BY posts DESC LIMIT 0, 1");
	$hotforum = $db->fetch_array($query);

	$stats_total['visitors'] = $stats_total['members'] + $stats_total['guests'];
	@$pageviewavg = sprintf ("%01.2f", $stats_total['hits'] / $stats_total['visitors']);
	@$activeindex = round(($membersaddavg / $members + $postsaddavg / $posts) * 1500 + $threadreplyavg * 10 + $mempostavg * 1 + $mempostpercent / 10 + $pageviewavg);

	$statsbar_month = statsdata('month', $maxmonth);

	include template('stats_main');

} elseif($type == 'week' || $type == 'hour' || $type == 'agent') {

	switch($type) {
		case 'week':	$statsbar_week = statsdata('week', $maxweek); break;
		case 'hour':	$statsbar_hour = statsdata('hour', $maxhour); break;
		case 'agent':	$statsbar_browser = statsdata('browser', $maxbrowser, 0);
				$statsbar_os = statsdata('os', $maxos, 0); break;
	}

	include template('stats_misc');

} elseif($type == 'threads') {

	$threadview = $threadreply = array();
	$query = $db->query("SELECT views, tid, subject FROM $table_threads ORDER BY views DESC LIMIT 0, 20");
	while($thread = $db->fetch_array($query)) {
		$thread[subject] = wordscut($thread[subject], 45);
		$threadview[] = $thread;
	}
	$query = $db->query("SELECT replies, tid, subject FROM $table_threads ORDER BY replies DESC LIMIT 0, 20");
	while($thread = $db->fetch_array($query)) {
		$thread[subject] = wordscut($thread[subject], 50);
		$threadreply[] = $thread;
	}

	include template('stats_threads');

} elseif($type == 'member') {

	$members = '';
	$credits = $total = $thismonth = $today = array();
	$query = $db->query("SELECT username, uid, credit FROM $table_members ORDER BY credit DESC LIMIT 0, 20");
	while($member = $db->fetch_array($query)) {
		$credits[] = $member;
	}

	$query = $db->query("SELECT username, uid, postnum FROM $table_members ORDER BY postnum DESC LIMIT 0, 20");
	while($member = $db->fetch_array($query)) {
		$total[] = $member;
	}

	$query = $db->query("SELECT DISTINCT(author) AS username, COUNT(pid) AS postnum FROM $table_posts WHERE dateline >= ".($timestamp - 86400 * 30)." GROUP BY author ORDER BY postnum DESC LIMIT 0, 20");
	while($member = $db->fetch_array($query)) {
		$thismonth[] = $member;
	}

	$query = $db->query("SELECT DISTINCT(author) AS username, COUNT(pid) AS postnum FROM $table_posts WHERE dateline >= ".($timestamp - 86400)." GROUP BY author ORDER BY postnum DESC LIMIT 0, 20");
	while($member = $db->fetch_array($query)) {
		$today[] = $member;
	}

	for($i = 0; $i < 20; $i++) {
		$members .= "<tr $bgcolor><td><li> <a href=\"viewpro.php?username=".rawurlencode($credits[$i][username])."\">".$credits[$i][username]."</a></td><td align=\"right\">".$credits[$i][credit]."</td><td bgcolor=\"".ALTBG1."\"></td>\n".
			"<td><li type=\"square\"> <a href=\"viewpro.php?username=".rawurlencode($total[$i][username])."\">".$total[$i][username]."</a></td><td align=\"right\">".$total[$i]['postnum']."</td><td bgcolor=\"".ALTBG1."\"></td>\n".
			"<td><li> <a href=\"viewpro.php?username=".rawurlencode($thismonth[$i][username])."\">".$thismonth[$i][username]."</a></td><td align=\"right\">".$thismonth[$i]['postnum']."</td><td bgcolor=\"".ALTBG1."\"></td>\n".
			"<td><li type=\"square\"> <a href=\"viewpro.php?username=".rawurlencode($today[$i][username])."\">".$today[$i][username]."</a></td><td align=\"right\">".$today[$i]['postnum']."</td></tr>\n";
		$bgcolor = $bgcolor ? '' : 'bgcolor="'.ALTBG2.'"';
	}

	include template('stats_member');

} else {

	showmessage('undefined_action');

}

function statsdata($type, $max, $sort = 1) {
	global $barno;

	$statsbar = '';
	$sum = 0;

	$datarray = $GLOBALS["stats_$type"];
	if(is_array($datarray)) {
		if($sort) {
			ksort($datarray);
		}
		foreach($datarray as $count) {
			$sum += $count;
		}
	} else {
		$datarray = array();
	}

	foreach($datarray as $var => $count) {
		$barno ++;
		switch($type) {
			case month:
				$var = substr($var, 0, 4).'-'.substr($var, -2);
				break;
			case week:
				switch($var) {
					case 00: $var = 'Sunday'; break;
					case 01: $var = 'Monday'; break;
					case 02: $var = 'Tuesday'; break;
					case 03: $var = 'Wednesday'; break;
					case 04: $var = 'Thursday'; break;
					case 05: $var = 'Friday'; break;
					case 06: $var = 'Saturday'; break;
				}
				break;
			case hour:
				$var = intval($var);
				if($var <= 12) {
					$var = "$var AM";
				} else {
					$var -= 12;
					$var = "$var PM";
				}
				break;
			default:
				$var = '<img src="images/stats/'.strtolower(str_replace('/', '', $var)).'.gif" border="0"> '.$var;
				break;
		}
		@$width = intval(370 * $count / $max);
		@$percent = sprintf ("%01.1f", 100 * $count / $sum);
		$width = $width ? $width : '2';
		$var = $count == $max ? '<span class="bold"><i>'.$var.'</i></span>' : $var;
		$count = '<img src="images/common/bar0'.($barno % 10).'.gif" height="8" border="0"><img src="images/common/bar'.($barno % 10).'.gif" width="'.$width.'" height="8" border="0"><img src="images/common/bar1'.($barno % 10).'.gif" height="8" border="0"> &nbsp; <span class="bold">'.$count.'</span> ('.$percent.'%)';
		$statsbar .= "<tr><td width=\"100\">$var</td><td width=\"500\">$count</td></tr>\n";
	}

	return $statsbar;
}

?>