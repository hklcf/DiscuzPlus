<?php 

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php'; 

require $discuz_root.'./include/misc.php'; 
require $discuz_root.'./include/attachment.php'; 
require $discuz_root.'./forumdata/cache/cache_forumdisplay.php'; 

if (!isset($page)) { 
    $page = 1; 
    $start = 0; 
} else { 
    $start = ($page - 1) * $tpp; 
} 

$forums = is_array($forums) ? '\'0\',\''.implode("','", $forums).'\'' : stripslashes($forums); 

$fids = '0'; 
$forumlist = array(); 
$forumcheck = array(); 
foreach($_DCACHE['forums'] as $fid => $forum) { 
    if($forum['type'] != 'group' && (!$forum['viewperm'] && $allowview) || ($forum['viewperm'] && strstr($forum['viewperm'], "\t$groupid\t"))) { 
        $forumlist[] = array('fid' => $fid, 'name' => $forum['name']); 
        if(!$forums || strpos($forums, "'$fid'")) { 
            $forumcheck[$fid] = 'checked'; 
        } 
        $fids .= ",'$fid'"; 
    } 
} 

if($forums == $fids) { 
    $forums = ''; 
} 

$keywordadd = $keyword ? "AND subject LIKE '%$keyword%'" : NULL; 
$forumadd = $forums ? "AND fid IN ($forums)" : NULL; 

$query = $db->query("SELECT COUNT(*) FROM $table_threads WHERE digest>'0' $forumadd AND fid IN ($fids) $keywordadd"); 
$threadcount = $db->result($query, 0); 

if(!$threadcount) { 
    showmessage('digest_nonexistence'); 
} 

if(!$order || !in_array($order, array('dateline', 'lastpost', 'replies', 'views'))) { 
    $order = 'digest'; 
} 
$ordercheck = array($order => 'selected="selected"'); 

$threadlist = array(); 
$query = $db->query("SELECT * FROM $table_threads WHERE digest>'0' $forumadd AND fid IN ($fids) $keywordadd ORDER BY $order DESC LIMIT $start, $tpp"); 
while($thread = $db->fetch_array($query)) { 
    if($thread['attachment']) { 
        $thread['subject'] = attachicon($thread['attachment']).' '.$thread['subject']; 
    } 
    $thread['forumname'] = $_DCACHE['forums'][$thread['fid']]['name']; 
    $thread['dateline'] = gmdate($dateformat, $thread['dateline'] + $timeoffset * 3600); 
    $thread['lastpost'] = gmdate("$dateformat $timeformat", $thread['lastpost'] + $timeoffset * 3600); 
    $threadlist[] =$thread; 
} 

$keyword = rawurlencode($keyword); 
$multipage = multi($threadcount, $tpp, $page, "digest.php?order=$order&keyword=$keyword&forums=".rawurlencode($forums)); 

include template('digest'); 

?>