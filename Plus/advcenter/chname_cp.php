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

	showtype("��W���߫�x�޲z","top");
	showsetting('����(�Фŧ��)', "readonly",$chname[version],"text");

	showtype("��W���߰򥻳]�m");
	showsetting("��W���ߦW�١G", "chname[chname]", $chname[chname], "text", "��W���ߦW�١C");
	showsetting("��W���߶}���G", "chname[chcheck]", $chname[chcheck], "radio", "���'�O'�Y�O������W���ߡC");
	showsetting("�����q���޲z���W�١G", "chname[chadmin]", $chname[chadmin], "text", "<font color=red>�������׾º޲z��</font>");
	showsetting("��W�һݪ����G", "chname[chmoney]", $chname[chmoney], "text", "�|�����@���W�٩һݪ����C(�t�η|�۰ʦ���)");
	showsetting("��W�һݿn���G", "chname[chcredit]", $chname[chcredit], "text", "�|���W�ٻݭn�h�ֿn���H�W�~����C");
	showsetting("��W�O����ܼơG", "chname[chnum]", $chname[chnum], "text", "�|����W�O���C��C����ܦh�ֱ��A�w�]��10");
	showsetting("��W�W�ٳ̤j�ơG", "chname[chul]", $chname[chul], "text", "�|���n�D��蠟�W�٦p�W�L�o�ӼơA�N�����W");
	showsetting("��W�W�ٳ̤p�ơG", "chname[chdl]", $chname[chdl], "text", "�|���n�D��蠟�W�٦p�����o�ӼơA�N�����W");
	showsetting("��W��]�̤p�ơG", "chname[chreason]", $chname[chreason], "text", "�|����W��]�����o�ӼơA�N�����W");

	showtype("", "bottom");
?>
</table></td></tr></table><br><br>
<center>
<BR><BR>
<center><input type="submit" name="savesettings" value="�T�{�ק�"></center>
</form>
</td></tr>
<?
}
else {
	savesettings("$configfile",'$chname='.arrayeval($chname).";\n\n");
	cpmsg("��W���߳]�m��s���\�C");
	}

?>