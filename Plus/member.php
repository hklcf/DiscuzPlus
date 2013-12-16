<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';

if($action == 'online') {

	$discuz_action = 31;
	@include language('actions');

	if(!$page) {
		$page = 1;
	}
	$start = ($page - 1) * $memberperpage;

	$query = $db->query("SELECT COUNT(*) FROM $table_sessions");
	$num = $db->result($query, 0);
	$multipage = multi($num, $memberperpage, $page, "member.php?action=online");

	$onlinelist = array();
	$query = $db->query("SELECT s.*, f.name, t.subject FROM $table_sessions s
				LEFT JOIN $table_forums f ON s.fid=f.fid
				LEFT JOIN $table_threads t ON s.tid=t.tid
				ORDER BY lastactivity DESC LIMIT $start, $memberperpage");
	while($online = $db->fetch_array($query)){
		$online['lastactivity'] = gmdate($timeformat, $online['lastactivity'] + ($timeoffset * 3600));
if($discuz_user!=$online['username'] && !$isadmin && $online['invisible']==1) $online['username']='隱身會員';
		$online['usernameenc'] = $online['username'] ? rawurlencode($online['username']) : NULL;
		$online['action'] = $actioncode[$online['action']];
		$online['subject'] = $online[subject] ? wordscut($online['subject'], 35) : NULL;
                if($isadmin) {
                require_once $discuz_root.'./include/misc.php';
                        $online['iplocation'] = convertip($online['ip']);
                }
		$onlinelist[] = $online;
	}

	include template('whosonline');

} elseif($action == 'list') {

	$discuz_action = 41;
	if(!$memliststatus) {
		showmessage('member_list_disable');
	}

	if(!$order || ($order != 'regdate' && $order != 'username' && $order != 'gender' && $order != 'credit')) {
		$order = 'regdate';
	}

	if($page) {
		$start_limit = ($page - 1) * $memberperpage;
	} else {
		$start_limit = 0;
		$page = 1;
	}

	$sql = '';
	if($admins == 'yes') {
		$sql .= " WHERE status='Admin' OR status='SuperMod' OR status='Moderator'";
	} elseif($vip == 'yes') {
		$sql .= " WHERE status='vip'";
	} else {
		$sql .= $srchmem ? " WHERE BINARY username LIKE '%$srchmem%' OR username='$srchmem'" : NULL;
	}

	$query = $db->query("SELECT COUNT(*) FROM $table_members $sql");
	if ($desc !='asc' && $desc !='desc' ) $desc=''; //fix: 非法字符注入
	$multipage = multi($db->result($query, 0), $memberperpage, $page, "member.php?action=list&srchmem=".rawurlencode($srchmem)."&order=$order&admins=$admins".($desc ? "&desc=$desc" : NULL));

	$memberlist = array();
	$query = $db->query("SELECT username, gender, email, site, location, regdate, lastvisit, postnum, credit, showemail FROM $table_members $sql ORDER BY ".($order == 'username' ? 'BINARY username' : $order)." $desc LIMIT $start_limit, $memberperpage");
	while ($member = $db->fetch_array($query)) {
		$member['usernameenc'] = rawurlencode($member['username']);
		$member['regdate'] = gmdate($dateformat, $member['regdate'] + $timeoffset * 3600 );
		$member['site'] = str_replace('http://', '', $member['site']);
		$member['lastvisit'] = gmdate("$dateformat $timeformat", $member['lastvisit'] + ($timeoffset * 3600));
		$memberlist[] = $member;
	}

	include template('memberlist');

} elseif($action == 'markread') {

	if($discuz_user) {
		$db->query("UPDATE $table_members SET lastvisit='$timestamp' WHERE username='$discuz_user'");
	}
	setcookie('lastvisit', $timestamp, $timestamp + 86400 * 365, $cookiepath, $cookiedomain);
	showmessage('mark_read_succeed', 'index.php');

} elseif($action == 'lostpasswd') {

	$discuz_action = 141;//fix:用戶動作無指示
	if(!$lostpwsubmit) {
		include template('lostpasswd');
	} else {
		$secques = quescrypt($questionid, $answer);
		$query = $db->query("SELECT username, email FROM $table_members WHERE username='$username' AND secques='$secques' AND email='$email'");
		if(!$member = $db->fetch_array($query)) {
			showmessage('getpasswd_account_notmatch');
		}

		$newpass = random(20);
		$rcvtime = $timestamp;
		$member['username'] = addslashes($member['username']);
		$db->query("UPDATE $table_members SET pwdrecover='$newpass', pwdrcvtime='$rcvtime' WHERE username='$member[username]' AND email='$member[email]'");

		sendmail($member['email'], 'get_passwd_subject', 'get_passwd_content');
		showmessage('getpasswd_send_succeed');
	}

} elseif($action == 'getpasswd') {

	$discuz_action = 141;
	if(!$id) {
		showmessage('undefined_action');
	}
	$query = $db->query("SELECT username FROM $table_members WHERE pwdrcvtime>$timestamp-864000 AND pwdrecover='$id'");
	$username = $db->result($query, 0);
	if(!$username) {
		$query = $db->query("UPDATE $table_members SET pwdrecover='', pwdrcvtime='' WHERE pwdrcvtime<$timestamp-864000");
		showmessage('getpasswd_id_illegal');
	}
	if(!$getpwsubmit || $newpasswd1 != $newpasswd2) {
		include template('getpasswd');
	} else {
		$password = md5($newpasswd1);
		$query = $db->query("UPDATE $table_members SET password='$password', pwdrecover='', pwdrcvtime='' WHERE pwdrecover='$id'");
		showmessage('getpasswd_succeed');
	}	

} else {

	showmessage('undefined_action');

}

?>