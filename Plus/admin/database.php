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
			cpmsg("�u���ƥ�����A���~��ϥΤ����ƥ��\��C");
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
					cpmsg("��Ƥ��L�k�O�s����A���A���ˬd�ؿ��ݩʡC");
				} elseif($multivol) {
					cpmsg("�����ƥ��G��Ƥ�� #$volume ���\�ЫءA�{���N�۰��~��C", "admincp.php?action=export&type=$type&saveto=server&filename=$filename&multivol=1&sizelimit=$sizelimit&volume=$volume&tableid=$tableid&startfrom=$startrow&exportsubmit=yes");
				} else {
					cpmsg("��Ʀ��\�ƥ��ܦ��A�� <a href=\"$filename\">$filename</a> ���C");
				}
			} else {
				cpmsg("�z�S����J�ƥ����W�A�Ъ�^�ק�C");
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
			cpmsg("���߱z�A���� $volume �ӳƥ���󦨥\�ЫءA�ƥ������C\n<br>$filelist");
		} else {
			cpheader();
			cpmsg("�ƥ��X���A��ƪ�S�����e�C");
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
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>��Ƴƥ��\��ھڱz����ܳƥ������׾¤峹�M�]�m��ơA�ɥX����Ƥ��i�Ρu��ƫ�_�v�\��� phpMyAdmin �ɤJ�C</ul>
<ul><li>���󪺳ƥ��u�ݤ�u�ಾ attachments �ؿ��M���Y�i�ADiscuz! �����ѳ�W�ƥ��C</ul>
<ul><li>�j�P��ĳ�G�ƥ�����A���Шϥ� .sql �@���X�i�W�A�o�N����᪺���@�a�ӫܤj��K�C</ul>
</td></tr></table></td></tr></table>

<br><br><form name="backup" method="post" action="admincp.php?action=export">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">��Ƴƥ��覡</td></tr>
<tr>
<td bgcolor="<?=ALTBG1?>" width="40%"><input type="radio" value="all" name="type"> �����ƥ�</td>
<td bgcolor="<?=ALTBG2?>" width="60%">�]�A�׾¥�����ƪ���(�ҪO�M�����󰣥~)</td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>"><input type="radio" value="standard" checked name="type"> �зǳƥ�(����)</td>
<td bgcolor="<?=ALTBG2?>">�]�A�`�Ϊ�������ƪ���</td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>"><input type="radio" value="majority" name="type"> ��²�ƥ�</td>
<td bgcolor="<?=ALTBG2?>">�ȥ]�A�Τ�B�O���]�m�Τ峹���</td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>"><input type="radio" value="mini" name="type" > �̤p�ƥ�</td>
<td bgcolor="<?=ALTBG2?>">�ȥ]�A�Τ�B�O���]�m�Ψt�γ]�m���</td></tr>

<tr bgcolor="<?=ALTBG2?>" class="header"><td colspan="2">��ܥؼЦ�m</td></tr>

<tr bgcolor="<?=ALTBG2?>">
<td colspan="2"><input type="radio" value="local" name="saveto" onclick="this.form.filename.disabled=this.checked;if(this.form.multivol.checked) {alert('�`�N�G\n\n�ƥ��쥻�a�L�k�ϥΤ����ƥ��\��C');this.form.multivol.checked=false;this.form.sizelimit.disabled=true;}"> �ƥ��쥻�a</td></tr>
<tr bgcolor="<?=ALTBG2?>"><td><input type="radio" value="server" checked name="saveto" onclick="this.form.filename.disabled=!this.checked"> �ƥ�����A��</td>
<td><input type="text" size="40" name="filename" value="./forumdata/dz_<?=date('md').'_'.random(5)?>.sql" onclick="alert('�`�N�G\n\n��Ƥ��O�s�b���A�����i���ؿ��U�A��L�H��    \n�i��U���o��o�Ǥ��A�o�O���w�����C�]����    \n�b�ϥ��H�����W���P�ɡA�ήɧR���ƥ����C');"></td>
</tr>


<tr class="header"><td colspan="2">�ϥΤ����ƥ�</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td><input type="checkbox" name="multivol" value="1" onclick="this.form.sizelimit.disabled=!this.checked;if(this.checked && this.form.saveto[1].checked!=true) {alert('�`�N�G\n\n�u����ܳƥ�����A���~��ϥΤ����ƥ��\��C');this.form.saveto[1].checked=true;this.form.filename.disabled=false;}"> �����׭���(KB)</td>
<td><input type="text" size="40" name="sizelimit" value="1024" disabled></td>
</tr></table></td></tr></table><br><center>
<input type="submit" name="exportsubmit" value="�ƥ����"></center></form>
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
			cpmsg("�ؿ����s�b�εL�k�X�ݡA���ˬd ./forumdata/ �ؿ��C");
		}
		krsort($exportlog);
		reset($exportlog);

		$exportinfo = "";
		foreach($exportlog as $dateline => $info) {
			$info[dateline] = is_int($dateline) ? gmdate("$dateformat $timeformat", $dateline + $timeoffset * 3600) : "����";
			switch($info[type]) {
				case all: $info[type] = "����"; break;
				case standard: $info[type] = "�з�"; break;
				case majority: $info[type] = "��²"; break;
				case mini: $info[type] = "�̤p"; break;
			}
			$info[size] = sizecount($info[size]);
			$info[multivol] = $info[multivol] ? "�O" : "�_";
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
				($info['version'] != strip_tags($version) ? " onclick=\"return confirm('�ɤJ�M��e Discuz! �������@�P����Ʒ����i�ಣ�͵L�k�ѨM���G�١A�z�T�w�~��ܡH');\"" : "").">[�ɤJ]</a></td>\n";
		}

?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>���\��b��_�ƥ���ƪ��P�ɡA�N�����л\�즳��ơA�нT�w�O�_�ݭn��_�A�H�K�y����Ʒl���C</ul>
<ul><li>��ƫ�_�\��u���_�ѷ�e���� Discuz! �ɥX����Ƥ��A��L�n��ɥX�榡�i��L�k�ѧO�C</ul>
<ul><li>�q���a��Ƹ�ƻݭn���A���䴩���W�ǨëO�Ҹ�Ƥؤo�p�󤹳\�W�Ǫ��W���A�_�h�u��ϥαq���A����_�C</ul>
<ul><li>�p�G�z�ϥΤF�����ƥ��A�u�ݤ�u�ɤJ���� 1�A��L��Ƥ��|�Ѩt�Φ۰ʾɤJ�C</ul>
</td></tr></table></td></tr></table>

<br><form name="restore" method="post" action="admincp.php?action=import" enctype="multipart/form-data">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header">
<td colspan="2">��ƫ�_</td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>" width="40%"><input type="radio" name="from" value="server" checked onclick="this.form.datafile_server.disabled=!this.checked;this.form.datafile.disabled=this.checked">�q���A��(��g���W�� URL)�G</td>
<td bgcolor="<?=ALTBG2?>" width="60%"><input type="text" size="40" name="datafile_server" value="./forumdata/"></td></tr>

<tr>
<td bgcolor="<?=ALTBG1?>" width="40%"><input type="radio" name="from" value="local" onclick="this.form.datafile_server.disabled=this.checked;this.form.datafile.disabled=!this.checked">�q���a���G</td>
<td bgcolor="<?=ALTBG2?>" width="60%"><input type="file" size="29" name="datafile" disabled></td></tr>

</table></td></tr></table><br><center>
<input type="submit" name="importsubmit" value="��Ƹ��"></center>
</form>

<br><form method="post" action="admincp.php?action=import">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%" class="smalltxt">
<tr class="header"><td colspan="9">��Ƴƥ��O��</td></tr>
<tr align="center" class="category"><td width="45"><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">�R</td>
<td>���W</td><td>����</td>
<td>�ƥ��ɶ�</td><td>����</td>
<td>�ؤo</td><td>�h��</td>
<td>����</td><td>�ާ@</td></tr>
<?=$exportinfo?>
</table></td></tr></table><br><center>
<input type="submit" name="deletesubmit" value="�R����w�ƥ�"></center></form>
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
				cpmsg("������Ʀ��\�ɤJ�׾¸�Ʈw�C");
			} else {
				cpmsg("��Ƥ�󤣦s�b�G�i����A�������\�W�Ǥ��Τؤo�W�L����C");
			}
		} elseif(!strpos($sqldump, "Discuz! Data Dump")) {
			cpmsg("��Ƥ��D Discuz! �榡�A�L�k�ɤJ�C");
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
					cpmsg('������Ʀ��\�ɤJ��Ʈw�A�z�ݭn�۰ʾɤJ������L�����ƥ��ܡH',
						"admincp.php?action=import&from=server&datafile_server=$datafile_next&autoimport=yes&importsubmit=yes",
						'form');
				} elseif($autoimport) {
					cpmsg("��Ƥ�� #$dumpinfo[volume] ���\�ɤJ�A�{���N�۰��~��C", "admincp.php?action=import&from=server&datafile_server=$datafile_next&autoimport=yes&importsubmit=yes");
				} else {
					updatecache();
					cpmsg("��Ʀ��\�ɤJ�׾¸�Ʈw�C");
				}
			} else {
				updatecache();
				cpmsg("��Ʀ��\�ɤJ�׾¸�Ʈw�C");
			}
		}
	} elseif($deletesubmit) {

		if(is_array($delete)) {
			foreach($delete as $filename) {
				@unlink($filename);
			}
			cpmsg("���w�ƥ���󦨥\�R���C");
		} else {
			cpmsg("�z�S����ܭn�R�����ƥ����A�Ъ�^�C");
		}

	}

} elseif($action == "runquery") {

	if(!$sqlsubmit) {

?>
<br><br><form method="post" action="admincp.php?action=runquery">
<table cellspacing="0" cellpadding="0" border="0" width="550" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan=2>Discuz! ��Ʈw�ɯ�</td></tr>
<tr bgcolor="<?=ALTBG1?>" align="center">
<td valign="top">�бN��Ʈw�ɯŻy�y�H�K�b�U���G<br><textarea cols="85" rows="10" name="queries"></textarea><br>
<br><center>�`�N�G���T�O�ɯŦ��\�A�Ф��n�ק� SQL �y�y�����󳡤��C<br><br>
<input type="submit" name="sqlsubmit" value="��Ʈw�ɯ�"></center>
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

		cpmsg($sqlerror ? "�ɯſ��~�AMySQL ���ܡG$sqlerror" : "Discuz! ��Ƶ��c���\�ɯšC");
	}	

} elseif($action == "optimize") {

	$query = $db->query("SELECT VERSION()");
	$dbversion = $db->result($query, 0);
	if($dbversion < '3.23') {
		cpmsg("MySQL �����C�� 3.23�A���䴩�̨Τƥ\��C");
	} else {
?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>��ƪ�̨Τƪ��\��p�P�ϽL��z�{���A���˱z�w���̨ΤƸ�Ʈw�H��ָ�ƸH���A�O���}�n���s���M�˯��ʯ�C</ul>
<ul><li>���\��� MySQL 3.23 �H�W�����䴩�A��e���A�� MySQL �����G<?=$dbversion?>�C</ul>
</td></tr></table></td></tr></table>

<br><br><form name="optimize" method="post" action="admincp.php?action=optimize">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr align="center" class="header">
<td>�̨Τ�</td><td>��ƪ�W</td><td>����</td><td>�O����</td>
<td>���</td><td>����</td><td>�H��</td></tr>
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
			echo "<tr class=\"header\"><td colspan=\"7\" align=\"right\">�@���θ�Ʈw�G".sizecount($totalsize)."</td></tr></table><tr><td align=\"center\"><br><input type=\"submit\" name=\"optimizesubmit\" value=\"�̨ΤƸ�ƪ�\"></td></tr>\n";
		} else {
			$db->unbuffered_query("DELETE FROM $table_subscriptions");
			$query = $db->query("SHOW TABLE STATUS LIKE '$tablepre%'");
			while($table = $db->fetch_array($query)) {
				$tablename = ${$table[Name]};
				if(!$tablename) {
					$tablename = "���̨Τ�";
				} else {
					$tablename = "�̨Τ�";
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
			echo "<tr class=\"header\"><td colspan=\"7\" align=\"right\">�@���θ�Ʈw�G".sizecount($totalsize)."</td></tr></table>";
		}
	}

	echo "</table></form>";
}

?>