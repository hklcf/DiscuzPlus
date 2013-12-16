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

include $discuz_root.'./include/attachment.php';

if($action == "export" && $exportsubmit && $type) {
	$db->query("SET SQL_QUOTE_SHOW_CREATE = 0");
	$sqldump = "";
	$time = gmdate("$dateformat $timeformat", $timestamp + $timeoffset * 3600);
	if($type == "all") {
		$tables = array('announcements', 'attachments', 'banned', 'buddys', 'favorites', 'forumlinks', 'forums',
				'karmalog', 'members', 'postpay', 'posts', 'searchindex', 'sessions', 'settings', 'smilies',
				'stats', 'styles', 'stylevars', 'subscriptions', 'templates', 'threads', 'pm', 'usergroups',
				'words');
	} elseif($type == "standard") {
		$tables = array('announcements', 'attachments', 'banned', 'buddys', 'forumlinks', 'forums', 'karmalog',
				'members', 'postpay', 'posts', 'settings', 'smilies', 'stats', 'styles', 'stylevars',
				'templates', 'threads', 'usergroups', 'words');
	} elseif($type == "majority") {
		$tables = array('attachments', 'forumlinks', 'forums', 'members', 'postpay', 'posts', 'settings', 'smilies', 'stats',
				'styles', 'stylevars', 'templates', 'threads', 'usergroups');
	} elseif($type == "mini") {
		$tables = array('announcements', 'banned', 'forumlinks', 'forums', 'members', 'settings', 'smilies', 'stats',
				'styles', 'stylevars', 'templates', 'usergroups', 'words');
	}

	$sqldump = '';
	if($multivol) {
		if($saveto == 'server') {
			$volume = intval($volume) + 1;
			$tableid = $tableid ? $tableid - 1 : 0;
			$startfrom = intval($startfrom);
			for($i = $tableid; $i < count($tables) && strlen($sqldump) < $sizelimit * 1000; $i++) {
				$sqldump .= sqldumptable($tablepre.$tables[$i], $startfrom, strlen($sqldump));
				$startfrom = 0;
			}
			$tableid = $i;
		} else {
			cpheader();
			cpmsg("只有備份到伺服器才能使用分卷備份功能。");
		}
	} else {
		foreach($tables as $table) {
			$sqldump .= sqldumptable($tablepre.$table);
		}
	}

	$dumpfile = substr($filename, 0, strrpos($filename, "."))."-%s".strrchr($filename, ".");
	if(trim($sqldump)) {
		$dumpversion = strip_tags($version);
		$sqldump = "# Identify: ".base64_encode("$timestamp,$dumpversion,$type,$multivol,$volume")."\n".
			"#\n".
			"# Discuz! Data Dump".($multivol ? " Volume $volume" : NULL)."\n".
			"# Version: Discuz! $dumpversion\n".
			"# Time: $time\n".
			"# Type: $type\n".
			"# Tablepre: $tablepre\n".
			"#\n".
			"# Discuz! Community: http://www.Discuz.net\n".
			"# Please visit our website for newest infomation about Discuz!\n".
			"# --------------------------------------------------------\n\n\n".
			$sqldump;

		if($saveto == "local") {
			ob_end_clean();
			header('Content-Encoding: none');
			header('Content-Type: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
			header('Content-Disposition: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename="dz_'.date('ymd').'.sql"');
			header('Content-Length: '.strlen($sqldump));
			header('Pragma: no-cache');
			header('Expires: 0');
			echo $sqldump;
			discuz_exit();
		} elseif($saveto == "server") {
			cpheader();
			if($filename != "") {
				@$fp = fopen(($multivol ? sprintf($dumpfile, $volume) : $filename), "w");
				@flock($fp, 3);
				if(@!fwrite($fp, $sqldump)) {
					@fclose($fp);
					cpmsg("資料文件無法保存到伺服器，請檢查目錄屬性。");
				} elseif($multivol) {
					cpmsg("分卷備份：資料文件 #$volume 成功創建，程式將自動繼續。", "admincp.php?action=export&type=$type&saveto=server&filename=$filename&multivol=1&sizelimit=$sizelimit&volume=$volume&tableid=$tableid&startfrom=$startrow&exportsubmit=yes");
				} else {
					cpmsg("資料成功備份至伺服器 <a href=\"$filename\">$filename</a> 中。");
				}
			} else {
				cpmsg("您沒有輸入備份文件名，請返回修改。");
			}
		}
	} else {
		if($multivol) {
			$volume--;
			$filelist = "<ul>";
			for($i = 1; $i <= $volume; $i++) {
				$filename = sprintf($dumpfile, $i);
				$filelist .= "<li><a href=\"$filename\">$filename\n";
			}
			cpheader();
			cpmsg("恭喜您，全部 $volume 個備份文件成功創建，備份完成。\n<br>$filelist");
		} else {
			cpheader();
			cpmsg("備份出錯，資料表沒有內容。");
		}
	}
}

cpheader();

if($action == "export") {
	if(!$exportsubmit) {

?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>特別提示</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>資料備份功能根據您的選擇備份全部論壇文章和設置資料，導出的資料文件可用「資料恢復」功能或 phpMyAdmin 導入。</ul>
<ul><li>附件的備份只需手工轉移 attachments 目錄和文件即可，Discuz! 不提供單獨備份。</ul>
<ul><li>強烈建議：備份到伺服器請使用 .sql 作為擴展名，這將給日後的維護帶來很大方便。</ul>
</td></tr></table></td></tr></table>

<br><br><form name="backup" method="post" action="admincp.php?action=export">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">資料備份方式</td></tr>
<tr>
<td bgcolor="<?=ALTBG1?>" width="40%"><input type="radio" value="all" name="type"> 全部備份</td>
<td bgcolor="<?=ALTBG2?>" width="60%">包括論壇全部資料表資料(模板和附件文件除外)</td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>"><input type="radio" value="standard" checked name="type"> 標準備份(推薦)</td>
<td bgcolor="<?=ALTBG2?>">包括常用的全部資料表資料</td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>"><input type="radio" value="majority" name="type"> 精簡備份</td>
<td bgcolor="<?=ALTBG2?>">僅包括用戶、板塊設置及文章資料</td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>"><input type="radio" value="mini" name="type" > 最小備份</td>
<td bgcolor="<?=ALTBG2?>">僅包括用戶、板塊設置及系統設置資料</td></tr>

<tr bgcolor="<?=ALTBG2?>" class="header"><td colspan="2">選擇目標位置</td></tr>

<tr bgcolor="<?=ALTBG2?>">
<td colspan="2"><input type="radio" value="local" name="saveto" onclick="this.form.filename.disabled=this.checked;if(this.form.multivol.checked) {alert('注意：\n\n備份到本地無法使用分卷備份功能。');this.form.multivol.checked=false;this.form.sizelimit.disabled=true;}"> 備份到本地</td></tr>
<tr bgcolor="<?=ALTBG2?>"><td><input type="radio" value="server" checked name="saveto" onclick="this.form.filename.disabled=!this.checked"> 備份到伺服器</td>
<td><input type="text" size="40" name="filename" value="./forumdata/dz_<?=date('md').'_'.random(5)?>.sql" onclick="alert('注意：\n\n資料文件保存在伺服器的可見目錄下，其他人有    \n可能下載得到這些文件，這是不安全的。因此請    \n在使用隨機文件名的同時，及時刪除備份文件。');"></td>
</tr>


<tr class="header"><td colspan="2">使用分卷備份</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td><input type="checkbox" name="multivol" value="1" onclick="this.form.sizelimit.disabled=!this.checked;if(this.checked && this.form.saveto[1].checked!=true) {alert('注意：\n\n只有選擇備份到伺服器才能使用分卷備份功能。');this.form.saveto[1].checked=true;this.form.filename.disabled=false;}"> 文件長度限制(KB)</td>
<td><input type="text" size="40" name="sizelimit" value="1024" disabled></td>
</tr></table></td></tr></table><br><center>
<input type="submit" name="exportsubmit" value="備份資料"></center></form>
<?

	}

} elseif($action == 'import') {

	 if(!$importsubmit && !$deletesubmit) {
	 	$exportlog = array();
	 	if(is_dir($discuz_root.'./forumdata')) {
	 		$dir = dir($discuz_root.'./forumdata');
			while($entry = $dir->read()) {
				$entry = "./forumdata/$entry";
				if (is_file($entry) && strtolower(strrchr($entry, ".")) == ".sql") {
					$filesize = filesize($entry);
					$fp = fopen($entry, "r");
					$identify = explode(",", base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", fgets($fp, 256))));
					fclose ($fp);
 
					$exportlog[$identify[0]] = array(	"version" => $identify[1],
										"type" => $identify[2],
										"multivol" => $identify[3],
										"volume" => $identify[4],
										"filename" => $entry,
										"size" => $filesize);
				}
			}
			$dir->close();
		} else {
			cpmsg("目錄不存在或無法訪問，請檢查 ./forumdata/ 目錄。");
		}
		krsort($exportlog);
		reset($exportlog);

		$exportinfo = "";
		foreach($exportlog as $dateline => $info) {
			$info[dateline] = is_int($dateline) ? gmdate("$dateformat $timeformat", $dateline + $timeoffset * 3600) : "未知";
			switch($info[type]) {
				case all: $info[type] = "全部"; break;
				case standard: $info[type] = "標準"; break;
				case majority: $info[type] = "精簡"; break;
				case mini: $info[type] = "最小"; break;
			}
			$info[size] = sizecount($info[size]);
			$info[multivol] = $info[multivol] ? "是" : "否";
			$info[volume] = $info[multivol] ? $info[volume] : "";
			$exportinfo .= "<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[]\" value=\"$info[filename]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\"><a href=\"$info[filename]\">".substr(strrchr($info[filename], "/"), 1)."</a></td>\n".
				"<td bgcolor=\"".ALTBG1."\">$info[version]</td>\n".
				"<td bgcolor=\"".ALTBG2."\">$info[dateline]</td>\n".
				"<td bgcolor=\"".ALTBG1."\">$info[type]</td>\n".
				"<td bgcolor=\"".ALTBG2."\">$info[size]</td>\n".
				"<td bgcolor=\"".ALTBG1."\">$info[multivol]</td>\n".
				"<td bgcolor=\"".ALTBG2."\">$info[volume]</td>\n".
				"<td bgcolor=\"".ALTBG1."\"><a href=\"admincp.php?action=import&from=server&datafile_server=$info[filename]&importsubmit=yes\"".
				($info['version'] != strip_tags($version) ? " onclick=\"return confirm('導入和當前 Discuz! 版本不一致的資料極有可能產生無法解決的故障，您確定繼續嗎？');\"" : "").">[導入]</a></td>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>特別提示</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>本功能在恢復備份資料的同時，將全部覆蓋原有資料，請確定是否需要恢復，以免造成資料損失。</ul>
<ul><li>資料恢復功能只能恢復由當前版本 Discuz! 導出的資料文件，其他軟體導出格式可能無法識別。</ul>
<ul><li>從本地恢複資料需要伺服器支援文件上傳並保證資料尺寸小於允許上傳的上限，否則只能使用從伺服器恢復。</ul>
<ul><li>如果您使用了分卷備份，只需手工導入文件卷 1，其他資料文件會由系統自動導入。</ul>
</td></tr></table></td></tr></table>

<br><form name="restore" method="post" action="admincp.php?action=import" enctype="multipart/form-data">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header">
<td colspan="2">資料恢復</td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>" width="40%"><input type="radio" name="from" value="server" checked onclick="this.form.datafile_server.disabled=!this.checked;this.form.datafile.disabled=this.checked">從伺服器(填寫文件名或 URL)：</td>
<td bgcolor="<?=ALTBG2?>" width="60%"><input type="text" size="40" name="datafile_server" value="./forumdata/"></td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>" width="40%"><input type="radio" name="from" value="local" onclick="this.form.datafile_server.disabled=this.checked;this.form.datafile.disabled=!this.checked">從本地文件：</td>
<td bgcolor="<?=ALTBG2?>" width="60%"><input type="file" size="29" name="datafile" disabled></td></tr>

</table></td></tr></table><br><center>
<input type="submit" name="importsubmit" value="恢複資料"></center>
</form>

<br><form method="post" action="admincp.php?action=import">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%" class="smalltxt">
<tr class="header"><td colspan="9">資料備份記錄</td></tr>
<tr align="center" class="category"><td width="45"><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">刪</td>
<td>文件名</td><td>版本</td>
<td>備份時間</td><td>類型</td>
<td>尺寸</td><td>多卷</td>
<td>卷號</td><td>操作</td></tr>
<?=$exportinfo?>
</table></td></tr></table><br><center>
<input type="submit" name="deletesubmit" value="刪除選定備份"></center></form>
<?

	 } elseif($importsubmit) {

		$readerror = 0;
		if($from == "server") {
			$datafile = $datafile_server;
			$datafile_size = @filesize($datafile_server);
		}
		@$fp = fopen($datafile, "r");
		if($datafile_size) {
			@flock($fp, 3);
			$sqldump = @fread($fp, $datafile_size);
		} else {
			$sqldump = @fread($fp, 99999999);
		}
		@fclose($fp);
		if(!$sqldump) {
			if($autoimport) {
				updatecache();
				cpmsg("分卷資料成功導入論壇資料庫。");
			} else {
				cpmsg("資料文件不存在：可能伺服器不允許上傳文件或尺寸超過限制。");
			}
		} elseif(!strpos($sqldump, "Discuz! Data Dump")) {
			cpmsg("資料文件非 Discuz! 格式，無法導入。");
		} else {
			$identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", substr($sqldump,0, 256))));
			$dumpinfo = array('multivol' => $identify[3], 'volume' => intval($identify[4]));

			$sqlquery = splitsql($sqldump);
			unset($sqldump);
			foreach($sqlquery as $sql) {
				if(trim($sql) != '') {
					$db->query($sql);
				}
			}

			if($dumpinfo['multivol']) {
				$datafile_next = str_replace("-$dumpinfo[volume].sql", '-'.($dumpinfo['volume'] + 1).'.sql', $datafile_server);
				if($dumpinfo['volume'] == 1) {
					cpmsg('分卷資料成功導入資料庫，您需要自動導入本次其他的的備份嗎？',
						"admincp.php?action=import&from=server&datafile_server=$datafile_next&autoimport=yes&importsubmit=yes",
						'form');
				} elseif($autoimport) {
					cpmsg("資料文件 #$dumpinfo[volume] 成功導入，程式將自動繼續。", "admincp.php?action=import&from=server&datafile_server=$datafile_next&autoimport=yes&importsubmit=yes");
				} else {
					updatecache();
					cpmsg("資料成功導入論壇資料庫。");
				}
			} else {
				updatecache();
				cpmsg("資料成功導入論壇資料庫。");
			}
		}
	} elseif($deletesubmit) {

		if(is_array($delete)) {
			foreach($delete as $filename) {
				@unlink($filename);
			}
			cpmsg("指定備份文件成功刪除。");
		} else {
			cpmsg("您沒有選擇要刪除的備份文件，請返回。");
		}

	}

} elseif($action == "runquery") {

	if(!$sqlsubmit) {

?>
<br><br><form method="post" action="admincp.php?action=runquery">
<table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan=2>Discuz! 資料庫升級</td></tr>
<tr bgcolor="<?=ALTBG1?>" align="center">
<td valign="top">請將資料庫升級語句黏貼在下面：<br><textarea cols="85" rows="10" name="queries"></textarea><br>
<br><center>注意：為確保升級成功，請不要修改 SQL 語句的任何部分。<br><br>
<input type="submit" name="sqlsubmit" value="資料庫升級"></center>
</td>
</tr>
</table>
</td></tr></table>
</form></td></tr>
<?

	} else {

		$sqlquery = splitsql(str_replace(" cdb_", " $tablepre", $queries));
		foreach($sqlquery as $sql) {
			if(trim($sql) != "") {
				$db->query(stripslashes($sql), 1);
				$sqlerror = $db->error();
				if($sqlerror) {
					break;
				}
			}
		}

		cpmsg($sqlerror ? "升級錯誤，MySQL 提示：$sqlerror" : "Discuz! 資料結構成功升級。");
	}	

} elseif($action == "optimize") {

	$query = $db->query("SELECT VERSION()");
	$dbversion = $db->result($query, 0);
	if($dbversion < '3.23') {
		cpmsg("MySQL 版本低於 3.23，不支援最佳化功能。");
	} else {
?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>特別提示</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>資料表最佳化的功能如同磁盤整理程式，推薦您定期最佳化資料庫以減少資料碎片，保持良好的存取和檢索性能。</ul>
<ul><li>本功能需 MySQL 3.23 以上版本支援，當前伺服器 MySQL 版本：<?=$dbversion?>。</ul>
</td></tr></table></td></tr></table>

<br><br><form name="optimize" method="post" action="admincp.php?action=optimize">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr align="center" class="header">
<td>最佳化</td><td>資料表名</td><td>類型</td><td>記錄數</td>
<td>資料</td><td>索引</td><td>碎片</td></tr>
<?
		$optimizetable = "";
		$totalsize = 0;
		if(!$optimizesubmit) {
			$query = $db->query("SHOW TABLE STATUS LIKE '$tablepre%'");
			while($table = $db->fetch_array($query)) {
				echo "<tr>\n".
					"<td bgcolor=\"".ALTBG1."\" align=\"center\"><input type=\"checkbox\" name=\"$table[Name]\" value=\"1\" checked></td>\n".
					"<td td onClick=\"document.optimize.$table[Name].checked = !document.optimize.$table[Name].checked;\" style=\"cursor: hand\" onmouseover=\"this.style.backgroundColor='".ALTBG1."';\" onmouseout=\"this.style.backgroundColor='".ALTBG2."';\" bgcolor=\"".ALTBG2."\" align=\"center\">$table[Name]</td>\n".
					"<td bgcolor=\"".ALTBG1."\" align=\"center\">$table[Type]</td>\n".
					"<td bgcolor=\"".ALTBG2."\" align=\"center\">$table[Rows]</td>\n".
					"<td bgcolor=\"".ALTBG1."\" align=\"center\">$table[Data_length]</td>\n".
					"<td bgcolor=\"".ALTBG2."\" align=\"center\">$table[Index_length]</td>\n".
					"<td bgcolor=\"".ALTBG1."\" align=\"center\">$table[Data_free]</td>\n".
					"</tr>\n";
				$totalsize += $table[Data_length] + $table[Index_length];
			}
			echo "<tr class=\"header\"><td colspan=\"7\" align=\"right\">共佔用資料庫：".sizecount($totalsize)."</td></tr></table><tr><td align=\"center\"><br><input type=\"submit\" name=\"optimizesubmit\" value=\"最佳化資料表\"></td></tr>\n";
		} else {
			$db->unbuffered_query("DELETE FROM $table_subscriptions");
			$query = $db->query("SHOW TABLE STATUS LIKE '$tablepre%'");
			while($table = $db->fetch_array($query)) {
				$tablename = ${$table[Name]};
				if(!$tablename) {
					$tablename = "未最佳化";
				} else {
					$tablename = "最佳化";
					$db->query("OPTIMIZE TABLE $table[Name]");
				}
				echo "<tr>\n".
					"<td bgcolor=\"".ALTBG1."\" align=\"center\">$tablename</td>\n".
					"<td bgcolor=\"".ALTBG2."\" align=\"center\">$table[Name]</td>\n".
					"<td bgcolor=\"".ALTBG1."\" align=\"center\">$table[Type]</td>\n".
					"<td bgcolor=\"".ALTBG2."\" align=\"center\">$table[Rows]</td>\n".
					"<td bgcolor=\"".ALTBG1."\" align=\"center\">$table[Data_length]</td>\n".
					"<td bgcolor=\"".ALTBG2."\" align=\"center\">$table[Index_length]</td>\n".
					"<td bgcolor=\"".ALTBG1."\" align=\"center\">0</td>\n".
					"</tr>\n";
				$totalsize += $table[Data_length] + $table[Index_length];
			}
			echo "<tr class=\"header\"><td colspan=\"7\" align=\"right\">共佔用資料庫：".sizecount($totalsize)."</td></tr></table>";
		}
	}

	echo "</table></form>";
}

?>