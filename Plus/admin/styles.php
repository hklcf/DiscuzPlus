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
		cpmsg('指定的風格不存在，無法導出。');
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
				"<td bgcolor=\"".ALTBG1."\"><a href=\"admincp.php?action=styles&export=$styleinfo[styleid]\">[下載]</a></td>\n".
				"<td bgcolor=\"".ALTBG2."\"><a href=\"admincp.php?action=styles&edit=$styleinfo[styleid]\">[詳情]</a></td></tr>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>特別提示</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br>
<ul><li>您可以指導用戶通過在 URL 加入 styleid 變量來輕鬆切換當前界面風格，如 http://your.com/discuz/index.php?styleid=2 或 http://your.com/discuz/forumdisplay.php?fid=2&styleid=3</ul>
<ul><li>界面方案的『可用』是指用戶是否選擇使用此風格，但無論設置可用與否，都可以將此界面設置為預設界面或某分論壇的預設界面。</ul>
</td></tr></table></td></tr></table><br><br>

<form method="post" action="admincp.php?action=styles">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header" align="center">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">刪</td>
<td>方案名稱</td><td>可用</td><td>styleID</td><td>所用模版</td><td>字元集</td><td>導出</td><td>編輯</td></tr>
<?=$styleselect?>
<tr bgcolor="<?=ALTBG2?>"><td height="1" colspan="8"></td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>">新增：</td>
<td bgcolor="<?=ALTBG2?>"><input type='text' name="newname" size="18"></td>
<td colspan="6" bgcolor="<?=ALTBG2?>">&nbsp;</td>
</tr></table></td></tr></table><br>
<center><input type="submit" name="stylesubmit" value="更新界面設置"></center></form>

<br><form method="post" action="admincp.php?action=styles">
<table cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="90%" bgcolor="<?=BORDERCOLOR?>" align="center">
<tr class="header"><td>導入界面方案 - 請將導出的文件內容黏貼如下</td></tr>
<tr><td bgcolor="<?=ALTBG1?>" align="center"><textarea  name="styledata" cols="80" rows="8"></textarea><br>
<input type="checkbox" name="ignoreversion" value="1"> 允許導入不同版本 Discuz! 的界面(易產生錯誤！)</td></tr>
</table><br><center><input type="submit" name="importsubmit" value="將風格文件導入 Discuz!"></center></form>
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
				cpmsg("您不能直接刪除系統預設的風格，請返回選擇其他風格為預設後再進行刪除。");
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

		cpmsg("界面方案成功更新。");

	} elseif($importsubmit) {

		$styledata = preg_replace("/(#.*\s+)*/", '', $styledata);
		$stylearray = daddslashes(unserialize(base64_decode($styledata)), 1);

		if(!is_array($stylearray)) {
			cpmsg("界面文件已損壞，無法導入，請返回。");
		}

		if(empty($ignoreversion) && strip_tags($stylearray['version']) != strip_tags($version)) {
			cpmsg("導出界面所用 Discuz! ($stylearray[version])與當前版本($version)不一致，請返回。");
		}

		if($stylearray['templateid'] != 1) {
			$templatedir = $discuz_root.'./'.$stylearray['directory'];
			if(!is_dir($templatedir)) {
				if(!@mkdir($templatedir, 0777)) {
					$basedir = dirname($stylearray['directory']);
					cpmsg("模版目錄無法自動建立，請設置 $basedir 目錄屬性為 777<br>或通過 FTP 建立目錄 $stylearray[directory] 並設置屬性為 777。");
				}
			}

			foreach($stylearray['template'] as $name => $file) {
				$name = $templatedir.'/'.str_replace('_DOT_', '.', $name);
				if(file_exists($name)) {
					cpmsg("文件名重複，請返回確認模版目錄為空後再試。");
				}
				if(!$fp = fopen($name, 'wb')) {
					cpmsg("模版文件無法寫入，請返回設置 $stylearray[directory] 目錄屬性為 777 後再試。");
				}
				flock($fp, 3);
				fwrite($fp, $file);
				fclose($fp);
			}

			$renameinfo = '';
			$query = $db->query("SELECT COUNT(*) FROM $table_templates WHERE name='$stylearray[tplname]'");
			if($db->result($query, 0)) {
				$stylearray['tplname'] .= '_'.random(4);
				$renameinfo .= "導入模版名稱與現有模版重複，新模版被重命名為<br><b>$stylearray[tplname]</b>。";
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
			$renameinfo .= "導入名稱與現有界面重複，新界面被重命名為<br><b>$stylearray[name]</b>。";
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
		cpmsg($renameinfo.'界面方案成功導入。');

	} elseif($edit) {

		if(!$editsubmit) {

			$query = $db->query("SELECT name, templateid FROM $table_styles WHERE styleid='$edit'");
			if(!$style = $db->fetch_array($query)) {
				cpmsg("指定論壇界面不存在，請返回。");
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

			showtype('編輯界面方案 - '.$style['name'], 'top');
			showsetting('界面方案名稱：', 'namenew', $style['name'], 'text', "識別界面風格的標誌，請勿使用空格或特殊符號", '55%');
			showsetting('匹配模版：', '', '', $tplselect, '與本套界面風格相匹配的模版名稱', '55%');
			showsetting('論壇 logo：', "stylevar[{$stylestuff[boardimg][id]}]", $stylestuff['boardimg']['subst'], 'text', "如使用 Flash  動畫，請用逗號隔開 URL，寬度和高度，如“logo.swf,80,40”", '55%');
			showsetting('界面圖片目錄：', "stylevar[{$stylestuff[imgdir][id]}]", $stylestuff['imgdir']['subst'], 'text', '', '55%');
			showsetting('Smilies 圖片目錄：', "stylevar[{$stylestuff[smdir][id]}]", $stylestuff['smdir']['subst'], 'text', '', '55%');

			showtype('文字及顏色設置');
			showsetting('禁用粗體字顯示：', "stylevar[{$stylestuff[nobold][id]}]", $stylestuff['nobold']['subst'], 'radio', "選擇「是」前臺將不顯示任何粗體字內容", '55%');
			showsetting('正常字體設置：', "stylevar[{$stylestuff[font][id]}]", $stylestuff['font']['subst'], 'text', "多個候選字體間請用半形逗號 \",\" 分割", '55%');
			showsetting('正常字號設置：', "stylevar[{$stylestuff[fontsize][id]}]", $stylestuff['fontsize']['subst'], 'text', "可使用字號、pt、px(推薦) 等單位", '55%');
			showsetting('小號字體設置：', "stylevar[{$stylestuff[smfont][id]}]", $stylestuff['smfont']['subst'], 'text', "多個候選字體間請用半形逗號 \",\" 分割", '55%');
			showsetting('小號字號設置：', "stylevar[{$stylestuff[smfontsize][id]}]", $stylestuff['smfontsize']['subst'], 'text', "可使用字號、pt、px(推薦) 等單位", '55%');
			showsetting('超連結文字顏色：', "stylevar[{$stylestuff[link][id]}]", $stylestuff['link']['subst'], 'color', '', '55%');
			showsetting('表頭文字顏色：', "stylevar[{$stylestuff[headertext][id]}]", $stylestuff['headertext']['subst'], 'color', '', '55%');
			showsetting('欄目文字顏色：', "stylevar[{$stylestuff[cattext][id]}]", $stylestuff['cattext']['subst'], 'color', '', '55%');
			showsetting('表格中文字顏色：', "stylevar[{$stylestuff[tabletext][id]}]", $stylestuff['tabletext']['subst'], 'color', '', '55%');
			showsetting('頁面中 (表格除外) 文字顏色：', "stylevar[{$stylestuff[text][id]}]", $stylestuff['text']['subst'], 'color', '', '55%');

			showtype('表格與背景顏色設置');
			showsetting('表格邊框寬度：', "stylevar[{$stylestuff[borderwidth][id]}]", $stylestuff['borderwidth']['subst'], 'text', '', '55%');
			showsetting('表格邊緣空隙：', "stylevar[{$stylestuff[tablespace][id]}]", $stylestuff['tablespace']['subst'],   'text', '', '55%');
			showsetting('表格邊框顏色：', "stylevar[{$stylestuff[bordercolor][id]}]", $stylestuff['bordercolor']['subst'], 'color', '', '55%');
			showsetting('頁面背景：', "stylevar[{$stylestuff[bgcolor][id]}]", $stylestuff['bgcolor']['subst'], 'color', "輸入 16 進制顏色或圖片連結", '55%');
			showsetting('表頭背景顏色：', "stylevar[{$stylestuff[headercolor][id]}]", $stylestuff['headercolor']['subst'], 'color', "輸入   16 進制顏色或圖片連結", '55%');
			showsetting('欄目背景顏色：', "stylevar[{$stylestuff[catcolor][id]}]", $stylestuff['catcolor']['subst'], 'color', "輸入 16 進制顏色或圖片連結", '55%');
			showsetting('表格背景配色 1：', "stylevar[{$stylestuff[altbg1][id]}]", $stylestuff['altbg1']['subst'], 'color', "建議設置為相對表格背景色 2 較深的顏色", '55%');
			showsetting('表格背景配色 2：', "stylevar[{$stylestuff[altbg2][id]}]", $stylestuff['altbg2']['subst'], 'color', "建議設置為相對表格背景色 1 較淺的顏色", '55%');
			showsetting('表格寬度：', "stylevar[{$stylestuff[tablewidth][id]}]", $stylestuff['tablewidth']['subst'], 'text', "可設置為像素或百分比", '55%');
			showtype('', "bottom");

?>
<br><br>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header" align="center">
<td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">刪</td>
<td>變量</td><td>替換內容</td></tr>
<?=$stylecustom?>
<tr bgcolor="<?=ALTBG2?>"><td height="1" colspan="3"></td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>">新增：</td>
<td bgcolor="<?=ALTBG2?>"><input type='text' name="newcvar" size="20"></td>
<td bgcolor="<?=ALTBG1?>"><textarea name="newcsubst" cols="50" rows="2"></textarea></td>
</tr></table></td></tr></table><br>
<?

			echo "<br><center><input type=\"submit\" name=\"editsubmit\" value=\"更新方案設置\"></center></form>";

		} else {

			if($newcvar && $newcsubst) {
				$query = $db->query("SELECT COUNT(*) FROM $table_stylevars WHERE variable='$newcvar' AND styleid='$edit'");
				if($db->result($query, 0)) {
					cpmsg('新增的替換變量名已經存在，請返回修改。');
				} elseif(!preg_match("/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/s", $newcvar)) {
					cpmsg('新增的替換變量名稱不合法，請返回修改。');
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
			cpmsg("界面方案成功更新。");

		}

	}

}

?>