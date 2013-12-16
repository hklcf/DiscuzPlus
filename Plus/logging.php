<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';

if($action == 'logout') {

	clearcookies();
	$discuz_user = $discuz_pw = $discuz_secques ='';
	$secques = quescrypt($questionid, $answer);
	$status = 'Guest';
	$groupid = 1;
	$styleid = $_DCACHE['settings']['styleid'];

	showmessage('logout_succeed', $referer ? $referer : 'index.php');

}elseif($action=='relogin'){

	$cookietime=intval($HTTP_COOKIE_VARS['_cookietime']);
	setcookie('invisible', $invi, $cookietime, $cookiepath, $cookiedomain);
	if($invi!=0){$invi=1;}
	$db->query("UPDATE $table_sessions SET invisible=$invi WHERE username='$discuz_user'");
	$invisible=$invi;
	if($invi==0){
		showmessage('您已經成功現身!', $referer ? $referer : 'index.php');
	} else{
		showmessage('您已經成功隱身!', $referer ? $referer : 'index.php');
	}

} elseif($action == 'login') {

	if(!$loginsubmit) {

		$discuz_action = 6;
		$styleselect = '';
			if($_DCACHE['themelists']) {
		foreach($_DCACHE['themelists'] as $styleinfo) {
			$styleselect .= "<option value=\"$styleinfo[styleid]\">$styleinfo[name]</option>\n";
		}
		unset($_DCACHE['themelists']);
		}

		switch($HTTP_COOKIE_VARS['_cookietime']) {
			case '31536000': $year_checked = 'checked'; break;
			case '86400': $day_checked = 'checked'; break;
			case '3600': $hour_checked = 'checked'; break;
			case '0': $task_checked = 'checked'; break;
			default: $month_checked = "checked";
		}

		include template('login');

	} else {

		$discuz_user = $discuz_pw = $discuz_secques ='';
		$secques = quescrypt($questionid, $answer);
		if( !$referer || stristr($referer, 'logging.php')) $referer='index.php';	// fix:避免重複登陸信息
		$errorlog = "$username\t".substr($password, 0, 20);
		for($i = 30; $i < strlen($password); $i++) {
			$errorlog .= "*";
		}
		$errorlog .= substr($password, -1)."\t$onlineip\t$timestamp\n";
		$password = md5($password);
		$query = $db->query("SELECT m.username as discuz_user, m.password as discuz_pw, m.status, m.styleid AS styleidmem, m.lastvisit, u.groupid, u.isadmin, u.specifiedusers LIKE '%\t".addcslashes($username, '%_')."\t%' AS specifieduser
					FROM $table_members m LEFT JOIN $table_usergroups u ON u.specifiedusers LIKE '%\t".addcslashes($username, '%_')."\t%' OR (u.status=m.status AND ((u.creditshigher='0' AND u.creditslower='0' AND u.specifiedusers='') OR (m.credit>=u.creditshigher AND m.credit<u.creditslower)))
					WHERE username='$username' AND password='$password' AND secques='$secques' ORDER BY specifieduser DESC");
		@extract($db->fetch_array($query));
		$discuz_user = addslashes($discuz_user);
		$discuz_userss = stripslashes($discuz_user);

		if($bbclosed && !$isadmin) {
			showmessage($closedreason ? $closedreason : 'Sorry, this forum is temporarily closed.');
		}

		if(!$discuz_user) {
			@$fp = fopen($discuz_root.'./forumdata/illegallog.php', 'a');
			@flock($fp, 3);
			@fwrite($fp, $errorlog);
			@fclose($fp);
			showmessage('login_invalid', 'index.php');
		} else {
			$styleid = empty($HTTP_POST_VARS['styleid']) ? ($styleidmem ? $styleidmem :
					$_DCACHE['settings']['styleid']) : $HTTP_POST_VARS['styleid'];
			
			$_cookietime = isset($HTTP_POST_VARS['cookietime']) ? $HTTP_POST_VARS['cookietime'] :
					($HTTP_COOKIE_VARS['_cookietime'] ? $HTTP_COOKIE_VARS['_cookietime'] : 0);
			$cookietime = empty($_cookietime) ? 0 : $timestamp + $_cookietime;
			setcookie('_cookietime', $_cookietime, $timestamp + 31536000, $cookiepath, $cookiedomain);
			setcookie('_discuz_user', $discuz_user, $cookietime, $cookiepath, $cookiedomain);
			setcookie('_discuz_pw', $discuz_pw, $cookietime, $cookiepath, $cookiedomain);
			setcookie('lastvisit', $lastvisit, $timestamp + 3600, $cookiepath, $cookiedomain);
			setcookie('_discuz_secques', $secques, $cookietime, $cookiepath, $cookiedomain);
			$invisible = intval($HTTP_POST_VARS['invisible']);
		if ($invisible!=1) $invisible=0;
			setcookie('invisible', $invisible, $cookietime, $cookiepath, $cookiedomain);

			//fix: 避免相同用戶名出現在論壇在線名單
			$db->query("DELETE FROM $table_sessions WHERE username='$discuz_user'");
			if(strstr(strtolower($referer), 'logging.php')) $referer='index.php';	// by cnteacher
			$sessionupdated=0;

			showmessage('login_succeed', $referer);
		}

	}

}

?>