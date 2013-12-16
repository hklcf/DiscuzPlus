<?php

/*
        Version: 1.1.1(BUG Fixed)
        Author: HKLCF (admin@hklcf.com)
        Copyright: HKLCF (www.hklcf.com)
        Last Modified: 2004/09/22
*/

require "./include/common.php";
require $discuz_root.'./include/forum.php';

$discuz_action = 1;

if(isset($showoldetails)) {
        switch ($showoldetails) {
                case 'no': setcookie('onlinedetail', 0, $timestamp + 86400 * 365, $cookiepath, $cookiedomain); break;
                case 'yes': setcookie('onlinedetail', 1, $timestamp + 86400 * 365, $cookiepath, $cookiedomain); break;
        }
} else {
        setcookie('onlinedetail', $showdetails, $timestamp + 86400 * 365, $cookiepath, $cookiedomain);
        $showoldetails = false;
}

$currenttime = gmdate($timeformat, $timestamp + $timeoffset * 3600);
$lastvisittime = gmdate("$dateformat $timeformat", $lastvisit + $timeoffset * 3600);

$memberenc = rawurlencode($lastmember);
$discuz_userenc = rawurlencode($discuz_userss);
$newthreads = $timestamp - $lastvisit;

if(empty($gid)) {

        $navigation = $navtitle = '';

        $announcements = '';
        if($_DCACHE['announcements']) {
                $space = '';
                foreach($_DCACHE['announcements'] as $announcement) {
                        if($timestamp >= $announcement['starttime'] && ($timestamp <= $announcement['endtime'] || !$announcement['endtime'])) {
                                $announcements .= $space.'<a href="announcement.php?id='.$announcement['id'].'#'.$announcement['id'].'"><span class="bold">'.$announcement['subject'].'</span> '.'('.gmdate($dateformat, $announcement['starttime'] + $timeoffset * 3600).')</a>';
                                $space = '&nbsp; &nbsp; &nbsp; &nbsp;';
                        }
                }
        }
        unset($_DCACHE['announcements']);

        $threads = $posts = 0;
        $forumlist = $catforumlist = $forums = $catforums = $categories = $closeforum = array();
        $cat_close = $_COOKIE['discuz_collapse'] ? " ".urldecode($_COOKIE['discuz_collapse']) : NULL;
        if($_DCACHE['homeforums']) {
                foreach($_DCACHE['homeforums'] as $forum) {
                if($forum['type'] != 'sub') {
                        $forum['fup'] ? $forums[] = $forum : ($forum['type'] == 'group' ? $categories[] = $forum : $catforums[] = $forum);
        } elseif((!$forum['viewperm'] && $allowview) || ($forum['viewperm'] && strstr($forum['viewperm'], "\t$groupid\t"))) {
        $subforumlist[$forum['fup']] .= "※<a href=forumdisplay.php?fid=".$forum['fid']." ><font color='$forum[namecolor]'>".$forum['name']."</font></a><br>";
                }
        if(strpos($cat_close, "category_".$forum['fid'])) {
                $closeforum['status'] =  "display:none";
                $closeforum['image'] = "cat_open.gif";
        } else {
                $closeforum['status'] = "";
                $closeforum['image'] = "cat_close.gif";
        }
                $forumname[$forum['fid']] = strip_tags($forum['name']);
                if($forum['type'] == 'forum') {  //fix:子論壇帖子數重複計算
                        $threads += $forum['threads'];
                        $posts += $forum['posts'];
                }
        }

        foreach($categories as $group) {
                $group_forum = array();
                foreach($forums as $forum) {
                        if($forum['fup'] == $group['fid'] && $forum['type'] == 'forum') {
                                forum($forum);
                                if($forum) {
                                        $group_forum[] = $forum;
                                }
                        }
                }
                if($group_forum) {
                        $forumlist = array_merge($forumlist, array($group), $group_forum);
                }
        }

         foreach($catforums as $forum) {
                forum($forum);
                if(isset($forum)) {
                        $catforumlist[] = $forum;
                }
        }
        if($catforumlist) {
                $forumlist[] = array('fid' => 0, 'type' => 'group', 'name' => $bbname);
                $forumlist = array_merge($forumlist, $catforumlist);
        }
}
        unset($_DCACHE['homeforums']);
        unset($forums, $catforums, $catforumlist, $categories, $group, $forum);

        if($whosonlinestatus) {
                $onlineinfo = explode("\t", $onlinerecord);
                $detailstatus = (!isset($HTTP_COOKIE_VARS['onlinedetail']) && $onlineinfo[0] > 500) || (($HTTP_COOKIE_VARS['onlinedetail'] || $showoldetails == 'no') && $showoldetails != 'yes');

                if($detailstatus) {
                        @include language('actions');

                        updatesession();
                        $onlinenum = $membercount = $guestcount = 0;
                        $whosonline = array();
                        $query = $db->query("SELECT ip, username, status, lastactivity, action, fid, invisible FROM $table_sessions ORDER BY lastactivity DESC");
                $pk_invis_count=0;
                        while($online = $db->fetch_array($query)) {
                                if($online['username']) {
                                        $membercount++;
        if($discuz_user!=$online['username'] && !$isadmin && $online['invisible']==1) $online['username']='隱身會員';
        if ($online['invisible']==1) $pk_invis_count++;
                                        $online['usernameenc'] = rawurlencode($online['username']);
                                        switch($online['status']) {
                                                case 'Admin': $online['icon'] = 'online_admin.gif'; break;
                                                case 'SuperMod': $online['icon'] = 'online_supermod.gif'; break;
                                                case 'Moderator': $online['icon'] = 'online_moderator.gif'; break;
                        case 'vip': $online['icon'] = 'online_vip.gif'; break;
                                                default: $online['icon'] = 'online_member.gif'; break;
                                               }
        if ($discuz_user!=$online['username'] && $online['invisible']==1) $online['icon'] = 'online_invisible.gif';
                                } else {
                                        $guestcount++;
                        $online['username'] = "訪客";
                        $online['usernameenc'] = rawurlencode($online['username']);
                        $online['icon'] = 'online_guest.gif';
                                                }
                        $online['fid'] = $online['fid'] ? $forumname[$online[fid]] : 0;
                        $online['action'] = $actioncode[$online['action']];
                        $online['lastactivity'] = gmdate($timeformat, $online['lastactivity'] + ($timeoffset * 3600));
                                $whosonline[] = $online;
                        }
                        $onlinenum = $membercount + $guestcount;
                        unset($online);
                } else {
                        $query = $db->query("SELECT COUNT(*) FROM $table_sessions");
                        $onlinenum = $db->result($query, 0);
                }

                if($onlinenum > $onlineinfo[0]) {
                        $db->query("UPDATE $table_settings SET onlinerecord='$onlinenum\t$timestamp'");
                        require $discuz_root.'./include/cache.php';
                        updatecache("settings");
                        $onlineinfo = array($onlinenum, $timestamp);
                }

                $onlineinfo[1] = gmdate("$dateformat $timeformat", $onlineinfo[1] + ($timeoffset * 3600));
        }

        if($discuz_user && $newpm) {
                require $discuz_root.'./include/pmprompt.php';
        }

        include template('index');

} else {

        $query = $db->query("SELECT fid, type, name FROM $table_forums WHERE fid='$gid' AND type='group'");
        $cat = $db->fetch_array($query);
        $navigation = '&raquo; '.$cat['name'];
        $navtitle = " - $cat[name]";
        $forumlist = array($cat);

        $threads = $posts = 0;
        $queryg = $db->query("SELECT type, fid, name, lastpost FROM $table_forums WHERE type='group' AND fid='$gid' AND status='1' ORDER BY displayorder");
        $group = $db->fetch_array($queryg);
        $query = $db->query("SELECT * FROM $table_forums WHERE type='forum' AND status='1' AND fup='$group[fid]' ORDER BY displayorder");
        while($forum = $db->fetch_array($query)) {
                $threads += $forum['threads'];
                $posts += $forum['posts'];

                forum($forum);
                if($forum) {
                        $forumlist[] = $forum;
                }
        }
        include template('index');

}

?>
