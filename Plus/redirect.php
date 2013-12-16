<?php

/*
	Version: 1.1.4(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/12/4
*/

require './include/common.php';

if(isset($fid) && empty($forum)) {
	showmessage('forum_nonexistence');
}

if($goto == 'lastpost') {

	if($highlight) {
		$highlight = "&highlight=".rawurlencode($highlight);
	}
	if($tid) {
		$query = $db->query("SELECT p.pid, p.dateline, t.tid, t.replies FROM $table_threads t, $table_posts p WHERE p.tid=t.tid AND t.tid='$tid' ORDER BY p.dateline DESC LIMIT 0,1");
		$post = $db->fetch_array($query);
		$page = ceil(($post['replies'] + 1) / $ppp);

		header("Location: {$boardurl}viewthread.php?tid=$post[tid]&page=$page&sid=$sid#pid$post[pid]$highlight");
	} else {
		$query = $db->query("SELECT p.pid, p.dateline, t.tid, t.replies FROM $table_threads t, $table_posts p WHERE p.tid=t.tid AND t.fid='$fid' ORDER BY p.dateline DESC LIMIT 0,1");
		$post = $db->fetch_array($query);
		$page = '&page='.ceil(($post['replies'] + 1) / $ppp);

		header("Location: {$boardurl}viewthread.php?tid=$post[tid]&page=$page&sid=$sid#pid$post[pid]");
	}

} elseif($goto == 'newpost') {

	if($highlight) {
		$highlight = "&highlight=".rawurlencode($highlight);
	}

	$posts = 0;
	$query = $db->query("SELECT pid, dateline FROM $table_posts WHERE tid='$tid' AND dateline>'$lastvisit' ORDER BY dateline");
	while($post = $db->fetch_array($query)) {
		if($post['dateline'] < $lastvisit) {
			$posts++;
		} else {
			break;
		}
	}

	$page = ceil($posts / $ppp);
	header("Location: viewthread.php?tid=$tid&page=$page&#pid$post[pid]");

} elseif($goto == 'nextnewset') {

	if($fid && $tid) {
		$query = $db->query("SELECT lastpost FROM $table_threads WHERE tid='$tid'");
		$this_lastpost = $db->result($query, 0);
		$query = $db->query("SELECT tid FROM $table_threads WHERE fid='$fid' AND lastpost>'$this_lastpost' ORDER BY lastpost ASC LIMIT 0, 1");
		if($next = $db->fetch_array($query)) {
			header("Location: {$boardurl}viewthread.php?tid=$next[tid]&sid=$sid");
		} else {
			showmessage('redirect_nextnewset_nonexistence');//fix:提示信息顯示疏漏
		}
	} else {
		showmessage('undefined_action');
	}

} elseif($goto == 'nextoldset') {

	if($fid && $tid) {
		$query = $db->query("SELECT lastpost FROM $table_threads WHERE tid='$tid'");
		$this_lastpost = $db->result($query, 0);
		$query = $db->query("SELECT tid FROM $table_threads WHERE fid='$fid' AND lastpost<'$this_lastpost' ORDER BY lastpost DESC LIMIT 0, 1");
		if($last = $db->fetch_array($query)) {
			header("Location: {$boardurl}viewthread.php?tid=$last[tid]&sid=$sid");
		} else {
			showmessage('reditect_nextoldset_nonexistence');
		}
	} else {
		showmessage('undefined_action');
	}

}

?>