<?

/*
	Version: 1.1.2(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/11/08
*/

require './include/common.php';
require_once './advcenter/chname_config.php';

$navtitle = ' - ��W����';
$discuz_action = 182;

$chmoney = $chname[chmoney];
$chcredit = $chname[chcredit];
$chadmin = $chname[chadmin];
$chul = $chname[chul];
$chdl = $chname[chdl];
$chreason = $chname[chreason];

$date=date('m��d�� H:i:s',$timestamp);
$text = "��W�|����ơG\n\n�·|���W�١G[color=blue] $discuz_user [/color]\n\n�s�|���W�١G[color=green][b] $newname [/b][/color]\n\n��W��]�G[color=darkblue] $reason [/color]\n\n��W�ɶ�: $date \n\n[color=red]**��W��p���]����D�A��PM�޲z��**[/color]";
$subject="�|����W�q��";

if(!$discuz_user) {
	showmessage('not_loggedin');
}

if($chname[chcheck]==1){
        showmessage("�藍�_�A�|����W���߲{���������I",'index.php');
}

$query = $db->query("SELECT COUNT(*) FROM $table_members WHERE username='$newname'");

if ($action=="changename") {
if ($credit<$chcredit) {showmessage("�A���n������F���W����C",'index.php');
	} else if($usermoney<$chmoney) {showmessage("�A�����������A�L�k�i���W�C",'index.php');
	} else if($credit<$chcredit) {showmessage("�A���n�����F��n�D�A�L�k�i���W�C",'index.php');
	} else if($db->result($query, 0)) {showmessage("<font color=red><b> $newname </b></font>�w�g�s�b�A�W�ٵL�k���C",'index.php');
	} else if(!$newname) {showmessage("�ж�g�n�D��蠟�|���W�١A��^���s��g�C",'chname.php');
	} else if(strlen($newname) > $chul || strlen($newname) < $chdl) {showmessage("�A���W�٤j�� $chul �� �p�� $chdl �L�k��W,�Ъ�^���",'chname.php');
	} else if(strlen($reason) < $chreason) {showmessage("�A����W��]�ӵu�F�A�Ъ�^���",'chname.php');
	} else if(!$reason) {showmessage("�ж�g���|���W�٭�]�A��^���s��g�C",'chname.php');
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
	showmessage("��W���\�A�A���W�٧אּ $newname �I<br>�p�z���b���X�{������D�A�ЧY�p���޲z��",'index.php');
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
