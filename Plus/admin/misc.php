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

if($action == "forumlinks") {

	if(!$forumlinksubmit) {

		$forumlinks = "";
		$query = $db->query("SELECT * FROM $table_forumlinks ORDER BY displayorder");
		while($forumlink = $db->fetch_array($query)) {
			$forumlink[note]=htmlspecialchars($forumlink[note]);
			$forumlinks .= "<tr bgcolor=\"".ALTBG2."\" align=\"center\">\n".
				"<td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[]\" value=\"$forumlink[id]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"3\" name=\"displayorder[$forumlink[id]]\" value=\"$forumlink[displayorder]\"></td>\n".
				"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"15\" name=\"name[$forumlink[id]]\"	value=\"$forumlink[name]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"15\" name=\"url[$forumlink[id]]\" value=\"$forumlink[url]\"></td>\n".
				"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"15\" name=\"note[$forumlink[id]]\"	value=\"$forumlink[note]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"15\" name=\"logo[$forumlink[id]]\"	value=\"$forumlink[logo]\"></td></tr>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>�p�G�z���Q�b��������p���׾¡A�Ч�w���U���R���Y�i�C</ul>
<ul><li>����g��r���������رN�H��ꫬ��ܡC</ul>
</td></tr></table></td></tr></table>

<br><form method="post"	action="admincp.php?action=forumlinks">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td	bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="6">�p���׾½s��</td></tr>
<tr align="center" class="category">
<td><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">�R</td>
<td>���Ǹ�</td><td>�׾¦W��</td><td>�׾� URL</td><td>��r����</td>
<td>logo �a�}(�i��)</td></tr>
<?=$forumlinks?>
<tr bgcolor="<?=ALTBG2?>"><td colspan="6" height="1"></td></tr>
<tr bgcolor="<?=ALTBG1?>" align="center">
<td>�s�W�G</td>
<td><input type="text" size="3" name="newdisplayorder"></td>
<td><input type="text" size="15" name="newname"></td>
<td><input type="text" size="15" name="newurl"></td>
<td><input type="text" size="15" name="newnote"></td>
<td><input type="text" size="15" name="newlogo"></td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="forumlinksubmit" value="��s�C��"></center></form></td></tr>
<?

	} else {

		if(is_array($delete)) {
			$ids = $comma =	"";
			foreach($delete	as $id)	{
				$ids .=	"$comma'$id'";
				$comma = ", ";
			}
			$db->query("DELETE FROM	$table_forumlinks WHERE	id IN ($ids)");
		}

		if(is_array($name)) {
			foreach($name as $id =>	$val) {
				$db->query("UPDATE $table_forumlinks SET displayorder='$displayorder[$id]', name='$name[$id]', url='$url[$id]',	note='$note[$id]', logo='$logo[$id]' WHERE id='$id'");
			}
		}

		if($newname != "") {
			$db->query("INSERT INTO	$table_forumlinks (displayorder, name, url, note, logo)	VALUES ('$newdisplayorder', '$newname',	'$newurl', '$newnote', '$newlogo')");
		}

		updatecache("forumlinks");
		cpmsg("�p���׾¦��\��s�C");
	}

} elseif($action == "censor") {

	if(!$censorsubmit) {

		$censorwords = "";
		$query = $db->query("SELECT * FROM $table_words");
		while($censor =	$db->fetch_array($query)) {
			$censorwords .=	"<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[]\" value=\"$censor[id]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\"><input	type=\"text\" size=\"30\" name=\"find[$censor[id]]\" value=\"$censor[find]\"></td>\n".
				"<td bgcolor=\"".ALTBG1."\"><input	type=\"text\" size=\"30\" name=\"replace[$censor[id]]\"	value=\"$censor[replacement]\"></td></tr>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="80%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>�����������e���i�H�ϥ� html	�N�X�C</ul>
<ul><li>�����v�T�{���Ĳv�A�Ф��n�]�m�L�h���ݭn���L�o���e�C</ul>
</td></tr></table></td></tr></table>

<br><form method="post"	action="admincp.php?action=censor">
<table cellspacing="0" cellpadding="0" border="0" width="80%" align="center">
<tr><td	bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr align="center" class="header"><td width="45"><input	type="checkbox"	name="chkall" class="header" onclick="checkall(this.form)">�R</td>
<td>���}���y</td><td>������</td></tr>
<?=$censorwords?>
<tr bgcolor="<?=ALTBG2?>"><td colspan="3" height="1"></td></tr>
<tr bgcolor="<?=ALTBG1?>">
<td align="center">�s�W�G</td>
<td align="center"><input type="text" size="30"	name="newfind"></td>
<td align="center"><input type="text" size="30"	name="newreplace"></td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="censorsubmit" value="��s�C��"></center>
</form>
<?

	} else {

		if(is_array($delete)) {
			$ids = $comma =	"";
			foreach($delete	as $id)	{
				$ids .=	"$comma'$id'";
				$comma = ", ";
			}
			$db->query("DELETE FROM	$table_words WHERE id IN ($ids)");
		}

		if(is_array($find)) {
			foreach($find as $id =>	$val) {
				$db->query("UPDATE $table_words	SET find='$find[$id]', replacement='$replace[$id]' WHERE id='$id'");
			}
		}

		if($newfind != "") {
			$db->query("INSERT INTO	$table_words (find, replacement) VALUES	('$newfind', '$newreplace')");
		}

		updatecache("censor");
		cpmsg("���y�L�o���\��s�C");

	}

} elseif($action == "smilies") {

	if(!$smiliesubmit) {

		$smilies = $picons = "";
		$query = $db->query("SELECT * FROM $table_smilies");
		while($smiley =	$db->fetch_array($query)) {
			if($smiley[type] == "smiley") {
				$smilies .= "<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[]\" value=\"$smiley[id]\"></td>\n".
					"<td bgcolor=\"".ALTBG2."\"><input	type=\"text\" size=\"25\" name=\"code[$smiley[id]]\" value=\"$smiley[code]\"></td>\n".
					"<td bgcolor=\"".ALTBG1."\"><input	type=\"text\" size=\"25\" name=\"url[$smiley[id]]\" value=\"$smiley[url]\"></td>\n".
					"<td bgcolor=\"".ALTBG2."\"><input	type=\"hidden\"	name=\"type[$smiley[id]]\" value=\"$smiley[type]\"><img	src=\"./".SMDIR."/$smiley[url]\"></td></tr>\n";
			} elseif($smiley[type] == "picon") {
				$picons	.= "<tr	align=\"center\"><td bgcolor=\"".ALTBG1."\"><input	type=\"checkbox\" name=\"delete[]\" value=\"$smiley[id]\"></td>\n".
					"<td colspan=\"2\" bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"35\"	name=\"url[$smiley[id]]\" value=\"$smiley[url]\"></td>\n".
					"<td bgcolor=\"".ALTBG1."\"><input	type=\"hidden\"	name=\"type[$smiley[id]]\" value=\"$smiley[type]\"><img	src=\"./".SMDIR."/$smiley[url]\"></td></tr>\n";
			}
		}

?>
<form method="post" action="admincp.php?action=smilies">
<table cellspacing="0" cellpadding="0" border="0" width="80%" align="center">
<tr><td	bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="4" align="left">Smilies	�s��</td></tr>
<tr align="center" class="category">
<td width="45">�R</td>
<td>Smilies �N�X</td><td>Smilies �W��</td><td>Smilies �Ϥ�</td></tr>
<?=$smilies?>
<tr><td	bgcolor="<?=ALTBG2?>" colspan="4" height="1"></td></tr>
<tr bgcolor="<?=ALTBG1?>" align="center"><td>�W�[�G</td>
<td><input type="text" size="25" name="newcode"></td>
<td><input type="text" size="25" name="newurl1"></td>
<td></td></tr><tr>
<td bgcolor="<?=ALTBG2?>" colspan="4" height="1"></td></tr>
<tr><td	colspan="4" class="header">�D�D�ϥ�</td></tr>
<tr align="center" class="category">
<td width="45">�R</td>
<td colspan="2">�ϥܦW��</td><td>�D�D�ϥ�</td></tr>
<?=$picons?>
<tr><td	bgcolor="<?=ALTBG2?>" colspan="4" height="1"></td></tr>
<tr bgcolor="<?=ALTBG1?>" align="center">
<td>�W�[�G</td><td colspan="2"><input type="text" name="newurl2" size="35"></td><td>&nbsp;</td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="smiliesubmit" value="�s�� Smilies �]�m"></center></form>
<?

	} else {

		if(is_array($delete)) {
			$ids = $comma =	"";
			foreach($delete	as $id)	{
				$ids .=	"$comma'$id'";
				$comma = ", ";
			}
			$db->query("DELETE FROM	$table_smilies WHERE id	IN ($ids)");
		}

		if(is_array($url)) {
			foreach($url as	$id => $val) {
				$db->query("UPDATE $table_smilies SET type='$type[$id]', code='$code[$id]', url='$url[$id]' WHERE id='$id'");
			}
		}

		if($newcode != "") {
			$query = $db->query("INSERT INTO $table_smilies	(type, code, url)
				VALUES ('smiley', '$newcode', '$newurl1')");
		}
		if($newurl2 != "") {
			$query = $db->query("INSERT INTO $table_smilies	(type, code, url)
				VALUES ('picon', '', '$newurl2')");
		}

		updatecache("smilies");
		updatecache("picons");
		cpmsg("Smilies �C���\��s�C");

	}

} elseif($action == 'updatecache') {

	updatecache();
	$tpl = dir($discuz_root.'./forumdata/templates');
	while($entry = $tpl->read()) {
		if (strpos($entry, '.tpl.php')) {
			@unlink($discuz_root.'./forumdata/templates/'.$entry);
		}
	}
	$tpl->close();

	cpmsg("�����w�s��s�����C");

}elseif($action == 'logout') {

	session_unregister('admin_user');
	session_unregister('admin_pw');
	cpmsg('�z�w���\�h�X�t�γ]�m�C');
}

?>