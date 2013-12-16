<?php

/*
	Version: 1.1.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/10/15
*/

require './include/common.php';
require_once './advcenter/link_config.php';
$navigation="聯盟申請";
$navtitle = " - $navigation";

if(!$discuz_user) {
	showmessage('not_loggedin');
}

if($action==link) {
	$timestamp = time();
	$date = date('m月d日 H:i:s',$timestamp);
	$text = "網站名稱：$link_name \n用戶名：$discuz_userss\n網址：$link_website \nLogo網址：$link_logo \nE-mail：$link_email \n網站簡介：$link_about \n時間：$date";
	$subject="$navigation";
if($link[pm] and $link[pm_to]) {
	$db->query("INSERT INTO $table_pm VALUES('$link[pm_to]', '$link[pm_to]', '$discuz_userss', 'inbox', '1', '$subject', '$timestamp', '$text')");
	$db->query("UPDATE $table_members SET newpm='1' WHERE username='$link[pm_to]' LIMIT 1");
}
	if($link[email] and $link[email_to]) {
		sendmail($link[email_to],$subject,$text);
	}
	if((!$link[pm] or !$link[pm_to]))
		showmessage("對不起！！我們暫時不接受新的申請！！",'index.php');
		showmessage("非常感謝你願意與我們交換連結，我們將馬上處理好你的申請，謝謝！！",'index.php');
}

include template('link');
?>