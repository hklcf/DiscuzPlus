<?

/*
	Version: 1.1.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
$discuz_action = 221;
$navtitle = '- 察看評分記錄';
$pid=intval($pid);

if(!$discuz_user) {
	showmessage('not_loggedin');
}
if ($pid<1){
	showmessage('數據格式錯誤！請返回。');
}

if($karma_view || $isadmin || ($ismoderator && $karma_adminview)){
	if(!$page) {
		$page = 1;
		}
	$start = ($page - 1) * $karma_perpage;
	$query = $db->query("SELECT COUNT(*) FROM $table_karmalog where pid='$pid'");
	$num = $db->result($query, 0);
	if ($num<1){
		showmessage('沒有找到該貼子的評分記錄！請返回。');
	}
	$multipage = multi($num, $karma_perpage, $page, "viewkarma.php?action=view");
	$karmalist = array();
	$query = $db->query("SELECT * FROM $table_karmalog where pid='$pid' ORDER BY dateline DESC LIMIT $start, $memberperpage");
	$karmanum=($page - 1) * $karma_perpage;
	while($karma = $db->fetch_array($query)){
		$karmanum++;
		$karma['id']= $karmanum;
		$karma['dateline'] = gmdate("$dateformat $timeformat", $karma['dateline'] + ($timeoffset * 3600));
		$karma['urluser'] =  rawurlencode($karma['username']) ;
		$karma['username'] = daddslashes($karma['username']) ;
		$karmalist[] = $karma;
	}
	include template('viewkarma');

}else{
	showmessage('管理員關閉了此功能！請返回。');
}

?>