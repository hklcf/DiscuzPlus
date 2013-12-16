<?php

if(!defined("IN_DISCUZ") || !defined("IN_ADVANCE_CENTER")) {
	exit("Access Denied");
}
if(!submitcheck($savesettings)) {
	require_once($configfile);
?>
<br><form method="post" action="admincp.php?action=advcenter&hackname=chname">
<input type="hidden" name="chname[version]" value="<?=$chname[version]?>">
<?

	showtype("改名中心後台管理","top");
	showsetting('版本(請勿更改)', "readonly",$chname[version],"text");

	showtype("改名中心基本設置");
	showsetting("改名中心名稱：", "chname[chname]", $chname[chname], "text", "改名中心名稱。");
	showsetting("改名中心開關：", "chname[chcheck]", $chname[chcheck], "radio", "選擇'是'即是關閉改名中心。");
	showsetting("接受通知管理員名稱：", "chname[chadmin]", $chname[chadmin], "text", "<font color=red>必須為論壇管理員</font>");
	showsetting("改名所需金錢：", "chname[chmoney]", $chname[chmoney], "text", "會員更改一次名稱所需金錢。(系統會自動扣除)");
	showsetting("改名所需積分：", "chname[chcredit]", $chname[chcredit], "text", "會員名稱需要多少積分以上才能更改。");
	showsetting("改名記錄顯示數：", "chname[chnum]", $chname[chnum], "text", "會員改名記錄列表每頁顯示多少條，預設為10");
	showsetting("改名名稱最大數：", "chname[chul]", $chname[chul], "text", "會員要求更改之名稱如超過這個數，將不能改名");
	showsetting("改名名稱最小數：", "chname[chdl]", $chname[chdl], "text", "會員要求更改之名稱如不足這個數，將不能改名");
	showsetting("改名原因最小數：", "chname[chreason]", $chname[chreason], "text", "會員改名原因不足這個數，將不能改名");

	showtype("", "bottom");
?>
</table></td></tr></table><br><br>
<center>
<BR><BR>
<center><input type="submit" name="savesettings" value="確認修改"></center>
</form>
</td></tr>
<?
}
else {
	savesettings("$configfile",'$chname='.arrayeval($chname).";\n\n");
	cpmsg("改名中心設置更新成功。");
	}

?>