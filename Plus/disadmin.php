<?php

/*
	Version: 1.1.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require "./include/common.php";
$navigation="管理團隊";

if(!$discuz_user) {
	showmessage('not_loggedin');
}

$diasadmin_file_name = "$discuz_root"."./forumdata/cache/disadmin_$type.seiral.php";
if ( file_exists($diasadmin_file_name) ) 
{
    $ttl = 30; //秒  Cache 設定更新時間 (3600 = 1 小時)
    $now = time();

    if($now - filemtime ( $diasadmin_file_name ) < $ttl)
    {
    	$fp = fopen($diasadmin_file_name , "r");
    	$serialize_cache = unserialize(fread($fp , filesize($diasadmin_file_name) ));
    	fclose($fp);
    	$modslist = $serialize_cache["modslist"];
    	$modsno = count($modslist);
    	include template('disadmin');
    	exit;
	}
    else clearstatcache();

}
	$modslist = array();
	$altbg1 = ALTBG1;
	$altbg2 = ALTBG2;

$SELECTWHERE = "SELECT * FROM $table_members WHERE";
$ORDER = "ORDER BY lastvisit DESC";

if(!$type||$type==''){
$query = $db->query("$SELECTWHERE status='Admin' or status='SuperMod' or status='Moderator' $ORDER");
}elseif($type=='Admin'){
$query = $db->query("$SELECTWHERE status='Admin' $ORDER");
}elseif($type=='SuperMod'){
$query = $db->query("$SELECTWHERE status='SuperMod' $ORDER");
}else{
$query = $db->query("$SELECTWHERE status='Moderator' $ORDER");
}

while($mods = $db->fetch_array($query)){
    if($mods['status'] == "Moderator" || $mods['status'] == "SuperMod"|| $mods['status'] == "Admin"){
        $forums_name = array();
        $query2 = $db->query("SELECT name,fid FROM $table_forums WHERE moderator like '%$mods[username]%'");
        while( $forums = $db->fetch_array($query2))
        {
            $forums_name[] = "　<a href=\"forumdisplay.php?fid=$forums[fid]\">" . $forums[name] . "</a>";
        }
        if( count($forums_name) > 0)
            $mods['forum_name'] = "<p><fieldset style='width: 45%;'><legend><strong>管 理 版 區</strong></legend>" . implode("<br>" , $forums_name ) . "</fieldset>";
        else
            $mods['forum_name'] = "<p>目前尚無管理任何版";
    }

	$bgno = $no++ % 2 + 1;
	$mods['thisbg'] = ${'altbg'.$bgno};

	$mods[authorenc] = rawurlencode($mods['username']);
	$mods['regdate'] = gmdate($dateformat, $mods['regdate']);

if($mods['avatar']) {
	$mods['avatar'] = '<img src='.$mods['avatar'].' border=0>';
} else {
	$mods['avatar'] = '';
}

	$mods['missdays'] = intval(($timestamp-$mods[lastvisit])/(3600*24));
	$mods['lastvisit'] = gmdate("$dateformat $timeformat", $mods[lastvisit] + $timeoffset * 3600);

$modslist[] = $mods;
}

$modsno=count($modslist);
$fp = @fopen( $diasadmin_file_name , "w");
if(!$fp) Die("File create error : $diasadmin_file_name");
$cache["modslist"] = $modslist;
$serialize_cache = serialize($cache);
fputs( $fp , $serialize_cache  , strlen($serialize_cache  ));
fclose($fp);
include template('disadmin');

?>