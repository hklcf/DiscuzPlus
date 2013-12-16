<?php

/*
	Version: 1.1.4(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/11/18
*/

if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

function attach_upload() {
	global $discuz_root, $attachsave, $attach, $attach_name, $attach_size, $attach_fname, $attachdir, $maxattachsize, $attachextensions;

	if(!function_exists('is_uploaded_file')) {
		if(!is_uploaded_file($attach)) {
			return false;
		}
	} elseif(!($attach != 'none' && $attach && trim($attach_name))) {
		return false;
	}

	$attach_name = daddslashes($attach_name);
	if($attachextensions && @!eregi(substr(strrchr($attach_name, '.'), 1), $attachextensions)) {
		showmessage('post_attachment_ext_notallowed');
	}

	if(!$attach_size || ($maxattachsize && $attach_size > $maxattachsize)) {
		showmessage('post_attachment_toobig');
	}

	$filename = $attach_name;

	$extension = strtolower(substr(strrchr($filename, '.'), 1));

	if(in_array($extension, array('jpg', 'gif', 'bmp','png'))) { 
	        $imagesize = getimagesize ("$attach"); 
	        if (!$imagesize[0] || !$imagesize[1]) showmessage('圖片格式非法！無法上傳！'); 
	}

	if($attachsave) {
		switch($attachsave) {
			case 1: $attach_subdir = 'forumid_'.$GLOBALS['fid']; break;
			case 2: $attach_subdir = 'ext_'.$extension; break;
			case 3: $attach_subdir = 'month_'.date('ym'); break;
			case 4: $attach_subdir = 'day_'.date('ymd'); break;
		}
		if(!is_dir($discuz_root.'./'.$attachdir.'/'.$attach_subdir)) {
			mkdir($discuz_root.'./'.$attachdir.'/'.$attach_subdir, 0777);
		}
		$attach_fname = $attach_subdir.'/';
	} else {
		$attach_fname = '';
	}

	$filename = substr($filename, 0, strlen($filename) - strlen($extension) - 1);
	if(preg_match("/[\x7f-\xff]+/s", $filename)) {
		$filename = str_replace('/', '', base64_encode(substr($filename, 0, 20)));
	}
	if(in_array($extension, array('php', 'php3', 'jsp', 'asp', 'cgi', 'pl'))) {
		$extension = '_'.$extension;
	}
	$attach_fname .= random(4)."_$filename.$extension";

	$attach_saved = false;

	$source = stripslashes($discuz_root.'./'.$attachdir.'/'.$attach_fname);
	if(@copy($attach, $source)) {
		$attach_saved = true;
	} elseif(function_exists('move_uploaded_file')) {
		if(@move_uploaded_file($attach, $source)) {
			$attach_saved = true;
		}
	}

	if(!$attach_saved && @is_readable($attach)) {
		@$fp = fopen($attach, 'rb');
		@flock($fp, 2);
		@$attachedfile = fread($fp, $attach_size);
		@fclose($fp);

		@$fp = fopen($source, 'wb');
		@flock($fp, 3);
		if(@fwrite($fp, $attachedfile)) {
			$attach_saved = true;
		}
		@fclose($fp);
	}

	if(!$attach_saved) {
		showmessage('post_attachment_save_error');
	} else {
		return true;
	}
}

function updatemember($operator, $username, $posts = 1) {
	global $db, $_DSESSION, $table_members, $discuz_user;

	if(strpos($username, ",")) {
		$member = $post = array();
		foreach(explode(",", $username) as $user) {
			$member[$user]++;
		}

		$curr_posts = $member[$user];
		$curr_username = $next_username = $curr_comma = $next_comma = "";
		foreach($member as $user => $postnum) {
		 	if($postnum == $curr_posts) {
		 		$curr_username .= "$curr_comma'$user'";
		 		$curr_comma = ", ";
		 	} else {
		 		for($i = 0; $i < $member[$user]; $i++) {
		 			$next_username .= "$next_comma$user";
		 			$next_comma = ",";
		 		}
			}
		}

		$username = $curr_username;
		$posts = $posts * $curr_posts;
	} else {
		$username = "'$username'";
	}

	$postcredits = $GLOBALS[postcredits] * $posts;
	if($username == $discuz_user || strstr($username, "'$discuz_user'")) { //debug
		$operator == "+" ? $_DSESSION[credit] += $postcredits : $_DSESSION[credit] -= $postcredits;
		$operator == "+" ? $_DSESSION[postnum] += $posts : $_DSESSION[postnum] -= $posts;
	}
	$db->query("UPDATE $table_members SET postnum=postnum$operator$posts, credit=credit$operator($postcredits) WHERE username IN ($username)");

	if($next_username) {
		updatemember($operator, $next_username, strpos($next_username, ",") ? $posts / $curr_posts : $member[$next_username]);
	}
}

function updateforumcount($fid) {
	global $db, $table_threads, $table_forums;
	$query = $db->query("SELECT COUNT(*) AS threadcount, SUM(t.replies) + COUNT(*) AS replycount FROM $table_threads t, $table_forums f WHERE (f.fid='$fid' OR (f.fup='$fid' AND f.type<>'group')) AND t.fid=f.fid AND t.closed NOT LIKE 'moved|%'");
	extract($db->fetch_array($query), EXTR_OVERWRITE);

	$query = $db->query("SELECT subject, lastpost, lastposter FROM $table_threads WHERE fid='$fid' ORDER BY lastpost DESC LIMIT 1");
	$thread = $db->fetch_array($query);
	$thread[subject] = addslashes($thread[subject]);
	$thread[lastposter] = addslashes($thread[lastposter]);
	$db->query("UPDATE $table_forums SET posts='$replycount', threads='$threadcount', lastpost='$thread[subject]\t$thread[lastpost]\t$thread[lastposter]' WHERE fid='$fid'");
}

function updatethreadcount($tid) {
	global $db, $table_threads, $table_posts;
	$query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE tid='$tid'");
	$replycount = $db->result($query, 0) - 1;
	if($replycount < 0) {
		$db->query("DELETE FROM $table_threads WHERE tid='$tid'");
	}
	$query = $db->query("SELECT dateline, author FROM $table_posts WHERE tid='$tid' ORDER BY dateline DESC LIMIT 0, 1");
	$lastpost = $db->fetch_array($query);
	$lastpost[author] = addslashes($lastpost[author]);
	$db->query("UPDATE $table_threads SET replies='$replycount', lastposter='$lastpost[author]', lastpost='$lastpost[dateline]' WHERE tid='$tid'");
}

?>