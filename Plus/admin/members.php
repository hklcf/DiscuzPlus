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

<tr><td class="header" colspan="2">�W�[�s�Τ�</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Y�ΡG</td>
<td align="right" bgcolor="<?=ALTBG2?>"><select name="newstatus">
<option value="Member">�����|��</option>
<option value="vip">VIP</option>
<option value="Moderator">�� &nbsp;&nbsp; �D</option>
<option value="SuperMod">�W�Ū��D</option>
<option value="Inactive">��������</option>
</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Τ�W�G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="newusername"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�K�X�G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="newpassword"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">E-mail�G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="newemail"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�o�e�q����W�z�a�}�G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="checkbox" name="emailnotify" value="yes" checked></td></tr>

</table></td></tr></table>
<br><center><input type="submit" name="addsubmit" value="�W�[�Τ�"></center>
</form>
<?

	} else {

		if(!trim($newpassword)) {
			cpmsg('�z�S����g�Τ�K�X�A�Ъ�^�ק�C');
		}

		if(!trim($newemail)) {
			cpmsg('�z�S����g E-mail �a�}�A�Ъ�^�ק�C');
		}

		$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE username='$newusername'");
		if($db->result($query, 0)) {
			cpmsg('�Τ�W�٤w�g�s�b�A�Ъ�^�ק�C');
		}

		$db->query("INSERT INTO $table_members (username, password, gender, status, regip, regdate, lastvisit, postnum, credit, email, tpp, ppp, styleid, dateformat, timeformat, showemail, newsletter, timeoffset)
			VALUES ('$newusername', '".md5($newpassword)."', '0', '$newstatus', 'hidden', '$timestamp', '$timestamp', '0', '0', '$newemail', '0', '0', '0', '{$_DCACHE[settings][dateformat]}', '{$_DCACHE[settings][timeformat]}', '1', '1', '{$_DCACHE[settings][timeoffset]}')");
		$db->query("UPDATE $table_settings SET lastmember='$newusername', totalmembers=totalmembers+1");

		if($emailnotify == 'yes') {
			sendmail($newemail, "[Discuz!]�z�Q $bbname �W�[���|��", "�z�n�A�ڬO $bbname �޲z�� {$discuz_user}�A\n".
				"�z�w�Q��㦨���ڭ̽׾ª��|���A�w��z����H���b���n�J�����G\n".
				"�Τ�W�١G$newusername\n".
				"�K�X�G$newpassword\n".
				"�w����{ $bbname ($boardurl) �I"
				);
		}

		updatecache('settings');
		cpmsg('�Τ�W�[���\�C');
	}

} elseif($action == 'members') {

	if(!$searchsubmit && !$deletesubmit && !$editsubmit && !$exportsubmit) {

?>
<br><form method="post" action="admincp.php?action=members">
<table cellspacing="0" cellpadding="0" border="0" width="80%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">

<tr><td class="header" colspan="2">�j�M�Τ�</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�����R���ŦX���󪺥Τ�G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="checkbox" name="deletesubmit" value="1"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Y�ΡG</td>
<td align="right" bgcolor="<?=ALTBG2?>"><select name="userstatus">
<option value="">�����Y��</option>
<option value="Admin">�� �z ��</option>
<option value="SuperMod">�W�Ū��D</option>
<option value="Moderator">�� &nbsp;&nbsp; �D</option>
<option value="vip">VIP</option>
<option value="Member">�����|��</option>
<option value="Banned">�T��X��</option>
<option value="PostBanned">�T��o��</option>
</select></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�|���s���G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="uid" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Τ�W�]�t�G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="username" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�n���p��G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="creditsless" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�n���j��G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="creditsmore" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�h�֤ѨS���n�J�׾¡G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="awaydays" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">���U IP �}�Y (�p 202.97)�G</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="regip" size="40"></td></tr>

</table></td></tr></table><br><center>
<input type="submit" name="searchsubmit" value="�j�M�Τ�"> &nbsp; 
<input type="submit" name="deletesubmit" value="�R���Τ�"> &nbsp; 
<input type="submit" name="exportsubmit" value="�ɥX E-mail"></center></form>
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
						"<option value=\"Member\">�����Y��</option>\n".
						"<option value=\"Admin\" ".$select['Admin'].">�� �z ��</option>\n".
						"<option value=\"SuperMod\" ".$select['SuperMod'].">�W�Ū��D</option>\n".
						"<option value=\"Moderator\" ".$select['Moderator'].">�� &nbsp;&nbsp; �D</option>\n".
						"<option value=\"vip\" ".$select['vip'].">VIP</option>\n".
						"<option value=\"Member\" ".$select['Member'].">�����|��</option>\n".
						"<option value=\"Banned\" ".$select['Banned'].">�T��X��</option>\n".
						"<option value=\"PostBanned\" ".$select['PostBanned'].">�T��o��</option></select></td>\n".
						"<td><input type=\"text\" size=\"15\" name=\"usercstatus[$member[uid]]\" value=\"$member[customstatus]\"></td>\n".
						"<td><a href=\"admincp.php?action=memberprofile&username=".rawurlencode($member[username])."\">[�s��]</a></tr>\n";
				}
						
?>
<form method="post" action="admincp.php?action=members">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td class="multi"><?=$multipage?></td></tr>
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr align="center" class="header">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">�R</td>
<td>�Τ�W</td><td>�K�X</td><td>�n��</td><td>�t���Y��</td><td>�Τ��Y��</td><td>�Բ�</td></tr>
<?=$members?>
</table></td></tr>
<tr><td class="multi"><?=$multipage?></td></tr>
</table><br><center>
<input type="submit" name="editsubmit" value="�ק�Τ���"></center>
</form>
<?

			} elseif($deletesubmit) {
				if(!$confirmed) {
					cpmsg("���ާ@���i��_�A�z�T�w�n�R���ŦX���󪺷|���ܡH", "admincp.php?action=members&deletesubmit=yes&username=$username&userstatus=$userstatus&creditsmore=$creditsmore&creditsless=$creditsless&awaydays=$awaydays&regip=$regip", "form");
				} else {
					$query = $db->query("DELETE FROM $table_members WHERE $conditions");
					$numdeleted = $db->affected_rows();
					updatecache('settings');
					cpmsg("�ŦX���� $numdeleted �ӥΤ�Q���\�R���C");
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
<tr class="header"><td>�Τ� E-mail �a�}�ɥX</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td><?=$export?>
</td></tr></table></td></tr></table>
<?

			}
		} else {
			cpmsg("�z�S�����ѷj�M������A�Ъ�^�ק�C");
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
		cpmsg("�ŦX���󪺥Τ�Q���\�s��C");
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

			$styleselect = "<select name=\"styleidnew\">\n<option value=\"\">--�ϥιw�]��--</option>";
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
<tr><td colspan="2" class="header">�s��ӤH��� - �򥻶���</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�t���Y�ΡG</td>
<td bgcolor="<?=ALTBG2?>"><select name="statusnew">
<option value="Member">�����Y��</option>
<option value="Admin" <?=$check[Admin]?>>�� �z ��</option>
<option value="SuperMod" <?=$check[SuperMod]?>>�W�Ū��D</option>
<option value="Moderator" <?=$check[Moderator]?>>�� &nbsp;&nbsp; �D</option>
<option value="vip" <?=$check[vip]?>>VIP</option>
<option value="Member" <?=$check[Member]?>>�����|��</option>
<option value="Banned" <?=$check[Banned]?>>�T��X��</option>
<option value="PostBanned" <?=$check[PostBanned]?>>�T��o��</option>
</select></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Τ�W�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="newusername" size="25" value="<?=$member[username]?>"> �p���O�S�O�ݭn�A�Ф��n�ק�Τ�W��</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�s�K�X�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="password" name="newpassword" size="25"> �п�J�s�K�X�A�p�G�����K�X���B�Яd�šC</td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">E-mail�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="emailnew" size="25" value="<?=$member[email]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�n���G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="creditnew" size="25" value="<?=$member[credit]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�{���G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="moneynew" size="25" value="<?=$member[money]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�s�ڡG</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="banknew" size="25" value="<?=$member[bank]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�o��峹�ơG</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="userpostnum" size="25" value="<?=$member[postnum]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">���U IP�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="regip" size="25" value="<?=$member[regip]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">���U����G</td>
<td bgcolor="<?=ALTBG2?>">
<input type="text" name="ryear" size="4" value="<?=$regdate[0]?>"> �~ 
<input type="text" name="rmonth" size="2" value="<?=$regdate[1]?>"> �� 
<input type="text" name="rday" size="2" value="<?=$regdate[2]?>"> �� </td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�W���X�ݡG</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="lyear" size="4" value="<?=$lastvisittime[0]?>"> �~ 
<input type="text" name="lmonth" size="2" value="<?=$lastvisittime[1]?>"> �� 
<input type="text" name="lday" size="2" value="<?=$lastvisittime[2]?>"> �� </td></tr>

<tr><td colspan="2" class="header">�s��ӤH��� - �i�ﶵ��</td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�D���G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="site" size="25" value="<?=$member[site]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">OICQ�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="oicq" size="25" value="<?=$member[oicq]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">ICQ�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="icq" size="25" value="<?=$member[icq]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">Yahoo�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="yahoo" size="25" value="<?=$member[yahoo]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">MSN�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="msn" size="25" value="<?=$member[msn]?>"/></td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�ӦۡG</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="memlocation" size="25" value="<?=$member[location]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�ͤ�G</td>
<td bgcolor="<?=ALTBG2?>">
<input type="text" name="byear" size="4" value="<?=$bday[0]?>"> �~ 
<input type="text" name="bmonth" size="2" value="<?=$bday[1]?>"> �� 
<input type="text" name="bday" size="2" value="<?=$bday[2]?>"> �� </td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�ӤH²���G</td>
<td bgcolor="<?=ALTBG2?>"><textarea rows="5" cols="30" name="bio"><?=$member[bio]?></textarea></td></tr>

<tr><td colspan="2" class="header">�s��ӤH��� - �׾­өʤƳ]�m</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�ɭ���סG</td>
<td bgcolor="<?=ALTBG2?>"><?=$styleselect?> <?=$currtheme?></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�C���D�D�ơG</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="tppnew" size="4" value="<?=$member[tpp]?>"> </td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�C���峹�ơG</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="pppnew" size="4" value="<?=$member[ppp]?>"> </td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�ɶ��榡�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="radio" value="24" name="timeformatnew" <?=$check24?>> 24 �p�ɨ�
<input type="radio" value="12" name="timeformatnew" <?=$check12?>> 12 �p�ɨ�</td></tr>

<tr><td bgcolor="<?=ALTBG1?>" width="21%">�ۦ�w�q�Y�ΡG</td>
<td bgcolor="<?=ALTBG2?>">
<input type="text" name="cstatus" size="25" value="<?=$member[customstatus]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">����榡<br>(yyyy/mm/dd�Amm/dd/yy ��)�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="dateformatnew" size="25" value="<?=$member[dateformat]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�u�T�����C��G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="ignorepm" size="25" value="<?=$member[ignorepm]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">��L�ﶵ�G</td>
<td bgcolor="<?=ALTBG2?>">
<input type="checkbox" name="showemail" value="1" <?=$emailchecked?>> E-mail �a�}�i��<br>
<input type="checkbox" name="newsletter" value="1" <?=$newschecked?>> ���\�����׾³q�� (E-mail �εu�T)<br>
<input type="text" name="timeoffset1" size="3" value="<?=$member[timeoffset]?>"> �ɶ��ե� (����ɶ� +8)�A�ثeGMT�зǮɶ� 05:52 AM</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Y���a�}�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" name="avatarnew" size="25" value="<?=$member[avatar]?>"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�ӤHñ�W�G</td>
<td bgcolor="<?=ALTBG2?>"><textarea rows="4" cols="30" name="sig"><?=$member[signature]?></textarea></td></tr>

</table></td></tr></table><br>
<center><input type="submit" name="editsubmit" value="�s��ӤH���"></center>
</form><br>
<?

		} else {
			cpmsg("���w�Τᤣ�s�b�C");
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
				$usernameadd = "���s�Τ�W�P�{���Τ�W�٭��ơA�L�k�ק�C";
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
		cpmsg("�Τ��Ʀ��\��s�C$usernameadd");
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
						"<td bgcolor=\"".ALTBG1."\"><a href=\"admincp.php?action=usergroups&type=detail&id=$group[groupid]\">[�Ա�]</a></td></tr>\n";
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
						"<td bgcolor=\"".ALTBG2."\"><a href=\"admincp.php?action=usergroups&type=detail&id=$group[groupid]\">[�Ա�]</a></td></tr>\n";
				} else {
					$sysgroup .= "<tr align=\"center\">\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"12\" name=\"group_title[{$group[groupid]}]\" value=\"$group[grouptitle]\"></td>\n".
						"<td bgcolor=\"".ALTBG1."\">$group[status]</td>\n".
						"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"2\"name=\"group_stars[{$group[groupid]}]\" value=\"$group[stars]\"></td>\n".
						"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"20\" name=\"group_avatar[{$group[groupid]}]\" value=\"$group[groupavatar]\"></td>\n".
						"<td bgcolor=\"".ALTBG2."\"><a href=\"admincp.php?action=usergroups&type=detail&id=$group[groupid]\">[�Ա�]</a></td></tr>\n";
				}
			}
			if($misconfig || $upperlimit < 9999 || $lowerlimit > -999) {
				$warning = "<script>alert('��e�n���]�w�s�b������D�A�Юھڴ��ܺɧ֭ץ��C');</script><span class=\"mediumtxt\"><b>ĵ�i�I</b>�z��e���]�w�å��л\��ӿn���d��(��ĳ -99999 �� 99999)�A�ά۾F��ն��n���W�U���s�b<br>�Żةέ��|�C�ХߧY�����|���ճ]�w�Ϋ�_��w�]�A�_�h�N�ɭP�����Τ�L�k�X�ݽ׾ª��Y�����D�I</span><br><br>";
			}

?>
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>Discuz! �׾ª��Τ�դ����t�βաB�S��թM�|���աA�ϧO�b��T�w�Ҧb�Τ�ժ��覡�G�t�βի��ӥΤ᪺�t���Y�νT�w�F�S��ի��ӫ��w���S�O�Τ�W�T�w�F�|���ի��ӷ|�����n���ӽT�w�C�C�Ӳեi�H���O�]�m�������v���C</ul>
<ul><li>�t�βթM�S��ժ��]�w���ݭn���w�n���ADiscuz! �w�d�F�q�׾º޲z����C�ȵ��� 8 �Өt���Y�ΡA�S��ժ��h�ӥΤ�W�����i�Υb�γr�� "," ���ΡC</ul>
<ul><li>�|���տn���]�w���`��d�򥲶��ມ����ڪ��n�D�A�p -99999 �� 99999�F�ӥB�A���P���դ����n���d�򤣭n�X�{���|�A�_�h�N�X�{�V�áC</ul>
<ul><li>�p�G�z���p�߻~�ާ@�A�ɭP���D�A�i�I���u��_�w�]�v���s�N�]�w��_���l���A�C</ul>
</td></tr></table></td></tr></table>

<form method="post" action="admincp.php?action=usergroups&type=member">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="7">�|���Τ�� - �I�����Y�νs��Բ��v���]�m</td></tr>
<tr class="category" align="center"><td width="45"><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">�R</td>
<td>���Y��</td><td>�n���U��</td><td>�n���W��</td><td>�P�P��</td><td>���Y��</td><td>�s��</td></tr>
<?=$membergroup?>
<tr height="1" bgcolor="<?=ALTBG2?>"><td colspan="7"></td></tr>
<tr align="center" bgcolor="<?=ALTBG1?>"><td>�s�W�G</td>
<td><input type="text" size="12" name="grouptitlenew"></td>
<td><input type="text" size="6" name="creditshighernew"></td>
<td><input type="text" size="6" name="creditslowernew"></td>
<td><input type="text" size="2" name="starsnew"></td>
<td><input type="text" size="20" name="groupavatarnew"></td>
<td>&nbsp;</td>
</tr></table></td></tr></table><br><center><?=$warning?>
<input type="submit" name="groupsubmit" value="�s��|���Τ��">&nbsp;
<input type="button" name="reset" value="��_��w�]�]�w" onClick="top.main.location.href='admincp.php?action=usergroups&type=member&reset=yes&groupsubmit=yes';"></center></form><br><br>

<form method="post" action="admincp.php?action=usergroups&type=specified">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="6">�S��Τ�� - �I�����Y�νs��Բ��v���]�m</td></tr>
<tr class="category" align="center"><td width="45"><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">�R</td>
<td>���Y��</td><td>�]�t�Τ�</td><td>�P�P��</td><td>���Y��</td><td>�s��</td></tr>
<?=$specifiedgroup?>
<tr height="1" bgcolor="<?=ALTBG2?>"><td colspan="6"></td></tr>
<tr align="center" bgcolor="<?=ALTBG1?>"><td>�s�W�G</td>
<td><input type="text" size="12" name="grouptitlenew"></td>
<td><input type="text" size="20" name="specifiedusersnew"></td>
<td><input type="text" size="2" name="starsnew"></td>
<td><input type="text" size="20" name="groupavatarnew"></td>
<td>&nbsp;</td>
</tr></table></td></tr></table><br><center>
<input type="submit" name="groupsubmit" value="�s��S��Τ��"></center></form><br><br>

<form method="post" action="admincp.php?action=usergroups&type=system">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="5">�t�ΥΤ�� �X �I�����Y�νs��Բ��v���]�m</td></tr>
<tr class="category" align="center">
<td>���Y��</td><td>�t���Y��</td><td>�P�P��</td><td>���Y��</td><td>�s��</td></tr>
<?=$sysgroup?>
</table></td></tr></table><br><center>
<input type="submit" name="groupsubmit" value="�s��t�ΥΤ��"></center></form>
<?

		} else {

			if(!$detailsubmit) {
				$query = $db->query("SELECT * FROM $table_usergroups WHERE groupid='$id'");
				$group = $db->fetch_array($query);
				$checksearch = array($group['allowsearch'] => 'checked');
				$checkavatar = array($group['allowavatar'] => 'checked');

				echo "<form method=\"post\" action=\"admincp.php?action=usergroups&type=detail&id=$id\">\n";

				showtype("�s��Τ��", "top");
				showsetting("�Τ���Y��", "grouptitlenew", $group[grouptitle], "text");

				showtype("���v��");
				if($group[status] == "Guest") {
					echo "<input type=\"hidden\" name=\"allowvisitnew\" value=\"1\">\n";
				} else {
					showsetting("���\�X�ݽ׾¡G", "allowvisitnew", $group[allowvisit], "radio", "��ܡu�_�v�N�����T��Τ�X�ݽ׾ª����󭶭�");
				}
				showsetting("���\�s���峹�G", "allowviewnew", $group[allowview], "radio", "�]�m�O�_���\�s���S���]�m�S���v�����@��峹");
				showsetting("���\�d�ݲέp��ơG", "allowviewstatsnew", $group[allowviewstats], "radio", "�]�m�O�_���\�Τ�d�ݽ׾²έp���");
				showsetting("���\�ϥηj�M�G", '', '', "<input type=\"radio\" name=\"allowsearchnew\" value=\"0\" $checksearch[0]> �T�ηj�M<br><input type=\"radio\" name=\"allowsearchnew\" value=\"1\" $checksearch[1]> �u���\�j�M���D<br><input type=\"radio\" name=\"allowsearchnew\" value=\"2\" $checksearch[2]> ���\�j�M�峹���e", "�]�m�O�_���\�׾¤峹�j�M�\��");
				showsetting("���\�ϥ��Y���G", '', '', "<input type=\"radio\" name=\"allowavatarnew\" value=\"0\" $checkavatar[0]> �T���Y��<br><input type=\"radio\" name=\"allowavatarnew\" value=\"1\" $checkavatar[1]> ���\�ϥν׾´����Y��<br><input type=\"radio\" name=\"allowavatarnew\" value=\"2\" $checkavatar[2]> ���\�ۦ�w�q�Y��", "�]�m�O�_���\�ϥ��Y���M�i���Y��������");
				showsetting("���\�ۦ�w�q�Y�ΡG", "allowcstatusnew", $group[allowcstatus], "radio", "�]�m�O�_���\�Τ�]�m�ۤv���Y�ΦW�r�æb�峹�����");
				showsetting("���\�ѻP�����G", "allowkarmanew", $group[allowkarma], "radio", "�]�m�O�_�i�H���O�H���峹����");
				showsetting("�C���̤j�������ơG", "maxkarmaratenew", $group[maxkarmarate], "text", "�]�m�C���������\���̤j���ơA�ݭn�֦��ѻP�������v���~���ġC");
				showsetting("�C�ѳ̤j�������ơG", "maxrateperdaynew", $group[maxrateperday], "text", "�]�m�C 24 �p�ɤ��\�������̤j���ơA�ݭn�֦��ѻP�������v���~���ġC");
				showsetting("�u�T����c�e�q�G", "maxpmnumnew", $group[maxpmnum], "text", "�]�m�Τ�u�T�̤j�i�O�s���T���ƥ�");
				showsetting("�Ƨѿ��e�q�G", "maxmemonumnew", $group[maxmemonum], "text", "�]�m�Τ�Ƨѿ��̤j�i�O�s���ƥءA�p�� 0 �h�T��Τ�ϥΡC");

				showtype("�峹����");
				showsetting("���\�o��峹�G", "allowpostnew", $group[allowpost], "radio", "�]�m�O�_���\�o�s���D�εo��^��");
				showsetting("���\�]�m�峹�v���G", "allowsetviewpermnew", $group[allowsetviewperm], "radio", "�]�m�O�_���\�]�m�峹�ݭn���w�n���H�W�~�i�s��");
				showsetting("���\�o�_�벼�G", "allowpostpollnew", $group[allowpostpoll], "radio", "�]�m�O�_���\�o���벼�峹");
				showsetting("���\�ѻP�벼�G", "allowvotenew", $group[allowvote], "radio", "�]�m�O�_���\�ѻP�׾ª��벼");
				showsetting("���\ñ�W���ϥ� BB �N�X�G", "allowsigbbcodenew", $group[allowsigbbcode], "radio", "�]�m�O�_�ѪR�Τ�ñ�W���� BB �N�X");
				showsetting("���\ñ�W���ϥ� [img] �N�X�G", "allowsigimgcodenew", $group[allowsigimgcode], "radio", "�]�m�O�_�ѪR�Τ�ñ�W���� [img] �N�X");
				showsetting("�̤jñ�W���סG", "maxsigsizenew", $group[maxsigsize], "text", "�]�m�Τ�ñ�W�̤j�줸�ռ�");

				showtype("�������");
				showsetting("���\�U������G", "allowgetattachnew", $group[allowgetattach], "radio", "�]�m�O�_���\�q�S���]�m�S���v�����׾¤��U������");
				showsetting("���\�o������G", "allowpostattachnew", $group[allowpostattach], "radio", "�]�m�O�_���\�W�Ǫ����S���]�m�S���v�����׾¤��C�ݭn PHP �]�m���\�~���ġA�аѦҨt�γ]�m�����C");
				showsetting("���\�]�m�����v���G", "allowsetattachpermnew", $group[allowsetattachperm], "radio", "�]�m�O�_���\�]�m����ݭn���w�n���H�W�~�i�U��");
				showsetting("�̤j����ؤo�G", "maxattachsizenew", $group[maxattachsize], "text", "�]�m����̤j�줸�ռơA�ݭn PHP �]�m���\�~���ġA�аѦҨt�γ]�m�����C");
				showsetting("���\���������G", "attachextensionsnew", $group[attachextensions], "text", "�]�m���\�W�Ǫ������X�i�W�A�h���X�i�W�����Υb�γr�� \",\" ����");

				showtype("�޲z�v��");
				showsetting("�֦����D�v���G", "ismoderatornew", $group[ismoderator], "radio", "�]�m�O�_�֦����D�v��");
				showsetting("�֦��W�Ū��D�v���G", "issupermodnew", $group[issupermod], "radio", "�]�m�O�_�֦��W�Ū��D�v��");
				showsetting("�֦��޲z���v���G", "isadminnew", $group[isadmin], "radio", "�]�m�O�_�֦��޲z���v��");

				showtype("", "bottom");

				echo "<br><center><input type=\"submit\" name=\"detailsubmit\" value=\"��s�v���]�m\"><center></form>";

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
				cpmsg("�Τ���v���]�m���\��s�C");

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
					cpmsg("���ާ@���i��_�A�z�T�w�n�M���{��<br>�O���ç�Τ�ճ]�w��_�w�]�ܡH", "admincp.php?action=usergroups&type=member&reset=yes&groupsubmit=yes", "form");
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
					cpmsg("�Τ�|���զ��\��_�C");
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
				cpmsg("�藍�_�A�S��Τ�դ��]�t�޲z���Ϊ��D($admins)�A�o�i��y���޲z�v�����򥢡A�z�i�H�q�L��L�]�m�覡�F��һݪ��v���]�w�A�Ъ�^�ק�C");
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
		cpmsg("�Τ�զ��\��s�C�p�z�W�[�F�s���Τ�աA<br>�Ф��n�ѰO�ק��������v���]�m�C");
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
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>�z�� IP �a�}���G<?=$onlineip?></ul>
<ul><li>�n�T��Y�a�}�q�A�Цb�U���a�}���ӳ����Ρu*�v�N���C</ul>
</td></tr></table></td></tr></table>

<form method="post" action="admincp.php?action=ipban">
<table cellspacing="0" cellpadding="0" border="0" width="75%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header" align="center">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">�R</td>
<td>IP �a�}</td><td>�a�z��m</td><td>�޲z��</td><td>�[�J�ɶ�</td></tr>
<?=$ipbanned?>
<tr bgcolor="<?=ALTBG2?>"><td colspan="5" height="1"></td></tr>
<tr bgcolor="<?=ALTBG1?>">
<td colspan="5">�T��s IP�G<b>
<input type="text" name="ip1new" value="<?=$iptoban[0]?>" size="3" maxlength="3"> . 
<input type="text" name="ip2new" value="<?=$iptoban[1]?>" size="3" maxlength="3"> . 
<input type="text" name="ip3new" value="<?=$iptoban[2]?>" size="3" maxlength="3"> . 
<input type="text" name="ip4new" value="<?=$iptoban[3]?>" size="3" maxlength="3"></b></td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="ipbansubmit" value="��s IP �T��C��"></center>
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
				cpmsg('�ާ@���~�I�z�ۤv�� IP �w�g�s�b��T��C���A�Ъ�^�ק�C');
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
					cpmsg("�s���T�� IP �w�g�s�b��C���A�Ъ�^�C");
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

		cpmsg('IP �T��C���\��s�C');
	}
	
}

?>