<?php

/*
	Version: 1.1.4(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/12/16
*/

require './include/common.php';

$discuz_action = 7;

if(!$discuz_user || !$discuz_pw) {
	showmessage('not_loggedin');
}

if(!isset($action)) {

	$query = $db->query("SELECT avatar FROM $table_members WHERE username='$discuz_user'");
	$avatar = $db->result($query, 0);

	$buddyonline = $buddyoffline = array();
	$query = $db->query("SELECT b.*, s.username AS onlineuser, s.invisible FROM $table_buddys b LEFT JOIN $table_sessions s ON s.username=b.buddyname WHERE b.username='$discuz_user'");
	while($buddy = $db->fetch_array($query)) {
		$buddyuser = array('buddy' => $buddy['buddyname'], 'buddyenc' => rawurlencode($buddy['buddyname']));
		if($buddy['onlineuser'] && ($isadmin || !$buddy['invisible'])) {
			$buddyonline[] = $buddyuser;
		} else {
			$buddyoffline[] = $buddyuser;
		}
	}

	$avatar = $avatar ? image($avatar) : "&nbsp;";

	$msgexists = 0;
	$msglist = array();
	$query = $db->query("SELECT * FROM $table_pm WHERE msgto='$discuz_user' AND folder='inbox' ORDER BY dateline DESC LIMIT 0, 5");
	while($message = $db->fetch_array($query)) {
		$msgexists = 1;
		$message['dateline'] = gmdate("$dateformat $timeformat", $message['dateline'] + $timeoffset * 3600);
		$message['subject'] = $message['new'] ? "<b>$message[subject]</b>" : $message['subject'];

		$msglist[] = $message;
	}

	$subsexists = 0;
	$subslist = array();
	$query = $db->query("SELECT t.*, f.name FROM $table_subscriptions s, $table_threads t, $table_forums f WHERE t.tid=s.tid AND f.fid=t.fid AND s.username='$discuz_user' ORDER BY t.lastpost DESC LIMIT 0, 5");
	while($subs = $db->fetch_array($query)) {
		$subsexists = 1;
		$subs['lastposterenc'] = rawurlencode($subs['lastposter']);
		$subs['lastpost'] = gmdate("$dateformat $timeformat", $subs['lastpost'] + $timeoffset * 3600);

		$subslist[] = $subs;
	}

	include template('memcp_home');

} elseif($action == 'profile') {

	if(!$editsubmit) {

		$query = $db->query("SELECT * FROM $table_members WHERE username='$discuz_user'");
		$member = $db->fetch_array($query);

		$emailchecked = $member['showemail'] ? 'checked="checked"' : NULL;
		$newschecked = $member['newsletter'] ? 'checked="checked"' : NULL;
		$tppchecked = array($member['tpp'] => 'selected="selected"');
		$pppchecked = array($member['ppp'] => 'selected="selected"');
		
		$currtime = gmdate($timeformat);

		if($member['gender'] == 1) {
			$checkmale = 'checked';
		} elseif($member[gender] == 2) {
			$checkfemale = 'checked';
		} else {
			$checkunknown = 'checked';
		}

		$styleselect = '';
		$query = $db->query("SELECT styleid, name FROM $table_styles WHERE available='1'");
		while($style = $db->fetch_array($query)) {
			$styleselect .= "<option value=\"$style[styleid]\" ".
				($style['styleid'] == $member['styleid'] ? 'selected="selected"' : NULL).
				">$style[name]</option>\n";
		}

		$bday = explode('-', $member['bday']);
		$bday[0] = $bday[0] == '0000' ? '' : $bday[0];
		$month = array(intval($bday[1]) => "selected=\"selected\"");

		for($num = 1; $num <= 31; $num++) {
			$dayselect .= "<option value=\"$num\" ".
				($bday[2] == $num ? 'selected="selected"' : NULL).
				">$num</option>\n";
		}

		$member['dateformat'] = str_replace('n', 'mm', $member['dateformat']);
		$member['dateformat'] = str_replace('j', 'dd', $member['dateformat']);
		$member['dateformat'] = str_replace('y', 'yy', $member['dateformat']);
		$member['dateformat'] = str_replace('Y', 'yyyy', $member['dateformat']);
		$member['timeformat'] == 'H:i' ? $check24 = 'checked="checked"' : $check12 = 'checked="checked"';

		$imgcodeis = $allowsigimgcode ? 'On' : 'Off';
		$bbcodeis = $allowsigbbcode ? 'On' : 'Off';

		include template('memcp_profile');

	} else {

		if($newpassword) {
			if(md5($oldpassword) != $discuz_pw) {
				showmessage('profile_passwd_wrong');
			} elseif(ereg('"', $newpassword) || ereg("'", $newpassword)) {
				showmessage('profile_passwd_illegal');
			}
			$newpassword = md5($newpassword);
			$newpasswdadd = ", password='$newpassword'";
		} else {
			$newpassword = $discuz_pw;
			$newpasswdadd = "";
		}

		$secquesnew = $questionidnew == -1 ? $discuz_secques : quescrypt($questionidnew, $answernew);
		if(($adminid == 1 || $adminid == 2 || $adminid == 3) && !$secquesnew) {
			showmessage('profile_admin_security_invalid');
		}

		if(!strstr($emailnew, '@') || $emailnew != addslashes($emailnew) || $emailnew != htmlspecialchars($emailnew)) {
			showmessage('profile_email_illegal');
		}

		if (!ereg("[0-9]", $oicqnew) && $oicqnew){
			showmessage('profile_oicq_illegal');
		}

		if (!ereg("[0-9]", $icqnew) && $icqnew){
			showmessage('profile_icq_illegal');
		}

		if($maxsigsize && strlen($signew) > $maxsigsize) {
			showmessage('profile_sig_toolang');
		}

		if($allowavatar == 2 && $avatarnew) {
			if($maxavatarsize) {
				if(strstr($avatarnew, ',')) {
					$avatarinfo = explode(',', $avatarnew);
					if(trim($avatarinfo[1]) > $maxavatarsize || trim($avatarinfo[2]) > $maxavatarsize) {
						showmessage('profile_avatar_toobig');
					}
				} elseif($image_size = @getimagesize($avatarnew)) {
					if($image_size[0] > $maxavatarsize || $image_size[1] > $maxavatarsize) {
						showmessage('profile_avatar_toobig');
					}
				}
			}
			$avatarnew = dhtmlspecialchars($avatarnew);
			$avataradd = ", avatar='$avatarnew'";
		} else {
			$avataradd = '';
		}

		$locationnew = dhtmlspecialchars($locationnew);
		$icqnew = dhtmlspecialchars($icqnew);
		$yahoonew = dhtmlspecialchars($yahoonew);
		$oicqnew = dhtmlspecialchars($oicqnew);
		$emailnew = dhtmlspecialchars($emailnew);
		$sitenew = dhtmlspecialchars($sitenew);
		$bionew = dhtmlspecialchars($bionew);
		$bdaynew = dhtmlspecialchars($bdaynew);
		$cstatusnew = $allowcstatus ? dhtmlspecialchars($cstatusnew) : '';
		$timeformatnew = $timeformatnew == '12' ? 'h:i A' : 'H:i';

		$bdaynew = ($month && $day && $year) ? "$year-$month-$day" : '';

		$dateformatnew = str_replace('mm', 'n', $dateformatnew);
		$dateformatnew = str_replace('dd', 'j', $dateformatnew);
		$dateformatnew = str_replace('yyyy', 'Y', $dateformatnew);
		$dateformatnew = str_replace('yy', 'y', $dateformatnew);

		if($regverify == 1) {
			$query = $db->query("SELECT email FROM $table_members WHERE username='$discuz_user'");
			if($emailnew != $db->result($query, 0)) {
				if(!$doublee) {
					$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE email='$emailnew'");
					if($db->result($query, 0)) {
						showmessage('profile_email_duplicate');
					}
				}					
				$newpassword = random(8);
				$newpasswdadd = ", password='".md5($newpassword)."'";
				sendmail($emailnew, 'email_verify_subject', 'email_verify_content');
			}
		}

		$db->query("UPDATE $table_members SET secques='$secquesnew', gender='$gendernew', email='$emailnew', site='$sitenew', oicq='$oicqnew',
			location='$locationnew', bio='$bionew', signature='$signew', showemail='$showemailnew', timeoffset='$timeoffsetnew',
			icq='$icqnew', yahoo='$yahoonew', styleid='$styleidnew', bday='$bdaynew', tpp='$tppnew', ppp='$pppnew',
			".($allowcstatus ? "customstatus='$cstatusnew', " : '')." newsletter='$newsletternew', timeformat='$timeformatnew', msn='$msnnew',
			dateformat='$dateformatnew', pwdrecover='', pwdrcvtime='' $avataradd $newpasswdadd WHERE username='$discuz_user'");

		$discuz_pw = $newpassword;
		$styleid = $styleidnew;

		if($regverify == 1 && $emailnew != $email) {
			showmessage('profile_email_identify');
		} else {
			showmessage('profile_succeed', 'memcp.php');
		}
	}

} elseif($action == 'favorites') {

	if($favadd && !$favsubmit) {

		$query = $db->query("SELECT tid FROM $table_favorites WHERE tid='$favadd' AND username='$discuz_user'");
		if($db->num_rows($query)) {
			showmessage('favorite_exists'); 
		} else {
			$db->query("INSERT INTO $table_favorites (tid, username)
				VALUES ('$favadd', '$discuz_user')");
			showmessage('favorite_add_succeed', $referer);
		}

	} elseif(!$favadd && !$favsubmit) {

		$query = $db->query("SELECT t.*, f.name FROM $table_favorites fav, $table_threads t, $table_forums f WHERE fav.tid=t.tid AND fav.username='$discuz_user' AND t.fid=f.fid ORDER BY t.lastpost DESC");
		if($db->num_rows($query)) {
			$favexists = 1;
			$favlist = array();
			while($fav = $db->fetch_array($query)) {
				$fav['lastposterenc'] = rawurlencode($fav['lastposter']);
				$fav['lastpost'] = gmdate("$dateformat $timeformat", $fav['lastpost'] + $timeoffset * 3600);

				$favlist[] = $fav;
			}
		} else {
			$favexists = 0;
		}

		include template('memcp_misc');

	} elseif(!$favadd && $favsubmit) {

		$ids = $comma = '';
		if(is_array($delete)) {
			foreach($delete as $deleteid) {
				$ids .= $comma.$deleteid;
				$comma = ', ';
			}
		}

		if($ids) {
			$db->query("DELETE FROM $table_favorites WHERE username='$discuz_user' AND tid IN ($ids)");
		}
		showmessage('favorite_update_succeed', $referer);
	}

} elseif($action == 'subscriptions') {

	if($subadd && !$subsubmit) {

		$query = $db->query("SELECT tid FROM $table_subscriptions WHERE tid='$subadd' AND username='$discuz_user'");
		if($db->num_rows($query)) {
			showmessage('subscription_exists');
		} else {
			$db->query("INSERT INTO $table_subscriptions (username, email, tid, lastnotify)
				VALUES ('$discuz_user', '$email', '$subadd', '')");
			showmessage('subscription_add_succeed', $referer);
		}

	} elseif(!$subadd && !$subsubmit) {

		$query = $db->query("SELECT t.*, f.name FROM $table_subscriptions s, $table_threads t, $table_forums f WHERE t.tid=s.tid AND f.fid=t.fid AND s.username='$discuz_user' ORDER BY t.lastpost DESC");
		if($db->num_rows($query)) {
			$subsexists = 1;
			$sublist = array();
			while($subs = $db->fetch_array($query)) {
				$subs['lastposterenc'] = rawurlencode($subs['lastposter']);
				$subs['lastpost'] = gmdate("$dateformat $timeformat", $subs['lastpost'] + $timeoffset * 3600);

				$subslist[] = $subs;
			}
		} else {
			$subsexists = 0;
		}

		include template('memcp_misc');

	} elseif(!$subadd && $subsubmit) {

		$ids = $comma = '';
		if(is_array($delete)) {
			foreach($delete as $deleteid) {
				$ids .= "$comma$deleteid";
				$comma = ", ";
			}
		}

		if($ids) {
			$db->query("DELETE FROM $table_subscriptions WHERE username='$discuz_user' AND tid IN ($ids)");
		}
		showmessage('subscription_update_succeed', $referer);
	}

} elseif($action == 'viewavatars') {

	if(!$avasubmit) {

		$app = 16;
		$avatarsdir = $discuz_root.'./images/avatars';
		if(!$page) {
			$page = 1;
		}

		$query = $db->query("SELECT avatar FROM $table_members WHERE username='$discuz_user'");
		$member = $db->fetch_array($query);
		$avatarlist = "";
		$num = 1;
		if(is_dir($avatarsdir)) {
			$adir = dir($avatarsdir);
			while($entry = $adir->read()) {
				if ($entry != '.' && $entry != '..') {
					if (is_file("$avatarsdir/$entry")) {
						$avatars[$num] = $entry;
						$num++;
					}
				}
			}
			$adir->close();
			$num--;
		} else {
			showmessage('profile_avatardir_nonexistence');
		}

		$start = ($page - 1) * $app;
		$end = ($start + $app > $num) ? ($num - 1) : ($start + $app - 1);

		$multipage = multi($num, $app, $page, "memcp.php?action=viewavatars");
		for($i = $start; $i <= $end; $i += 4) {
			$avatarlist .= "<tr>\n";
			for($j = 0; $j < 4; $j++) {
				$thisbg = ($thisbg == ALTBG1) ? ALTBG2 : ALTBG1;
				$avatarlist .= "<td bgcolor=\"$thisbg\" width=\"25%\" align=\"center\">";
				if($avatars[$i + $j] && ($i + $j)) {
					$avatarlist .= "<img src=\"images/avatars/".$avatars[$i + $j]."\"></td>\n";
				} else {
					$avatarlist .= "&nbsp;</td>\n";
				}
			}
			$avatarlist .= "</tr><tr>\n";
			for($j = 0; $j < 4; $j++) {
				$avatarlist .= "<td bgcolor=\"$thisbg\" width=\"25%\" align=\"center\">";
				if($avatars[$i + $j] && ($i + $j)) {
					if(strpos($member['avatar'], $avatars[$i + $j])) {
						$checked = "checked";
					} else {
						$checked = "";
					}
					$avatarlist .= "<input type=\"radio\" value=\"images/avatars/".$avatars[$i + $j]."\" name=\"avatarnew\" $checked>".$avatars[$i + $j]."\n";
				} elseif($i + $j == 0) {
					if(!$member['avatar']) {
						$checked = "checked";
					}
					$avatarlist .= "<input type=\"radio\" value=\"\" name=\"avatarnew\" $checked><span class=\"bold\">不使用頭像</span>\n";
				} else {
					$avatarlist .= "&nbsp;</td>\n";
				}
				$thisbg = ($thisbg == ALTBG1) ? ALTBG2 : ALTBG1;
			}
			$avatarlist .= "</tr><tr><td bgcolor=\"".ALTBG1."\" colspan=\"4\" height=\"1\"></td></tr>\n\n";
		}

		include template('memcp_misc');

	} elseif($avasubmit) {

		$db->query("UPDATE $table_members SET avatar='$avatarnew' WHERE username='$discuz_user'");
		showmessage('profile_avatar_succeed', 'memcp.php?action=profile');

	}

} elseif($action == 'buddylist') {

	if(empty($delete)) {
		$buddy = trim($buddy);
		$query = $db->query("SELECT COUNT(*) FROM $table_buddys WHERE username='$discuz_user' AND buddyname='$buddy'");
		if($db->result($query, 0)) {
			showmessage('buddy_add_invalid');
		}
		$query = $db->query("SELECT username FROM $table_members WHERE username='$buddy'");
		$buddy = addslashes($db->result($query, 0));
		if(empty($buddy)) {
			showmessage('buddy_add_nonexistence');
		}
		$db->query("INSERT INTO $table_buddys VALUES ('$discuz_user', '$buddy')");
		showmessage('buddy_add_succeed', 'memcp.php');
	} else {
		$db->query("DELETE FROM $table_buddys WHERE username='$discuz_user' AND buddyname='$delete'");
		showmessage('buddy_delete_succeed', 'memcp.php');
	}

} elseif($action == 'permission'){
        $query = $db ->query("SELECT u.* FROM $table_usergroups u
                                                                                        LEFT JOIN $table_members m ON (u.status=m.status AND (u.creditshigher='0' AND u.creditslower='0' AND u.specifiedusers='')) OR  (m.credit>=u.creditshigher AND m.credit<u.creditslower) WHERE m.username = '$discuz_user'");
        $permission = $db->fetch_array($query);
        $permission['allowvisit'] = $permission['allowvisit'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['ismoderator'] = $permission['ismoderator'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['issupermod'] = $permission['issupermod'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['isadmin'] = $permission['isadmin'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowviewstats'] = $permission['allowviewstats'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowview'] = $permission['allowview'] == "1" ? "√" : "<font color=red>×</font>";
    $permission['allowpost'] = $permission['allowpost'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowsetviewperm'] = $permission['allowsetviewperm'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowpostpoll'] = $permission['allowpostpoll'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowvote'] = $permission['allowvote'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowgetattach'] = $permission['allowgetattach'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowpostattach'] = $permission['allowpostattach'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowsetattachperm'] = $permission['allowsetattachperm'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['attachextensions'] = $permission['attachextensions'] ? $permission['attachextensions'] : "允許所有附件類型";
        $permission['allowcstatus'] = $permission['allowcstatus'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowkarma'] = $permission['allowkarma'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowsigimgcode'] = $permission['allowsigimgcode'] == "1" ? "√" : "<font color=red>×</font>";
        $permission['allowsigbbcode'] = $permission['allowsigbbcode'] == "1" ? "√" : "<font color=red>×</font>";
        include template('memcp_permission');

} elseif($action == 'credits') {

	include template('header');
	echo base64_decode('PGNlbnRlcj48c3BhbiBjbGFzcz1cIm1lZGl1bXR4dFwiIHN0eWxlPVwiZm9udC1zaXplOiAyMHB4OyBmb250LXdlaWdodDogYm9sZFwiPkRpc2N1eiEgQ3JlZGl0czwvc3Bhbj48YnI+PGJyPjx0YWJsZSBjZWxsc3BhY2luZz1cIjBcIiBjZWxscGFkZGluZz1cIjBcIiBib3JkZXI9XCIwXCIgd2lkdGg9XCI0MDBcIiBhbGlnbj1cImNlbnRlclwiPjx0cj48dGQgYmdjb2xvcj1cIiRib3JkZXJjb2xvclwiPjx0YWJsZSBib3JkZXI9XCIwXCIgY2VsbHNwYWNpbmc9XCIkYm9yZGVyd2lkdGhcIiBjZWxscGFkZGluZz1cIiR0YWJsZXNwYWNlXCIgd2lkdGg9XCIxMDAlXCI+PHRyIGNsYXNzPVwiaGVhZGVyXCI+PHRkIGNvbHNwYW49XCIyXCIgYWxpZ249XCJjZW50ZXJcIj5EaXNjdXohIERldmVsb3BlcjwvdGQ+PC90cj48dHI+PHRkIGJnY29sb3I9XCIkYWx0YmcyXCIgYWxpZ249XCJjZW50ZXJcIiBjb2xzcGFuPVwiMlwiIGNsYXNzPVwiYm9sZFwiPkRpc2N1eiEgaXMgZGV2ZWxvcGVkIGJ5IENyb3NzZGF5IFN0dWRpbywgQWxsIFJpZ2h0cyBSZXNlcnZlZC48L3RkPjwvdHI+PHRyPjx0ZCBiZ2NvbG9yPVwiJGFsdGJnMVwiIHdpZHRoPVwiNDAlXCIgY2xhc3M9XCJib2xkXCI+UHJvZ3JhbWluZzo8L3RkPjx0ZCBiZ2NvbG9yPVwiJGFsdGJnMlwiPjxhIGhyZWY9XCJodHRwOi8vd3d3LmNyb3NzZGF5LmNvbVwiIHRhcmdldD1cIl9ibGFua1wiPkNyb3NzZGF5PC9hPjwvdGQ+PC90cj48dHI+PHRkIGJnY29sb3I9XCIkYWx0YmcxXCIgY2xhc3M9XCJib2xkXCI+VGhlbWUgRGVzaWduOjwvdGQ+PHRkIGJnY29sb3I9XCIkYWx0YmcyXCI+PGEgaHJlZj1cImh0dHA6Ly93d3cuY3Jvc3NkYXkuY29tXCIgdGFyZ2V0PVwiX2JsYW5rXCI+Q3Jvc3NkYXk8L2E+PC90ZD48L3RyPjwvdGFibGU+PC90ZD48L3RyPjwvdGFibGU+PGJyPjxicj48dGFibGUgY2VsbHNwYWNpbmc9XCIwXCIgY2VsbHBhZGRpbmc9XCIwXCIgYm9yZGVyPVwiMFwiIHdpZHRoPVwiNDAwXCIgYWxpZ249XCJjZW50ZXJcIj48dHI+PHRkIGJnY29sb3I9XCIkYm9yZGVyY29sb3JcIj48dGFibGUgYm9yZGVyPVwiMFwiIGNlbGxzcGFjaW5nPVwiJGJvcmRlcndpZHRoXCIgY2VsbHBhZGRpbmc9XCIkdGFibGVzcGFjZVwiIHdpZHRoPVwiMTAwJVwiIHN0eWxlPVwid29yZC1icmVhazoga2VlcC1hbGxcIj48dHIgY2xhc3M9XCJoZWFkZXJcIj48dGQgY29sc3Bhbj1cIjJcIiBhbGlnbj1cImNlbnRlclwiPkRpc2N1eiEgU3VwcG9ydCBUZWFtPC90ZD48L3RyPjx0cj48dGQgYmdjb2xvcj1cIiRhbHRiZzFcIiB3aWR0aD1cIjQwJVwiIHZhbGlnbj1cInRvcFwiIGNsYXNzPVwiYm9sZFwiPkFydCBTdXBwb3J0OjwvdGQ+PHRkIGJnY29sb3I9XCIkYWx0YmcyXCI+PGEgaHJlZj1cImh0dHA6Ly90eWMudWRpLmNvbS50dy9jZGJcIiB0YXJnZXQ9XCJfYmxhbmtcIj50eWM8L2E+LCA8YSBocmVmPVwiaHR0cDovL3NtaWNlLm5ldC9+eW91cmFuL2NkYi9pbmRleC5waHBcIiB0YXJnZXQ9XCJfYmxhbmtcIj7Qx8q0PC9hPiwgPGEgaHJlZj1cImh0dHA6Ly93d3cuY25tYXlhLm9yZ1wiIHRhcmdldD1cIl9ibGFua1wiPrr8wOq6/c2/PC9hPjwvdGQ+PC90cj48dHI+PHRkIGJnY29sb3I9XCIkYWx0YmcxXCIgdmFsaWduPVwidG9wXCIgY2xhc3M9XCJib2xkXCI+UGx1Z2luczo8L3RkPjx0ZCBiZ2NvbG9yPVwiJGFsdGJnMlwiPjxhIGhyZWY9XCJodHRwOi8vd3d3Lm51Y3BwLmNvbVwiIHRhcmdldD1cIl9ibGFua1wiPktuaWdodEU8L2E+LCA8YSBocmVmPVwiaHR0cDovL3d3dy56YzE4LmNvbS9cIiB0YXJnZXQ9XCJfYmxhbmtcIj5mZWl4aW48L2E+LCA8YSBocmVmPVwiaHR0cDovL3NtaWNlLm5ldC9+eW91cmFuL2NkYi9pbmRleC5waHBcIiB0YXJnZXQ9XCJfYmxhbmtcIj7Qx8q0PC9hPiwgPGEgaHJlZj1cImh0dHA6Ly90cnVlaG9tZS5uZXRcIiB0YXJnZXQ9XCJfYmxhbmtcIj7Az7H4vsawyTwvYT48L3RkPjwvdHI+PHRyPjx0ZCBiZ2NvbG9yPVwiJGFsdGJnMVwiIHZhbGlnbj1cInRvcFwiIGNsYXNzPVwiYm9sZFwiPk9mZmljYWwgVGVzdGVyczo8L3RkPjx0ZCBiZ2NvbG9yPVwiJGFsdGJnMlwiPjxhIGhyZWY9XCJodHRwOi8vdHJ1ZWhvbWUubmV0XCIgdGFyZ2V0PVwiX2JsYW5rXCI+wM+x+L7GsMk8L2E+LCBhYnUsIDxhIGhyZWY9XCJodHRwOi8vd3d3Lm51Y3BwLmNvbVwiIHRhcmdldD1cIl9ibGFua1wiPktuaWdodEU8L2E+LCA8YSBocmVmPVwiaHR0cDovL3d3dy56YzE4LmNvbVwiIHRhcmdldD1cIl9ibGFua1wiPmZlaXhpbjwvYT4sIDxhIGhyZWY9XCJodHRwOi8vc21pY2UubmV0L355b3VyYW4vY2RiL2luZGV4LnBocFwiIHRhcmdldD1cIl9ibGFua1wiPtDHyrQ8L2E+LCA8YSBocmVmPVwiaHR0cDovL3R5Yy51ZGkuY29tLnR3L2NkYlwiIHRhcmdldD1cIl9ibGFua1wiPnR5YzwvYT4sIDxhIGhyZWY9XCJodHRwOi8vd3d3LnR4eXgubmV0XCIgdGFyZ2V0PVwiX2JsYW5rXCI+8Km2+TwvYT4sIDxhIGhyZWY9XCJodHRwOi8vcy10bS5uZXRcIiB0YXJnZXQ9XCJfYmxhbmtcIj7Evrb6PC9hPiwgPGEgaHJlZj1cImh0dHA6Ly93d3cub3VycGhwLmNvbVwiIHRhcmdldD1cIl9ibGFua1wiPlNoYXJteTwvYT4sIDxhIGhyZWY9XCJodHRwOi8vd3d3LmVhY2h1LmNvbVwiIHRhcmdldD1cIl9ibGFua1wiPlIuQzwvYT4sIDxhIGhyZWY9XCJodHRwOi8vd3d3Lmp1bm9tYXkuY29tXCIgdGFyZ2V0PVwiX2JsYW5rXCI+QVNVUkE8L2E+LCA8YSBocmVmPVwiaHR0cDovL3d3dy5IYWtrYU9ubGluZS5jb21cIiB0YXJnZXQ9XCJfYmxhbmtcIj7OtMP7seLW2zwvYT4sIDxhIGhyZWY9XCJodHRwOi8vM3B1bmsuY29tXCIgdGFyZ2V0PVwiX2JsYW5rXCI+M3B1bms8L2E+LCA8YSBocmVmPVwiaHR0cDovL3d3dy5wdWZmZXIuaWR2LnR3L2NkYlwiIHRhcmdldD1cIl9ibGFua1wiPnB1ZmZlcjwvYT48L3RkPjwvdHI+PC90YWJsZT48L3RkPjwvdHI+PC90YWJsZT48YnI+PGJyPg==');
	include template('footer');

}

?>