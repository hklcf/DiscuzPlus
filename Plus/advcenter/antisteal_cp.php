<?php
if(!defined("IN_DISCUZ") || !defined("IN_ADVANCE_CENTER")) {
	exit("Access Denied");
}
if(!submitcheck($savesettings)) {
	require_once($configfile);
?>
<br><form method="post" method=post action="admincp.php?action=advcenter&hackname=antisteal">
<input type="hidden" name="antisteal[version]" value="<?=$antisteal[version]?>">
<?
		showtype('基本信息', "top");
		showsetting('版本(請勿更改)', "readonly",$antisteal[version],"text");

		showtype('反盜連插件管理中心');
		showsetting("是否關閉反盜連:", "antisteal[close]",$antisteal[close], "radio","選是停止反盜連");
		showsetting("使用黑名單法:", "antisteal[method]",$antisteal[method], "radio","選是將使用黑名單方式，選否則使用白名單");
		showsetting("黑名單:", "antisteal[black]",$antisteal[black], "textarea", "被列入黑名單之網站盜連時將會顯示反盜連圖片<br>網址與網址之間以逗號隔開。");
		showsetting("白名單:", "antisteal[white]",$antisteal[white], "textarea", "白名單以外的網站均視為黑名單<br>網址與網址之間以逗號隔開。");
		showsetting("反盜連圖片:", "antisteal[pic]",$antisteal[pic], "text", "被盜連時，顯示的警告圖片");
?>
	</table></td></tr></table>
	</td></tr></table><br><br>
	<center><input type="submit" name="savesettings" value="確認修改"></center>
	</form>
	
	</td></tr>
<?
} else {
        savesettings("$configfile",'$antisteal='.arrayeval($antisteal).";\n\n");
        cpmsg("反盜連中心設置更新成功。");
}
?>