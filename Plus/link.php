<?php

/*
	Version: 1.1.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/10/15
*/

require './include/common.php';
require_once './advcenter/link_config.php';
$navigation="�p���ӽ�";
$navtitle = " - $navigation";

if(!$discuz_user) {
	showmessage('not_loggedin');
}

if($action==link) {
	$timestamp = time();
	$date = date('m��d�� H:i:s',$timestamp);
	$text = "�����W�١G$link_name \n�Τ�W�G$discuz_userss\n���}�G$link_website \nLogo���}�G$link_logo \nE-mail�G$link_email \n����²���G$link_about \n�ɶ��G$date";
	$subject="$navigation";
if($link[pm] and $link[pm_to]) {
	$db->query("INSERT INTO $table_pm VALUES('$link[pm_to]', '$link[pm_to]', '$discuz_userss', 'inbox', '1', '$subject', '$timestamp', '$text')");
	$db->query("UPDATE $table_members SET newpm='1' WHERE username='$link[pm_to]' LIMIT 1");
}
	if($link[email] and $link[email_to]) {
		sendmail($link[email_to],$subject,$text);
	}
	if((!$link[pm] or !$link[pm_to]))
		showmessage("�藍�_�I�I�ڭ̼Ȯɤ������s���ӽСI�I",'index.php');
		showmessage("�D�`�P�§A�@�N�P�ڭ̥洫�s���A�ڭ̱N���W�B�z�n�A���ӽСA���¡I�I",'index.php');
}

include template('link');
?>