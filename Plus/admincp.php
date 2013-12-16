<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
require $discuz_root.'./include/cache.php';
require $discuz_root.'./admin/global.php';

$discuz_action = 161;

if(@file_exists($discuz_root.'./install.php')) {
	@unlink($discuz_root.'./install.php');
	if(@file_exists($discuz_root.'./install.php')) {
		discuz_exit('�Х� FTP �n��R�� install.php�I');
	}
}

if(@file_exists($discuz_root.'./updata.php')) {
	@unlink($discuz_root.'./updata.php');
	if(@file_exists($discuz_root.'./updata.php')) {
		discuz_exit('�Х� FTP �n��R�� updata.php�I');
	}
}

if(empty($action) || !empty($direct)) {
	$action = empty($action) ? 'main' : $action;
?>

<html>
<head>
<title>Discuz! Plus Administrators' Control Panel</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CHARSET?>">
</head>

<frameset cols="160,*" frameborder="no" border="0" framespacing="0" rows="*"> 
<frame name="menu" noresize scrolling="yes" src="admincp.php?action=menu&sid=<?=$sid?>">
<frameset rows="20,*" frameborder="no" border="0" framespacing="0" cols="*"> 
<frame name="header" noresize scrolling="no" src="admincp.php?action=header&sid=<?=$sid?>">
<frame name="main" noresize scrolling="yes" src="admincp.php?action=<?=$action?>&extr=<?=$extr?>&sid=<?=$sid?>">
</frameset></frameset><noframes></noframes></html>
<?
} elseif($action == 'header') {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CHARSET?>">
<? include template('css'); ?>
</head>

<body leftmargin="0" topmargin="0">
<table cellspacing="0" cellpadding="2" border="0" width="100%" height="100%" bgcolor="<?=ALTBG2?>">
<tr valign="middle" class="smalltxt">
<td width="33%"><a href="http://discuz.hklcf.com" target="_blank">Discuz! Plus <?=$version?> �t�γ]�m</a></td>
<td width="33%" align="center"><a href="http://discuz.hklcf.com" target="_blank">Discuz! Plus �x�����</a></td>
<td width="34%" align="right"><a href="index.php" target="_blank">�׾­���</a></TD>
</tr>
</table>
</body></html>
<?
} elseif($action == 'menu') {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=CHARSET?>">
<? include template('css'); ?>
</head>

<body leftmargin="3" topmargin="3">

<br><table cellspacing="0" cellpadding="0" border="0" width="100%" align="center" style="table-layout: fixed">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table width="100%" border="0" cellspacing="1" cellpadding="0">
<tr><td bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="3" cellpadding="<?=TABLESPACE?>">
<tr><td bgcolor="<?=ALTBG1?>" align="center"><a href="admincp.php?action=menu&expand=1_2_3_4_5_6_7_8_9_10">[EXPAND]</a> &nbsp; <a href="admincp.php?action=menu&expand=0">[REDUCE]</a></td></tr>
<?

		if(preg_match("/(^|_)$change($|_)/is", $expand)) {
			$expandlist = explode('_', $expand);
			$expand = $underline = '';
			foreach($expandlist as $count) {
				if($count != $change) {
					$expand .= $underline.$count;
					$underline = '_';
				}
			}
		} else {
			$expand .= isset($expand) ? '_'.$change : $change;
		}

		if($expand || $expand == '0') {
			setcookie('expand_menu', $expand, $timestamp + 2592000, $cookiepath, $cookiedomain);
		} else {
			$expand = $HTTP_COOKIE_VARS['expand_menu'];
		}

		$pluginsarray = array();
		if(is_array($plugins)) {
			foreach($plugins as $plugin) {
				if($plugin[name] && $plugin[cpurl]) {
					$pluginsarray[] = array('name' => $plugin['name'], 'url' => $plugin['cpurl']);
				}
			}
		}

		$menucount = 0;
		showmenu('����', 'admincp.php?action=main');
		showmenu('Discuz! �ﶵ', 'admincp.php?action=settings');
		showmenu('�׾³]�m', array(	array('name' => '�K�[�׾�', 'url' => 'admincp.php?action=forumadd'),
						array('name' => '�s��׾�', 'url' => 'admincp.php?action=forumsedit'),
						array('name' => '�X�ֽ׾�', 'url' => 'admincp.php?action=forumsmerge')));
		showmenu('�Τ�޲z', array(	array('name' => '�K�[�Τ�', 'url' => 'admincp.php?action=addmember'),
						array('name' => '�s��Τ�', 'url' => 'admincp.php?action=members'),
						array('name' => '�Τ�սs��', 'url' => 'admincp.php?action=usergroups'),
						array('name' => '�o���ƯŧO', 'url' => 'admincp.php?action=ranks'),
						array('name' => '�T�� IP', 'url' => 'admincp.php?action=ipban')));
		showmenu('�ɭ�����', array(	array('name' => '�ɭ�����', 'url' => 'admincp.php?action=styles'),
						array('name' => '�ҪO�s��', 'url' => 'admincp.php?action=templates')));
		showmenu('��L�]�m', array(	array('name' => '�׾¤��i', 'url' => 'admincp.php?action=announcements'),
						array('name' => '�p���׾�', 'url' => 'admincp.php?action=forumlinks'),
						array('name' => '���y�L�o', 'url' => 'admincp.php?action=censor'),
						array('name' => 'Smilies �s��', 'url' => 'admincp.php?action=smilies'),
						array('name' => '�A�Ⱦ��Ѽ�', 'url' => 'admincp.php?action=phpinfo')));
		showmenu('�ƾڮw', array(	array('name' => '��Ƴƥ�', 'url' => 'admincp.php?action=export'),
						array('name' => '��ƫ�_', 'url' => 'admincp.php?action=import'),
						array('name' => '�ƾڮw�ɯ�', 'url' => 'admincp.php?action=runquery'),
						array('name' => '�ƾڪ��u��', 'url' => 'admincp.php?action=optimize')));
		showmenu('�׾º��@', array(	array('name' => '�s�����', 'url' => 'admincp.php?action=attachments'),
						array('name' => '��q�R��', 'url' => 'admincp.php?action=prune'),
						array('name' => '�M�z�u����', 'url' => 'admincp.php?action=pmprune')));
		showmenu('Discuz! �u��', array(	array('name' => '�׾³q��', 'url' => 'admincp.php?action=newsletter'),
						array('name' => '��s�w�s', 'url' => 'admincp.php?action=updatecache'),
						array('name' => '��s�׾²έp', 'url' => 'admincp.php?action=counter')));
		showmenu('�B��O��', array(	array('name' => '�K�X���~�O��', 'url' => 'admincp.php?action=illegallog'),
						array('name' => '�Τ�����O��', 'url' => 'admincp.php?action=karmalog'),
						array('name' => '���D�޲z�O��', 'url' => 'admincp.php?action=modslog'),
						array('name' => '��x�X�ݰO��', 'url' => 'admincp.php?action=cplog'),
						array('name' => '�Ȧ����O��', 'url' => 'admincp.php?action=bankchglog'),
						array('name' => '�n���R��O��', 'url' => 'admincp.php?action=bankbuylog')));
		showmenu('����]�m', $pluginsarray);
		showmenu('�h�X', 'admincp.php?action=logout');

?>
</table></td></tr></table></td></tr></table>

</body>
</html>
<?

} else {

	session_set_cookie_params(0, $cookiepath, $cookiedomain);
	session_name('admin_sid');
	session_start();
	session_register('admin_user', 'admin_pw', 'errorlog');

	$HTTP_SESSION_VARS['admin_user'] = isset($HTTP_POST_VARS['adusername']) ? $HTTP_POST_VARS['adusername'] : $HTTP_SESSION_VARS['admin_user'];
	$HTTP_SESSION_VARS['admin_pw'] = isset($HTTP_POST_VARS['adpassword']) ? md5($HTTP_POST_VARS['adpassword']) : $HTTP_SESSION_VARS['admin_pw'];

	if(!$isadmin || $HTTP_SESSION_VARS['errorlog'] >= 2) {
		clearcookies();
		$discuz_user = $discuz_pw = '';
		$status = 'Guest';
		$groupid = 14;
		$styleid = $_DCACHE['settings']['styleid'];

		cpheader();
		cpmsg("�A�S���v���X�ݨt�γ]�m�C");
	} elseif($HTTP_SESSION_VARS['admin_user'] != $discuz_user || $HTTP_SESSION_VARS['admin_pw'] != $discuz_pw) {
		if($HTTP_SESSION_VARS['admin_user']) {
			$HTTP_SESSION_VARS['errorlog']++;
		}
		$action = empty($action) ? 'main' : $action;
		cpheader();
	if($discuz_secques == '') cpmsg('�@���޲z�̤��@�A�z�ݭn��g�w�����ݩM���ץH�O�ٽ׾ª��w���A�Ъ�^�C');

?>
<br><br><br><br><br><br>
<form method="post" action="admincp.php?action=<?=$action?>&extr=<?=$extr?>">
<input type="hidden" name="adusername" value="<?=$discuz_user?>">
<table cellspacing="0" cellpadding="0" border="0" width="60%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�п�J�z���޲z���K�X</td></tr>
<tr><td bgcolor="<?=ALTBG1?>" width="25%">�Τ�W�G</td><td bgcolor="<?=ALTBG2?>"><?=$discuz_user?> <a href="logging.php?action=logout&referer=index.php" target="_blank">[�h�X�n�J]</a></td></tr>
<tr><td bgcolor="<?=ALTBG1?>" width="25%">�K�X�G</td><td bgcolor="<?=ALTBG2?>"><input type="password" size="25" name="adpassword"></td></tr>
</td></tr></table></td></tr></table>
<br><center><input type="submit" value="�� &nbsp; ��"></center></form>
<br><br>
<?
		cpfooter();
		discuz_exit();
	}
}

if ($action == 'viewattachsize') {

		require_once $discuz_root.'./include/attachment.php';

		$serverinfo = PHP_OS.' / PHP v'.PHP_VERSION;
		$serverinfo .= @ini_get('safe_mode') ? ' �w���Ҧ�' : NULL;
		$dbversion = $db->result($db->query("SELECT VERSION()"), 0);

		if(@ini_get("file_uploads")) {
			$fileupload = "�O: file ".ini_get("upload_max_filesize")." - form ".ini_get("post_max_size");
		} else {
			$fileupload = "<font color=\"red\">�_</font>";
		}

		$forumselect = $groupselect = '';
		$query = $db->query("SELECT groupid, grouptitle FROM $table_usergroups ORDER BY status, creditslower");
		while($group = $db->fetch_array($query)) {
			$groupselect .= "<option value=\"$group[groupid]\">$group[grouptitle]</option>\n";
		}
		$query = $db->query("SELECT fid, name FROM $table_forums WHERE type='forum' OR type='sub'");
		while($forum = $db->fetch_array($query)) {
			$forumselect .= "<option value=\"$forum[fid]\">$forum[name]</option>\n";
		}

		$dbsize = 0;
		$query = $db->query("SHOW TABLE STATUS LIKE '$tablepre%'", 1);
		while($table = $db->fetch_array($query)) {
			$dbsize += $table[Data_length] + $table[Index_length];
		}
		$dbsize = $dbsize ? sizecount($dbsize) : "����";


		$attachsize = dirsize("./$attachdir");
		$attachsize = $attachsize ? sizecount($attachsize) : "����";
		cpheader();

?>
<font class="mediumtxt">
<b> <a href="http://discuz.hklcf.com" target="_blank">Discuz! Plus <?=$version?></a> Administrators' Control Panel</b><br>
Copyright&copy; <a href="http://discuz.hklcf.com" target="_blank">HKLCF.COM</a>, 2004.
<br><br><br><table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="3">�ֱ��覡</td></tr>
<form method="post" action="admincp.php?action=forumdetail"><tr bgcolor="<?=ALTBG2?>"><td>�s��׾�</td>
<td><select name="fid"><?=$forumselect?></select></td><td><input type="submit" value="�� ��"></td></tr></form>
<form method="post" action="admincp.php?action=usergroups&type=detail"><tr bgcolor="<?=ALTBG1?>"><td>�s��Τ��</td>
<td><select name="id"><?=$groupselect?></td><td><input type="submit" value="�� ��"></td></tr></form>
<form method="post" action="admincp.php?action=members"><tr bgcolor="<?=ALTBG2?>"><td>�s��Τ�</td>
<td><input type="text" size="25" name="username"></td><td><input type="submit" name="searchsubmit" value="�� ��"></td></tr></form>
<form method="post" action="admincp.php?action=export&type=mini&saveto=server"><tr bgcolor="<?=ALTBG1?>"><td>��²�ƥ�</td>
<td><input type="text" size="25" name="filename" value="./forumdata/dz_<?=date("md")."_".random(5)?>.sql"></td><td><input type="submit" name="exportsubmit" value="�� ��"></td></tr></form>
</table></td></tr></table></td></tr></table><br><br>
<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�t�ΫH��</td></tr>
<tr bgcolor="<?=ALTBG2?>"><td width="50%">�ާ@�t�Τ� PHP</td><td><?=$serverinfo?></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>MySQL ����</td><td><?=$dbversion?></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>�W�ǳ\�i</td><td><?=$fileupload?></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>��e�ƾڮw�ؤo</td><td><?=$dbsize?></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>��e����ؤo</td><td><?=$attachsize?></td></tr>
</table></td></tr></table></td></tr></table><br><br>
<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">Discuz! Plus �}�o�ζ�</td></tr>
<tr bgcolor="<?=ALTBG2?>"><td width="50%">���~�����P�}�o</td><td><a href="http://www.crossdasy.com" target="_blank">Crossday</a> (3.1.2), <a href="http://discuz.hklcf.com" target="_blank">HKLCF</a> (1.1.0)</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>�������s</td><td><a href="http://discuz.hklcf.com" target="_blank">����HKLCF����</a></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>Hack �P����]�p</td><td><a href="http://discuz.hklcf.com" target="_blank">HKLCF</a></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>�ɭ����u�]�p</td><td><a href="http://hklcf.com" target="_blank">HKLCF</a></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>�x�����</td><td><a href="http://discuz.hklcf.com" target="_blank">http://Discuz.hklcf.com</a></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>�޳N����׾�</td><td><a href="http://discuz.hklcf.com" target="_blank">http://Discuz.hklcf.com</a></td></tr>
</table></td></tr></table></td></tr></table>
<?
}

	if ($action == 'main') {

		require_once $discuz_root.'./include/attachment.php';

		$serverinfo = PHP_OS.' / PHP v'.PHP_VERSION;
		$serverinfo .= @ini_get('safe_mode') ? ' �w���Ҧ�' : NULL;
		$dbversion = $db->result($db->query("SELECT VERSION()"), 0);

		if(@ini_get("file_uploads")) {
			$fileupload = "�O: file ".ini_get("upload_max_filesize")." - form ".ini_get("post_max_size");
		} else {
			$fileupload = "<font color=\"red\">�_</font>";
		}

		$forumselect = $groupselect = '';
		$query = $db->query("SELECT groupid, grouptitle FROM $table_usergroups ORDER BY status, creditslower");
		while($group = $db->fetch_array($query)) {
			$groupselect .= "<option value=\"$group[groupid]\">$group[grouptitle]</option>\n";
		}
		$query = $db->query("SELECT fid, name FROM $table_forums WHERE type='forum' OR type='sub'");
		while($forum = $db->fetch_array($query)) {
			$forumselect .= "<option value=\"$forum[fid]\">$forum[name]</option>\n";
		}

		$dbsize = 0;
		$query = $db->query("SHOW TABLE STATUS LIKE '$tablepre%'", 1);
		while($table = $db->fetch_array($query)) {
			$dbsize += $table[Data_length] + $table[Index_length];
		}
		$dbsize = $dbsize ? sizecount($dbsize) : "����";

		//$attachsize = dirsize("./$attachdir");
		//$attachsize = $attachsize ? sizecount($attachsize) : "����";
		$attachsize = "<a href=\"admincp.php?action=viewattachsize\">�έp��e����ؤo</a>\n";

		cpheader();

?>

<font class="mediumtxt">
<b> <a href="http://discuz.hklcf.com" target="_blank">Discuz! Plus<?=$version?></a> Administrators' Control Panel</b><br>
Copyright&copy; <a href="http://discuz.hklcf.com" target="_blank">HKLCF.COM</a>, 2004.
<br><br><br><table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="3">�ֱ��覡</td></tr>
<form method="post" action="admincp.php?action=forumdetail"><tr bgcolor="<?=ALTBG2?>"><td>�s��׾�</td>
<td><select name="fid"><?=$forumselect?></select></td><td><input type="submit" value="�� ��"></td></tr></form>
<form method="post" action="admincp.php?action=usergroups&type=detail"><tr bgcolor="<?=ALTBG1?>"><td>�s��Τ��</td>
<td><select name="id"><?=$groupselect?></td><td><input type="submit" value="�� ��"></td></tr></form>
<form method="post" action="admincp.php?action=members"><tr bgcolor="<?=ALTBG2?>"><td>�s��Τ�</td>
<td><input type="text" size="25" name="username"></td><td><input type="submit" name="searchsubmit" value="�� ��"></td></tr></form>
<form method="post" action="admincp.php?action=export&type=mini&saveto=server"><tr bgcolor="<?=ALTBG1?>"><td>��²�ƥ�</td>
<td><input type="text" size="25" name="filename" value="./forumdata/dz_<?=date("md")."_".random(5)?>.sql"></td><td><input type="submit" name="exportsubmit" value="�� ��"></td></tr></form>
</table></td></tr></table></td></tr></table><br><br>
<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�t�ΫH��</td></tr>
<tr bgcolor="<?=ALTBG2?>"><td width="50%">�ާ@�t�Τ� PHP</td><td><?=$serverinfo?></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>MySQL ����</td><td><?=$dbversion?></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>�W�ǳ\�i</td><td><?=$fileupload?></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>��e�ƾڮw�ؤo</td><td><?=$dbsize?></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>��e����ؤo</td><td><?=$attachsize?></td></tr>
</table></td></tr></table></td></tr></table><br><br>
<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">Discuz! Plus�}�o�ζ�</td></tr>
<tr bgcolor="<?=ALTBG2?>"><td width="50%">���~�����P�}�o</td><td><a href="http://www.crossdasy.com" target="_blank">Crossday</a> (3.1.2), <a href="http://discuz.hklcf.com" target="_blank">HKLCF</a> (1.1.0)</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>�������s</td><td><a href="http://discuz.hklcf.com" target="_blank">����HKLCF����</a></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>Hack �P����]�p</td><td><a href="http://discuz.hklcf.com" target="_blank">HKLCF</a></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>�ɭ����u�]�p</td><td><a href="http://hklcf.com" target="_blank">HKLCF</a></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>�x�����</td><td><a href="http://discuz.hklcf.com" target="_blank">http://Discuz.hklcf.com</a></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>�޳N����׾�</td><td><a href="http://discuz.hklcf.com" target="_blank">http://Discuz.hklcf.com</a></td></tr>
</table></td></tr></table></td></tr></table>
<?

	} elseif($action == 'settings') {
		require $discuz_root.'./admin/settings.php';
	} elseif($action == 'phpinfo') {
		require $discuz_root.'./admin/phpinfo.php';
	} elseif($action == 'forumadd' || $action == 'forumsedit' || $action == 'forumsmerge' || $action == 'forumdetail' || $action == 'forumdelete') {
		require $discuz_root.'./admin/forums.php';
	} elseif($action == 'addmember' || $action == 'members' || $action == 'memberprofile' || $action == 'usergroups' || $action == 'ipban') {
		require $discuz_root.'./admin/members.php';
	} elseif($action == 'ranks') {
		require $discuz_root.'./admin/rank.php';
	} elseif($action == 'announcements') {
		require $discuz_root.'./admin/announcements.php';
	} elseif($action == 'styles') {
		require $discuz_root.'./admin/styles.php';
	} elseif($action == 'templates' || $action == 'tpladd' || $action == 'tpledit') {
		require $discuz_root.'./admin/templates.php';
	} elseif($action == 'forumlinks' || $action == 'censor' || $action == 'smilies' || $action == 'updatecache' || $action == 'logout') {
		require $discuz_root.'./admin/misc.php';
	} elseif($action == 'export' || $action == 'import' || $action == 'runquery' || $action == 'optimize') {
		require $discuz_root.'./admin/database.php';
	} elseif($action == 'attachments') {
		require $discuz_root.'./admin/attachments.php';
	} elseif($action == 'counter') {
		require $discuz_root.'./admin/counter.php';
	} elseif($action == 'prune' || $action == 'pmprune') {
		require $discuz_root.'./admin/prune.php';
	} elseif($action == 'newsletter') {
		require $discuz_root.'./admin/newsletter.php';
	} elseif($action == 'advcenter') {
		require $discuz_root.'./admin/advcenter.php';
	} elseif($action == 'illegallog' || $action == 'karmalog' || $action == 'modslog' || $action == 'cplog' || $action == 'bankchglog' || $action == 'bankbuylog') {
		require $discuz_root.'./admin/logs.php';
	}

	if($action != 'menu' && $action != 'header') {
		cpfooter();
	}

discuz_output();

?>