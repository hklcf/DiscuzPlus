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
?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td >Discuz! ���󤤤� v1.0</td></tr>
<tr bgcolor="<?=ALTBG2?>"><td width="100%">������޲z���ߡA�ȶȤ�� Discuz! Plus �}�o�έק�᪺����C</td></tr>
</table></table></table>
<?

if(empty($hackname)) $hackname="home";

if($hackname !="home" ){
	define('IN_ADVANCE_CENTER', TRUE);
	if (@file_exists($discuz_root.'./advcenter/'.$hackname.'_cp.php')) {
		$configfile=$discuz_root.'./advcenter/'.$hackname.'_config.php';
		require $discuz_root.'./advcenter/'.$hackname.'_cp.php';
	}else{
	echo $discuz_root.'advcenter/'.$hackname.'_cp.php';
	cpmsg("�z�S���w�ˬ���������Ϊ̸Ӵ���S����x�޲z������C");
	}
}else{
?>
<br><br><table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">HKLCF ���ܦU�ɪB�ͦ@�P���y Discuz! Plus</td></tr>
<tr bgcolor="<?=ALTBG2?>"><td width=100>�޳N���</td><td><a href="http://discuz.hklcf.com" target="_blank">http://discuz.hklcf.com</a></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>����}�o</td><td><a href="http://hklcf.com" target="_blank">�W��</a></td></tr>
<tr bgcolor="<?=ALTBG2?>"><td>�Ҫ��}�o</td><td><a href="http://hklcf.com" target="_blank">HKLCF</a></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>�{�Ǵ���</td><td><a href="http://discuz.hklcf.com" target="_blank">�W��</a></td></tr>
</table></td></tr></table></td></tr></table>
<?
} function savesettings($filename, $filedate) {
	if(@$fp = fopen("$filename", 'w')) {
		fwrite($fp, "<?php\n//Discuz! Plus Hack config file, DO NOT modify me!\n".
			"//Created on ".date("M j, Y, G:i")."\n\n$filedate\n\n?>");
		fclose($fp);
	} else {
		discuz_exit('Can not write to cache file, please check directory ./advcenter/ .');
	}
}
?>