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
		showtype('�򥻫H��', "top");
		showsetting('����(�Фŧ��)', "readonly",$antisteal[version],"text");

		showtype('�ϵs�s����޲z����');
		showsetting("�O�_�����ϵs�s:", "antisteal[close]",$antisteal[close], "radio","��O����ϵs�s");
		showsetting("�ϥζ¦W��k:", "antisteal[method]",$antisteal[method], "radio","��O�N�ϥζ¦W��覡�A��_�h�ϥΥզW��");
		showsetting("�¦W��:", "antisteal[black]",$antisteal[black], "textarea", "�Q�C�J�¦W�椧�����s�s�ɱN�|��ܤϵs�s�Ϥ�<br>���}�P���}�����H�r���j�}�C");
		showsetting("�զW��:", "antisteal[white]",$antisteal[white], "textarea", "�զW��H�~�������������¦W��<br>���}�P���}�����H�r���j�}�C");
		showsetting("�ϵs�s�Ϥ�:", "antisteal[pic]",$antisteal[pic], "text", "�Q�s�s�ɡA��ܪ�ĵ�i�Ϥ�");
?>
	</table></td></tr></table>
	</td></tr></table><br><br>
	<center><input type="submit" name="savesettings" value="�T�{�ק�"></center>
	</form>
	
	</td></tr>
<?
} else {
        savesettings("$configfile",'$antisteal='.arrayeval($antisteal).";\n\n");
        cpmsg("�ϵs�s���߳]�m��s���\�C");
}
?>