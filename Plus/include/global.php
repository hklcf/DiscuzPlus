<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

function dhtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val);
		}
	} else {
		$string = str_replace('&', '&amp;', $string);
		$string = str_replace('"', '&quot;', $string);
		$string = str_replace('<', '&lt;', $string);
		$string = str_replace('>', '&gt;', $string);
		$string = preg_replace('/&amp;(#\d{3,5};)/', '&\\1', $string);
	}
	return $string;
}

function discuz_exit($message = '') {
	global $db;
	discuz_output();
	$db->close();
	exit($message);
}

function daddslashes($string, $force = 0) {
	if(!$GLOBALS['magic_quotes_gpc'] || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

function url_rewriter($url, $tag = '') {
	global $sid;
	$tag = stripslashes($tag);
	if(!$tag || (!preg_match("/^(http:\/\/|mailto:|#|javascript)/i", $url) && !strpos($url, 'sid='))) {
		$pos = strpos($url, '#');
		if($pos) {
			$urlret = substr($url, $pos);
			$url = substr($url, 0, $pos);
		}
		$url .= strpos($url, '?') ? '&' : '?';
		$url .= "sid=$sid$urlret";
	}
	return $tag.$url;
}

function updatesession() {
	if(empty($GLOBALS['sessionupdated'])) {
		global $db, $sessionexists, $sessionupdated, $sid, $onlineip, $ipbanned, $status, $discuz_user, $timestamp, $groupid, $styleid, $discuz_action, $fid, $tid, $onlinehold, $logincredits, $table_sessions, $table_members;

		$sessionupdated = 1;
	if (intval($GLOBALS['invisible'])==1) $GLOBALS['invisible']=1;
		else $GLOBALS['invisible']=0;
		if($sessionexists == 1) {
			$db->query("UPDATE $table_sessions SET status='$status', lastactivity='$timestamp', groupid='$groupid', username='$discuz_user', styleid='$styleid', action='$discuz_action', fid='$fid', tid='$tid', invisible='$GLOBALS[invisible]' WHERE sid='$sid'");
		} else {
			$db->unbuffered_query("DELETE FROM $table_sessions WHERE sid='$sid' OR lastactivity<'".($timestamp - $onlinehold)."' OR (ip='$onlineip' AND lastactivity>'".($timestamp - 60)."') OR ('$discuz_user'<>'' AND username='$discuz_user')");
$ips = explode('.', $GLOBALS['onlineip']); 
			$query = $db->query("SELECT COUNT(*) FROM $GLOBALS[table_banned] WHERE (ip1='$ips[0]' OR ip1='-1') AND (ip2='$ips
[1]' OR ip2='-1') AND (ip3='$ips[2]' OR ip3='-1') AND (ip4='$ips[3]' OR ip4='-1')"); 
            if(!$db->result($query, 0)) {
			$db->query("INSERT INTO $table_sessions (sid, ip, ipbanned, status, username, lastactivity, groupid, styleid, action, fid, tid,invisible)
				VALUES ('$sid', '$onlineip', '$ipbanned', '$status', '$discuz_user', '$timestamp', '$groupid', '$styleid', '$discuz_action', '$fid', '$tid', '$GLOBALS[invisible]')");
}else {
			$groupid=2; 
		}
			if($discuz_user) {
				$logincredits = intval($logincredits);
				$db->unbuffered_query("UPDATE $table_members SET credit=credit+($logincredits), lastvisit=$timestamp+$onlinehold WHERE username='$discuz_user'");
			}
		}
	}
}

function discuz_output() {
	global $sid;

	if(empty($GLOBALS['HTTP_COOKIE_VARS']['sid'])) {
		$content = preg_replace(array(	"/\<a(\s*[^\>]+\s*)href\=([\"|\']?)([^\"\'\s]+)/ies",
						"/(\<form.+?\>)/is"),
					array(	"url_rewriter('\\3','<a\\1href=\\2')",
					 	"\\1\n<input type=\"hidden\" name=\"sid\" value=\"$sid\">"),
					ob_get_contents());
		ob_end_clean();
		$GLOBALS['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
		echo $content;
	}
}

function clearcookies() {
	global $timestamp, $cookiepath, $cookiedomain, $discuz_user, $discuz_pw, $discuz_secques, $status;
	setcookie('_discuz_user', '', $timestamp - 86400 * 365, $cookiepath, $cookiedomain);
	setcookie('_discuz_pw', '', $timestamp - 86400 * 365, $cookiepath, $cookiedomain);
	setcookie('_discuz_secques', '', $timestamp - 86400 * 365, $cookiepath, $cookiedomain);
	$discuz_user = $discuz_pw = $discuz_secques = '';
	$status = '訪客';
}

function image($imageinfo, $basedir = "", $remark = "") {
	if($basedir) {
		$basedir .= "/";
	}
	if(strstr($imageinfo, ",")) {
		$flash = explode(",", $imageinfo);
		return "<embed src=\"$basedir".trim($flash[0])."\" width=\"".trim($flash[1])."\" height=\"".trim($flash[2])."\" type=\"application/x-shockwave-flash\" $remark></embed>";
	} else {
		return "<img src=\"$basedir$imageinfo\" $remark border=\"0\">";
	}
}

function language($file, $templateid = 0, $tpldir = '') {
	global $discuz_root;

	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	$languagepack = $discuz_root.'./'.$tpldir.'/'.$file.'.lang.php';
	if(file_exists($languagepack)) {
		return $languagepack;
	} elseif($templateid != 1 && $tpldir != './templates/default') {
		return language($file, 1, './templates/default');
	} else {
		return FALSE;
	}
}

function template($file, $templateid = 0, $tpldir = '') {
	global $discuz_root, $tplrefresh;

	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	$tplfile = $discuz_root.'./'.$tpldir.'/'.$file.'.htm';
	$objfile = $discuz_root.'./forumdata/templates/'.$templateid.'_'.$file.'.tpl.php';
	if(TEMPLATEID != 1 && $templateid != 1 && !file_exists($tplfile)) {
		return template($file, 1, './templates/default/');
	}
	if($tplrefresh == 1 || ($tplrefresh > 1 && substr($GLOBALS['timestamp'], -1) > $tplrefresh)) {
		if(@filemtime($tplfile) > @filemtime($objfile)) {
			require_once $discuz_root.'./include/template.php';
			parse_template($file, $templateid, $tpldir);
		}
	}
	return $objfile;
}

function random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function sendmail($to, $subject, $message, $from = '') {
	extract($GLOBALS, EXTR_SKIP);
	require $discuz_root.'./include/sendmail.php';
}

function debuginfo() {
	if($GLOBALS['debug']) {
		global $db, $starttime, $gzipcompress;
		$mtime = explode(' ', microtime());
		$totaltime = number_format(($mtime[1] + $mtime[0] - $starttime), 6);
		echo '<br>頁面執行時間 '.$totaltime.' 秒, 數據庫查詢 '.$db->querynum.' 次'.
			($gzipcompress ? ', Gzip 已啟用' : NULL);
	}
}

function multi($num, $perpage, $curr_page, $mpurl) {
	$multipage = '';
	if($num > $perpage) {
		$page = 10;
		$offset = 2;

		$pages = ceil($num / $perpage);
		$from = $curr_page - $offset;
		$to = $curr_page + $page - $offset - 1;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			if($from < 1) {
				$to = $curr_page + 1 - $from;
				$from = 1;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $curr_page - $pages + $to;
				$to = $pages;
				if(($to - $from) < $page && ($to - $from) < $pages) {
					$from = $pages - $page + 1;
				}
			}
		}
		$first = $curr_page -1;
		$end = $curr_page +1;
		$multipage .= "<table cellspacing=\"1\" cellpadding=\"0\" class=\"tableborder\"><tr class=\"pgnumbg\"><td class=\"pgheader\"><span title=\"共 $num 篇\">&nbsp;$num&nbsp;</span></td><td class=\"pgheader\"><span title=\"共 $pages 頁中的第 $curr_page 頁\">&nbsp;$curr_page/$pages&nbsp;</td>";
			if($curr_page > 2) {
				$multipage .= "<td>&nbsp;<a href=\"$mpurl&page=1\" title=\"回到第一頁\"><b>|</b><</a>&nbsp;</td>";
			}
			if($curr_page > 1) {
				$multipage .= "<td>&nbsp;<a  href=\"$mpurl&page=$first\" title=\"上一頁\"><</a>&nbsp;</td>";
			}
		for($i = $from; $i <= $to; $i++) {
			if($i != $curr_page) {
				$multipage .= "<td>&nbsp;<a href=\"$mpurl&page=$i\">$i</a>&nbsp;</td>";
			} else {
				$multipage .= "<td>&nbsp;<u><b>$i</b></u>&nbsp;</td>";
			}
		}
			if($pages != $curr_page) {
				$multipage .= "<td>&nbsp;<a href=\"$mpurl&page=$end\" title=\"下一頁\">></a>&nbsp;</td>";
			}
			if($pages > 2 && $pages > $curr_page +1) {
				$multipage .= $pages > $page ? "<td>&nbsp;<a href=\"$mpurl&page=$pages\" title=\"最後一頁\">><b>|</b></a>&nbsp;</td></tr></table>" : "<td><a href=\"$mpurl&page=$pages\" title=\"最後一頁\">><b>|</b></a>&nbsp;</td></tr></table>";
		}else{
			$multipage .= "</tr></table>";
		}
	}
	return $multipage;
}

function showmessage($show_message, $url_forward = '') {
	extract($GLOBALS, EXTR_SKIP);
	$discuz_action = 255;

	include language('messages');
	if(isset($language[$show_message])) {
		eval("\$show_message = \"".$language[$show_message]."\";");
	}
	$url_redirect = $url_forward ? '<meta http-equiv="refresh" content="2;url='.url_rewriter($url_forward).'">' : NULL;

	include template('showmessage');
	discuz_exit();
}

function wordscut($string, $length) {
	if(strlen($string) > $length) {
		for($i = 0; $i < $length - 3; $i++) {
			if(ord($string[$i]) > 127) {
				$wordscut .= $string[$i].$string[$i + 1];
				$i++;
			} else {
				$wordscut .= $string[$i];
			}
		}
		return $wordscut.' ...';
	}
	return $string;
}
	
function modcheck($username, $fid = 0) {
        global $isadmin, $issupermod, $ismoderator;
        if($fid) {
                global $table_forums, $db;
                $query = $db->query("SELECT moderator FROM $table_forums WHERE fid='$fid'");
                $forum = $db->fetch_array($query);
        } else {
                global $forum;
        }
        if($isadmin || $issupermod) {
                return 1;
        } elseif($ismoderator) {
                $pk0909_username=strtolower($username);
                $pk0909_modlist=strtolower($forum['moderator']);
                $pk0909_mod_array = explode(',', $pk0909_modlist);
                foreach($pk0909_mod_array as $pk0909_permod) {
                if ($pk0909_permod==$pk0909_username) return 1;
        }
                return 0;
        } else {
                return 0;
        }
}
//cnteacher hack function
function getmoneygroup($money = 0 ){
        global $banksettings,$bankgroup;
        if (!$banksettings['groups'] or !$bankgroup){
                        return "保密";
        }else{
                foreach($bankgroup as $group) {
                        if ($money >= $group['min'] && $money< $group['max']) 
                        return $group['name'];
                }
        }
}

function submitcheck($var, $allowget = 0) {
	if($var) {
		global $HTTP_SERVER_VARS;
		$referer = parse_url($HTTP_SERVER_VARS['HTTP_REFERER']);
		$checkserver =$referer['port']?$referer['host'].":".$referer['port']:$referer['host'];
		if($allowget || (!$allowget && $HTTP_SERVER_VARS['REQUEST_METHOD'] == 'POST' && $referer['host'] == $HTTP_SERVER_VARS['HTTP_HOST'])) {
			return $var;
		} else {
			showmessage('undefined_action');
		}
	} else {
		return FALSE;
	}
}

function quescrypt($questionid, $answer) {
	return $questionid > 0 && $answer != '' ? substr(md5($answer.md5($questionid)), 16, 8) : '';
}

?>