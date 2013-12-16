<?php

if(!defined("IN_DISCUZ") || !defined("IN_ADVANCE_CENTER")) {
	exit("Access Denied");
}
if(!submitcheck($savesettings)) {
	require_once($configfile);
?>
<br><form method="post" action="admincp.php?action=advcenter&hackname=link">
<input type="hidden" name="link[version]" value="<?=$link[version]?>">
<?

	showtype("聯盟申請後台管理","top");
	showsetting('版本(請勿更改)', "readonly",$link[version],"text");

	showtype("聯盟申請基本設置");
	showsetting("短消息申請：", "link[pm]", $link[pm], "radio", "選擇'是'即使用短消息申請。");
	showsetting("短消息接收人：", "link[pm_to]", $link[pm_to], "text", "<font color=red>必須為論壇管理員</font>。");
	showsetting("電郵申請：", "link[email]", $link[email], "radio", "選擇'是'即使用電郵申請。");
	showsetting("接收人電郵：", "link[email_to]", $link[email_to], "text", "<font color=red>必須為論壇管理員</font>。");
	showsetting("網站簡介：", "link[about]", $link[about], "text", "你的網站簡介。");
	showsetting("Logo地址：", "link[logo]", $link[logo], "text", "你的網站Logo。");
	showtype("", "bottom");
?>
</table></td></tr></table><br><br>
<center>
<BR><BR>
<center><input type="submit" name="savesettings" value="確認修改"></center>
</form>
</td></tr>
<?
} else {
	savesettings("$configfile",'$link='.arrayeval($link).";\n\n");
	cpmsg("聯盟申請設置更新成功。");
	}
?>