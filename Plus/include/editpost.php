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

$discuz_action = 13;

$query = $db->query("SELECT pid FROM $table_posts WHERE tid='$tid' ORDER BY dateline LIMIT 0, 1");
$isfirstpost = $db->result($query, 0) == $pid ? 1 : 0;

$query = $db->query("SELECT author, dateline FROM $table_posts WHERE pid='$pid' AND tid='$tid' AND fid='$fid'");
$orig = $db->fetch_array($query);
$orig[author] = addslashes($orig['author']);

if(!$ismoderator && $discuz_user != $orig['author']) {
	showmessage('post_edit_nopermission');
}

if(!$editsubmit) {

	$query = $db->query("SELECT * FROM $table_posts WHERE pid='$pid' AND tid='$tid' AND fid='$fid'");
	$postinfo = $db->fetch_array($query);

	$usesigcheck = $postinfo['usesig'] ? 'checked="checked"' : NULL;
	$urloffcheck = $postinfo['parseurloff'] ? 'checked="checked"' : NULL;
	$smileyoffcheck = $postinfo['smileyoff'] ? 'checked="checked"' : NULL;
	$codeoffcheck = $postinfo['bbcodeoff'] ? 'checked="checked"' : NULL;

	if($issupermod && $thread['pollopts']) {
		$polloptions = unserialize($thread['pollopts']);
		for($i = 0; $i < count($polloptions['options']); $i++) {
			$polloptions['options'][$i][0] = htmlspecialchars(stripslashes($polloptions['options'][$i][0]))."\n";
		}
	} else {
		$polloptions = '';
	}
	if($allowpostattach) {
		if($postinfo['aid']) {
			require $discuz_root.'./include/attachment.php';
			$query = $db->query("SELECT * FROM $table_attachments WHERE aid='$postinfo[aid]'");
			$postattach = $db->fetch_array($query);
			$attachsize = sizecount($postattach[filesize]);
			$attachicon = attachicon(substr(strrchr($postattach[attachment], "."), 1)."\t".$postattach[filetype]);
		}
	}

	$postinfo['subject'] = str_replace('"', "&quot;", $postinfo['subject']);
	$postinfo['message'] = dhtmlspecialchars($postinfo['message']);
	$postinfo['message'] = preg_replace("/\n{2}\[ Last edited by .+? on .+? at .+? \]$/s", '', $postinfo['message']);
	if($previewpost) {
		$postinfo['message'] = $message;
	}

	include template('post_editpost');

} else {

	if(!$delete) {

		if(strlen(htmlspecialchars($subject)) > 100) {
			showmessage('post_subject_toolang');
		}

		if(!$issupermod && $maxpostsize && strlen($message) > $maxpostsize) {
			showmessage('post_message_toolang');
		}

		$viewpermadd = ($allowsetviewperm && $isfirstpost) ? "creditsrequire='$viewperm'" : NULL;
		$attachpermadd = $allowsetattachperm ? "creditsrequire='$origattachperm'" : NULL;

		$subject = dhtmlspecialchars($subject);

		if($isfirstpost) {
			if(!$subject || !$message) {
				showmessage('post_sm_isnull');
			}

			$polloptsadd = '';
			if($issupermod) {
				if(trim($polloptions)) {
					$pollarray = unserialize($thread['pollopts']);
					$pollarray['max'] = 0;
					foreach($polloptions as $key => $option) {
						if(trim($option)) {
							$pollarray['options'][$key][0] = $option;
							if($pollarray['options'][$key][1] > $pollarray['max']) {
								$pollarray['max'] = $pollarray['options'][$key][1];
							
							}
						} else {
							$pollarray['total'] -= $pollarray['options'][$key][1];
							unset($pollarray['options'][$key]);
						}
					}
					$pollarray['multiple'] = $multiplepoll;
					$polloptsadd = ", pollopts='".addslashes(serialize($pollarray))."'";
				}
			}
			$db->unbuffered_query("UPDATE $table_threads SET icon='$posticon', subject='$subject' $polloptsadd WHERE tid='$tid'");
		} else {
			if(!$subject && !$message) {
				showmessage('post_sm_isnull');
			}
		}

		if ($issell){
			$message="[sell=".$price."]".$message."[/sell]";
		}

		if ($editedby && ($timestamp - $orig['dateline']) > 60 && !$isadmin){
			$editdate = gmdate($_DCACHE['settings']['dateformat'], $timestamp + $timeoffset * 3600);
			$edittime = gmdate($_DCACHE['settings']['timeformat'], $timestamp + $timeoffset * 3600);
			$message .= "\n\n[ Last edited by $discuz_user on $editdate at $edittime ]";
		}

		if(($attachedit == 'delete' || ($attachedit == 'new' && attach_upload())) && ((!$forum[postattachperm] && $allowpostattach) || ($forum[postattachperm] && strstr($forum[postattachperm], "\t$groupid\t")))) {
			$query = $db->query("SELECT attachment FROM $table_attachments WHERE pid='$pid'");
			$thread_attachment = $db->result($query, 0);
			@unlink($discuz_root.'./'.$attachdir.'/'.$thread_attachment);
			$db->unbuffered_query("DELETE FROM $table_attachments WHERE pid='$pid'");

			if($attachedit == 'new') {
				$attachperm = $allowsetattachperm ? $attachperm : 0;
				$db->query("INSERT INTO $table_attachments (tid, pid, creditsrequire, filename, filetype, filesize, attachment, downloads)
					VALUES ('$tid', '$pid', '$attachperm', '$attach_name', '$attach_type', '$attach_size', '$attach_fname', '0')");
				$attach_type = substr(strrchr($attach_name, '.'), 1)."\t".$attach_type;
				$aid = $db->insert_id();
			} else {
				$query = $db->query("SELECT attachment, filetype FROM $table_attachments WHERE tid='$tid' ORDER BY pid DESC LIMIT 0, 1");
				if($thread_attachment = $db->fetch_array($query)) {
					$attach_type = substr(strrchr($thread_attachment['attachment'], '.'), 1)."\t".$thread_attachment[filetype];
				} else {
					$attach_type = '';
				}
				$aid = 0;
			}
			if($viewpermadd) {
				$viewpermadd = ", $viewpermadd";
			}
			$db->query("UPDATE $table_posts SET aid='$aid', message='$message', usesig='$usesig', bbcodeoff='$bbcodeoff', parseurloff='$parseurloff',
				smileyoff='$smileyoff', icon='$posticon', subject='$subject' WHERE pid='$pid'");
			$db->unbuffered_query("UPDATE $table_threads SET attachment='$attach_type' $viewpermadd WHERE tid='$tid'");
		} else {
			$db->query("UPDATE $table_posts SET message='$message', usesig='$usesig', bbcodeoff='$bbcodeoff', parseurloff='$parseurloff',
				smileyoff='$smileyoff', icon='$posticon', subject='$subject' WHERE pid='$pid'");
			if($attachpermadd) {
				$db->unbuffered_query("UPDATE $table_attachments SET $attachpermadd WHERE pid='$pid'");
			}
			if($viewpermadd) {
				$db->unbuffered_query("UPDATE $table_threads SET $viewpermadd WHERE tid='$tid'");
			}
		}

		$modaction = 'editpost';

	} elseif($delete && !$isfirstpost) {

		updatemember('-', $orig['author']);

		$query = $db->query("SELECT pid, filetype, attachment FROM $table_attachments WHERE tid='$tid'");
		$attach_type = '';
		while($thread_attachment = $db->fetch_array($query)) {
			if($thread_attachment['filetype']) {
				$attach_type = substr(strrchr($thread_attachment['attachment'], '.'), 1)."\t".$thread_attachment['filetype'];
			}
			if($thread_attachment[pid] == $pid) {
				@unlink($discuz_root.'./'.$attachdir.'/'.$thread_attachment['attachment']);
			}
		}
		$db->unbuffered_query("UPDATE $table_threads SET attachment='$attach_type' WHERE tid='$tid'");
		$db->unbuffered_query("DELETE FROM $table_attachments WHERE pid='$pid'");
		$db->query("DELETE FROM $table_posts WHERE pid='$pid'");
		updatethreadcount($tid);
		updateforumcount($fid);

		$modaction = 'delposts';

	} elseif($delete && $isfirstpost) {
		$query = $db->query("SELECT author FROM $table_posts WHERE tid='$tid'");
		while($result = $db->fetch_array($query)) {
			updatemember('-', addslashes($result[author]));
		}
		$db->unbuffered_query("DELETE FROM $table_threads WHERE tid='$tid' OR closed='moved|$tid'");

		$query = $db->query("SELECT attachment FROM $table_attachments WHERE tid='$tid'");
		while($thread_attachment = $db->fetch_array($query)) {
			@unlink($discuz_root.'./'.$attachdir.'/'.$thread_attachment['attachment']);
		}

		$db->unbuffered_query("DELETE FROM $table_attachments WHERE tid='$tid'");
		$db->query("DELETE FROM $table_posts WHERE tid='$tid'");
		updateforumcount($fid);

		$modaction = 'delete';

	}

	if($discuz_user != $orig['author']) {
		@$fp = fopen($discuz_root.'./forumdata/modslog.php', 'a');
		@flock($fp, 3);
		@fwrite($fp, "$discuz_user\t$status\t$onlineip\t$timestamp\t$fid\t$forum[name]\t$tid\t$thread[subject]\t$modaction\n");
		@fclose($fp);
	}

	if($delete && $isfirstpost) {
		showmessage('post_edit_delete_succeed', "forumdisplay.php?fid=$fid");
	} else {
		showmessage('post_edit_succeed', "viewthread.php?tid=$tid&page=$page#pid$pid");
	}

}

?>