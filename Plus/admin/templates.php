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

if($action == 'templates') {

	if(!$edit) {

		if(!$tplsubmit) {

			$templates = '';
			$query = $db->query("SELECT * FROM $table_templates");
			while($tpl = $db->fetch_array($query)) {
				$templates .= "<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[]\" value=\"$tpl[templateid]\"></td>\n".
					"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"8\" name=\"namenew[$tpl[templateid]]\" value=\"$tpl[name]\"></td>\n".
					"<td bgcolor=\"".ALTBG1."\"><input type=\"text\" size=\"6\" name=\"charsetnew[$tpl[templateid]]\" value=\"$tpl[charset]\"></td>\n".
					"<td bgcolor=\"".ALTBG2."\"><input type=\"text\" size=\"20\" name=\"directorynew[$tpl[templateid]]\" value=\"$tpl[directory]\"></td>\n".
					"<td bgcolor=\"".ALTBG1."\">$tpl[copyright]</td>\n".
					"<td bgcolor=\"".ALTBG2."\"><a href=\"admincp.php?action=templates&edit=$tpl[templateid]\">[�Ա�]</a></td></tr>\n";
			}

?>
<form method="post" action="admincp.php?action=templates">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header" align="center">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">�R</td>
<td>�Ҫ��W��</td><td>�r����</td><td>�Ҧb�ؿ�</td><td>�ۧ@�v�T��</td><td>�s��</td></tr>
<?=$templates?>
<tr bgcolor="<?=ALTBG2?>"><td height="1" colspan="76"></td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>">�s�W�G</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" size="8" name="newname"></td>
<td bgcolor="<?=ALTBG1?>"><input type="text" size="6" name="newcharset"></td>
<td bgcolor="<?=ALTBG2?>"><input type="text" size="20" name="newdirectory"></td>
<td bgcolor="<?=ALTBG1?>"><input type="text" size="25" name="newcopyright"></td>
<td bgcolor="<?=ALTBG2?>">&nbsp;</td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="tplsubmit" value="��s�ɭ��]�m"></center></form>
<?

		} else {

			if($newname) {
				if(!$newcharset || !$newdirectory) {
					cpmsg('�z�S����g�Ҫ��r�����ΩҦb�ؿ��A�Ъ�^�ק�C');
				} elseif(substr(trim($newdirectory), -1) == '/') {
					cpmsg('�Ҫ��ؿ��̫ᤣ��]�t "/"�A�Ъ�^�ק�C');
				} elseif(!is_dir($discuz_root.'./'.$newdirectory)) {
					cpmsg("�Ҫ��ؿ� $newdirectory ���s�b�A�Ъ�^�ק�C");
				}
				$db->unbuffered_query("INSERT INTO $table_templates (name, charset, directory, copyright)
					VALUES ('$newname', '$newcharset', '$newdirectory', '$newcopyright')");
			}

			foreach($directorynew as $id => $directory) {
				if(!$delete || ($delete && !in_array($id, $delete))) {
					if(!is_dir($directory)) {
						cpmsg("�Ҫ��ؿ� $directory ���s�b�A�Ъ�^�ק�C");
					} elseif($id == 1 && $directory != './templates/default') {
						cpmsg('�z����ק�w�]�Ҫ����ؿ��]�m�A�Ъ�^�C');
					}
					$db->unbuffered_query("UPDATE $table_templates SET name='$namenew[$id]', charset='$charsetnew[$id]', directory='$directorynew[$id]' WHERE templateid='$id'");
				}
			}

			if(is_array($delete)) {
				if(in_array('1', $delete)) {
					cpmsg('�z����R���w�]�Ҫ��A�Ъ�^�C');
				}
				$ids = $comma = '';
				foreach($delete as $id) {
					$ids .= "$comma'$id'";
					$comma = ', ';
				}
				$db->unbuffered_query("DELETE FROM $table_templates WHERE templateid IN ($ids) AND templateid<>'1'");
				$db->unbuffered_query("UPDATE $table_styles SET templateid='1' WHERE templateid IN ($ids)");
			}

			updatecache('styles');
			cpmsg('�Ҫ��M�t���\��s�C');

		}

	} else {

		$query = $db->query("SELECT * FROM $table_templates WHERE templateid='$edit'");
		if(!$template = $db->fetch_array($query)) {
			cpmsg('���w�Ҫ��M�t���s�b�Τw�Q�R���A�Ъ�^�C');
		} elseif(!is_dir($discuz_root.'./'.$template['directory'])) {
			cpmsg('�Ҫ��Ҧb�ؿ� $template[directory] ���s�b�A���ˬd�����]�m�C');
		}

		$warning = $template['templateid'] == 1 ?
				'�z���b�ק�w�]�Ҫ��A���F�X�R��L�Ҫ�����K�A�j�P��ĳ�z���n��w�]�Ҫ������e�i��ק�C' :
				'���M�t���ä��n�D��������A�үʤ֪��Ҫ��N�ϥιw�]�Ҫ��N���A���y���]��������L�ʡC';
		if($keyword) {
			$keywordadd = " - �]�t����r '".htmlspecialchars($keyword)."' ���Ҫ� - <a href=\"admincp.php?action=templates&edit=$edit\" style=\"color: ".HEADERTEXT."\">[ �d�ݥ����Ҫ� ]</a>";
			$keywordenc = rawurlencode($keyword);
		}

		$tplarray = $langarray = array();
		$tpldir = dir($discuz_root.'./'.$template['directory']);
		while($entry = $tpldir->read()) {
			$extension = strtolower(substr(strrchr($entry, '.'), 1));
			if($extension == 'htm') {
				$tplname = substr($entry, 0, -4);
				$pos = strpos($tplname, '_');
				if($keyword) {
					if(!stristr(implode("\n", file($discuz_root."./$template[directory]/$entry")), $keyword)) {
						continue;
					}
				}
				if(!$pos) {
					$tplarray[$tplname][] = $tplname;
				} else {
					$tplarray[substr($tplname, 0, $pos)][] = $tplname;
				}
			} elseif($extension == 'php') {
				$langarray[] = substr($entry, 0, -9);
			}
		}
		$tpldir->close();

		ksort($tplarray);
		ksort($langarray);
		$templates = $languages = '';

		foreach($tplarray as $tpl => $subtpls) {
			$templates .= "<ul><li><b>$tpl �Ҫ���</b><ul>\n";
			foreach($subtpls as $subtpl) {
				$filename = "$subtpl.htm";
				$templates .= "<li>$subtpl &nbsp; <a href=\"admincp.php?action=tpledit&templateid=$template[templateid]&fn=$filename&keyword=$keywordenc\">[�s��]</a> ".
					"<a href=\"admincp.php?action=tpledit&templateid=$template[templateid]&fn=$filename&delete=yes\">[�R��]</a>";
			}
			$templates .= "</ul></ul>\n";
		}
		foreach($langarray as $lang) {
			$languages .= "<ul><li>$lang &nbsp; <a href=\"admincp.php?action=tpledit&templateid=$template[templateid]&fn=$lang.lang.php\">[�s��]</a></ul>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="3">�Ҫ����@</td></tr>

<form method="post" action="admincp.php?action=tpladd&edit=<?=$edit?>">
<tr bgcolor="<?=ALTBG2?>"><td width="25%">�W�[�Ҫ�</td><td width="55%"><input type="text" name="name" size="40" maxlength="40"></td>
<td width="20%"><input type="submit" value="�W �["></td></tr></form>

<form method="get" action="admincp.php">
<input type="hidden" name="action" value="templates">
<input type="hidden" name="edit" value="<?=$edit?>">
<tr bgcolor="<?=ALTBG1?>"><td>�j�M�Ҫ����e</td><td><input type="text" name="keyword" size="40"></td>
<td><input type="submit" value="�j �M"></td></tr></form>

</table></td></tr></table></td></tr></table><br><br>

<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>��ܼҪ�<?=$keywordadd?></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td><br><center><b><?=$warning?></b></center><br>
<ul><li><b>Discuz! �y���]</b><?=$languages?></ul>
<ul><li><b>Discuz! �Ҫ�</b><?=$templates?></ul>
</td></tr></table></td></tr></table>
<?

	}
			
} elseif($action == 'tpledit') {

	$query = $db->query("SELECT * FROM $table_templates WHERE templateid='$templateid'");
	if(!$template = $db->fetch_array($query)) {
		cpmsg('���w�Ҫ��M�t���s�b�A�Ъ�^�C');
	}

	$filename = $discuz_root."./$template[directory]/$fn";
	if(!is_writeable($filename)) {
		cpmsg("���w��� $template[directory]/$fn �L�k�g�J�A<br>�Y�ݽu�W�s��A�бN�Ӥ��M�Ҧb�ؿ��ݩʳ]�m�� 777�C");
	}

	if(!$editsubmit && $delete != 'yes') {

		$keywordenc = rawurlencode($keyword);

		$fp = fopen($filename, 'r');
		$content = fread($fp, filesize($filename));
		fclose($fp);

		$content = str_replace("\\'", "'", htmlspecialchars($content));

?>
<script language="JavaScript">
var n = 0;
function displayHTML(obj) {
	win = window.open(" ", 'popup', 'toolbar = no, status = no, scrollbars=yes');
	win.document.write("" + obj.value + "");
}
function HighlightAll(obj) {
	obj.focus();
	obj.select();
	if (document.all) {
		obj.createTextRange().execCommand("Copy");
		window.status = "�N�Ҫ����e�ƻs��ŶK��";
		setTimeout("window.status=''", 1800);
	}
}
function findInPage(obj, str) {
	var txt, i, found;
	if (str == "") {
		return false;
	}
	if (document.layers) {
		if (!obj.find(str)) {
			while(obj.find(str, false, true)) {
				n++;
			}
		} else {
			n++;
		}
		if (n == 0) {
			alert('�������w�r��C');
		}
	}
	if (document.all) {
		txt = obj.createTextRange();
		for (i = 0; i <= n && (found = txt.findText(str)) != false; i++) {
			txt.moveStart('character', 1);
			txt.moveEnd('textedit');
		}
		if (found) {
			txt.moveStart('character', -1);
			txt.findText(str);
			txt.select();
			txt.scrollIntoView();
			n++;
		} else {
			if (n > 0) {
				n = 0;
				findInPage(str);
			} else {
				alert("�������w�r��C");
			}
		}
	}
	return false;
}
</script>
<form method="post" action="admincp.php?action=tpledit&templateid=<?=$templateid?>&fn=<?=$fn?>">
<input type="hidden" name="keyword" value="<?=$keywordenc?>">
<table cellspacing="0" cellpadding="0" border="0" width="60%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr><td class="header">�s��Ҫ� - <?=$fn?></td></tr>
<tr><td bgcolor="<?=ALTBG1?>" align="center">
<textarea cols="100" rows="25" name="templatenew"><?=$content?></textarea><br><br>
<input name="search" type="text" accesskey="t" size="20" onChange="n=0;">
<input type="button" value="�d��" accesskey="f" onClick="findInPage(this.form.templatenew, this.form.search.value)">&nbsp;&nbsp;&nbsp;
<input type="button" value="��^" accesskey="e" onClick="history.go(-1)">
<input type="button" value="�w��" accesskey="p" onClick="displayHTML(this.form.templatenew)">
<input type="button" value="�ƻs" accesskey="c" onClick="HighlightAll(this.form.templatenew)">&nbsp;&nbsp;&nbsp;
<input type="submit" name="editsubmit" value="�T�{�ק�">
</td></tr></table></td></tr></table>
</form>

<?

	} elseif($delete == 'yes') {

		if(!$confirmed) {
			cpmsg("���ާ@���i��_�A�z�T�w�n�R���Ҫ� $fn �ܡH", "admincp.php?action=tpledit&templateid=$templateid&fn=$fn&delete=yes", 'form');
		} else {
			if(@unlink($filename)) {
				cpmsg('���w�Ҫ����\�R���A�{�b�N��J�Ҫ��C��C', "admincp.php?action=templates&edit=$templateid");
			} else {
				cpmsg('�{���L�v�R���Ҫ����A�Х� FTP �n�J��A�աC');
			}
		}

	} else {

		$fp = fopen($filename, 'w');
		flock($fp, 3);
		fwrite($fp, stripslashes(str_replace("\x0d\x0a", "\x0a", $templatenew)));
		fclose($fp);

		cpmsg('���w�Ҫ����\�s��A�{�b�N��J�Ҫ��C��C', "admincp.php?action=templates&edit=$templateid&keyword=$keyword");

	}

} elseif($action == 'tpladd') {

	$query = $db->query("SELECT * FROM $table_templates WHERE templateid='$edit'");
	if(!$template = $db->fetch_array($query)) {
		cpmsg('���w�Ҫ��M�t���s�b�Τw�Q�R���A�Ъ�^�C');
	} elseif(!is_dir($discuz_root.'./'.$template[directory])) {
		cpmsg('�Ҫ��Ҧb�ؿ� $template[directory] ���s�b�A���ˬd�����]�m�C');
	} elseif(file_exists($discuz_root."./$template[directory]/$name.htm")) {
		cpmsg('�s�W�Ҫ��w�g�s�b�A�Ъ�^��ܨ�L�W�١C');
	} elseif(!@$fp = fopen($discuz_root."./$template[directory]/$name.htm", 'w')) {
		cpmsg("���w��� $template[directory]/$name.htm �L�k�g�J�A<br>�Y�ݽu�W�s��A�бN�Ӥ��M�Ҧb�ؿ��ݩʳ]�m�� 777�C");
	}

	@fclose($fp);
	cpmsg('���w�Ҫ����\�W�[�A�{�b�N��J�Ҫ��s�譶�C', "admincp.php?action=tpledit&templateid=1&fn=$name.htm");
	
}	

?>