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

	showtype("�p���ӽЫ�x�޲z","top");
	showsetting('����(�Фŧ��)', "readonly",$link[version],"text");

	showtype("�p���ӽа򥻳]�m");
	showsetting("�u�����ӽСG", "link[pm]", $link[pm], "radio", "���'�O'�Y�ϥεu�����ӽСC");
	showsetting("�u���������H�G", "link[pm_to]", $link[pm_to], "text", "<font color=red>�������׾º޲z��</font>�C");
	showsetting("�q�l�ӽСG", "link[email]", $link[email], "radio", "���'�O'�Y�ϥιq�l�ӽСC");
	showsetting("�����H�q�l�G", "link[email_to]", $link[email_to], "text", "<font color=red>�������׾º޲z��</font>�C");
	showsetting("����²���G", "link[about]", $link[about], "text", "�A������²���C");
	showsetting("Logo�a�}�G", "link[logo]", $link[logo], "text", "�A������Logo�C");
	showtype("", "bottom");
?>
</table></td></tr></table><br><br>
<center>
<BR><BR>
<center><input type="submit" name="savesettings" value="�T�{�ק�"></center>
</form>
</td></tr>
<?
} else {
	savesettings("$configfile",'$link='.arrayeval($link).";\n\n");
	cpmsg("�p���ӽг]�m��s���\�C");
	}
?>