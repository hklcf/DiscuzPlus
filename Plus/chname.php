<?

/*
	Version: 1.1.2(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/11/08
*/

require './include/common.php';
require_once './advcenter/chname_config.php';

$navtitle = ' - 改名中心';
$discuz_action = 182;

$chmoney = $chname[chmoney];
$chcredit = $chname[chcredit];
$chadmin = $chname[chadmin];
$chul = $chname[chul];
$chdl = $chname[chdl];
$chreason = $chname[chreason];

$date=date('m月d日 H:i:s',$timestamp);
$text = "改名會員資料：\n\n舊會員名稱：[color=blue] $discuz_user [/color]\n\n新會員名稱：[color=green][b] $newname [/b][/color]\n\n改名原因：[color=darkblue] $reason [/color]\n\n改名時間: $date \n\n[color=red]**改名後如有因何問題，請PM管理員**[/color]";
$subject="會員改名通知";

if(!$discuz_user) {
	showmessage('not_loggedin');
}

if($chname[chcheck]==1){
        showmessage("對不起，會員改名中心現正關閉中！",'index.php');
}

$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE username='$newname'");

if ($action=="changename") {
if ($credit<$chcredit) {showmessage("你的積分不能達到改名條件。",'index.php');
	} else if($usermoney<$chmoney) {showmessage("你的金錢不足，無法進行改名。",'index.php');
	} else if($credit<$chcredit) {showmessage("你的積分未達到要求，無法進行改名。",'index.php');
	} else if($db->result($query, 0)) {showmessage("<font color=red><b> $newname </b></font>已經存在，名稱無法更改。",'index.php');
	} else if(!$newname) {showmessage("請填寫要求更改之會員名稱，返回重新填寫。",'chname.php');
	} else if(strlen($newname) > $chul || strlen($newname) < $chdl) {showmessage("你的名稱大於 $chul 或 小於 $chdl 無法改名,請返回更改",'chname.php');
	} else if(strlen($reason) < $chreason) {showmessage("你的改名原因太短了，請返回更改",'chname.php');
	} else if(!$reason) {showmessage("請填寫更改會員名稱原因，返回重新填寫。",'chname.php');
        } else {
	$query = $db->query("UPDATE $table_members SET money=money-$chmoney WHERE username='$discuz_user'");
	$query = $db->query("UPDATE $table_buddys SET username='$newname' WHERE username='$discuz_user'");
	$query = $db->query("UPDATE $table_buddys SET buddyname='$newname' WHERE buddyname='$discuz_user'");
	$query = $db->query("UPDATE $table_favorites SET username='$newname' WHERE username='$discuz_user'");
	$query = $db->query("UPDATE $table_subscriptions SET username='$newname' WHERE username='$discuz_user'");
	$query = $db->query("UPDATE $table_members SET username='$newname' WHERE username='$discuz_user'");
	$query = $db->query("UPDATE $table_posts SET author='$newname' WHERE author='$discuz_user'");
	$query = $db->query("UPDATE $table_threads SET author='$newname' WHERE author='$discuz_user'");
	$query = $db->query("UPDATE $table_threads SET lastposter='$newname' WHERE lastposter='$discuz_user'");
	$query = $db->query("UPDATE $table_forums SET lastpost=REPLACE(lastpost, '\t$discuz_user', '\t$discuz_user')");
	$query = $db->query("UPDATE $table_pm SET msgfrom='$newname' WHERE msgfrom='$discuz_user'");
	$query = $db->query("UPDATE $table_pm SET msgto='$newname' WHERE msgto='$discuz_user'");
	$query = $db->query("UPDATE $table_members SET newpm='1' WHERE username='$discuz_user'");
	$query = $db->query("INSERT INTO $table_pm VALUES('$chadmin', '$chadmin', '$discuz_userss', 'inbox', '1', '$subject', '$timestamp', '$text')");
	$query = $db->query("INSERT INTO $table_pm VALUES('$newname', '$newname', '$newname', 'inbox', '1', '$subject', '$timestamp', '$text')");
	$query = $db->query("INSERT INTO $table_chname ( `id` , `newname` , `oldname` , `reason` , `dateline` ) VALUES ('', '$newname', '$discuz_user', '$reason', '$timestamp')");
	showmessage("改名成功，你的名稱改為 $newname ！<br>如您的帳號出現任何問題，請即聯絡管理員",'index.php');
			}
		}

if ($action=="list") {

$num=$chname[chnum];
if ($page) {
$start_limit = ($page - 1) * $num;
} else {
$start_limit = 0;
$page = 1;
}
$query = $db->query("SELECT COUNT(*) FROM $tablepre"."chname");
	$multipage = multi($db->result($query, 0), $num, $page, "chname.php?action=list");
	$query = $db->query("SELECT * FROM $tablepre"."chname ORDER BY id desc  LIMIT $start_limit,$num");

	while ($member = $db->fetch_array($query)) {
		$member['usernameenc'] = rawurlencode($member['username']);
		$member['dateline'] = gmdate("$dateformat $timeformat", $member['dateline'] + $timeoffset * 3600);
	$chnamelist[] = $member;
}

}

include template('chname');
?>
