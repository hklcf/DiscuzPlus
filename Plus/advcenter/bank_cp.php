<?php
if(!defined("IN_DISCUZ") || !defined("IN_ADVANCE_CENTER")) {
	exit("Access Denied");
}
if(!submitcheck($savesettings)) {
	require_once($configfile);
	$banksettings['groupname']=$cnteacher_bankgroup;
?>
<br><form method="post" action="admincp.php?action=advcenter&hackname=bank">
<input type="hidden" name="banksettings[version]" value="<?=$banksettings[version]?>">
<?
	if ($banksettings[message]){
		$banksettings[message]=stripcslashes($banksettings[message]);
		$banksettings[message]=str_replace("<br>", "\n", $banksettings[message]);
	}

	showtype('�Ȧ�򥻫H��', "top");
	showsetting('����(�Фŧ��)', "readonly",$banksettings[version],"text");

showtype("�Ȧ�򥻳]�m");
	showsetting("�f�����W�١G", "banksettings[moneyname]",$banksettings[moneyname], "text", "�Ҧp�G�����A�������C");
	showsetting("�Ȧ�s�ڧQ�v�G", "banksettings[accrual]",$banksettings[accrual], "text", "�Τ᪺�s�ڤ�Q�v�C");
	showsetting("�Τ�]�I���šG", "banksettings[groups]", $banksettings[groups], "radio", "�O�_�ϥΰ]�I���ť\��C");
	showsetting("��ܩ��l�R�污�p�G", "banksettings[showpostpay]", $banksettings[showpostpay], "radio", "�O�_�b�Ȧ椤��ܦ������l�R�檺�@�ǫH���M�έp�C<font color='red'><br>�`�N�G�p�G�z�S���w�˥��H�����l�R�洡��A�Ф��n���ն}�񦹥\��A�_�h�A���Ȧ�i��u�@�����`�C</font>");
	showsetting('','','','');
	showsetting("�O�_�����Ȧ�G", "banksettings[close]",$banksettings[close], "radio", "�p�G�����Ȧ�h�|��������i�J�A���O���v�T�޲z���ϥΡC");
	showsetting("�����Ȧ��]", "banksettings[message]", stripcslashes($banksettings[message]), "textarea", "�p�G�����Ȧ�A�h��ܵ��|�����H���C");

showtype("�Ȧ���b�]�m");
	showsetting("�O�_���\�|����b�G", "banksettings[allowchange]", $banksettings[allowchange], "radio", "��ܡ��_���N�����Ȧ���b�\��C");
	showsetting("��b�s�ڭ���G", "banksettings[minsave]", $banksettings[minsave], "text", "�п�J��ơC�p�G�|���s�ڧC�_�o�ӼƭȱN�L�k��b�C�Ψӭ���h���`�U�A�Ȩ��ذe�������C");
	showsetting("��b����O�v�G", "banksettings[changetax]", $banksettings[changetax], "text", "�C����b�V�|��������������B������O�C����������O�п�J0�C");

showtype("�n���R��]�m");
	showsetting("�O�_���\�n���R��G", "banksettings[allowsell]", $banksettings[allowsell], "radio", "��ܡ��_���N�����n���R��\��C");
	showsetting("�n���R�J����G", "banksettings[buy]",  $banksettings[buy], "text", "�п�J1-1000�����,��ĳ10");
	showsetting("�n����X����G", "banksettings[sell]", $banksettings[sell], "text", "�п�J1-1000����ơA��ĳ8�C�d�U���n�j�_�R�J������");
	showsetting("�R�����O�v�G", "banksettings[selltax]", $banksettings[selltax], "text", "�C������V�|������������B������O�C����������O�п�J0�C");
	showtype("", "bottom");
?>
</table></td></tr></table><br><br>
<center>
<BR><BR>

<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center"><tr><td bgcolor="<?=BORDERCOLOR?>"><table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%"><tr class="header"><td colspan="4">�]�I���ų]�m</td></tr>
<tr class="header" align="center"><td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">�R</td>
<td>���ŦW��</td><td>�]�I�U��</td><td>�]�I�W��</td></tr>
<?
	if ($bankgroup){
		$i=0;
		foreach($bankgroup as $oldgroup) {
		$i++;
		$bankgrouplist .="
		<tr ><td width=\"45\" bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[$i]\" ></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"oldgroup_name[$i]\" size=\"20\" value=\"{$oldgroup['name']}\"></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"oldgroup_min[$i]\" size=\"20\"  value=\"{$oldgroup['min']}\"></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"oldgroup_max[$i]\" size=\"20\"  value=\"{$oldgroup['max']}\"></td>
		</tr>";
		}
	}
	$bankgrouplist .="
		<tr ><td width=\"45\" bgcolor=\"".ALTBG1."\">�K�[</td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"newgroup_name\" size=\"20\"></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"newgroup_min\" size=\"20\" ></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"newgroup_max\" size=\"20\" ></td>
		</tr>";
	echo $bankgrouplist;
?>

</table></td></tr></table><br><br>
<center><input type="submit" name="savesettings" value="�T�{�ק�"></center>
</form>
</td></tr>

<?

} else {

	$banksettings[message]=str_replace("\n", '<br>', $banksettings[message]);
	$banksettings[message]=str_replace("\r", '', $banksettings[message]);
	if(is_array($oldgroup_name)){
		foreach($oldgroup_name as $id => $title) {
				if(!$delete[$id]) {
				    $tempgroup['name']=$title;
					$tempgroup['min']=$oldgroup_min[$id];
					$tempgroup['max']=$oldgroup_max[$id];
					$endgroup[]=$tempgroup;
				}
		}
	}
	if ($newgroup_name){
		$tempgroup['name']=$newgroup_name;
		$tempgroup['min']=$newgroup_min;
		$tempgroup['max']=$newgroup_max;
		$endgroup[]=$tempgroup;
	}

	if($endgroup){
		savesettings("$configfile",'$banksettings='.arrayeval($banksettings).";\n\n".'$bankgroup='.arrayeval($endgroup));
	}else{
		savesettings("$configfile",'$banksettings='.arrayeval($banksettings).";\n\n");
	}
	cpmsg("Discuz! �Ȧ�]�m��s���\�C");
}

?>