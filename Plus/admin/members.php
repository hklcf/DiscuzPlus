<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

if(!defined("IN_DISCUZ")) {
	exit("Access Denied");
}

cpheader();

if($action == 'addmember') {

	if(!$addsubmit) {

?>
<br><form method="post" action="admincp.php?action=addmember">
<table cellspacing="0" cellpadding="0" border="0" width="80%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">

<tr><td class="header" colspan="2">增加新用戶</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">頭銜：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><select name="newstatus">
<option value="Member">正式會員</option>
<option value="vip">VIP</option>
<option value="Moderator">版 &nbsp;&nbsp; 主</option>
<option value="SuperMod">超級版主</option>
<option value="Inactive">等待驗證</option>
</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">用戶名：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="newusername"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">密碼：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="newpassword"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">E-mail：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="newemail"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">發送通知到上述地址：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="checkbox" name="emailnotify" value="yes" checked></td></tr>

</table></td></tr></table>
<br><center><input type="submit" name="addsubmit" value="增加用戶"></center>
</form>
<?

	} else {

		if(!trim($newpassword)) {
			cpmsg('您沒有填寫用戶密碼，請返回修改。');
		}

		if(!trim($newemail)) {
			cpmsg('您沒有填寫 E-mail 地址，請返回修改。');
		}

		$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE username='$newusername'");
		if($db->result($query, 0)) {
			cpmsg('用戶名稱已經存在，請返回修改。');
		}

		$db->query("INSERT INTO $table_members (username, password, gender, status, regip, regdate, lastvisit, postnum, credit, email, tpp, ppp, styleid, dateformat, timeformat, showemail, newsletter, timeoffset)
			VALUES ('$newusername', '".md5($newpassword)."', '0', '$newstatus', 'hidden', '$timestamp', '$timestamp', '0', '0', '$newemail', '0', '0', '0', '{$_DCACHE[settings][dateformat]}', '{$_DCACHE[settings][timeformat]}', '1', '1', '{$_DCACHE[settings][timeoffset]}')");
		$db->query("UPDATE $table_settings SET lastmember='$newusername', totalmembers=totalmembers+1");

		if($emailnotify == 'yes') {
			sendmail($newemail, "[Discuz!]您被 $bbname 增加為會員", "您好，我是 $bbname 管理員 {$discuz_user}，\n".
				"您已被批准成為我們論壇的會員，歡迎您往後以此帳號登入本站：\n".
				"用戶名稱：$newusername\n".
				"密碼：$newpassword\n".
				"歡迎光臨 $bbname ($boardurl) ！"
				);
		}

		updatecache('settings');
		cpmsg('用戶增加成功。');
	}

} elseif($action == 'members') {

	if(!$searchsubmit && !$deletesubmit && !$editsubmit && !$exportsubmit) {

?>
<br><form method="post" action="admincp.php?action=members">
<table cellspacing="0" cellpadding="0" border="0" width="80%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">

<tr><td class="header" colspan="2">搜尋用戶</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">直接刪除符合條件的用戶：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="checkbox" name="deletesubmit" value="1"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">頭銜：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><select name="userstatus">
<option value="">任何頭銜</option>
<option value="Admin">管 理 員</option>
<option value="SuperMod">超級版主</option>
<option value="Moderator">版 &nbsp;&nbsp; 主</option>
<option value="vip">VIP</option>
<option value="Member">正式會員</option>
<option value="Banned">禁止訪問</option>
<option value="PostBanned">禁止發言</option>
</select></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">會員編號：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="uid" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">用戶名包含：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="username" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">積分小於：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="creditsless" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">積分大於：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="creditsmore" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">多少天沒有登入論壇：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="awaydays" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">註冊 IP 開頭 (如 202.97)：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="regip" size="40"></td></tr>

</table></td></tr></table><br><center>
<input type="submit" name="searchsubmit" value="搜尋用戶"> &nbsp; 
<input type="submit" name="deletesubmit" value="刪除用戶"> &nbsp; 
<input type="submit" name="exportsubmit" value="導出 E-mail"></center></form>
<?

	} elseif($searchsubmit || $deletesubmit || $exportsubmit) {

		if(!$page) {
			$page = 1;
		}
		$offset = ($page - 1) * $memberperpage;

		$conditions = "";
		$conditions .= $uid != "" ? " AND uid='$uid'" : NULL;
		$conditions .= $username != "" ? " AND (username LIKE '%$username%' OR username='$username')" : NULL;
		$conditions .= $userstatus != "" ? " AND status='$userstatus'" : NULL;
		$conditions .= $creditsmore != "" ? " AND credit>'$creditsmore'" : NULL;
		$conditions .= $creditsless != "" ? " AND credit<'$creditsless'" : NULL;
		$conditions .= $awaydays != "" ? " AND lastvisit<'".($timestamp - $awaydays * 86400)."'" : NULL;
		$conditions .= $regip != "" ? " AND regip LIKE '$regip%'" : NULL;

		if($conditions) {

			$conditions = substr($conditions, 5);
			if($searchsubmit) {
				$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE $conditions");
				$num = $db->result($query, 0);
				$multipage = multi($num, $memberperpage, $page, "admincp.php?action=members&searchsubmit=yes&username=$username&userstatus=$userstatus&creditsmore=$creditsmore&creditsless=$creditsless&awaydays=$awaydays&regip=$regip");

				$query = $db->query("SELECT * FROM $table_members WHERE $conditions LIMIT $offset, $memberperpage");
				while($member = $db->fetch_array($query)) {
					$select = array($member[status] => "selected=\"selected\"");
					$members .= "<tr align=\"center\" bgcolor=\"".ALTBG2."\" align=\"center\">\n".
						"<td><input type=\"checkbox\" name=\"delete[]\" value=\"$member[uid]\"></td>\n".
						"<td>$member[username]</td>\n".
						"<td><input type=\"text\" size=\"10\" name=\"passwdnew[$member[uid]]\"></td>\n".
						"<td><input type=\"text\" size=\"5\" name=\"creditnew[$member[uid]]\" value=\"$member[credit]\"> $creditunit</td>\n".
						"<td><select name=\"statusnew[$member[uid]]\">\n".
						"<option value=\"Member\">未知頭銜</option>\n".
						"<option value=\"Admin\" ".$select['Admin'].">管 理 員</option>\n".
						"<option value=\"SuperMod\" ".$select['SuperMod'].">超級版主</option>\n".
						"<option value=\"Moderator\" ".$select['Moderator'].">版 &nbsp;&nbsp; 主</option>\n".
						"<option value=\"vip\" ".$select['vip'].">VIP</option>\n".
						"<option value=\"Member\" ".$select['Member'].">正式會員</option>\n".
						"<option value=\"Banned\" ".$select['Banned'].">禁止訪問</option>\n".
						"<option value=\"PostBanned\" ".$select['PostBanned'].">禁止發言</option></select></td>\n".
						"<td><input type=\"text\" size=\"15\" name=\"usercstatus[$member[uid]]\" value=\"$member[customstatus]\"></td>\n".
						"<td><a href=\"admincp.php?action=memberprofile&username=".rawurlencode($member[username])."\">[編輯]</a></tr>\n";
				}
						
?>
<form method="post" action="admincp.php?action=members">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td class="multi"><?=$multipage?></td></tr>
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr align="center" class="header">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">刪</td>
<td>用戶名</td><td>密碼</td><td>積分</td><td>系統頭銜</td><td>用戶頭銜</td><td>詳細</td></tr>
<?=$members?>
</table></td></tr>
<tr><td class="multi"><?=$multipage?></td></tr>
</table><br><center>
<input type="submit" name="editsubmit" value="修改用戶資料"></center>
</form>
<?

			} elseif($deletesubmit) {
				if(!$confirmed) {
					cpmsg("本操作不可恢復，您確定要刪除符合條件的會員嗎？", "admincp.php?action=members&deletesubmit=yes&username=$username&userstatus=$userstatus&creditsmore=$creditsmore&creditsless=$creditsless&awaydays=$awaydays&regip=$regip", "form");
				} else {
					$query = $db->query("DELETE FROM $table_members WHERE $conditions");
					$numdeleted = $db->affected_rows();
					updatecache('settings');
					cpmsg("符合條件的 $numdeleted 個用戶被成功刪除。");
				}
			} elseif($exportsubmit) {
				$export = $comma = '';
				$query = $db->query("SELECT username, email FROM $table_members WHERE $conditions");
				while($member = $db->fetch_array($query)) {
					$export .= "$comma$member[username] &lt;$member[email]&gt;";
					$comma = ', ';
				}

?>
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>用戶 E-mail 地址導出</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td><?=$export?>
</td></tr></table></td></tr></table>
<?

			}
		} else {
			cpmsg("您沒有提供搜尋的條件，請返回修改。");
		}
	} elseif($editsubmit) {
		if(is_array($delete)) {
			$ids = $comma = '';
			foreach($delete as $id) {
				$ids .= "$comma'$id'";
				$comma = ', ';
			}
			$db->query("DELETE FROM $table_members WHERE uid IN ($ids)");
			updatecache('settings');
		}
		if(is_array($statusnew)) {
			foreach($statusnew as $id => $val) {
				$passwdadd = $passwdnew[$id] != "" ? ", password='".md5($passwdnew[$id])."'" : NULL;
				$db->query("UPDATE $table_members SET status='$statusnew[$id]', credit='$creditnew[$id]', customstatus='$usercstatus[$id]' $passwdadd WHERE uid='$id'");
				$mytemp110=array();
			$my_query111=$db->query("SELECT username as uname FROM $table_members where uid='$id'");
			while($mytemp110 = $db->fetch_array($my_query111)){
			$my_temper = AddSlashes($mytemp110['uname']);
			$db->query("DELETE from $table_sessions WHERE username='$my_temper'");
		}
unset($mytemp110, $my_query111, $my_temper);
			}
		}
		cpmsg("符合條件的用戶被成功編輯。");
	}

} elseif($action == 'memberprofile') {

	if(!$editsubmit) {

		$username = empty($username) ? $extr : $username;
		$query = $db->query("SELECT * FROM $table_members WHERE username='$username'");
		if($member = $db->fetch_array($query)) {

			$check = array($member[status] => "selected=\"selected\"");
			if($member[showemail]) {
					$emailchecked = "checked=\"checked\"";
			}
			if($member[newsletter]) {
				$newschecked = "checked=\"checked\"";
			}

			$currdate = gmdate("$timeformat");

			$styleselect = "<select name=\"styleidnew\">\n<option value=\"\">--使用預設值--</option>";
			$query = $db->query("SELECT styleid, name FROM $table_styles");
			while($style = $db->fetch_array($query)) {
				$styleselect .= "<option value=\"$style[styleid]\" ".($style['styleid'] == $member['styleid'] ? 'selected="selected"' : NULL).">$style[name]</option>\n";
			}
			$styleselect .= "</select>";

			$bday = explode("-", $member[bday]);
			$member[dateformat] = str_replace("n", "mm", $member[dateformat]);
			$member[dateformat] = str_replace("j", "dd", $member[dateformat]);
			$member[dateformat] = str_replace("y", "yy", $member[dateformat]);
			$member[dateformat] = str_replace("Y", "yyyy", $member[dateformat]);
			$member[timeformat] == "H:i" ? $check24 = "checked=\"checked\"" : $check12 = "checked=\"checked\"";

			$regdate = explode("-", gmdate("Y-n-j", $member[regdate] + ($timeoffset * 3600)));
			$lastvisittime = explode("-", gmdate("Y-n-j", $member[lastvisit] + ($timeoffset * 3600)));
			$username = stripslashes($username);

?>
<form method="post" action="admincp.php?action=memberprofile&username=<?=rawurlencode($username)?>">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr><td colspan="2" class="header">編輯個人資料 - 基本項目</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">系統頭銜：</td>
<td bgcolor="<?=ALTBG2?>"><select name="statusnew">
<option value="Member">未知頭銜</option>
<option value="Admin" <?=$check[Admin]?>>管 理 員</option>
<option value="SuperMod" <?=$check[SuperMod]?>>超級版主</option>
<option value="Moderator" <?=$check[Moderator]?>>版 &nbsp;&nbsp; 主</option>
<option value="vip" <?=$check[vip]?>>VIP</option>
<option value="Member" <?=$check[Member]?>>正式會員</option>
<option value="Banned" <?=$check[Banned]?>>禁止訪問</option>
<option value="PostBanned" <?=$check[PostBanned]?>>禁止發言</option>
</select></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">用戶名：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="newusername" size="25" value="<?=$member[username]?>"> 如不是特別需要，請不要修改用戶名稱</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">新密碼：</td>
<td bgcolor="<?=ALTBG2?>"><input type="password" name="newpassword" size="25"> 請輸入新密碼，如果不更改密碼此處請留空。</td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">E-mail：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="emailnew" size="25" value="<?=$member[email]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">積分：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="creditnew" size="25" value="<?=$member[credit]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">現金：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="moneynew" size="25" value="<?=$member[money]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">存款：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="banknew" size="25" value="<?=$member[bank]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">發表文章數：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="userpostnum" size="25" value="<?=$member[postnum]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">註冊 IP：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="regip" size="25" value="<?=$member[regip]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">註冊日期：</td>
<td bgcolor="<?=ALTBG2?>">
<input type="text" name="ryear" size="4" value="<?=$regdate[0]?>"> 年 
<input type="text" name="rmonth" size="2" value="<?=$regdate[1]?>"> 月 
<input type="text" name="rday" size="2" value="<?=$regdate[2]?>"> 日 </td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">上次訪問：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="lyear" size="4" value="<?=$lastvisittime[0]?>"> 年 
<input type="text" name="lmonth" size="2" value="<?=$lastvisittime[1]?>"> 月 
<input type="text" name="lday" size="2" value="<?=$lastvisittime[2]?>"> 日 </td></tr>

<tr><td colspan="2" class="header">編輯個人資料 - 可選項目</td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">主頁：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="site" size="25" value="<?=$member[site]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">OICQ：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="oicq" size="25" value="<?=$member[oicq]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">ICQ：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="icq" size="25" value="<?=$member[icq]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">Yahoo：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="yahoo" size="25" value="<?=$member[yahoo]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">MSN：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="msn" size="25" value="<?=$member[msn]?>"/></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">來自：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="memlocation" size="25" value="<?=$member[location]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">生日：</td>
<td bgcolor="<?=ALTBG2?>">
<input type="text" name="byear" size="4" value="<?=$bday[0]?>"> 年 
<input type="text" name="bmonth" size="2" value="<?=$bday[1]?>"> 月 
<input type="text" name="bday" size="2" value="<?=$bday[2]?>"> 日 </td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">個人簡介：</td>
<td bgcolor="<?=ALTBG2?>"><textarea rows="5" cols="30" name="bio"><?=$member[bio]?></textarea></td></tr>

<tr><td colspan="2" class="header">編輯個人資料 - 論壇個性化設置</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">界面方案：</td>
<td bgcolor="<?=ALTBG2?>"><?=$styleselect?> <?=$currtheme?></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">每頁主題數：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="tppnew" size="4" value="<?=$member[tpp]?>"> </td></tr>

<tr><td bgcolor="<?=ALTBG1?>">每頁文章數：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="pppnew" size="4" value="<?=$member[ppp]?>"> </td></tr>

<tr><td bgcolor="<?=ALTBG1?>">時間格式：</td>
<td bgcolor="<?=ALTBG2?>"><input type="radio" value="24" name="timeformatnew" <?=$check24?>> 24 小時制
<input type="radio" value="12" name="timeformatnew" <?=$check12?>> 12 小時制</td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">自行定義頭銜：</td>
<td bgcolor="<?=ALTBG2?>">
<input type="text" name="cstatus" size="25" value="<?=$member[customstatus]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">日期格式<br>(yyyy/mm/dd，mm/dd/yy 等)：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="dateformatnew" size="25" value="<?=$member[dateformat]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">短訊忽略列表：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="ignorepm" size="25" value="<?=$member[ignorepm]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">其他選項：</td>
<td bgcolor="<?=ALTBG2?>">
<input type="checkbox" name="showemail" value="1" <?=$emailchecked?>> E-mail 地址可見<br>
<input type="checkbox" name="newsletter" value="1" <?=$newschecked?>> 允許接收論壇通知 (E-mail 或短訊)<br>
<input type="text" name="timeoffset1" size="3" value="<?=$member[timeoffset]?>"> 時間校正 (香港時間 +8)，目前GMT標準時間 05:52 AM</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">頭像地址：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="avatarnew" size="25" value="<?=$member[avatar]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">個人簽名：</td>
<td bgcolor="<?=ALTBG2?>"><textarea rows="4" cols="30" name="sig"><?=$member[signature]?></textarea></td></tr>

</table></td></tr></table><br>
<center><input type="submit" name="editsubmit" value="編輯個人資料"></center>
</form><br>
<?

		} else {
			cpmsg("指定用戶不存在。");
		}

	} else {

		if($bmonth == "" || $bday == "" || $byear == "") {
			$bday = "";
		} else {
			$bday = "$byear-$bmonth-$bday";
		}

		$regdate = gmmktime(0, 0, 0, $rmonth, $rday, $ryear) - $timeoffset * 3600;
		$lastvisittime = gmmktime(0, 0, 0, $lmonth, $lday, $lyear) - $timeoffset * 3600;
		if($newpassword) {
			$password = md5($newpassword);
			$passwdadd = ", password='$password'";
		}

		$dateformatnew = str_replace("mm", "n", $dateformatnew);
		$dateformatnew = str_replace("dd", "j", $dateformatnew);
		$dateformatnew = str_replace("yyyy", "Y", $dateformatnew);
		$dateformatnew = str_replace("yy", "y", $dateformatnew);
		$timeformatnew = $timeformatnew == "24" ? "H:i" : "h:i A";

		$db->query("UPDATE $table_members SET status='$statusnew', email='$emailnew', credit='$creditnew', money='$moneynew', bank='$banknew', postnum='$userpostnum', regip='$regip', regdate='$regdate', lastvisit='$lastvisittime', site='$site', oicq='$oicq', icq='$icq', yahoo='$yahoo', msn='$msn', location='$memlocation', bday='$bday', bio='$bio', styleid='$styleidnew', tpp='$tppnew', ppp='$pppnew', timeformat='$timeformatnew', customstatus='$cstatus', ignorepm='$ignorepm', showemail='$showemail', newsletter='$newsletter', timeoffset='$timeoffset1', avatar='$avatarnew', signature='$sig' $passwdadd WHERE username='$username'");
		$db->query("DELETE from $table_sessions WHERE username='$username'");
		if($username != $newusername) {
			$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE username='$newusername'");
			if($db->result($query, 0)) {
				$usernameadd = "但新用戶名與現有用戶名稱重複，無法修改。";
			} else {
				$db->query("UPDATE $table_buddys SET username='$newusername' WHERE username='$username'");
				$db->query("UPDATE $table_buddys SET buddyname='$newusername' WHERE buddyname='$username'");
				$db->query("UPDATE $table_favorites SET username='$newusername' WHERE username='$username'");
				$db->query("UPDATE $table_subscriptions SET username='$newusername' WHERE username='$username'");
				$db->query("UPDATE $table_members SET username='$newusername' WHERE username='$username'");
				$db->query("UPDATE $table_posts SET author='$newusername' WHERE author='$username'");
				$db->query("UPDATE $table_threads SET author='$newusername' WHERE author='$username'");
				$db->query("UPDATE $table_threads SET lastposter='$newusername' WHERE lastposter='$username'");
				$db->query("UPDATE $table_forums SET lastpost=REPLACE(lastpost, '\t$username', '\t$newusername')");
				$db->query("UPDATE $table_pm SET msgfrom='$newusername' WHERE msgfrom='$username'");
				$db->query("UPDATE $table_pm SET msgto='$newusername' WHERE msgto='$username'");
			}
		}
		cpmsg("用戶資料成功更新。$usernameadd");
	}

} elseif($action == "usergroups") {

	if(!$groupsubmit) {

		if($type != "detail" || !$id) {
			$membergroup = $specifiedgroup = $sysgroup = "";
			$upperlimit = $lowerlimit = $misconfig = 0;
			$query = $db->query("SELECT groupid, specifiedusers, status, grouptitle, creditshigher, creditslower, stars, groupavatar FROM $table_usergroups ORDER BY creditslower");
			while($group = $db->fetch_array($query)) {
				if($group[status] == "Member" && !$group[specifiedusers]) {
					$membergroup .= "<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[{$group[groupid]}]\" value=\"$group[groupid]\"></td>\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"12\" name=\"group_title[{$group[groupid]}]\" value=\"$group[grouptitle]\"></td>\n".
						"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"6\" name=\"group_creditshigher[{$group[groupid]}]\" value=\"$group[creditshigher]\">\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"6\" name=\"group_creditslower[{$group[groupid]}]\" value=\"$group[creditslower]\"></td>\n".
						"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"2\"name=\"group_stars[{$group[groupid]}]\" value=\"$group[stars]\"></td>\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"20\" name=\"group_avatar[{$group[groupid]}]\" value=\"$group[groupavatar]\"></td>".
						"<td bgcolor=\"".ALTBG1."\"><a href=\"admincp.php?action=usergroups&type=detail&id=$group[groupid]\">[詳情]</a></td></tr>\n";
					if($group[creditshigher] > 0 && $upperlimit != $group[creditshigher]) {
						//echo "$upperlimit $group[creditshigher]<br>"; //debug
						$misconfig = 1;
					}
					$lowerlimit = $group[creditshigher] < $lowerlimit ? $group[creditshigher] : $lowerlimit;
					$upperlimit = $group[creditslower] > $upperlimit ? $group[creditslower] : $upperlimit;
				} elseif($group[specifiedusers]) {
					$group[specifiedusers] = str_replace("\t", ", ", substr($group[specifiedusers], 1, -1));
					$specifiedgroup .= "<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[{$group[groupid]}]\" value=\"$group[groupid]\"></td>\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"12\" name=\"group_title[{$group[groupid]}]\" value=\"$group[grouptitle]\"></td>\n".
						"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"20\" name=\"group_specifiedusers[{$group[groupid]}]\" value=\"$group[specifiedusers]\">\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"2\"name=\"group_stars[{$group[groupid]}]\" value=\"$group[stars]\"></td>\n".
						"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"20\" name=\"group_avatar[{$group[groupid]}]\" value=\"$group[groupavatar]\"></td>\n".
						"<td bgcolor=\"".ALTBG2."\"><a href=\"admincp.php?action=usergroups&type=detail&id=$group[groupid]\">[詳情]</a></td></tr>\n";
				} else {
					$sysgroup .= "<tr align=\"center\">\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"12\" name=\"group_title[{$group[groupid]}]\" value=\"$group[grouptitle]\"></td>\n".
						"<td bgcolor=\"".ALTBG1."\">$group[status]</td>\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"2\"name=\"group_stars[{$group[groupid]}]\" value=\"$group[stars]\"></td>\n".
						"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"20\" name=\"group_avatar[{$group[groupid]}]\" value=\"$group[groupavatar]\"></td>\n".
						"<td bgcolor=\"".ALTBG2."\"><a href=\"admincp.php?action=usergroups&type=detail&id=$group[groupid]\">[詳情]</a></td></tr>\n";
				}
			}
			if($misconfig || $upperlimit < 9999 || $lowerlimit > -999) {
				$warning = "<script>alert('當前積分設定存在明顯問題，請根據提示盡快修正。');</script><span class=\"mediumtxt\"><b>警告！</b>您當前的設定並未覆蓋整個積分範圍(建議 -99999 到 99999)，或相鄰兩組間積分上下限存在<br>空隙或重疊。請立即完善會員組設定或恢復到預設，否則將導致部分用戶無法訪問論壇的嚴重問題！</span><br><br>";
			}

?>
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>特別提示</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>Discuz! 論壇的用戶組分為系統組、特殊組和會員組，區別在於確定所在用戶組的方式：系統組按照用戶的系統頭銜確定；特殊組按照指定的特別用戶名確定；會員組按照會員的積分來確定。每個組可以分別設置相應的權限。</ul>
<ul><li>系統組和特殊組的設定不需要指定積分，Discuz! 預留了從論壇管理員到遊客等的 8 個系統頭銜，特殊組的多個用戶名之間可用半形逗號 "," 分割。</ul>
<ul><li>會員組積分設定的總體範圍必須能滿足實際的要求，如 -99999 到 99999；而且，不同的組之間積分範圍不要出現重疊，否則將出現混亂。</ul>
<ul><li>如果您不小心誤操作，導致問題，可點擊「恢復預設」按鈕將設定恢復到初始狀態。</ul>
</td></tr></table></td></tr></table>

<form method="post" action="admincp.php?action=usergroups&type=member">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="7">會員用戶組 - 點擊組頭銜編輯詳細權限設置</td></tr>
<tr class="category" align="center"><td width="45"><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">刪</td>
<td>組頭銜</td><td>積分下限</td><td>積分上限</td><td>星星數</td><td>組頭像</td><td>編輯</td></tr>
<?=$membergroup?>
<tr height="1" bgcolor="<?=ALTBG2?>"><td colspan="7"></td></tr>
<tr align="center" bgcolor="<?=ALTBG1?>"><td>新增：</td>
<td><input type="text" size="12" name="grouptitlenew"></td>
<td><input type="text" size="6" name="creditshighernew"></td>
<td><input type="text" size="6" name="creditslowernew"></td>
<td><input type="text" size="2" name="starsnew"></td>
<td><input type="text" size="20" name="groupavatarnew"></td>
<td>&nbsp;</td>
</tr></table></td></tr></table><br><center><?=$warning?>
<input type="submit" name="groupsubmit" value="編輯會員用戶組">&nbsp;
<input type="button" name="reset" value="恢復到預設設定" onClick="top.main.location.href='admincp.php?action=usergroups&type=member&reset=yes&groupsubmit=yes';"></center></form><br><br>

<form method="post" action="admincp.php?action=usergroups&type=specified">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="6">特殊用戶組 - 點擊組頭銜編輯詳細權限設置</td></tr>
<tr class="category" align="center"><td width="45"><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">刪</td>
<td>組頭銜</td><td>包含用戶</td><td>星星數</td><td>組頭像</td><td>編輯</td></tr>
<?=$specifiedgroup?>
<tr height="1" bgcolor="<?=ALTBG2?>"><td colspan="6"></td></tr>
<tr align="center" bgcolor="<?=ALTBG1?>"><td>新增：</td>
<td><input type="text" size="12" name="grouptitlenew"></td>
<td><input type="text" size="20" name="specifiedusersnew"></td>
<td><input type="text" size="2" name="starsnew"></td>
<td><input type="text" size="20" name="groupavatarnew"></td>
<td>&nbsp;</td>
</tr></table></td></tr></table><br><center>
<input type="submit" name="groupsubmit" value="編輯特殊用戶組"></center></form><br><br>

<form method="post" action="admincp.php?action=usergroups&type=system">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="5">系統用戶組 — 點擊組頭銜編輯詳細權限設置</td></tr>
<tr class="category" align="center">
<td>組頭銜</td><td>系統頭銜</td><td>星星數</td><td>組頭像</td><td>編輯</td></tr>
<?=$sysgroup?>
</table></td></tr></table><br><center>
<input type="submit" name="groupsubmit" value="編輯系統用戶組"></center></form>
<?

		} else {

			if(!$detailsubmit) {
				$query = $db->query("SELECT * FROM $table_usergroups WHERE groupid='$id'");
				$group = $db->fetch_array($query);
				$checksearch = array($group['allowsearch'] => 'checked');
				$checkavatar = array($group['allowavatar'] => 'checked');

				echo "<form method=\"post\" action=\"admincp.php?action=usergroups&type=detail&id=$id\">\n";

				showtype("編輯用戶組", "top");
				showsetting("用戶組頭銜", "grouptitlenew", $group[grouptitle], "text");

				showtype("基本權限");
				if($group[status] == "Guest") {
					echo "<input type=\"hidden\" name=\"allowvisitnew\" value=\"1\">\n";
				} else {
					showsetting("允許訪問論壇：", "allowvisitnew", $group[allowvisit], "radio", "選擇「否」將徹底禁止用戶訪問論壇的任何頁面");
				}
				showsetting("允許瀏覽文章：", "allowviewnew", $group[allowview], "radio", "設置是否允許瀏覽沒有設置特殊權限的一般文章");
				showsetting("允許查看統計資料：", "allowviewstatsnew", $group[allowviewstats], "radio", "設置是否允許用戶查看論壇統計資料");
				showsetting("允許使用搜尋：", '', '', "<input type=\"radio\" name=\"allowsearchnew\" value=\"0\" $checksearch[0]> 禁用搜尋<br><input type=\"radio\" name=\"allowsearchnew\" value=\"1\" $checksearch[1]> 只允許搜尋標題<br><input type=\"radio\" name=\"allowsearchnew\" value=\"2\" $checksearch[2]> 允許搜尋文章內容", "設置是否允許論壇文章搜尋功能");
				showsetting("允許使用頭像：", '', '', "<input type=\"radio\" name=\"allowavatarnew\" value=\"0\" $checkavatar[0]> 禁用頭像<br><input type=\"radio\" name=\"allowavatarnew\" value=\"1\" $checkavatar[1]> 允許使用論壇提供頭像<br><input type=\"radio\" name=\"allowavatarnew\" value=\"2\" $checkavatar[2]> 允許自行定義頭像", "設置是否允許使用頭像和可用頭像的類型");
				showsetting("允許自行定義頭銜：", "allowcstatusnew", $group[allowcstatus], "radio", "設置是否允許用戶設置自己的頭銜名字並在文章中顯示");
				showsetting("允許參與評分：", "allowkarmanew", $group[allowkarma], "radio", "設置是否可以給別人的文章評分");
				showsetting("每次最大評價分數：", "maxkarmaratenew", $group[maxkarmarate], "text", "設置每次評分允許的最大分數，需要擁有參與評分的權限才有效。");
				showsetting("每天最大評價分數：", "maxrateperdaynew", $group[maxrateperday], "text", "設置每 24 小時允許評分的最大分數，需要擁有參與評分的權限才有效。");
				showsetting("短訊收件箱容量：", "maxpmnumnew", $group[maxpmnum], "text", "設置用戶短訊最大可保存的訊息數目");
				showsetting("備忘錄容量：", "maxmemonumnew", $group[maxmemonum], "text", "設置用戶備忘錄最大可保存的數目，如為 0 則禁止用戶使用。");

				showtype("文章相關");
				showsetting("允許發表文章：", "allowpostnew", $group[allowpost], "radio", "設置是否允許發新話題或發表回覆");
				showsetting("允許設置文章權限：", "allowsetviewpermnew", $group[allowsetviewperm], "radio", "設置是否允許設置文章需要指定積分以上才可瀏覽");
				showsetting("允許發起投票：", "allowpostpollnew", $group[allowpostpoll], "radio", "設置是否允許發布投票文章");
				showsetting("允許參與投票：", "allowvotenew", $group[allowvote], "radio", "設置是否允許參與論壇的投票");
				showsetting("允許簽名中使用 BB 代碼：", "allowsigbbcodenew", $group[allowsigbbcode], "radio", "設置是否解析用戶簽名中的 BB 代碼");
				showsetting("允許簽名中使用 [img] 代碼：", "allowsigimgcodenew", $group[allowsigimgcode], "radio", "設置是否解析用戶簽名中的 [img] 代碼");
				showsetting("最大簽名長度：", "maxsigsizenew", $group[maxsigsize], "text", "設置用戶簽名最大位元組數");

				showtype("附件相關");
				showsetting("允許下載附件：", "allowgetattachnew", $group[allowgetattach], "radio", "設置是否允許從沒有設置特殊權限的論壇中下載附件");
				showsetting("允許發布附件：", "allowpostattachnew", $group[allowpostattach], "radio", "設置是否允許上傳附件到沒有設置特殊權限的論壇中。需要 PHP 設置允許才有效，請參考系統設置首頁。");
				showsetting("允許設置附件權限：", "allowsetattachpermnew", $group[allowsetattachperm], "radio", "設置是否允許設置附件需要指定積分以上才可下載");
				showsetting("最大附件尺寸：", "maxattachsizenew", $group[maxattachsize], "text", "設置附件最大位元組數，需要 PHP 設置允許才有效，請參考系統設置首頁。");
				showsetting("允許附件類型：", "attachextensionsnew", $group[attachextensions], "text", "設置允許上傳的附件擴展名，多個擴展名之間用半形逗號 \",\" 分割");

				showtype("管理權限");
				showsetting("擁有版主權限：", "ismoderatornew", $group[ismoderator], "radio", "設置是否擁有版主權限");
				showsetting("擁有超級版主權限：", "issupermodnew", $group[issupermod], "radio", "設置是否擁有超級版主權限");
				showsetting("擁有管理員權限：", "isadminnew", $group[isadmin], "radio", "設置是否擁有管理員權限");

				showtype("", "bottom");

				echo "<br><center><input type=\"submit\" name=\"detailsubmit\" value=\"更新權限設置\"><center></form>";

			} else {

				if($isadminnew) {
					$ismoderatornew = $issupermodnew = 1;
				} elseif($issupermodnew) {
					$ismoderatornew = 1;
				}
				$db->query("UPDATE $table_usergroups SET grouptitle='$grouptitlenew', allowvisit='$allowvisitnew',
					allowview='$allowviewnew', allowviewstats='$allowviewstatsnew', allowsearch='$allowsearchnew',
					allowavatar='$allowavatarnew', allowcstatus='$allowcstatusnew', allowkarma='$allowkarmanew',
					maxkarmarate='$maxkarmaratenew', maxrateperday='$maxrateperdaynew', maxpmnum='$maxpmnumnew', allowpost='$allowpostnew',
					maxmemonum='$maxmemonumnew', allowsetviewperm='$allowsetviewpermnew', allowpostpoll='$allowpostpollnew',
					allowvote='$allowvotenew', allowsigbbcode='$allowsigbbcodenew', allowsigimgcode='$allowsigimgcodenew',
					maxsigsize='$maxsigsizenew', allowgetattach='$allowgetattachnew',
					allowpostattach='$allowpostattachnew', allowsetattachperm='$allowsetattachpermnew',
					maxattachsize='$maxattachsizenew', attachextensions='$attachextensionsnew',
					ismoderator='$ismoderatornew', issupermod='$issupermodnew', isadmin='$isadminnew' WHERE groupid='$id'");

				updatecache("usergroups");
				cpmsg("用戶組權限設置成功更新。");

			}

		}

	} else {

		if($type == "member") {
			if($reset != "yes") {
				if($grouptitlenew && ($creditshighernew || $creditslowernew)) {
					$db->query("INSERT INTO $table_usergroups (grouptitle, status, creditshigher, creditslower, stars, groupavatar, allowvisit, allowview, allowpost, allowsigbbcode)
						VALUES ('$grouptitlenew', 'Member', '$creditshighernew', '$creditslowernew', '$starsnew', '$groupavatarnew', '1', '1', '1', '1')");
				}
				if(is_array($group_title)) {
					$ids = $comma = "";
					foreach($group_title as $id => $title) {
						if($delete[$id]) {
							$ids .= "$comma'$id'";
							$comma = ', ';
						} else {
							$db->query("UPDATE $table_usergroups SET grouptitle='$group_title[$id]', creditshigher='$group_creditshigher[$id]', creditslower='$group_creditslower[$id]', stars='$group_stars[$id]', groupavatar='$group_avatar[$id]' WHERE groupid='$id'");
						}
					}
				}
				if($ids) {
					$db->query("DELETE FROM $table_usergroups WHERE groupid IN ($ids)");
				}
			} else {
				if(!$confirmed) {
					cpmsg("本操作不可恢復，您確定要清除現有<br>記錄並把用戶組設定恢復預設嗎？", "admincp.php?action=usergroups&type=member&reset=yes&groupsubmit=yes", "form");
				} else {
					$db->query("DELETE FROM $table_usergroups WHERE status='Member' AND specifiedusers=''");
					$groupreset =
<<<EOT
INSERT INTO cdb_usergroups VALUES ('', '', 'Member', 'Newbie', 0, 50, 1, '', 0, 0, 1, 1, 1, 0, 1, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 20, 0, 80, 0, 0, 0, '');
INSERT INTO cdb_usergroups VALUES ('', '', 'Member', 'Member', 50, 200, 2, '', 0, 1, 1, 1, 1, 1, 1, 0, 1, 1, 0, 0, 0, 1, 0, 1, 0, 0, 0, 30, 0, 100, 4, 10, 0, '');
INSERT INTO cdb_usergroups VALUES ('', '', 'Member', 'Conqueror', 200, 500, 3, '', 0, 2, 1, 1, 1, 1, 1, 0, 1, 2, 0, 0, 0, 1, 0, 1, 0, 0, 0, 50, 0, 150, 6, 15, 256000, 'gif,jpg,png');
INSERT INTO cdb_usergroups VALUES ('', '', 'Member', 'Lord', 500, 1000, 4, '', 1, 2, 1, 1, 1, 1, 1, 1, 1, 2, 1, 0, 0, 1, 0, 1, 0, 0, 0, 60, 0, 200, 10, 30, 512000, 'zip,rar,chm,txt,gif,jpg,png');
INSERT INTO cdb_usergroups VALUES ('', '', 'Member', 'King', 1000, 3000, 6, '', 1, 2, 1, 1, 1, 1, 1, 1, 1, 2, 1, 1, 1, 1, 1, 1, 0, 0, 0, 80, 0, 300, 15, 40, 1024000, '');
INSERT INTO cdb_usergroups VALUES ('', '', 'Member', 'Forum Legend', 3000, 9999999, 8, '', 1, 2, 1, 1, 1, 1, 1, 1, 1, 2, 1, 1, 1, 1, 1, 1, 0, 0, 0, 100, 0, 500, 20, 50, 2048000, '');
INSERT INTO cdb_usergroups VALUES ('', '', 'Member', 'Beggar', -9999999, 0, 0, '', 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '');
EOT;

					$sqlquery = splitsql($groupreset);
					foreach($sqlquery as $sql) {
						$db->query($sql);
					}

					updatecache("usergroups");
					cpmsg("用戶會員組成功恢復。");
				}
			}
		} elseif($type == "specified") {
			if($specifiedusersnew) {
				$specified = trim($specifiedusersnew);
				$comma = ", ";
			} else {
				$specified = $comma = "";
			}
			if(is_array($group_specifiedusers)) {
				foreach($group_specifiedusers as $user) {
					$specified .= $comma.trim($user);
					$comma = ", ";
				}
			}
			$admins = $comma = "";
			$specified = "'".str_replace(",", "', '", str_replace(" ", "", $specified))."'";
			$query = $db->query("SELECT username FROM $table_members WHERE username IN ($specified) AND (status='Admin' OR status='SuperMod' OR status='Moderator')");
			while($member = $db->fetch_array($query)) {
				$admins .= "$comma$member[username]";
				$comma = ", ";
			}
			if($admins) {
				cpmsg("對不起，特殊用戶組中包含管理員或版主($admins)，這可能造成管理權限的遺失，您可以通過其他設置方式達到所需的權限設定，請返回修改。");
			}

			if($grouptitlenew && $specifiedusersnew) {
				$specifiedusersnew = "\t".str_replace(",", "\t", str_replace(" ", "", $specifiedusersnew))."\t";
				$db->query("INSERT INTO $table_usergroups (grouptitle, specifiedusers, status, stars, groupavatar, allowvisit, allowview, allowpost, allowsigbbcode)
					VALUES ('$grouptitlenew', '$specifiedusersnew', 'Member', '$starsnew', '$groupavatarnew', '1', '1', '1', '1')");
			}
			if(is_array($group_title)) {
				$ids = $comma = "";
				foreach($group_title as $id => $title) {
					if($delete[$id]) {
						$ids .= "$comma'$id'";
						$comma = ", ";
					} else {
						$group_specifiedusers[$id] = "\t".str_replace(",", "\t", str_replace(" ", "", $group_specifiedusers[$id]))."\t";
						$db->query("UPDATE $table_usergroups SET grouptitle='$group_title[$id]', specifiedusers='$group_specifiedusers[$id]', stars='$group_stars[$id]', groupavatar='$group_avatar[$id]' WHERE groupid='$id'");
					}
				}
			}
			if($ids) {
				$db->query("DELETE FROM $table_usergroups WHERE groupid IN ($ids)");
			}
		} elseif($type == "system") {
			if(is_array($group_title)) {
				foreach($group_title as $id => $title) {
					$db->query("UPDATE $table_usergroups SET grouptitle='$group_title[$id]', stars='$group_stars[$id]', groupavatar='$group_avatar[$id]' WHERE groupid='$id'");
				}
			}
		}

		updatecache("usergroups");
		cpmsg("用戶組成功更新。如您增加了新的用戶組，<br>請不要忘記修改其相應的權限設置。");
	}

} elseif($action == 'ipban') {

	if(!$ipbansubmit) {

		require $discuz_root.'./include/misc.php';

		$iptoban = explode('.', $extr);

		$ipbanned = '';
		$query = $db->query("SELECT * FROM $table_banned ORDER BY dateline");
		while($banned = $db->fetch_array($query)) {
			for($i = 1; $i <= 4; $i++) {
				if ($banned["ip$i"] == -1) {
					$banned["ip$i"] = '*';
				}
			}
			$ipdate = gmdate("$dateformat $timeformat", $banned[dateline] + $timeoffset * 3600);
			$theip = "$banned[ip1].$banned[ip2].$banned[ip3].$banned[ip4]";
			$ipbanned .= "<tr align=\"center\">\n".
				"<td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[".$banned[id]."]\" value=\"$banned[id]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\">$theip</td>\n".
				"<td bgcolor=\"".ALTBG1."\">".convertip($theip, "./")."</td>\n".
				"<td bgcolor=\"".ALTBG2."\">$banned[admin]</td>\n".
				"<td bgcolor=\"".ALTBG1."\">$ipdate</td></tr>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="75%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>特別提示</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>您的 IP 地址為：<?=$onlineip?></ul>
<ul><li>要禁止某地址段，請在下面地址中該部分用「*」代替。</ul>
</td></tr></table></td></tr></table>

<form method="post" action="admincp.php?action=ipban">
<table cellspacing="0" cellpadding="0" border="0" width="75%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header" align="center">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">刪</td>
<td>IP 地址</td><td>地理位置</td><td>管理員</td><td>加入時間</td></tr>
<?=$ipbanned?>
<tr bgcolor="<?=ALTBG2?>"><td colspan="5" height="1"></td></tr>
<tr bgcolor="<?=ALTBG1?>">
<td colspan="5">禁止新 IP：<b>
<input type="text" name="ip1new" value="<?=$iptoban[0]?>" size="3" maxlength="3"> . 
<input type="text" name="ip2new" value="<?=$iptoban[1]?>" size="3" maxlength="3"> . 
<input type="text" name="ip3new" value="<?=$iptoban[2]?>" size="3" maxlength="3"> . 
<input type="text" name="ip4new" value="<?=$iptoban[3]?>" size="3" maxlength="3"></b></td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="ipbansubmit" value="更新 IP 禁止列表"></center>
</form>
<?

	} else {

		if($ip1new != '' && $ip2new != '' && $ip3new != '' && $ip4new != '') {
			$own = 0;
			$ip = explode('.', $onlineip);
			for($i = 1; $i <= 4; $i++) {
				if(!is_numeric(${'ip'.$i.'new'})) {
					${'ip'.$i.'new'} = -1;
					$own++;
				} elseif(${'ip'.$i.'new'} == $ip[$i - 1]) {
					$own++;
				}
				${'ip'.$i.'new'} = intval(${'ip'.$i.'new'});
			}

			if($own == 4) {
				cpmsg('操作錯誤！您自己的 IP 已經存在於禁止列表中，請返回修改。');
			}

			$query = $db->query("SELECT * FROM $table_banned");
			while($banned = $db->fetch_array($query)) {
				$exists = 0;
				for($i = 1; $i <= 4; $i++) {
					if($banned["ip$i"] == -1) {
						$exists++;
					} elseif($banned["ip$i"] == ${"ip".$i."new"}) {
						$exists++;
					}
				}
				if($exists == 4) {
					cpmsg("新的禁止 IP 已經存在於列表中，請返回。");
				}
			}

			$db->query("DELETE FROM $table_sessions WHERE ip LIKE '".str_replace('-1', '%', "$ip1new.$ip2new.$ip3new.$ip4new")."'");
			$db->query("INSERT INTO $table_banned (ip1, ip2, ip3, ip4, admin, dateline)
				VALUES ('$ip1new', '$ip2new', '$ip3new', '$ip4new', '$discuz_user', '$timestamp')");

		}

		$ids = $comma = '';
		if(is_array($delete)) {
			foreach($delete as $id) {
				$ids .= "$comma'$id'";
				$comma = ', ';
			}
		}
		if($ids) {
			$db->query("DELETE FROM $table_banned WHERE id IN ($ids)");
		}

		cpmsg('IP 禁止列表成功更新。');
	}
	
}

?>