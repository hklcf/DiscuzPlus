<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
require $discuz_root.'./include/forum.php';
require $discuz_root.'./include/attachment.php';

if(!$allowsearch) {
	showmessage('group_nopermission');
}

if(!$searchsubmit && !$page) {

	$discuz_action = 111;
	$forumselect = forumselect();
	intval($onlinerecord) <= 500 ? $navcheck = "checked" : $gradcheck = "checked";

} else {

	$discuz_action = 112;

	$forbidsearch = 0;
	if(!isset($HTTP_COOKIE_VARS['lastsearch'])) {
		$query = $db->query("SELECT lastactivity, action FROM $table_sessions WHERE ip='$onlineip'");
		if($searchsess = $db->fetch_array($query)) {
			if($timestamp - $searchsess['lastactivity'] < $searchctrl && $searchsess['action'] == $discuz_action) {
				$forbidsearch = 1;
			}
		}
	} elseif($timestamp - $HTTP_COOKIE_VARS['lastsearch'] < $searchctrl) {
		$forbidsearch = 1;
	}

	if($forbidsearch) {
		showmessage('search_ctrl');
	}

	setcookie('lastsearch', $timestamp, 0, $cookiepath, $cookiedomain);

	if(!isset($srchfid)) {
		$srchfid = 'all';
	}

	$srchtxt = trim($srchtxt);
	$srchuname = trim($srchuname);

	$fids = $comma = '';
	foreach($_DCACHE[forums] as $fid => $forum) {
		if((!$forum[viewperm] && $allowview) || ($forum[viewperm] && strstr($forum[viewperm], "\t$groupid\t"))) {
			$fids .= "$comma'$fid'";
			$comma = ", ";
		}
	}

	if(!$srchtxt && !$srchuname && !$srchfrom) {
		showmessage('search_invalid');
	} elseif(empty($srchfid)) {
		showmessage('search_forum_invalid');
	} elseif(!$fids) {
		showmessage('group_nopermission');
	}

	if($srchfrom && !$srchtxt && !$srchuname) {

		$searchfrom = $before ? '<=' : '>=';
		$searchfrom .= $timestamp - $srchfrom;
		$sqlsrch = "FROM $table_threads t, $table_forums f WHERE t.fid IN ($fids) AND t.lastpost$searchfrom AND closed NOT LIKE 'moved|%' AND f.password='' AND f.fid=t.fid";
		if($srchfid != "all" && $srchfid) {
			$sqlsrch .= " AND t.fid='$srchfid'";
		}

	} else {

		$db->query("DELETE FROM $table_searchindex WHERE dateline<$timestamp-3600");

		$sqlsrch = "FROM $table_posts p, $table_threads t, $table_forums f WHERE t.fid IN ($fids) AND p.tid=t.tid AND f.password='' AND f.fid=t.fid";

		if($srchtxt) {
			if(preg_match("(AND|\+|&|\s)", $srchtxt) && !preg_match("(OR|\|)", $srchtxt)) {
				$andor = ' AND ';
				$sqltxtsrch = '1';
				$srchtxt = preg_replace("/( AND |&| )/is", "+", $srchtxt);
			} else {
				$andor = ' OR ';
				$sqltxtsrch = '0';
				$srchtxt = preg_replace("/( OR |\|)/is", "+", $srchtxt);
			}
			$srchtxt = str_replace('*', '%', $srchtxt);
			foreach(explode('+', $srchtxt) as $text) {
				$text = trim($text);
				if($text) {
					$sqltxtsrch .= $andor;
					$sqltxtsrch .= $allowsearch == 2 && !$titleonly ? " (p.message LIKE '%$text%' OR p.subject LIKE '%$text%')" : "t.subject LIKE '%$text%'";
				}
			}
			$sqlsrch .= " AND ($sqltxtsrch)";
		}

		if($srchuname) {
			$srchuname = str_replace('*', '%', $srchuname);
			if($srchuname != htmlentities($srchuname)) {
				$srchuname = "%$srchuname%";
			}
			$sqlsrch .= " AND p.author LIKE '$srchuname'";
		}
		if($srchfid != 'all' && $srchfid) {
			$sqlsrch .= " AND p.fid='$srchfid'";
		}
		if($srchfrom) {
			$searchfrom = $before ? '<=' : '>=';
			$searchfrom .= $timestamp - $srchfrom;
			$sqlsrch .= " AND t.lastpost$searchfrom";
		}

	}

	$highlight = "&highlight=".rawurlencode(str_replace('%', '+', $srchtxt).(trim($srchuname) ? '+'.str_replace('%', '+', $srchuname) : NULL));
	$pagenum = $page + 1;
	if(!$page) {
		$page = 1;
	}
	$offset = ($page - 1) * $tpp;

	$mplink = "search.php?srchtxt=".rawurlencode($srchtxt)."&srchuname=".rawurlencode($srchuname)."&srchfid=$srchfid&srchfrom=$srchfrom&orderby=$orderby&titleonly=$titleonly&before=$before";
	if($dispmode == 'gradual') {
		$total = 1;
		$mplink = "$mplink&page=".($page + 1)."&dispmode=gradual";
	} else {
		$keywords = "$srchtxt|$srchuname|$srchfid|$srchfrom|$titleonly|$before";
		$query = $db->query("SELECT results FROM $table_searchindex WHERE keywords='$keywords'");
		$total = $db->result($query, 0);
		if($total == '') {
			$query = $db->query("SELECT COUNT(DISTINCT(t.tid)) $sqlsrch");
			$total = $db->result($query, 0);
			$db->query("INSERT INTO $table_searchindex (keywords, results, dateline)
				VALUES ('$keywords', '$total', '$timestamp')");
		}

		$multipage = multi($total, $tpp, $page, "$mplink&dispmode=nav");
	}

	if($total) {

		$orderby = $orderby == 'views' || $orderby == 'replies' ? $orderby : 'lastpost';
		$query = $db->query("SELECT t.* $sqlsrch GROUP BY t.tid ORDER BY $orderby DESC LIMIT $offset, $tpp");

		if (!isset($page)) {
			$page = 1;
			$start = 0;
		} else {
			$start = ($page - 1) * $tpp;
		}

		$found = 0;
		$threadlist = array();
		while($thread = $db->fetch_array($query)) {
			$found = 1;

			$thread['authorenc'] = rawurlencode($thread['author']);
			$thread['forumname'] = $_DCACHE['forums'][$thread['fid']]['name'];
			$thread['dateline'] = gmdate($dateformat, $thread['dateline'] + $timeoffset * 3600);
			$thread['lastpost'] = gmdate("$dateformat $timeformat", $thread['lastpost'] + $timeoffset * 3600);
			$thread['lastposterenc'] = rawurlencode($thread['lastposter']);

			$postsnum = $thread['replies'] + 1;
			if($postsnum  > $ppp) {
				$posts = $postsnum;
				$topicpages = ceil($posts / $ppp);
				for ($i = 1; $i <= $topicpages; $i++) {
					$pagelinks .= "<a href=\"viewthread.php?tid=$thread[tid]&page=$i\" target=\"_blank\">$i</a> ";
					if($i == 6) {
						$i = $topicpages + 1;
					}
				}
				if($topicpages > 6) {
					$pagelinks .= " .. <a href=\"viewthread.php?tid=$thread[tid]&page=$topicpages\" target=\"_blank\">$topicpages</a> ";
				}
				$thread['multipage'] = "&nbsp;&nbsp;&nbsp;( <img src=\"".IMGDIR."/multipage.gif\" align=\"absmiddle\" boader=0> $pagelinks)";
				$pagelinks = '';
			} else {
				$thread['multipage'] = '';
			}

			if($thread['attachment']) {
				$thread['subject'] = attachicon($thread['attachment']).' '.$thread['subject'];
			}

			$threadlist[] = $thread;

		}

	}

}

$fid =($srchfid=='all')?0:$srchfid; //fix: 搜索時候,用戶所在論壇問題
include template('search');

?>