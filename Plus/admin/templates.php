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
					"<td bgcolor=\"".ALTBG2."\"><a href=\"admincp.php?action=templates&edit=$tpl[templateid]\">[詳情]</a></td></tr>\n";
			}

?>
<form method="post" action="admincp.php?action=templates">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header" align="center">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">刪</td>
<td>模版名稱</td><td>字元集</td><td>所在目錄</td><td>著作權訊息</td><td>編輯</td></tr>
<?=$templates?>
<tr bgcolor="<?=ALTBG2?>"><td height="1" colspan="76"></td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>">新增：</td>
<td bgcolor="<?=ALTBG2?>"><input type="text" size="8" name="newname"></td>
<td bgcolor="<?=ALTBG1?>"><input type="text" size="6" name="newcharset"></td>
<td bgcolor="<?=ALTBG2?>"><input type="text" size="20" name="newdirectory"></td>
<td bgcolor="<?=ALTBG1?>"><input type="text" size="25" name="newcopyright"></td>
<td bgcolor="<?=ALTBG2?>">&nbsp;</td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="tplsubmit" value="更新界面設置"></center></form>
<?

		} else {

			if($newname) {
				if(!$newcharset || !$newdirectory) {
					cpmsg('您沒有填寫模版字元集或所在目錄，請返回修改。');
				} elseif(substr(trim($newdirectory), -1) == '/') {
					cpmsg('模版目錄最後不能包含 "/"，請返回修改。');
				} elseif(!is_dir($discuz_root.'./'.$newdirectory)) {
					cpmsg("模版目錄 $newdirectory 不存在，請返回修改。");
				}
				$db->unbuffered_query("INSERT INTO $table_templates (name, charset, directory, copyright)
					VALUES ('$newname', '$newcharset', '$newdirectory', '$newcopyright')");
			}

			foreach($directorynew as $id => $directory) {
				if(!$delete || ($delete && !in_array($id, $delete))) {
					if(!is_dir($directory)) {
						cpmsg("模版目錄 $directory 不存在，請返回修改。");
					} elseif($id == 1 && $directory != './templates/default') {
						cpmsg('您不能修改預設模版的目錄設置，請返回。');
					}
					$db->unbuffered_query("UPDATE $table_templates SET name='$namenew[$id]', charset='$charsetnew[$id]', directory='$directorynew[$id]' WHERE templateid='$id'");
				}
			}

			if(is_array($delete)) {
				if(in_array('1', $delete)) {
					cpmsg('您不能刪除預設模版，請返回。');
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
			cpmsg('模版套系成功更新。');

		}

	} else {

		$query = $db->query("SELECT * FROM $table_templates WHERE templateid='$edit'");
		if(!$template = $db->fetch_array($query)) {
			cpmsg('指定模版套系不存在或已被刪除，請返回。');
		} elseif(!is_dir($discuz_root.'./'.$template['directory'])) {
			cpmsg('模版所在目錄 $template[directory] 不存在，請檢查相關設置。');
		}

		$warning = $template['templateid'] == 1 ?
				'您正在修改預設模版，為了擴充其他模版的方便，強烈建議您不要對預設模版的內容進行修改。' :
				'本套系中並不要求必須完整，所缺少的模版將使用預設模版代替，但語言包必須完整無缺。';
		if($keyword) {
			$keywordadd = " - 包含關鍵字 '".htmlspecialchars($keyword)."' 的模版 - <a href=\"admincp.php?action=templates&edit=$edit\" style=\"color: ".HEADERTEXT."\">[ 查看全部模版 ]</a>";
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
			$templates .= "<ul><li><b>$tpl 模版組</b><ul>\n";
			foreach($subtpls as $subtpl) {
				$filename = "$subtpl.htm";
				$templates .= "<li>$subtpl &nbsp; <a href=\"admincp.php?action=tpledit&templateid=$template[templateid]&fn=$filename&keyword=$keywordenc\">[編輯]</a> ".
					"<a href=\"admincp.php?action=tpledit&templateid=$template[templateid]&fn=$filename&delete=yes\">[刪除]</a>";
			}
			$templates .= "</ul></ul>\n";
		}
		foreach($langarray as $lang) {
			$languages .= "<ul><li>$lang &nbsp; <a href=\"admincp.php?action=tpledit&templateid=$template[templateid]&fn=$lang.lang.php\">[編輯]</a></ul>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="0" width="100%">
<tr><td>
<table border="0" cellspacing="0" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="3">模版維護</td></tr>

<form method="post" action="admincp.php?action=tpladd&edit=<?=$edit?>">
<tr bgcolor="<?=ALTBG2?>"><td width="25%">增加模版</td><td width="55%"><input type="text" name="name" size="40" maxlength="40"></td>
<td width="20%"><input type="submit" value="增 加"></td></tr></form>

<form method="get" action="admincp.php">
<input type="hidden" name="action" value="templates">
<input type="hidden" name="edit" value="<?=$edit?>">
<tr bgcolor="<?=ALTBG1?>"><td>搜尋模版內容</td><td><input type="text" name="keyword" size="40"></td>
<td><input type="submit" value="搜 尋"></td></tr></form>

</table></td></tr></table></td></tr></table><br><br>

<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>選擇模版<?=$keywordadd?></td></tr>
<tr bgcolor="<?=ALTBG1?>"><td><br><center><b><?=$warning?></b></center><br>
<ul><li><b>Discuz! 語言包</b><?=$languages?></ul>
<ul><li><b>Discuz! 模版</b><?=$templates?></ul>
</td></tr></table></td></tr></table>
<?

	}
			
} elseif($action == 'tpledit') {

	$query = $db->query("SELECT * FROM $table_templates WHERE templateid='$templateid'");
	if(!$template = $db->fetch_array($query)) {
		cpmsg('指定模版套系不存在，請返回。');
	}

	$filename = $discuz_root."./$template[directory]/$fn";
	if(!is_writeable($filename)) {
		cpmsg("指定文件 $template[directory]/$fn 無法寫入，<br>若需線上編輯，請將該文件和所在目錄屬性設置為 777。");
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
		window.status = "將模版內容複製到剪貼版";
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
			alert('未找到指定字串。');
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
				alert("未找到指定字串。");
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
<tr><td class="header">編輯模版 - <?=$fn?></td></tr>
<tr><td bgcolor="<?=ALTBG1?>" align="center">
<textarea cols="100" rows="25" name="templatenew"><?=$content?></textarea><br><br>
<input name="search" type="text" accesskey="t" size="20" onChange="n=0;">
<input type="button" value="查找" accesskey="f" onClick="findInPage(this.form.templatenew, this.form.search.value)">&nbsp;&nbsp;&nbsp;
<input type="button" value="返回" accesskey="e" onClick="history.go(-1)">
<input type="button" value="預覽" accesskey="p" onClick="displayHTML(this.form.templatenew)">
<input type="button" value="複製" accesskey="c" onClick="HighlightAll(this.form.templatenew)">&nbsp;&nbsp;&nbsp;
<input type="submit" name="editsubmit" value="確認修改">
</td></tr></table></td></tr></table>
</form>

<?

	} elseif($delete == 'yes') {

		if(!$confirmed) {
			cpmsg("本操作不可恢復，您確定要刪除模版 $fn 嗎？", "admincp.php?action=tpledit&templateid=$templateid&fn=$fn&delete=yes", 'form');
		} else {
			if(@unlink($filename)) {
				cpmsg('指定模版成功刪除，現在將轉入模版列表。', "admincp.php?action=templates&edit=$templateid");
			} else {
				cpmsg('程式無權刪除模版文件，請用 FTP 登入後再試。');
			}
		}

	} else {

		$fp = fopen($filename, 'w');
		flock($fp, 3);
		fwrite($fp, stripslashes(str_replace("\x0d\x0a", "\x0a", $templatenew)));
		fclose($fp);

		cpmsg('指定模版成功編輯，現在將轉入模版列表。', "admincp.php?action=templates&edit=$templateid&keyword=$keyword");

	}

} elseif($action == 'tpladd') {

	$query = $db->query("SELECT * FROM $table_templates WHERE templateid='$edit'");
	if(!$template = $db->fetch_array($query)) {
		cpmsg('指定模版套系不存在或已被刪除，請返回。');
	} elseif(!is_dir($discuz_root.'./'.$template[directory])) {
		cpmsg('模版所在目錄 $template[directory] 不存在，請檢查相關設置。');
	} elseif(file_exists($discuz_root."./$template[directory]/$name.htm")) {
		cpmsg('新增模版已經存在，請返回選擇其他名稱。');
	} elseif(!@$fp = fopen($discuz_root."./$template[directory]/$name.htm", 'w')) {
		cpmsg("指定文件 $template[directory]/$name.htm 無法寫入，<br>若需線上編輯，請將該文件和所在目錄屬性設置為 777。");
	}

	@fclose($fp);
	cpmsg('指定模版成功增加，現在將轉入模版編輯頁。', "admincp.php?action=tpledit&templateid=1&fn=$name.htm");
	
}	

?>