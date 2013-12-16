<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

if($action == 'styles' && $export) {
	$query = $db->query("SELECT s.name, s.templateid, t.name AS tplname, t.charset, t.directory, t.copyright FROM $table_styles s LEFT JOIN $table_templates t ON t.templateid=s.templateid WHERE styleid='$export'");
	if(!$stylearray = $db->fetch_array($query)) {
		cpheader();
		cpmsg('���w�����椣�s�b�A�L�k�ɥX�C');
	}

	$stylearray['version'] = strip_tags($version);
	$time = gmdate("$dateformat $timeformat", $timestamp + $timeoffset * 3600);

	$query = $db->query("SELECT * FROM $table_stylevars WHERE styleid='$export'");
	while($style = $db->fetch_array($query)) {
		$stylearray['style'][$style['variable']] = $style['substitute'];
	}

	if($stylearray['templateid'] != 1) {
		$dir = dir($discuz_root.'./'.$stylearray['directory']);
		while($entry = $dir->read()) {
			$filename = $discuz_root.'./'.$stylearray['directory'].'/'.$entry;
			if(is_file($filename)) {
				$stylearray['template'][str_replace('.', '_DOT_', $entry)] = join('', file($filename));
			}
		}
		$dir->close();
	}

	$style_export = "# Discuz! Style Dump\n".
			"# Version: Discuz! $version\n".
			"# Time: $time\n".
			"# From: $bbname ($boardurl)\n".
			"#\n".
			"# This file was BASE64 encoded\n".
			"#\n".
			"# Discuz! Community: http://www.Discuz.net\n".
			"# Please visit our website for newest infomation about Discuz!\n".
			"# --------------------------------------------------------\n\n\n".
			wordwrap(base64_encode(serialize($stylearray)), 50, "\n", 1);

	ob_end_clean();
	header('Content-Encoding: none');
	header('Content-Type: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
	header('Content-Disposition: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename="dz_style_'.$stylearray['name'].'.txt"');
	header('Content-Length: '.strlen($style_export));
	header('Pragma: no-cache');
	header('Expires: 0');

	echo $style_export;
	discuz_exit();
}

cpheader();

if($action == 'styles' && !$export) {

	$predefinedvars = array('bgcolor', 'altbg1', 'altbg2', 'link', 'bordercolor', 'headercolor', 'headertext', 'catcolor',
				'tabletext', 'text', 'borderwidth', 'tablewidth', 'tablespace', 'fontsize', 'font',
				'smfontsize', 'smfont', 'nobold', 'boardimg', 'imgdir', 'smdir', 'cattext');

	if(!$stylesubmit && !$importsubmit && !$edit && !$export) {

		$styleselect = '';
		$query = $db->query("SELECT s.styleid, s.available, s.name, t.name AS tplname, t.charset, t.copyright FROM $table_styles s LEFT JOIN $table_templates t ON t.templateid=s.templateid");
		while($styleinfo = $db->fetch_array($query)) {
			$styleselect .= "<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[]\" value=\"$styleinfo[styleid]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"namenew[$styleinfo[styleid]]\" value=\"$styleinfo[name]\" size=\"18\"></td>\n".
				"<td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"availablenew[$styleinfo[styleid]]\" value=\"1\" ".($styleinfo['available'] ? 'checked' : NULL)."></td>\n".
				"<td bgcolor=\"".ALTBG2."\">$styleinfo[styleid]</td>\n".
				"<td bgcolor=\"".ALTBG1."\">$styleinfo[tplname]</td>\n".
				"<td bgcolor=\"".ALTBG2."\">$styleinfo[charset]</td>\n".
				"<td bgcolor=\"".ALTBG1."\"><a href=\"admincp.php?action=styles&export=$styleinfo[styleid]\">[�U��]</a></td>\n".
				"<td bgcolor=\"".ALTBG2."\"><a href=\"admincp.php?action=styles&edit=$styleinfo[styleid]\">[�Ա�]</a></td></tr>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br>
<ul><li>�z�i�H���ɥΤ�q�L�b URL �[�J styleid �ܶq�ӻ��P������e�ɭ�����A�p http://your.com/discuz/index.php?styleid=2 �� http://your.com/discuz/forumdisplay.php?fid=2&styleid=3</ul>
<ul><li>�ɭ���ת��y�i�Ρz�O���Τ�O�_��ܨϥΦ�����A���L�׳]�m�i�λP�_�A���i�H�N���ɭ��]�m���w�]�ɭ��άY���׾ª��w�]�ɭ��C</ul>
</td></tr></table></td></tr></table><br><br>

<form method="post" action="admincp.php?action=styles">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header" align="center">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">�R</td>
<td>��צW��</td><td>�i��</td><td>styleID</td><td>�ҥμҪ�</td><td>�r����</td><td>�ɥX</td><td>�s��</td></tr>
<?=$styleselect?>
<tr bgcolor="<?=ALTBG2?>"><td height="1" colspan="8"></td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>">�s�W�G</td>
<td bgcolor="<?=ALTBG2?>"><input type='text' name="newname" size="18"></td>
<td colspan="6" bgcolor="<?=ALTBG2?>">&nbsp;</td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="stylesubmit" value="��s�ɭ��]�m"></center></form>

<br><form method="post" action="admincp.php?action=styles">
<table cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="90%" bgcolor="<?=BORDERCOLOR?>" align="center">
<tr class="header"><td>�ɤJ�ɭ���� - �бN�ɥX����󤺮e�H�K�p�U</td></tr>
<tr><td bgcolor="<?=ALTBG1?>" align="center"><textarea  name="styledata" cols="80" rows="8"></textarea><br>
<input type="checkbox" name="ignoreversion" value="1"> ���\�ɤJ���P���� Discuz! ���ɭ�(�����Ϳ��~�I)</td></tr>
</table><br><center><input type="submit" name="importsubmit" value="�N������ɤJ Discuz!"></center></form>
<?

	} elseif($stylesubmit) {

		if(is_array($namenew)) {
			foreach($namenew as $id => $val) {
				$db->query("UPDATE $table_styles SET name='$namenew[$id]', available='$availablenew[$id]' WHERE styleid='$id'");
			}
		}

		if(is_array($delete)) {
			$ids = $comma = '';
			foreach($delete as $id) {
				$ids .= "$comma'$id'";
				$comma  = ', ';
			}
			$query = $db->query("SELECT COUNT(*) FROM $table_settings WHERE styleid IN ($ids)");
			if($db->result($query, 0)) {
				cpmsg("�z���ઽ���R���t�ιw�]������A�Ъ�^��ܨ�L���欰�w�]��A�i��R���C");
			}

			$db->query("DELETE FROM $table_styles WHERE styleid IN ($ids)");
			$db->query("DELETE FROM $table_stylevars WHERE styleid IN ($ids)");
			$db->query("UPDATE $table_members SET styleid='0' WHERE styleid IN ($ids)");
			$db->query("UPDATE $table_forums SET styleid='0' WHERE styleid IN ($ids)");
			$db->query("UPDATE $table_sessions SET styleid='$_DCACHE[settings][styleid]' WHERE styleid IN ($ids)");
		}

		if($newname) {
			$db->query("INSERT INTO $table_styles (name, templateid) VALUES ('$newname', '1')");
			$styleid = $db->insert_id();
			foreach($predefinedvars as $variable) {
				$db->query("INSERT INTO $table_stylevars (styleid, variable)
					VALUES ('$styleid', '$variable')");
			}
		}

		cpmsg("�ɭ���צ��\��s�C");

	} elseif($importsubmit) {

		$styledata = preg_replace("/(#.*\s+)*/", '', $styledata);
		$stylearray = daddslashes(unserialize(base64_decode($styledata)), 1);

		if(!is_array($stylearray)) {
			cpmsg("�ɭ����w�l�a�A�L�k�ɤJ�A�Ъ�^�C");
		}

		if(empty($ignoreversion) && strip_tags($stylearray['version']) != strip_tags($version)) {
			cpmsg("�ɥX�ɭ��ҥ� Discuz! ($stylearray[version])�P��e����($version)���@�P�A�Ъ�^�C");
		}

		if($stylearray['templateid'] != 1) {
			$templatedir = $discuz_root.'./'.$stylearray['directory'];
			if(!is_dir($templatedir)) {
				if(!@mkdir($templatedir, 0777)) {
					$basedir = dirname($stylearray['directory']);
					cpmsg("�Ҫ��ؿ��L�k�۰ʫإߡA�г]�m $basedir �ؿ��ݩʬ� 777<br>�γq�L FTP �إߥؿ� $stylearray[directory] �ó]�m�ݩʬ� 777�C");
				}
			}

			foreach($stylearray['template'] as $name => $file) {
				$name = $templatedir.'/'.str_replace('_DOT_', '.', $name);
				if(file_exists($name)) {
					cpmsg("���W���ơA�Ъ�^�T�{�Ҫ��ؿ����ū�A�աC");
				}
				if(!$fp = fopen($name, 'wb')) {
					cpmsg("�Ҫ����L�k�g�J�A�Ъ�^�]�m $stylearray[directory] �ؿ��ݩʬ� 777 ��A�աC");
				}
				flock($fp, 3);
				fwrite($fp, $file);
				fclose($fp);
			}

			$renameinfo = '';
			$query = $db->query("SELECT COUNT(*) FROM $table_templates WHERE name='$stylearray[tplname]'");
			if($db->result($query, 0)) {
				$stylearray['tplname'] .= '_'.random(4);
				$renameinfo .= "�ɤJ�Ҫ��W�ٻP�{���Ҫ����ơA�s�Ҫ��Q���R�W��<br><b>$stylearray[tplname]</b>�C";
			}
			$db->query("INSERT INTO $table_templates (name, charset, directory, copyright)
				VALUES ('$stylearray[tplname]', '$stylearray[charset]', '$stylearray[directory]', '$stylearray[copyright]')");
			$templateid = $db->insert_id();
		} else {
			$templateid = 1;
		}

		$query = $db->query("SELECT COUNT(*) FROM $table_styles WHERE name='$stylearray[name]'");
		if($db->result($query, 0)) {
			$stylearray['name'] .= '_'.random(4);
			$renameinfo .= "�ɤJ�W�ٻP�{���ɭ����ơA�s�ɭ��Q���R�W��<br><b>$stylearray[name]</b>�C";
		}
		$db->query("INSERT INTO $table_styles (name, templateid)
			VALUES ('$stylearray[name]', '$templateid')");
		$styleid = $db->insert_id();

		foreach($stylearray['style'] as $variable => $substitute) {
			$db->query("INSERT INTO $table_stylevars (styleid, variable, substitute)
				VALUES ('$styleid', '$variable', '$substitute')");
		}

		updatecache('styles');
		updatecache("themelists");
		cpmsg($renameinfo.'�ɭ���צ��\�ɤJ�C');

	} elseif($edit) {

		if(!$editsubmit) {

			$query = $db->query("SELECT name, templateid FROM $table_styles WHERE styleid='$edit'");
			if(!$style = $db->fetch_array($query)) {
				cpmsg("���w�׾¬ɭ����s�b�A�Ъ�^�C");
			}

			$stylecustom = '';
			$stylestuff = array();
			$query = $db->query("SELECT * FROM $table_stylevars WHERE styleid='$edit'");
			while($stylevar = $db->fetch_array($query)) {
				if(in_array($stylevar['variable'], $predefinedvars)) {
					$stylestuff[$stylevar['variable']] = array('id' => $stylevar['stylevarid'], 'subst' => $stylevar['substitute']);
				} else {
					$stylecustom .= "<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[]\" value=\"$stylevar[stylevarid]\"></td>\n".
						"<td bgcolor=\"".ALTBG2."\"><b>{".strtoupper($stylevar[variable])."}</b></td>\n".
						"<td bgcolor=\"".ALTBG1."\"><textarea name=\"stylevar[$stylevar[stylevarid]]\" cols=\"50\" rows=\"2\">$stylevar[substitute]</textarea></td>\n".
						"</tr>";
				}
			}

			$tplselect = "<select name=\"templateidnew\">\n";
			$query = $db->query("SELECT templateid, name FROM $table_templates");
			while($template = $db->fetch_array($query)) {
				$tplselect .= "<option value=\"$template[templateid]\"".
					($style['templateid'] == $template['templateid'] ? 'selected="selected"' : NULL).
					">$template[name]</option>\n";
			}
			$tplselect .= '</select>';

			echo "<form method=\"post\" action=\"admincp.php?action=styles&edit=$edit\">\n";

			showtype('�s��ɭ���� - '.$style['name'], 'top');
			showsetting('�ɭ���צW�١G', 'namenew', $style['name'], 'text', "�ѧO�ɭ����檺�лx�A�ФŨϥΪŮ�ίS��Ÿ�", '55%');
			showsetting('�ǰt�Ҫ��G', '', '', $tplselect, '�P���M�ɭ�����ۤǰt���Ҫ��W��', '55%');
			showsetting('�׾� logo�G', "stylevar[{$stylestuff[boardimg][id]}]", $stylestuff['boardimg']['subst'], 'text', "�p�ϥ� Flash  �ʵe�A�Хγr���j�} URL�A�e�שM���סA�p��logo.swf,80,40��", '55%');
			showsetting('�ɭ��Ϥ��ؿ��G', "stylevar[{$stylestuff[imgdir][id]}]", $stylestuff['imgdir']['subst'], 'text', '', '55%');
			showsetting('Smilies �Ϥ��ؿ��G', "stylevar[{$stylestuff[smdir][id]}]", $stylestuff['smdir']['subst'], 'text', '', '55%');

			showtype('��r���C��]�m');
			showsetting('�T�β���r��ܡG', "stylevar[{$stylestuff[nobold][id]}]", $stylestuff['nobold']['subst'], 'radio', "��ܡu�O�v�e�O�N����ܥ������r���e", '55%');
			showsetting('���`�r��]�m�G', "stylevar[{$stylestuff[font][id]}]", $stylestuff['font']['subst'], 'text', "�h�ӭԿ�r�鶡�ХΥb�γr�� \",\" ����", '55%');
			showsetting('���`�r���]�m�G', "stylevar[{$stylestuff[fontsize][id]}]", $stylestuff['fontsize']['subst'], 'text', "�i�ϥΦr���Bpt�Bpx(����) �����", '55%');
			showsetting('�p���r��]�m�G', "stylevar[{$stylestuff[smfont][id]}]", $stylestuff['smfont']['subst'], 'text', "�h�ӭԿ�r�鶡�ХΥb�γr�� \",\" ����", '55%');
			showsetting('�p���r���]�m�G', "stylevar[{$stylestuff[smfontsize][id]}]", $stylestuff['smfontsize']['subst'], 'text', "�i�ϥΦr���Bpt�Bpx(����) �����", '55%');
			showsetting('�W�s����r�C��G', "stylevar[{$stylestuff[link][id]}]", $stylestuff['link']['subst'], 'color', '', '55%');
			showsetting('���Y��r�C��G', "stylevar[{$stylestuff[headertext][id]}]", $stylestuff['headertext']['subst'], 'color', '', '55%');
			showsetting('��ؤ�r�C��G', "stylevar[{$stylestuff[cattext][id]}]", $stylestuff['cattext']['subst'], 'color', '', '55%');
			showsetting('��椤��r�C��G', "stylevar[{$stylestuff[tabletext][id]}]", $stylestuff['tabletext']['subst'], 'color', '', '55%');
			showsetting('������ (��氣�~) ��r�C��G', "stylevar[{$stylestuff[text][id]}]", $stylestuff['text']['subst'], 'color', '', '55%');

			showtype('���P�I���C��]�m');
			showsetting('�����ؼe�סG', "stylevar[{$stylestuff[borderwidth][id]}]", $stylestuff['borderwidth']['subst'], 'text', '', '55%');
			showsetting('�����t�ŻءG', "stylevar[{$stylestuff[tablespace][id]}]", $stylestuff['tablespace']['subst'],   'text', '', '55%');
			showsetting('�������C��G', "stylevar[{$stylestuff[bordercolor][id]}]", $stylestuff['bordercolor']['subst'], 'color', '', '55%');
			showsetting('�����I���G', "stylevar[{$stylestuff[bgcolor][id]}]", $stylestuff['bgcolor']['subst'], 'color', "��J 16 �i���C��ιϤ��s��", '55%');
			showsetting('���Y�I���C��G', "stylevar[{$stylestuff[headercolor][id]}]", $stylestuff['headercolor']['subst'], 'color', "��J   16 �i���C��ιϤ��s��", '55%');
			showsetting('��حI���C��G', "stylevar[{$stylestuff[catcolor][id]}]", $stylestuff['catcolor']['subst'], 'color', "��J 16 �i���C��ιϤ��s��", '55%');
			showsetting('���I���t�� 1�G', "stylevar[{$stylestuff[altbg1][id]}]", $stylestuff['altbg1']['subst'], 'color', "��ĳ�]�m���۹���I���� 2 ���`���C��", '55%');
			showsetting('���I���t�� 2�G', "stylevar[{$stylestuff[altbg2][id]}]", $stylestuff['altbg2']['subst'], 'color', "��ĳ�]�m���۹���I���� 1 ���L���C��", '55%');
			showsetting('���e�סG', "stylevar[{$stylestuff[tablewidth][id]}]", $stylestuff['tablewidth']['subst'], 'text', "�i�]�m�������Φʤ���", '55%');
			showtype('', "bottom");

?>
<br><br>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header" align="center">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">�R</td>
<td>�ܶq</td><td>�������e</td></tr>
<?=$stylecustom?>
<tr bgcolor="<?=ALTBG2?>"><td height="1" colspan="3"></td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>">�s�W�G</td>
<td bgcolor="<?=ALTBG2?>"><input type='text' name="newcvar" size="20"></td>
<td bgcolor="<?=ALTBG1?>"><textarea name="newcsubst" cols="50" rows="2"></textarea></td>
</tr></table></td></tr></table><br>
<?

			echo "<br><center><input type=\"submit\" name=\"editsubmit\" value=\"��s��׳]�m\"></center></form>";

		} else {

			if($newcvar && $newcsubst) {
				$query = $db->query("SELECT COUNT(*) FROM $table_stylevars WHERE variable='$newcvar' AND styleid='$edit'");
				if($db->result($query, 0)) {
					cpmsg('�s�W�������ܶq�W�w�g�s�b�A�Ъ�^�ק�C');
				} elseif(!preg_match("/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/s", $newcvar)) {
					cpmsg('�s�W�������ܶq�W�٤��X�k�A�Ъ�^�ק�C');
				}

				$db->query("INSERT INTO $table_stylevars (styleid, variable, substitute)
					VALUES ('$edit', '$newcvar', '$newcsubst')");
			}

			$db->query("UPDATE $table_styles SET name='$namenew', templateid='$templateidnew' WHERE styleid='$edit'");
			foreach($stylevar as $id => $substitute) {
				$db->query("UPDATE $table_stylevars SET substitute='$substitute' WHERE stylevarid='$id' AND styleid='$edit'");
			}

			if(is_array($delete)) {
				$ids = $comma = '';
				foreach($delete as $id) {
					$ids .= "$comma'$id'";
					$comma = ', ';
				}
				$db->query("DELETE FROM $table_stylevars WHERE stylevarid IN ($ids) AND styleid='$edit'");
			}

			updatecache('styles');
			updatecache("themelists");
			cpmsg("�ɭ���צ��\��s�C");

		}

	}

}

?>