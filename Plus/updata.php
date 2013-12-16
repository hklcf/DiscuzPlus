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
<title>Discuz! Plus 1.1.4 升級程序</title>
<table width="580" border="0" cellspacing="1" cellpadding="3" align=center bgcolor="<?=BORDERCOLOR?>"><tr><td class="header">
<table width="100%" cellpadding="0" cellspacing="0"><tr><td class="navtd">
&nbsp;<font class="navtd">Discuz! Plus 1.1.4 升級程序</font>
</td></tr></table></td></tr></table>
<table border=0 cellspacing=<?=BORDERWIDTH?> cellpadding=<?=TABLESPACE?> width=580 align=center bgcolor="<?=BORDERCOLOR?>"><tr width=100%><td valign=top width=100% bgcolor=<?=ALTBG1?>>

<?if(empty($step)){?>
<br>&nbsp;&nbsp;<font color=red><b>注意事項﹕</b></font>此升級程序只適合從 0723 版本升級至 1.x 版本。<br>&nbsp;&nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;升級完畢後需到後台更新緩存。<br><br>
<center><input type=button value="升級安裝" class="bginput" onclick="location.href='updata.php?step=update';"></center><br>

<?}elseif($step=='update'){?>
<br>&nbsp;&nbsp; 升級安裝注意事項:<br><br>
<li>升級安裝適合 0723 版本基礎上的升級</li><br><br>
&nbsp;&nbsp;點擊按鈕開始升級&nbsp;&nbsp;&nbsp;&nbsp;<input type=button value="開始升級" class="bginput" onclick="location.href='updata.php?step=doupdate';">

<?}elseif($step=='doupdate'){
	$stepname = '升級';
	$db->query("ALTER TABLE `{$tablepre}forums` ADD namecolor varchar(7) NOT NULL default '#000000' ;");
	$db->query("ALTER TABLE `{$tablepre}forums` ADD descolor varchar(7) NOT NULL default '#000000' ;");
	$db->query("UPDATE `{$tablepre}settings` SET `version` = '1.1.4' ;");
	$db->query("ALTER TABLE `{$tablepre}members` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Member' NOT NULL ;");
	$db->query("ALTER TABLE `{$tablepre}usergroups` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Member' NOT NULL ;");
	$db->query("ALTER TABLE `{$tablepre}sessions` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Guest' NOT NULL ;");
	$db->query("INSERT INTO {$tablepre}usergroups VALUES ('', '', 'vip', 'VIP', 0, 0, 7, '', 1, 2, 1, 1, 1, 1, 1, 1, 1, 2, 0, 1, 1, 1, 1, 1, 0, 0, 0, 600, 0, 1000, 0, 0, 1024000, '') ;");
}else{
	echo '<br>&nbsp;&nbsp;未定義的操作！<br><br><br>';
}
	if($step=='doupdate'){
?>
<meta http-equiv="refresh" content="1;URL=index.php">
<br>&nbsp;&nbsp;<?=$stepname?>完成。<br><br>
<?}?>
</td></tr></table>
<?
}else{
	showmessage('對不起，你並非管理員。');
}
?>