<?php

/*
	Version: 1.1.4(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/11/08
*/

require "./include/common.php";
if($isadmin or $adminid=='1'){
include template('css');
?>

<style type="text/css">
.bginput { background:#f4f7fb;font-family:tahoma,arial,helvetica,sans-serif;font-size:12px;color: #000000;border-right:3px;border-top:3px;border-left:3px;border-bottom:3px;border:#002040 solid 1px }
</style>
<title>Discuz! Plus 1.1.4 �ɯŵ{��</title>
<table width="580" border="0" cellspacing="1" cellpadding="3" align=center bgcolor="<?=BORDERCOLOR?>"><tr><td class="header">
<table width="100%" cellpadding="0" cellspacing="0"><tr><td class="navtd">
&nbsp;<font class="navtd">Discuz! Plus 1.1.4 �ɯŵ{��</font>
</td></tr></table></td></tr></table>
<table border=0 cellspacing=<?=BORDERWIDTH?> cellpadding=<?=TABLESPACE?> width=580 align=center bgcolor="<?=BORDERCOLOR?>"><tr width=100%><td valign=top width=100% bgcolor=<?=ALTBG1?>>

<?if(empty($step)){?>
<br>&nbsp;&nbsp;<font color=red><b>�`�N�ƶ��R</b></font>���ɯŵ{�ǥu�A�X�q 0723 �����ɯŦ� 1.x �����C<br>&nbsp;&nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;�ɯŧ�����ݨ��x��s�w�s�C<br><br>
<center><input type=button value="�ɯŦw��" class="bginput" onclick="location.href='updata.php?step=update';"></center><br>

<?}elseif($step=='update'){?>
<br>&nbsp;&nbsp; �ɯŦw�˪`�N�ƶ�:<br><br>
<li>�ɯŦw�˾A�X 0723 ������¦�W���ɯ�</li><br><br>
&nbsp;&nbsp;�I�����s�}�l�ɯ�&nbsp;&nbsp;&nbsp;&nbsp;<input type=button value="�}�l�ɯ�" class="bginput" onclick="location.href='updata.php?step=doupdate';">

<?}elseif($step=='doupdate'){
	$stepname = '�ɯ�';
	$db->query("ALTER TABLE `{$tablepre}forums` ADD namecolor varchar(7) NOT NULL default '#000000' ;");
	$db->query("ALTER TABLE `{$tablepre}forums` ADD descolor varchar(7) NOT NULL default '#000000' ;");
	$db->query("UPDATE `{$tablepre}settings` SET `version` = '1.1.4' ;");
	$db->query("ALTER TABLE `{$tablepre}members` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Member' NOT NULL ;");
	$db->query("ALTER TABLE `{$tablepre}usergroups` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Member' NOT NULL ;");
	$db->query("ALTER TABLE `{$tablepre}sessions` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Guest' NOT NULL ;");
	$db->query("INSERT INTO {$tablepre}usergroups VALUES ('', '', 'vip', 'VIP', 0, 0, 7, '', 1, 2, 1, 1, 1, 1, 1, 1, 1, 2, 0, 1, 1, 1, 1, 1, 0, 0, 0, 600, 0, 1000, 0, 0, 1024000, '') ;");
}else{
	echo '<br>&nbsp;&nbsp;���w�q���ާ@�I<br><br><br>';
}
	if($step=='doupdate'){
?>
<meta http-equiv="refresh" content="1;URL=index.php">
<br>&nbsp;&nbsp;<?=$stepname?>�����C<br><br>
<?}?>
</td></tr></table>
<?
}else{
	showmessage('�藍�_�A�A�ëD�޲z���C');
}
?>