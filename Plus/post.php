<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
require $discuz_root.'./include/discuzcode.php';
require $discuz_root.'./include/post.php';

$ismoderator = modcheck($discuz_user);

if($action) {
	if($tid && $fid) {
		$query = $db->query("SELECT * FROM $table_threads WHERE tid='$tid'");
		$thread = $db->fetch_array($query);
		$fid = $thread['fid'];
		$navigation = "&raquo; <a href=\"viewthread.php?tid=$tid\">$thread[subject]</a>";
		$navtitle = "- $thread[subject]";
	}

	$navigation = "&raquo; <a href=\"forumdisplay.php?fid=$fid\">$forum[name]</a> $navigation";
	$navtitle = " - $forum[name] $navtitle";
	if($forum['type'] == "sub") {
		$query = $db->query("SELECT name, fid FROM $table_forums WHERE fid='$forum[fup]'");
		$fup = $db->fetch_array($query);
		$navigation = "&raquo; <a href=\"forumdisplay.php?fid=$fup[fid]\">$fup[name]</a> $navigation";
		$navtitle = " - $fup[name] $navtitle";
	}

	$fupadd = $fup ? "OR (fid='$fup[fid]' && type<>'group')" : NULL;

	if(!$forum['viewperm'] && !$allowview) {
		showmessage('group_nopermission');
	} elseif($forum['viewperm'] && !strstr($forum['viewperm'], "\t$groupid\t")) {
		showmessage('forum_nopermission');
	} elseif($thread['creditsrequire'] && $thread['creditsrequire'] > $credit && !$ismoderator && !$thread['author'] && !$issupermod) {
		showmessage('thread_nopermission');
	}

	if(!$discuz_user && preg_match("/\[hide=?\d*\].+?\[\/hide\]/is", $message)) {
		showmessage('post_hide_nopermission');
	}

	if($previewpost || (!$previewpost && !$topicsubmit && !$replysubmit && !$editsubmit)) {

		$enctype = (!$forum['postattachperm'] && $allowpostattach) || ($forum['postattachperm'] && strstr($forum['postattachperm'], "\t$groupid\t")) ? 'enctype="multipart/form-data"' : NULL;

		if($smileyinsert && is_array($_DCACHE['smilies'])) {
			$smileyinsert = 1;
			$smcols = $smcols ? $smcols : 3;
			$smilies .= '<tr>';
			foreach($_DCACHE['smilies'] as $key => $smiley) {
				$smilies .= '<td align="center" valign="top"><img src="'.SMDIR.'/'.$smiley['url'].'" border="0" onmouseover="this.style.cursor=\'hand\';" onclick="AddText(\''.$smiley['code'].'\');"></td>'."\n";
				$smilies .= !(++$key % $smcols) ? '</tr><tr>' : NULL;
			}
		} else {
			$smileyinsert = 0;
		}

		if(is_array($_DCACHE['picons'])) {
		$icons .= " <input type=\"radio\" name=\"posticon\" value=\"0\" checked> µL "; 
			foreach($_DCACHE['picons'] as $key => $picon) {
				$icons .= ' <input type="radio" name="posticon" value="'.$picon['url'].'"><img src="'.SMDIR.'/'.$picon['url'].'">';
				$icons .= !(++$key % 9) ? '<br>' : NULL;
			}
		}

		$maxattachsize_kb = $maxattachsize / 1000;
		$allowimgcode = $forum['allowimgcode'] ? 'On' : 'Off';
		$allowhtml = $forum['allowhtml'] ? 'On' : 'Off';
		$allowsmilies = $forum['allowsmilies'] ? 'On' : 'Off';
		$allowbbcode = $forum['allowbbcode'] ? 'On' : 'Off';

		if($discuz_user && $signature && !$usesigcheck) {
			$usesigcheck = 'checked';
		}

		if(!$previewpost) {
			$viewperm = $subject = $message = '';
		}

	} else {

		if(!$discuz_user && !((!$forum['postperm'] && $allowpost) || ($forum['postperm'] && strstr($forum['postperm'], "\t$groupid\t")))) {
			$password = md5($password);
			$query = $db->query("SELECT m.username as discuz_user, m.password as discuz_pw, m.styleid, m.newpm, u.*, u.specifiedusers LIKE '%\t".addcslashes($discuz_user, '%_')."\t%' AS specifieduser
				FROM $table_members m LEFT JOIN $table_usergroups u ON u.specifiedusers LIKE '%\t".addcslashes($discuz_user, '%_')."\t%' OR (u.status=m.status
				AND ((u.creditshigher='0' AND u.creditslower='0' AND u.specifiedusers='') OR (m.credit>=u.creditshigher AND m.credit<u.creditslower)))
				WHERE username='$username' AND password='$password' ORDER BY specifieduser DESC");
			@extract($db->fetch_array($query));
			$discuz_userss = $discuz_user;
			$username = $discuz_user = addslashes($discuz_user);

			if(!$discuz_user) {
				showmessage('login_invalid', 'index.php');
			}

			setcookie('_discuz_user', $discuz_userss, $timestamp + 2592000, $cookiepath, $cookiedomain);
			setcookie('_discuz_pw', $discuz_pw, $timestamp + 2592000, $cookiepath, $cookiedomain);
$db->query("DELETE FROM $table_sessions WHERE username='$discuz_user'");
		} elseif($discuz_user) {
			$username = $discuz_user;
			$password = $discuz_pw;
		} else {
			$username = 'Guest';
		}

		if(!$forum['postperm'] && !$allowpost) {
			showmessage('group_nopermission');
		} elseif($forum['postperm'] && !strstr($forum['postperm'], "\t$groupid\t")) {
			showmessage('post_forum_nopermission');
		}

		if(!$parseurloff) {
			$message = parseurl($message);
		}

		$subject = trim(censor($subject));
		$message = trim(censor($message));
	}

	if($forum['password'] != $HTTP_COOKIE_VARS["fidpw$fid"] && $forum['password'] != $_DSESSION["fidpw$fid"] && $forum['password']) {
		header("Location: {$boardurl}forumdisplay.php?fid=$fid&sid=$sid");
		discuz_exit();
	}

	if($previewpost) {
		$currtime = gmdate("$dateformat $timeformat", $timestamp + $timeoffset * 3600);
		$author = $discuz_user ? $discuz_userss : stripslashes($username);
		$subject = stripslashes($subject);
		$message = stripslashes($message);
		$subject_preview = $subject;
		$message_preview = postify($message, $smileyoff, $bbcodeoff, $forum['allowsmilies'], $forum['allowhtml'], $forum['allowbbcode'], $forum['allowimgcode']);

		$urloffcheck = $parseurloff ? 'checked' : NULL;
		$message = dhtmlspecialchars($message);  //fix_beta:¢ø¢ú¢í¢þ¢ñ¢í£@
		$usesigcheck = $usesig ? 'checked' : NULL;
		$smileoffcheck = $smileyoff ? 'checked' : NULL;
		$codeoffcheck = $bbcodeoff ? 'checked' : NULL;

		$topicsubmit = $replysubmit = $editsubmit = '';
	}

	$allowpostattach = (!$forum['postattachperm'] && $allowpostattach) || ($forum['postattachperm'] && strstr($forum['postattachperm'], "\t$groupid\t")) ? 1 : 0;

	if($action == 'newthread') {
		require $discuz_root.'./include/newthread.php';
	} elseif($action == 'reply') {
		require $discuz_root.'./include/newreply.php';
	} elseif($action == 'edit') {
		require $discuz_root.'./include/editpost.php';
	}

} else {

	showmessage('undefined_action');

}

?>