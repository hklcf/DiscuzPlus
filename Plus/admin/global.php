<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

@set_time_limit(600);

if($action && $action != 'main' && $action != 'header' && $action != 'menu' && !strpos($action, 'log')) {
	$extra = $semicolon = '';
	if(is_array($HTTP_GET_VARS)) {
		foreach($HTTP_GET_VARS as $key => $val) {
			if($key != 'action' && $key != 'sid') {
				$extra .= "$semicolon$key=$val";
				$semicolon = '; ';
			}
		}
	}

	@$fp = fopen($discuz_root.'./forumdata/cplog.php', 'a');
	@flock($fp, 3);
	@fwrite($fp, "$discuz_user\t$onlineip\t$timestamp\t$action\t$extra\n");
	@fclose($fp);
}

function cpmsg($message, $url_forward = '', $msgtype = 'message') {
	extract($GLOBALS, EXTR_OVERWRITE);
	if($msgtype == 'form') {
		$message = "<form method=\"post\" action=\"$url_forward\"><br><br><br>$message<br><br><br><br>\n".
        		"<input type=\"submit\" name=\"confirmed\" value=\"確 定\"> &nbsp; \n".
       			"<input type=\"button\" value=\"返 回\" onClick=\"history.go(-1);\"></form><br>";
	} else {
		if($url_forward) {
			$message .= "<br><br><br><a href=\"$url_forward\">如果您的瀏覽器沒有自動跳轉，請點擊這裡</a>";
			$url_forward = url_rewriter($url_forward);
			$message .= "<script>setTimeout(\"redirect('$url_forward');\", 1250);</script>";
		} elseif(strpos($message, "返回")) {
			$message .= "<br><br><br><a href=\"javascript:history.go(-1);\" class=\"mediumtxt\">[ 點這裡返回上一頁 ]</a>";
		}
		$message = "<br><br><br>$message<br><br>";
	}

?>
<br><br><br><br><br><br><table cellspacing="0" cellpadding="0" border="0" width="460" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>"><table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>Discuz! Message</td></tr><tr><td bgcolor="<?=ALTBG2?>" align="center">
<table border="0" width="90%" cellspacing="0" cellpadding="0"><tr><td width="100%" align="center">
<?=$message?><br><br>
</td></tr></table></td></tr></table></td></tr></table><br><br><br>
<?

	cpfooter();
	discuz_exit();
}

function dir_writeable($dir) {
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.test", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.test");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function showforum($forum, $id, $type = '') {
	$dot = array(1 => "<li>", 2 => "<li type=\"circle\">", 3 => "<li type=\"square\">");
	$url = $type == "group" ? "./index.php?gid=$forum[fid]" : "./forumdisplay.php?fid=$forum[fid]";
	$editforum = "<a href=\"admincp.php?action=forumdetail&fid=$forum[fid]\">[編輯]</a> ";
	$hide = !$forum[status] ? " (隱藏)" : NULL;
	echo $dot[$id]."<a href=\"$url\" target=\"_blank\"><b>$forum[name]</b>$hide</a> - 順序：<input type=\"text\" name=\"order[{$forum[fid]}]\" value=\"$forum[displayorder]\" size=\"1\">".
		($forum['type'] != 'group' ? "&nbsp; 版主：<input type=\"text\" name=\"moderator[{$forum[fid]}]\" value=\"$forum[moderator]\" size=\"15\"> - " : " - ").
		"$editforum<a href=\"admincp.php?action=forumdelete&fid=$forum[fid]\">".
		"[刪除]</a><br></li>\n";
}

function showtype($name, $type = "") {
	if($type != "bottom") {
		if(!$type) {
			echo "</table></td></tr></table><br><br>\n";
		}
		if(!$type || $type == "top") {

?>
<a name="#<?=$name?>"></a>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header">
<td colspan="2"><?=$name?></td>
</tr>
<?

		}
	} else {
		echo "</table></td></tr></table>\n";
	}
}

function showsetting($setname, $varname, $value, $type = 'radio', $comment = '', $width = '60%') {
	$setname = "<b>$setname</b>";
	if($comment) {
		$setname .= "<br>$comment";
	}
	$aligntop = $type == "textarea" || $width != "60%" ?  "valign=\"top\"" : NULL;
	echo "<tr><td width=\"$width\" bgcolor=\"".ALTBG1."\" $aligntop>$setname</td>\n".
		"<td bgcolor=\"".ALTBG2."\">\n";

	if($type == 'radio') {
		$value ? $checktrue = "checked" : $checkfalse = "checked";
		echo "<input type=\"radio\" name=\"$varname\" value=\"1\" $checktrue> 是 &nbsp; \n".
			"<input type=\"radio\" name=\"$varname\" value=\"0\" $checkfalse> 否\n";
	} elseif($type == 'color') {
		$preview_varname = str_replace('[', '_', str_replace(']', '', $varname));
		echo "<input type=\"text\" size=\"30\" value=\"$value\" name=\"$varname\" onchange=\"this.form.$preview_varname.style.backgroundColor=this.value;\">\n".
			"<input type=\"button\" id=\"$preview_varname\" value=\"     \" style=\"background-color: $value\" disabled>\n";
	} elseif($type == 'text') {
		echo "<input type=\"text\" size=\"30\" value=\"$value\" name=\"$varname\">\n";
	} elseif($type == "textarea") {
		echo "<textarea rows=\"5\" name=\"$varname\" cols=\"30\">".htmlspecialchars($value)."</textarea>";
	} else {
		echo $type;
	}
	echo "</td></tr>\n";
}

function showmenu($title, $menus = array()) {
	global $menucount, $expand;

?>
<tr><td bgcolor="<?=ALTBG1?>"><a name="#<?=$menucount?>"></a>
<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center"> 
<tr><td bgcolor="<?=BORDERCOLOR?>"> 
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%"> 
<?

	if(is_array($menus)) {
		$menucount++;
		$expanded = preg_match("/(^|_)$menucount($|_)/is", $expand);
		echo "<tr><td width=\"100%\" class=\"header\"><img src=\"images/common/".($expanded ? "minus" : "plus").".gif\"><a href=\"admincp.php?action=menu&expand=$expand&change=$menucount#$menucount\" style=\"color: ".HEADERTEXT."\">$title</td></tr>\n";
		if($expanded) {
			foreach($menus as $menu) {
				echo "<tr><td bgcolor=\"".ALTBG2."\" onMouseOver=\"this.style.backgroundColor='".ALTBG1."'\" onMouseOut=\"this.style.backgroundColor='".ALTBG2."'\"><img src=\"images/common/spacer.gif\"><a href=\"".url_rewriter($menu[url])."\" target=\"main\">$menu[name]</a></td></tr>";
			}
		}
	} else {
		echo "<tr><td width=\"100%\" class=\"header\"><img src=\"images/common/plus.gif\"><a href=\"".url_rewriter($menus)."\" target=\"main\" style=\"color: ".HEADERTEXT."\">$title</a></td></tr>\n";
	}
	echo "</table></td></tr></table></td></tr>";
}

function sqldumptable($table, $startfrom = 0, $currsize = 0) {
	global $db, $multivol, $sizelimit, $startrow;

	$offset = 64;
	if(!$startfrom) {
		$tabledump = "DROP TABLE IF EXISTS $table;\n";

		$createtable = $db->query("SHOW CREATE TABLE $table");
		$create = $db->fetch_row($createtable);

		$tabledump .= $create[1].";\n\n";

/*		$tabledump .= "CREATE TABLE $table (\n";

		$firstfield = 1;

		$fields = $db->query("SHOW FIELDS FROM $table");
		while ($field = $db->fetch_array($fields)) {
			if (!$firstfield) {
				$tabledump .= ",\n";
			} else {
				$firstfield = 0;
			}
			$tabledump .= "\t$field[Field] $field[Type]";

			if ($field[Null] != "YES") {
				$tabledump .= " NOT NULL";
			}

			if (!empty($field["Default"])) {
				$tabledump .= " default '$field[Default]'";
			}
			if ($field[Extra] != "") {
				$tabledump .= " $field[Extra]";
			}
		}

		$db->free_result($fields);

		$keys = $db->query("SHOW KEYS FROM $table");
		while ($key = $db->fetch_array($keys)) {
			$kname = $key['Key_name'];
			if ($kname != "PRIMARY" and $key['Non_unique'] == 0) {
				$kname="UNIQUE|$kname";
			}
			if(!is_array($index[$kname])) {
				$index[$kname] = array();
			}
			$index[$kname][] = $key['Column_name'];
		}

		$db->free_result($keys);

		while(list($kname, $columns) = @each($index)){
			$tabledump .= ",\n";
			$colnames = implode($columns, ",");

			if($kname == "PRIMARY"){
				$tabledump .= "\tPRIMARY KEY ($colnames)";
			} else {
				if (substr($kname,0,6) == "UNIQUE") {
					$kname = substr($kname,7);
				}

				$tabledump .= "\tKEY $kname ($colnames)";

			}
		}

		$tabledump .= "\n);\n\n";
*/
	}

	$tabledumped = 0;
	$numrows = $offset;
	while(($multivol && $currsize + strlen($tabledump) < $sizelimit * 1000 && $numrows == $offset) || (!$multivol && !$tabledumped)) {
		$tabledumped = 1;
		if($multivol) {
			$limitadd = "LIMIT $startfrom, $offset";
			$startfrom += $offset;
		}

		$rows = $db->query("SELECT * FROM $table $limitadd");
		$numfields = $db->num_fields($rows);
		$numrows = $db->num_rows($rows);
		while ($row = $db->fetch_row($rows)) {
			$comma = "";
			$tabledump .= "INSERT INTO $table VALUES(";
			for($i = 0; $i < $numfields; $i++) {
				$tabledump .= $comma."'".mysql_escape_string($row[$i])."'";
				$comma = ",";
			}
			$tabledump .= ");\n";
		}
	}

	$startrow = $startfrom;
	$tabledump .= "\n";
	return $tabledump;
}

function splitsql($sql){
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == "#" ? NULL : $query;
		}
		$num++;
	}
	return($ret);
}

function cpheader() {
	extract($GLOBALS, EXTR_SKIP);

	echo '<html><head>';
	echo '<meta http-equiv="Content-Type" content="text/html; charset='.CHARSET.'">';
	include template('css');

?>

<script language="JavaScript">
function checkall(form) {
	for(var i = 0;i < form.elements.length; i++) {
		var e = form.elements[i];
		if (e.name != 'chkall' && e.disabled != true) {
			e.checked = form.chkall.checked;
		}
	}
}

function redirect(url) {
	window.location.replace(url);
}
</script>

<script language="JavaScript">
<!--
function select_all(formName, elementName, selectAllName) {
	if(document.forms[formName].elements[selectAllName].checked)
		for(var i = 0; i < document.forms[formName].elements[elementName].length; i++)
		document.forms[formName].elements[elementName][i].checked = true;
	else
		for(var i = 0; i < document.forms[formName].elements[elementName].length; i++)
		document.forms[formName].elements[elementName][i].checked = false;
}
//-->
</script>
</head>

<body <?=BGCODE?> text="<?=TEXT?>" leftmargin="10" topmargin="10">
<br>
<?

}

function cpfooter() {
	global $version;

?>
<br><br><br><br><hr size="0" noshade color="<?=BORDERCOLOR?>" width="80%"><center><font style="font-size: 11px; font-family: Tahoma, Verdana, Arial">
Powered by <a href="http://discuz.hklcf.com" style="color: <?=TEXT?>"><b>Discuz! Plus</b> <?=$version?></a> &nbsp;&copy; 2004, <b>
<a href="http://www.hklcf.com" target="_blank" style="color: <?=TEXT?>">HKLCF.COM</a></b></font>

</body>
</html>
<?

}

function dirsize($dir) { 
	@$dh = opendir($dir);
	$size = 0;
	while ($file = @readdir($dh)) {
		if ($file != "." and $file != "..") {
			$path = $dir."/".$file;
			if (is_dir($path)) {
				$size += dirsize($path);
			} elseif (is_file($path)) {
				$size += filesize($path);
			}
		}
	}
	@closedir($dh);
	return $size;
}

?>