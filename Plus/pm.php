<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
require $discuz_root.'./include/discuzcode.php';

$discuz_action = 201;
$navtitle = '';

if(!$discuz_user) {
	showmessage('not_loggedin');
}

$query = $db->query("SELECT COUNT(*) FROM $table_pm WHERE (msgfrom='$discuz_user' AND folder='outbox') OR (msgto='$discuz_user' AND folder='inbox')");
$pm_total = $db->result($query, 0);
@$storage_percent = round((100 * $pm_total / $maxpmnum) + 1).'%';

if(empty($action)) {

	if(empty($page)) {
		$start_limit = 0;
		$page = 1;
	} else {
		$page=intval($page); //fix: pages bug
		$start_limit = ($page - 1) * $tpp;
	}

	if(empty($folder) || $folder == 'inbox') {
		$folder = 'inbox';
		$query = $db->query("SELECT COUNT(*) FROM $table_pm WHERE msgto='$discuz_user' AND folder='inbox'");
		$pmnum = $db->result($query, 0);
		$query = $db->query("SELECT * FROM $table_pm WHERE msgto='$discuz_user' AND folder='inbox' ORDER BY dateline DESC LIMIT $start_limit, $tpp");
	} elseif($folder == 'outbox') {
		$query = $db->query("SELECT COUNT(*) FROM $table_pm WHERE msgfrom='$discuz_user' AND folder='outbox'");
		$pmnum = $db->result($query, 0);
		$query = $db->query("SELECT * FROM $table_pm WHERE msgfrom='$discuz_user' AND folder='outbox' ORDER BY dateline DESC LIMIT $start_limit, $tpp");
	} elseif($folder == 'track') {
		$query = $db->query("SELECT COUNT(*) FROM $table_pm WHERE msgfrom='$discuz_user' AND folder='inbox'");
		$pmnum = $db->result($query, 0);
		$query = $db->query("SELECT * FROM $table_pm WHERE msgfrom='$discuz_user' AND folder='inbox' ORDER BY dateline DESC LIMIT $start_limit, $tpp");
	}else{
		exit;
	}
	$multipage = multi($pmnum, $tpp, $page, "pm.php?folder=$folder");

	$pmlist = array();
	while($pm = $db->fetch_array($query)) {
		$pm['msgfromenc'] = rawurlencode($pm['msgfrom']);
		$pm['msgtoenc'] = rawurlencode($pm['msgto']);
		$pm['dateline'] = gmdate("$dateformat $timeformat", $pm['dateline'] + $timeoffset * 3600);
		$pm['subject'] = $pm['new'] ? "<b>$pm[subject]</b>" : $pm['subject'];
		$pmlist[] = $pm;
	}

} elseif($action == 'view') {

	$codecount = 0;

	//fix: 如果信箱滿了，則閱讀信件時候提醒
	if($pm_total > $maxpmnum  && !$ignore) {
		showmessage('pm_box_isfull', 'pm.php?pmid='.intval($pmid).'&action=view&ignore=1');
	}
	//fix:end

	$query = $db->query("SELECT * FROM $table_pm WHERE pmid='$pmid' AND (msgto='$discuz_user' OR msgfrom='$discuz_user')");
	if(!$pm = $db->fetch_array($query)) {
		showmessage('pm_nonexistence');
	}

	if($pm['new'] && !($pm['msgfrom'] == $discuz_user && $pm['msgto'] != $discuz_user && $pm['folder'] == 'inbox')) {
		$db->query("UPDATE $table_pm SET new='0' WHERE pmid='$pmid'");
	}

	$folder = $folder == 'track' ? $folder : $pm['folder'];
	$pm['msgfromenc'] = rawurlencode($pm['msgfrom']);
	$pm['msgtoenc'] = rawurlencode($pm['msgto']);

	$pm['dateline'] = gmdate("$dateformat $timeformat", $pm['dateline'] + $timeoffset * 3600);
	$pm['message'] = postify($pm['message'], 0, 0, 1, 0, 1, 1);

} elseif($action == 'send') {

	//plus: 如果用戶信箱滿了，禁止發送信息
	if($pm_total > $maxpmnum ) {
		showmessage('pm_box_isfull', 'pm.php'); 
	}
	//plus end

	if(!$pmsubmit || !$HTTP_POST_VARS['pmsubmit']) { //短消息漏洞問題

		$buddylist = array();
		$message=$subject=''; //fix: don't send message use Get.
		$query = $db->query("SELECT buddyname FROM $table_buddys WHERE username='$discuz_user'");
		while($buddy = $db->fetch_array($query)) {
			$buddylist[] = $buddy;
		}

		$subject = $message = '';

		if($pmid) {
			$query = $db->query("SELECT * FROM $table_pm WHERE pmid='$pmid' AND msgto='$discuz_user'");
			$pm = $db->fetch_array($query);

			$pm['subject'] = $message = str_replace('回覆: ', '', $pm[subject]);
			$pm['subject'] = $message = str_replace('轉發: ', '', $pm[subject]);
			$username = $pm['msgfrom'];

			if($do == 'reply') {
				$subject = "回覆: $pm[subject]";
				$pm[message] = dhtmlspecialchars(trim(preg_replace("/(\[quote])(.*)(\[\/quote])/siU", '', $pm[message])));
				$message = "[quote]$pm[message][/quote]\n";
				$touser = $pm['msgfrom'];
			}
			if($do == 'forward') {
				$subject = "轉發: $pm[subject]";
				$message = "[quote]$pm[message][/quote]\n";
				$touser = $pm['msgfrom'];
			}
		}

		//fix: 非法用戶名提交
		if ($username){
			$query = $db->query("SELECT username FROM $table_members WHERE username='$username'");
			if(!$db->fetch_array($query)) {
				showmessage('pm_send_nonexistence');
            }else{
				$touser = stripslashes($username);
			}
        }else{
           $touser='';
        }
		//fix:end

	} else {

		//fix, plus: pm flood check start 
		if (!$isadmin && !$issupermod) { 
		    $pk_check_time=$timestamp-15; 
		    $pk_pmcount = $db->result($db->query("Select count(*) from $table_pm where dateline>'$pk_check_time' and msgfrom='$discuz_user'"), 0); 
		    if ($pk_pmcount) showmessage('兩次發送短消息間隔少於15秒！'); 
		    unset($pk_check_time, $pk_pmcount); 
		} 
		//pm flood check end

		if(empty($msgto)) {
			$msgto = array_merge($msgtobuddys, NULL);
		} else {
			$query = $db->query("SELECT username FROM $table_members WHERE username='$msgto'");
			if(!$member = $db->fetch_array($query)) {
				showmessage('pm_send_nonexistence');
			}
			$msgto = array_merge($msgtobuddys, $member['username']);
		}

		$msgto_count = count($msgto);
		if(!$msgto_count || !trim($subject)) {
			showmessage('pm_send_invalid');
		}
		$maxpmsend = ceil($maxpmnum / 10);
		if($msgto_count > $maxpmsend) {
			showmessage('pm_send_toomany');
		}

		$msgto = daddslashes($msgto, 1);

		$users = $comma = '';
		foreach($msgto as $user) {
			$users .= $comma.'\''.trim($user).'\'';
			$comma = ', ';
		}

		$ignorenum = 0;
		$query = $db->query("SELECT username, ignorepm FROM $table_members WHERE username IN ($users)");
		if($db->num_rows($query) != $msgto_count) {
			showmessage('undefined_action');
		}
		while($member = $db->fetch_array($query)) {
			if(preg_match("/(,|^)\s*$discuz_user\s*(,|$)/i", $member['ignorepm'])) {
				showmessage('pm_send_ignore');
			}
		}
		
		$subject = dhtmlspecialchars(trim(censor($subject)));
		$message = trim(censor(parseurl($message)));

		foreach($msgto as $user) {
			$db->query("INSERT INTO $table_pm (msgto, msgfrom, folder, new, subject, dateline, message)
				VALUES('$user', '$discuz_user', 'inbox', '1', '$subject', '$timestamp', '$message')");
		}
		$db->query("UPDATE $table_members SET newpm='1' WHERE username IN ($users)");

		if($saveoutbox) {
			$msgto = $msgto_count > 1 ? 'myBuddys' : $msgto[0];
			$db->query("INSERT INTO $table_pm (pmid, msgto, msgfrom, folder, new, subject, dateline, message)
				VALUES('', '$msgto', '$discuz_user', 'outbox', '1', '$subject', '$timestamp', '".
				($msgto_count > 1 ? 'This message was deliverd to '.str_replace('\'', '', $users)."\n\n" : NULL)."$message')");
		}
		showmessage('pm_send_succeed', 'pm.php');
	}

} elseif($action == 'delete') {

	$msg_field = $folder == 'inbox' ? 'msgto' : 'msgfrom';
	if(!$pmid) {
		if(is_array($delete)) {
			$pmids = $comma = '';
			foreach($delete as $pmid) {
				$pmids .= "$comma'$pmid'";
				$comma = ',';
			}
			$db->query("DELETE FROM $table_pm WHERE $msg_field='$discuz_user' AND pmid IN ($pmids)");
		}
	} else {
		$db->query("DELETE FROM $table_pm WHERE ".$msg_field."='$discuz_user' AND pmid='$pmid'");
	}

	showmessage('pm_delete_succeed', "pm.php?folder=$folder");

} elseif($action == 'download' && !empty($pmid)) {

	$query = $db->query("SELECT * FROM $table_pm WHERE pmid='$pmid' AND (msgto='$discuz_user' OR msgfrom='$discuz_user')");
	if(!$pm = $db->fetch_array($query)) {
		showmessage('pm_nonexistence');
	}
	$pm['dateline'] = gmdate("$dateformat $timeformat", $pm['dateline'] + $timeoffset * 3600);

	$export = "Discuz! Private Message Export\n\n".
		"Date:\t\t$pm[dateline]\n".
		"From:\t\t$pm[msgfrom]\n".
		"To:\t\t$pm[msgto]\n".
		"Subject:\t$pm[subject]\n\n".
		"$pm[message]\n\n\n".
		"Welcome to $bbname ($boardurl)";

	ob_end_clean();
	header('Content-Encoding: none');
	header('Content-Type: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
	header('Content-Disposition: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename="pm_'.$discuz_user.'_'.$pmid.'.txt"');
	header('Content-Length: '.strlen($export));
	header('Pragma: no-cache');
	header('Expires: 0');

	echo $export;
	discuz_exit();

} elseif($action == 'ignore') {

	if(!$ignoresubmit) {
		$query = $db->query("SELECT ignorepm FROM $table_members WHERE username='$discuz_user'");
		$ignorepm = $db->result($query, 0);
	} else {
		$db->query("UPDATE $table_members SET ignorepm='$ignorelist' WHERE username='$discuz_user'");
		showmessage('pm_ignore_succeed', 'pm.php');
	}

}

include template('pm');

?>