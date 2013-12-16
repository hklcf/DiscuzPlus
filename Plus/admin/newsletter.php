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

if(!$newslettersubmit) {

	$count = 0;
	$usergroups = '';
	$query = $db->query("SELECT groupid, grouptitle FROM $table_usergroups WHERE status<>'Member' AND status<>'Guest' ORDER BY groupid");
	while($group = $db->fetch_array($query)) {
		$usergroups .= ($count++ % 3 == 0 ? '</tr><tr>' : '').
		"<td width=\"33%\" nowrap><input type=\"checkbox\" name=\"sendto[]\" value=\"$group[groupid]\"> $group[grouptitle]</td>";
	}

	$query_m = $db->query("SELECT groupid FROM $table_usergroups WHERE status='Member'");
	$n=0;
	while($group_m = $db->fetch_array($query_m)) {
		$sendtoo[$n] = $group_m['groupid'];
		$n++;
	}
	$sendtoo = ''.implode(',', $sendtoo).'';
	$usergroups .= "<td width=\"33%\" nowrap><input type=\"checkbox\" name=\"sendtoo[]\" value=\"$sendtoo\"> Members</td>";

?>
<br><br><form method="post" action="admincp.php?action=newsletter">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�׾³q��</td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>" valign="top">����H�G
<br><input type="checkbox" name="nlstatus" value="online"> �b�u�Τ�
</select>
</td><td bgcolor="<?=ALTBG2?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr><?=$usergroups?>
</tr>
</table>
</td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>">�D�D�G</td><td bgcolor="<?=ALTBG2?>"><input type="text" name="newssubject" size="70"></td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>" valign="top">���e�G</td><td bgcolor="<?=ALTBG2?>">
<textarea cols="70" rows="10" name="newsmessage"></textarea></td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>">�q�L�G</td><td bgcolor="<?=ALTBG2?>"><input type="radio" value="email" checked name="sendvia"> Email
<input type="radio" value="pm" checked name="sendvia"> �u����</td></tr>

</table></td></tr></table><br>
<center><input type="submit" name="newslettersubmit" value="�o�e�q��"></center>
</form>
<?

} else {

	if($newssubject && $newsmessage) {
		$newssubject = "[�t�γq��] ".$newssubject;
		if($nlstatus == "online") {
			if($sendto && $sendtoo) {
			if(is_array($sendto)) {
			$ids = '\''.implode('\',\'', $sendto).'\'';
		}
			$ids .= ", ";
			$ids .= '\''.implode('\',\'', $sendtoo).'\'';
		} elseif ($sendto && !$sendtoo) {
			if(is_array($sendto)) {
			$ids = '\''.implode('\',\'', $sendto).'\'';
		}
	} else {
		$ids = '\''.implode('\',\'', $sendtoo).'\'';
		}
		$query = $db->query("SELECT DISTINCT(m.username), m.email FROM $table_sessions s, $table_members m LEFT JOIN $table_usergroups u ON m.status = u.status WHERE s.username<>'' AND u.groupid IN ($ids) AND m.username=s.username AND m.newsletter='1'");
	} else {
		if($sendto && $sendtoo) {
		if(is_array($sendto)) {
		$ids = '\''.implode('\',\'', $sendto).'\'';
		}
		$ids .= ", ";
		$ids .= '\''.implode('\',\'', $sendtoo).'\'';
	} elseif ($sendto && !$sendtoo) {
		if(is_array($sendto)) {
		$ids = '\''.implode('\',\'', $sendto).'\'';
		}
	} else {
		$ids = '\''.implode('\',\'', $sendtoo).'\'';
	}
		$query = $db->query("SELECT DISTINCT(m.username), m.uid, m.email FROM $table_members m LEFT JOIN $table_usergroups u ON m.status = u.status WHERE u.groupid IN ($ids) AND newsletter='1'");
	}

	$emails = $sendto = $comma = '';
	while($memnews = $db->fetch_array($query)) {
		if($sendvia == 'pm') {
			$sendto .= "$comma'$memnews[username]'";
			$comma = ", ";
			$db->query("INSERT INTO $table_pm (msgto, msgfrom, folder, new, subject, dateline, message)
			VALUES('$memnews[username]', '$discuz_user', 'inbox', '1', '$newssubject', '$timestamp', '$newsmessage')"); 
		} else {
			$emails .= $comma.$memnews[email];
			$comma = ',';
		}
	}
	if($sendvia == "email") {
		sendmail($emails, $newssubject, $newsmessage);
		cpmsg("�׾³q�����\�o�e�C");
	} else {
		if($sendto) {
			$db->query("UPDATE $table_members SET newpm='1' WHERE username IN ($sendto)");
		}
		cpmsg("�׾³q�����\�o�e�C");
		}
	} else {
		cpmsg("�z�S����J���������D�Τ��e�A�Ъ�^�ק�C");
	}

}

?>