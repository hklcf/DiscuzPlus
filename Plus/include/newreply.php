<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

$discuz_action = 12;

if(empty($thread)) {
	showmessage('thread_nonexistence');
}

if(!$replysubmit) {

	if($repquote) {
		$query = $db->query("SELECT message, fid, author, dateline FROM $table_posts WHERE pid='$repquote'");
		$thaquote = $db->fetch_array($query);
		$quotefid = $thaquote['fid'];
		$message = $thaquote['message'];

		$time = gmdate("$dateformat $timeformat", $thaquote[dateline] + ($timeoffset * 3600));
		$message = preg_replace("/\[hide=?\d*\](.+?)\[\/hide\]/is", "[b]**** Hidden message by originally poster *****[/b]", $message);
		  $message = preg_replace("/\[sell=?\d*\](.+?)\[\/sell\]/is", "[b]**** 付費信息，已經隱藏 *****[/b]", $message); 
		$message = preg_replace("/(\[quote])(.*)(\[\/quote])/siU", "", $message);
		$message = wordscut(dhtmlspecialchars(trim($message)), 200);

		$message = preg_replace("/\n{2}\[ Last edited by .+? on .+? at .+? \]$/s", '', $message);
		$message = "[quote]Originally posted by [i]$thaquote[author][/i] at $time:\n$message [/quote]\n";
	}

	if($thread['replies'] <= $ppp) {
		$altbg1 = ALTBG1;
		$altbg2 = ALTBG2;
		$postcount = 0;
		$postlist = array();
		$query = $db->query("SELECT * FROM $table_posts WHERE tid='$tid' ORDER BY dateline DESC");
		while($post = $db->fetch_array($query)) {
			$bgno = $postcount++ % 2 + 1;
			$post['thisbg'] = ${altbg.$bgno};
			$post['dateline'] = gmdate("$dateformat $timeformat", $post[dateline] + $timeoffset * 3600);;
			$post['message'] = preg_replace("/\[hide=?\d*\](.+?)\[\/hide\]/is","[b]**** Hidden message by originally poster *****[/b]", $post['message']);
			$post['message'] = postify($post['message'], $post['smileyoff'], $post['bbcodeoff'], $forum['allowsmilies'], $forum['allowhtml'], $forum['allowbbcode'], $forum['allowimgcode']);

			$postlist[] = $post;
		}
	}

	include template('post_newreply');

} else {

	if(($subject == "" || ereg("^ *$", $subject)) && $message == "") {
		showmessage('post_sm_isnull');
	}

        if(strlen($message) < $postnum) {
                showmessage('post_message_tooshort');
        }

	if(strlen(htmlspecialchars($subject)) > 100) {
		showmessage('post_subject_toolang');
	}

	if(!$issupermod && $maxpostsize && strlen($message) > $maxpostsize) {
		showmessage('post_message_toolang');
	}

	if(!$isadmin && !$issupermod && $forum[lastpost]) {
		$lastpost = explode("\t", $forum[lastpost]);
		if(($timestamp - $floodctrl) <= $lastpost[1] && $username == $lastpost[2]) {
			showmessage('post_flood_ctrl');
		}
	}

	if (!$isadmin && !$issupermod) {$subject = dhtmlspecialchars($subject);}
	if($thread['closed'] && !$ismoderator) {
		showmessage('post_thread_closed');
	} else {
		$emails = $comma = '';
		$notifytime = $timestamp - 43200;
		$query = $db->query("SELECT email FROM $table_subscriptions WHERE username<>'$username' AND tid='$tid' AND lastnotify<'$notifytime'");
		while($subs = $db->fetch_array($query)) {
			$emails .= $comma.$subs['email'];
			$comma = ', ';
		}
		if($emails) {
			sendmail($emails, 'email_notify_subject', 'email_notify_content');
		}
		if($emailnotify && $username != 'Guest') {
			$query = $db->query("SELECT COUNT(*) FROM $table_subscriptions WHERE tid='$tid' AND username='$username'");
			if(!$db->result($query, 0)) {
				$db->unbuffered_query("INSERT INTO $table_subscriptions (username, email, tid)
					VALUES ('$username', '$email', '$tid')");
			}
		}
		if(attach_upload() && ((!$forum['postattachperm'] && $allowpostattach) || ($forum['postattachperm'] && strstr($forum['postattachperm'], "\t$groupid\t")))) {
			$attachperm = $allowsetattachperm ? $attachperm : 0;
			$db->query("INSERT INTO $table_attachments (tid, pid, creditsrequire, filename, filetype, filesize, attachment)
				VALUES ('$tid', '', '$attachperm', '$attach_name', '$attach_type', '$attach_size', '$attach_fname')");
			$aid = $db->insert_id();
			$attach_type = substr(strrchr($attach_name, '.'), 1)."\t".$attach_type;
		} else {
			$attach_type = '';
			$aid = 0;
		}

	if($issell){
		$message="[sell=".$price."]".$message."[/sell]";
	}
		$db->query("INSERT INTO $table_posts  (fid, tid, aid, icon, author, subject, dateline, message, useip, usesig, bbcodeoff, smileyoff, parseurloff)
			VALUES ('$fid', '$tid', '$aid', '$posticon', '$username', '$subject', '$timestamp', '$message', '$onlineip', '$usesig', '$bbcodeoff', '$smileyoff', '$parseurloff')");
		$pid = $db->insert_id();
		if($aid) {
			$db->query("UPDATE $table_attachments SET pid='$pid' WHERE aid='$aid'");
			$db->unbuffered_query("UPDATE $table_threads SET lastposter='$username', lastpost='$timestamp', replies=replies+1, attachment='$attach_type' WHERE tid='$tid' AND fid='$fid'");
		} else {
			$db->unbuffered_query("UPDATE $table_threads SET lastposter='$username', lastpost='$timestamp', replies=replies+1 WHERE tid='$tid' AND fid='$fid'");
		}
		updatemember('+', $username);
		$db->unbuffered_query("UPDATE $table_forums SET lastpost='".addslashes($thread[subject])."\t$timestamp\t$username', posts=posts+1 WHERE fid='$fid' $fupadd");
	}

	@$topicpages = ceil(($thread['replies'] + 2) / $ppp);
	require_once $discuz_root.'./include/cache.php';
	updatecache('homeforums');
	?><meta http-equiv="refresh" content="0;url='viewthread.php?tid=<?echo($tid)?>&pid=<?echo($pid)?>&page=<?echo($topicpages)?>#pid<?echo($pid)?>'"><?
}

?>