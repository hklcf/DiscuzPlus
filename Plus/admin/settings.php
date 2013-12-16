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
if(!$settingsubmit) {

	$query = $db->query("SELECT * FROM $table_settings");
	$settings = $db->fetch_array($query);

	$stylelist = "<select name=\"styleidnew\">\n";
	$query = $db->query("SELECT styleid, name FROM $table_styles");
	while($style = $db->fetch_array($query)) {
		$selected = $style[styleid] == $settings[styleid] ? "selected=\"selected\"" : NULL;
		$stylelist .= "<option value=\"$style[styleid]\" $selected>$style[name]</option>\n";
	}
	$stylelist .= "</select>";

	$settings[moddisplay] == "selectbox" ? $modselectbox = "checked" : $modflat = "checked";
	$settings[timeformat] == "H:i" ? $check24 = "checked" : $check12 = "checked";

	$settings[dateformat] = str_replace("n", "mm", $settings[dateformat]);
	$settings[dateformat] = str_replace("j", "dd", $settings[dateformat]);
	$settings[dateformat] = str_replace("y", "yy", $settings[dateformat]);
	$settings[dateformat] = str_replace("Y", "yyyy", $settings[dateformat]);

	if($settings[avastatus]) {
		$avataron = 'checked';
	} elseif($avastatus == 'list') {
		$avatarlist = 'checked';
	} else {
		$avataroff = 'checked';
	}

	$checkattach = array($settings['attachsave'] => 'checked');

?>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">
<br>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td align="center">[<a href="#�򥻳]�m">�򥻳]�m</a>] - [<a href="#�Τ���U�P�X�ݱ���">�Τ���U�P�X�ݱ���</a>] - 
[<a href="#�ɭ��P��ܤ覡">�ɭ��P��ܤ覡</a>] - [<a href="#�׾¥\��">�׾¥\��</a>] - 
[<a href="#�Τ��v��">�Τ��v��</a>] - [<a href="#��L�]�m">��L�]�m</a>]</td></tr>
</table></td></tr></table>

<br><form method="post" action="admincp.php?action=settings">
<input type="hidden" name="chcodeorig" value="<?=$settings[chcode]?>">
<?
	showtype("�򥻳]�m", "top");
	showsetting('�׾³̤j�u�W�H�ơG', 'maxonlinesnew', $settings['maxonlines'], 'text', "�г]�m�X�z���ƭȡA�d�� 10~65535�A��ĳ�]�m�������u�W�H�ƪ� 10 �����k");
	showsetting("�׾¦W�١G", "bbnamenew", $settings[bbname], "text", "�׾¦W�١A�N��ܦb�ɯ���M���D��");
	showsetting("�����W�١G", "sitenamenew", $settings[sitename], "text", "�����W�١A�N��ܦb�����������pô�覡�B");
	showsetting("���� URL�G", "siteurlnew", $settings[siteurl], "text", "���� URL�A�N�@���s����ܦb��������");
	showsetting("�׾������G", "bbclosednew", $settings[bbclosed], "radio", "�ȮɱN�׾������A��L�H�L�k�X�ݡA�����v�T�޲z���X��");
	showsetting("�׾���������]", "closedreasonnew", $settings[closedreason], "textarea", "�׾������ɥX�{�����ܰT��");

	showtype("�Τ���U�P�X�ݱ���");
	showsetting("���\�s�Τ���U�G", "regstatusnew", $settings[regstatus], "radio", "��ܡ��_���N�T��C�ȵ��U�����|���A�����v�T�L�h�w���U���|�����ϥ�");
	showsetting("�O�d�Τ�W�G", "censorusernew", $settings[censoruser], "text", "�t�ΫO�d���Τ�W�١A�s�Τ�N�L�k�H�o�ǦW�r���U�C�h�ӥΤ�W���ХΥb�γr�� \",\" ����");
	showsetting("���\�P�@ Email ���U���P�Τ�G", "doubleenew", $settings[doublee], "radio", "��ܡ��_���N�u���\�@�� Email �a�}�u����U�@�ӥΤ�W");
	showsetting("�s�Τ���U�ݵo�l������ Email �a�}�G", "regverifynew", $settings['regverify'], "radio", "��ܡ��O���N�V�Τ���U Email �o�e�@�����Ҷl��H�T�{�l�c�����ĩʡA�Τ᦬��l��ñҰʱb����~��֦����`���v��");
	showsetting("���õL�v�X�ݪ��׾¡G", "hideprivatenew", $settings[hideprivate], "radio", "�p�G�Τ��v���F����Y�ӽ׾ª��X�ݭn�D�A�t�αN�o�ǽ׾����ä����");
	showsetting("�s�Τ���U�o�e�u�T�G", "welcommsgnew", $settings[welcommsg], "radio", "�s�Τ���U��t�Φ۰ʵo�e�@���w��u�T");
	showsetting("�w��u�T���e�G", "welcommsgtxtnew", $settings[welcommsgtxt], "textarea", "�w��u�T���ԲӤ��e");
	showsetting('���U����ܳ\�i��ĳ�G', "bbrulesnew", $settings[bbrules], "radio", '�s�Τ���U����ܳ\�i��ĳ�A�P�N��~���~����U');
	showsetting('�\�i��ĳ���e�G', "bbrulestxtnew", $settings[bbrulestxt], "textarea", '���U�\�i��ĳ���ԲӤ��e');

	showtype("�ɭ��P��ܤ覡");
	showsetting("�w�]�׾­���G", "", "", $stylelist, "�׾¹w�]���ɭ�����A�C�ȩM�ϥιw�]���檺�|���N�H���������");
	showsetting("�C����ܥD�D�ơG", "topicperpagenew", $settings[topicperpage], "text", "�`�N�G�ק�H�U�T���]�m�u�v�T�C�ȩM�s���U���|���A�ѷ|�������ۨ����]�m���");
	showsetting("�C����ܤ峹�ơG", "postperpagenew", $settings[postperpage], "text");
	showsetting("�C����ܷ|���ơG", "memberperpagenew", $settings[memberperpage], "text");
	showsetting("�������D�̧C�峹�ơG", "hottopicnew", $settings[hottopic], "text", "�W�L�@�w�峹�ƪ����D�N��ܬ��������D");
	showsetting("���D��ܤ覡�G", "", "", "<input type=\"radio\" name=\"moddisplaynew\" value=\"flat\" $modflat> ������� &nbsp; <input type=\"radio\" name=\"moddisplaynew\" value=\"selectbox\" $modselectbox> �U�Ե��</td>", "�����׾¦C�����D��ܤ覡");
	showsetting("�ֳt�o��峹�G", "fastpostnew", $settings[fastpost], "radio", "�s���׾©M�峹����������ܧֳt�o��峹���");
	showsetting("���D�ֱ��޲z�G", 'modshortcutnew', $settings['modshortcut'], 'radio', "�b���D�M�޲z�����D�D�C������� �R����... ���ֱ��޲z�s��");

	showtype("�׾¥\��");
	showsetting("�ϥν׾¬y�q�έp�G", "statstatusnew", $settings[statstatus], "radio", "��ܡ��O���N���}�׾²έp�\��A���ѸԲӪ��׾³X�ݲέp�T���A���\��i��|�v�T�Ĳv");
	showsetting("��ܵ{���B��T���G", "debugnew", $settings[debug], "radio", "��ܡ��O���N�b���}�B��ܵ{���B��ɶ��M��Ʈw�d�ߦ���");
	showsetting("���� Gzip ���Y�G", "gzipcompressnew", $settings[gzipcompress], "radio", "�N�������e�H gzip ���Y��ǿ�A�i�H�[�ֶǿ�t�סA�� PHP 4.0.4 �H�W�~��ϥ�");
	showsetting("�O������ܽu�W�Τ�G", "whosonlinenew", $settings[whosonlinestatus], "radio", "�b�����M�׾¦C����ܦb�u�|���C��");
	showsetting("�峹����ܧ@�̪��A�G", "vtonlinestatusnew", $settings[vtonlinestatus], "radio", "�s���峹����ܧ@�̦b�u���A");
	showsetting("���H�o�_�Φ^�Ъ��D�D��ܥ[�I�ϥܡG", "dotfoldersnew", $settings[dotfolders], "radio", "�b�s���̵o�_�Ϋ�_���D�D����ܥ[�I�ϥܡA���\��D�`�v�T�Ĳv");
	showsetting('����O�s�覡�G', '', '', "<input type=\"radio\" name=\"attachsavenew\" value=\"0\" $checkattach[0]> �з�(�����s�J�P�@�ؿ�)<br><input type=\"radio\" name=\"attachsavenew\" value=\"1\" $checkattach[1]> ���׾¦s�J���P�ؿ�<br><input type=\"radio\" name=\"attachsavenew\" value=\"2\" $checkattach[2]> ����������s�J���P�ؿ�<br><input type=\"radio\" name=\"attachsavenew\" value=\"3\" $checkattach[3]> ������s�J���P�ؿ�<br><input type=\"radio\" name=\"attachsavenew\" value=\"4\" $checkattach[4]> ���Ѧs�J���P�ؿ�</td>", "���]�m�u�v�T�s�W�Ǫ�����A�]�m��蠟�e�����󤴦s��b��Ӧ�m�C�p�ϥΫD�зǪ��O�s�覡�A�нT�{ mkdir() ��ƥi���`�ϥΡA�_�h�N�X�{����L�k�O�s�����D");
	showsetting("�C���W�u�W�[�n���G", 'logincreditsnew', $settings['logincredits'], 'text', "�|���C���W�u�W�[���n���ơA�d�� 0��255 �������");
	showsetting("�o��峹�W�[�n���G", "postcreditsnew", $settings[postcredits], "text", "�C�o��@�g�峹�@�̼W�[�n���ơA�d�� 0��255 ������ơA��ĳ�]�m�� 0(�o��峹���[�n��) �� 1(�o��峹�n���[ 1)�C�p�G�ק糧���]�m�A�����|�����n���N�P�o��峹�Ƭ۹������s�p��");
	showsetting("�Q���J��ؼW�[�n���G", "digestcreditsnew", $settings[digestcredits], "text", "�峹�Q���J��ذϧ@�̼W�[�n���ơA�d�� 0��255 �������");
	showsetting("�w������ɶ�(��)�G", "floodctrlnew", $settings[floodctrl], "text", "�|���⦸�o��峹���j���o�p�󦹳]�m�A�_�h�{���O����ӳQ�T��");
	showsetting("�⦸�j�M�̤p���j(��)�G", "searchctrlnew", $settings[searchctrl], "text", "������c�N�X�ݡA�⦸�j�M���j���o�p�󦹮ɶ��]�m�A0 ��������");

	showtype("�Τ��v��");
	showsetting("���\�d�ݷ|���C��G", "memliststatusnew", $settings[memliststatus], "radio", "���\�|���M�C�Ȭd�ݷ|���C��M�����T��");
	showsetting("���\�V���D�����峹�G", "reportpostnew", $settings[reportpost], "radio", "���\�|���q�L�u�T�����D�M�޲z�������峹");
	showsetting("�峹�̤j�r�ơG", "maxpostsizenew", $settings[maxpostsize], "text", "�|���o��峹���פ���W�L���r�Ƴ]�m�A�޲z����������");
	showsetting("�Y���̤j�ؤo(����)�G", "maxavatarsizenew", $settings[maxavatarsize], "text", "�|���Y����󪺪��e����W�L���ؤo�]�m�A�� PHP 4.0.5 �H�W�A�_�h�г]�m�� 0");

	showtype("��L�]�m");
	showsetting("�ɶ��榡�G", "", "", "<input type=\"radio\" name=\"timeformatnew\" value=\"24\" $check24> 24 �p�ɨ� <input type=\"radio\" name=\"timeformatnew\" value=\"12\" $check12> 12 �p�ɨ�</td>", "�`�N�G�ק�H�U�T���]�m�u�v�T�C�ȩM�s���U���|���A�ѷ|�������ۨ����]�m���");
	showsetting("����榡�G", "dateformatnew", $settings[dateformat], "text", "�Х� yyyy(yy)�Bmm�Bdd ��ܡA�p�榡 yyyy-mm-dd �� 2004-01-01");
	showsetting("�t�ήɮt�G", "timeoffsetnew", $settings[timeoffset], "text", "�׾®ɶ��P GMT �зǮɶ����ɮt�A����ɶ��г]�m�� +8�A���D���A���ɶ�����A�_�h�L�ݧ��w�]�]�w");
	showsetting("�s��峹���[�s��O���G", "editedbynew", $settings[editedby], "radio", "60 ���s��峹���[�����g�峹��...��...�̫�s�表���O���A���޲z�����|�Q�O��");
	showsetting("�峹����ܹϤ�/�ʵe����G", "attachimgpostnew", $settings[attachimgpost], "radio", "�b�峹�������N�Ϥ��ΰʵe������ܥX�ӡA�Ӥ��ݭn�I������s��");
	showsetting("�o��峹���� Discuz! �N�X���U�G", "bbinsertnew", $settings[bbinsert], "radio", "�o��峹�����]�t Discuz! �N�X���Ŵ��J�u��A�i�H²�ƥN�X�M�峹���s�g");
	showsetting("�o��峹�� Smilies �N�X���U�G", "smileyinsertnew", $settings[smileyinsert], "radio", "�o��峹�����]�t Smilies �ֱ��u��A�I���ϥܧY�i���J Smilies");
	showsetting("�C����� Smilies �ӼơG", "smcolsnew", $settings[smcols], "text", "�o��峹�����C����� Smilies ���Ӽ�");
	showtype("", "bottom");
?>

</table></td></tr></table><br><br>
<center><input type="submit" name="settingsubmit" value="�T�{�ק�"></center>
</form>

</td></tr>

<?

} else {

	if(PHP_VERSION < "4.0.4" && $gzipcompressnew) {
		cpmsg("�z�� PHP �����C�� 4.0.4�A�L�k�ϥ� gzip ���Y�\��A�Ъ�^�ק�C");
	}

	if(PHP_VERSION < "4.0.5" && $maxavatarsizenew) {
		cpmsg("�z�� PHP �����C�� 4.0.5�A�L�k�����Y���j�p�A�Ъ�^�ק�C");
	}

	if($maxonlinesnew > 65535 || !is_numeric($maxonlinesnew)) {
		cpmsg("�z�]�m���̤j�u�W�H�ƶW�L 65535�A�Ъ�^�ק�C");
	}

	$timeformatnew = $timeformatnew == '24' ? 'H:i' : 'h:i A';

	$bbnamenew = dhtmlspecialchars($bbnamenew);
	$welcommsgtxtnew = dhtmlspecialchars($welcommsgtxtnew);

	$dateformatnew = str_replace("mm", "n", $dateformatnew);
	$dateformatnew = str_replace("dd", "j", $dateformatnew);
	$dateformatnew = str_replace("yyyy", "Y", $dateformatnew);
	$dateformatnew = str_replace("yy", "y", $dateformatnew);

	$query = $db->query("SELECT postcredits FROM $table_settings");
	$postcredits = $db->result($query, 0);
	if($postcredits != $postcreditsnew) {
		$db->query("UPDATE $table_members SET credit=credit+(postnum*($postcreditsnew-$postcredits))");
	}

	$db->query("UPDATE $table_settings SET bbname='$bbnamenew', regstatus='$regstatusnew', censoruser='$censorusernew',
		doublee='$doubleenew', regverify='$regverifynew', bbrules='$bbrulesnew', bbrulestxt='$bbrulestxtnew',
		welcommsg='$welcommsgnew', welcommsgtxt='$welcommsgtxtnew', bbclosed='$bbclosednew', closedreason='$closedreasonnew',
		sitename='$sitenamenew', siteurl='$siteurlnew', styleid='$styleidnew', moddisplay='$moddisplaynew',
		maxonlines='$maxonlinesnew', floodctrl='$floodctrlnew', searchctrl='$searchctrlnew',
		hottopic='$hottopicnew', topicperpage='$topicperpagenew', postperpage='$postperpagenew', memberperpage='$memberperpagenew',
		maxpostsize='$maxpostsizenew', maxavatarsize='$maxavatarsizenew', smcols='$smcolsnew', whosonlinestatus='$whosonlinenew',
		vtonlinestatus='$vtonlinestatusnew', gzipcompress='$gzipcompressnew', logincredits='$logincreditsnew',
		postcredits='$postcreditsnew', digestcredits='$digestcreditsnew', hideprivate='$hideprivatenew', fastpost='$fastpostnew',
		modshortcut='$modshortcutnew', memliststatus='$memliststatusnew', statstatus='$statstatusnew', debug='$debugnew',
		reportpost='$reportpostnew', bbinsert='$bbinsertnew', smileyinsert='$smileyinsertnew', editedby='$editedbynew',
		dotfolders='$dotfoldersnew', attachsave='$attachsavenew', attachimgpost='$attachimgpostnew', timeformat='$timeformatnew',
		dateformat='$dateformatnew', timeoffset='$timeoffsetnew'");

	$db->query("ALTER TABLE $table_sessions MAX_ROWS=$maxonlinesnew");

	updatecache("settings");
	cpmsg("Discuz! �`�W�ﶵ���\��s�C");
}

?>