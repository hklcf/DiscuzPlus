<?php

/*
	Version: 1.1.4(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

function arrayeval($array, $level = 0) {
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "Array\n$space(\n";
	$comma = "$space";
	foreach($array as $key => $val) {
		$key = is_string($key) ? "'".addcslashes($key, '\'\\')."'" : $key;
		$val = is_string($val) ? "'".addcslashes($val, '\'\\')."'" : $val;
		if(is_array($val)) {
			$evaluate .= "$comma$key => ".arrayeval($val, $level + 1);
		} else {
			$evaluate .= "$comma$key => $val";
		}
		$comma = ",\n$space";
	}
	$evaluate .= "\n$space)";
	return $evaluate;
}

function updatecache($cachename = '') {
	global $db, $bbname, $table_styles, $table_stylevars, $table_templates, $table_usergroups;

	$cachescript = array(	'settings'	=> array('settings'),
				'index'		=> array('announcements', 'homeforums', 'forumlinks'),
				'forumdisplay'	=> array('forums'),
				'viewthread'	=> array('forums', 'usergroups', 'smilies'),
				'post'		=> array('smilies', 'picons', 'censor'),
				'logging'		=> array('themelists'),
				'search'		=> array('forums'),
				'pm'		=> array('smilies', 'censor')
			);

	foreach($cachescript as $script => $cachenames) {
		if(!$cachename || ($cachename && in_array($cachename, $cachenames))) {
			writetocache($script, $cachenames);
		}
	}
	if(!$cachename || $cachename == 'styles') {
		$stylevars = array();
		$query = $db->query("SELECT * FROM $table_stylevars");
		while($var = $db->fetch_array($query)) {
			$stylevars[$var['styleid']][$var['variable']] = $var['substitute'];
		}
		$query = $db->query("SELECT s.*, t.charset, t.directory AS tpldir FROM $table_styles s LEFT JOIN $table_templates t ON s.templateid=t.templateid");
		while($data = $db->fetch_array($query)) {
			$data = array_merge($data, $stylevars[$data['styleid']]);

			$data['bgcode'] = strpos($data['bgcolor'], '.') ? "background-image: url(\"$data[imgdir]/$data[bgcolor]\")" : "background-color: $data[bgcolor]";
			$data['catbgcode'] = strpos($data['catcolor'], '.') ? "background-image: url(\"$data[imgdir]/$data[catcolor]\")" : "background-color: $data[catcolor]";
			$data['headerbgcode'] = strpos($data['headercolor'], '.') ? "background-image: url(\"$data[imgdir]/$data[headercolor]\")" : "background-color: $data[headercolor]";
			$data['boardlogo'] = image($data['boardimg'], $data['imgdir'], "alt=\"$bbname\"");
			$data['bold'] = $data['nobold'] ? 'normal' : 'bold';

			writetocache($data['styleid'], '', getcachevars($data, 'CONST'), 'style_');
		}
	}
	if(!$cachename || $cachename == 'usergroups') {
		$query = $db->query("SELECT * FROM $table_usergroups");
		while($data = $db->fetch_array($query)) {
			writetocache($data['groupid'], '', getcachevars($data), 'usergroup_');
		}
	}
}

function writetocache($script, $cachenames, $cachedata = '', $prefix = 'cache_') {
	global $db;
	if(is_array($cachenames) && !$cachedata) {
		foreach($cachenames as $name) {
			$cachedata .= getcachearray($name);
		}
	}

	$dir = "./forumdata/cache/";
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}		
	if(@$fp = fopen("$dir$prefix$script.php", 'w')) {
		fwrite($fp, "<?php\n//Discuz! cache file, DO NOT modify me!\n".
			"//Created on ".date("M j, Y, G:i")."\n\n$cachedata?>");
		fclose($fp);
	} else {
		discuz_exit('Can not write to cache file, please check directory ./forumdata/ and ./forumdata/cache/ .');
	}
}

function getcachearray($cachename) {
	global $db;

	$cols = '*';
	$conditions = '';
	switch($cachename) {
		case settings:
			$table = $GLOBALS['table_settings'];
			$cols = "bbname, regstatus, bbclosed, closedreason, sitename, siteurl, styleid, moddisplay, attachsave, floodctrl, searchctrl, hottopic, topicperpage, postperpage, memberperpage, maxpostsize, maxavatarsize, smcols, whosonlinestatus, vtonlinestatus, gzipcompress, logincredits, postcredits, digestcredits, hideprivate, regverify, fastpost, modshortcut, memliststatus, statstatus, debug, reportpost, bbinsert, smileyinsert, editedby, dotfolders, attachimgpost, timeformat, dateformat, timeoffset, version, onlinerecord, totalmembers, lastmember";
			break;
		case usergroups:
			$table = $GLOBALS['table_usergroups'];
			$cols = "specifiedusers, status, grouptitle, creditshigher, creditslower, stars, groupavatar, allowavatar, allowsigbbcode, allowsigimgcode";
			$conditions = "ORDER BY creditslower ASC";
			break;
		case announcements:
			$table = $GLOBALS['table_announcements'];
			$cols = " id, subject, starttime, endtime";
			$conditions = "ORDER BY starttime DESC, id DESC";
			break;
		case forums:
			$table = $GLOBALS['table_forums'];
			$cols = "fid, type, name, fup, viewperm";
			$conditions = "WHERE status='1' ORDER BY displayorder";
			break;
		case homeforums:
			$table = $GLOBALS['table_forums'];
			$cols = " fid, fup, type, icon, namecolor, name, descolor, description, moderator, threads, posts, lastpost, viewperm";
			$conditions = "WHERE status='1' ORDER BY displayorder";
			break;
		case forumlinks:
			$table = $GLOBALS['table_forumlinks'];
			$conditions = "ORDER BY displayorder";
			break;
		case smilies:
			$table = $GLOBALS['table_smilies'];
			$conditions = "WHERE type='smiley' ORDER BY LENGTH(code) DESC";
			break;
		case picons:
			$table = $GLOBALS['table_smilies'];
			$conditions = "WHERE type='picon'";
			break;
		case censor:
			$table = $GLOBALS['table_words'];
			break;
		case themelists:
			$table = $GLOBALS['table_styles'];
			$cols = " styleid,name ";
			$conditions = "ORDER BY styleid";
			break;
	}

	$data = array();
	$query = $db->query("SELECT $cols FROM $table $conditions");
	switch($cachename) {
		case 'settings':
			$data = $db->fetch_array($query);
			$query = $db->query("SELECT COUNT(*) FROM $GLOBALS[table_members]");
			$data['totalmembers'] = $db->result($query, 0);
			$query = $db->query("SELECT username FROM $GLOBALS[table_members] ORDER BY regdate DESC LIMIT 1");
			$data['lastmember'] = $db->result($query, 0);
			$data['version'] = '1.1.4';
			break;
		case 'censor':
			$data['find'] = $data['replace'] = array();
			while($censor = $db->fetch_array($query)) {
				$data['find'][] = "/".str_replace("/", "\/", $censor['find'])."/is";
				$data['replace'][] = addslashes($censor['replacement']);
			}
			break;
		case 'forums':
			while($forum = $db->fetch_array($query)) {
				$data[$forum['fid']] = $forum;
				$forum['name'] = strip_tags($forum['name']);
				unset($forum['fid']);
			}
			break;
		case 'forumlinks':
			$tightlink_text = $tightlink_logo = '';
			while($flink = $db->fetch_array($query)) {
				if($flink['note']) {
					$forumlink['content'] = "<a href=\"$flink[url]\" target=\"_blank\"><span class=\"bold\">$flink[name]</span></a><br>$flink[note]";
					if($flink['logo']) {
						$forumlink['type'] = 1;
						$forumlink['logo'] = $flink['logo'];
					} else {
						$forumlink['type'] = 2;
					}
					$data[] = $forumlink;
				} else {
					if($flink['logo']) {
						$tightlink_logo .= "<a href=\"$flink[url]\" target=\"_blank\"><img src=\"$flink[logo]\" border=\"0\" alt=\"$flink[name]\"></a> &nbsp; ";
					} else {
						$tightlink_text .= "<a href=\"$flink[url]\" target=\"_blank\">[$flink[name]]</a> ";
					}
				}
			}
			if($tightlink_logo || $tightlink_text) {
				$tightlink_logo .= $tightlink_text ? '<br>' : '';
				$data[] = array('type' => 3, 'content' => $tightlink_logo.$tightlink_text);
			}
			break;
		default:
			while($datarow = $db->fetch_array($query)) {
				$data[] = $datarow;
			}
	}
	return "\$_DCACHE['$cachename'] = ".arrayeval($data).";\n\n";
}

function getcachevars($data, $type = 'VAR') {
	foreach($data as $key => $val) {
		$val = str_replace("'", "\\'", $val);
		$evaluate .= $type == 'VAR' ? "\$$key = '$val';\n" : "define('".strtoupper($key)."', '$val');\n";
	}
	return $evaluate;
}

?>