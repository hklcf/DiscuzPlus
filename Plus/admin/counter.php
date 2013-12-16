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

cpheader();

if(!$forumsubmit && !$membersubmit && !$threadsubmit) {

?>
<br><br><form method="post" action="admincp.php?action=counter">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">重建主題文章數</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">每個循環更新的主題數： &nbsp; &nbsp; <input type="text" name="pertask" value="300"></td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="threadsubmit" value="更 新"> &nbsp;
<input type="reset" value="重 置"></center></form><br>

<form method="post" action="admincp.php?action=counter">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">重建用戶發表文章數</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">每個循環更新的用戶數： &nbsp; &nbsp; <input type="text" name="pertask" value="30"></td>
</tr></table></td></tr></table><br><center>
<input type="submit" name="membersubmit" value="更 新"> &nbsp;
<input type="reset" value="重 置"></center></form><br>

<form method="post" action="admincp.php?action=counter">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">重建論壇文章數</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">每個循環更新的論壇數： &nbsp; &nbsp; <input type="text" name="pertask" value="15"></td>
</tr></table></td></tr></table><br><center>
<input type="submit" name="forumsubmit" value="更 新"> &nbsp;
<input type="reset" value="重 置"></center></form><br>
<?

} elseif($forumsubmit) {

	if(!$current) {
		$current = 0;
	}
	$pertask = intval($pertask);
	$current = intval($current);
	$next = $current + $pertask;
	$nextlink = "admincp.php?action=counter&current=$next&pertask=$pertask&forumsubmit=1";
	$processed = 0;

	$queryf = $db->query("SELECT fid, type FROM $table_forums WHERE type<>'group' LIMIT $current, $pertask");
	while($forum = $db->fetch_array($queryf)) {
		$processed = 1;

		$fids = "'$forum[fid]'";
		$query = $db->query("SELECT fid FROM $table_forums WHERE fup='$forum[fid]'");
		while($sub = $db->fetch_array($query)) {
			$fids .= ", '$sub[fid]'";
		}

		$query = $db->query("SELECT COUNT(*) FROM $table_threads WHERE fid IN ($fids)");
		$threadnum = $db->result($query, 0);
		$query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE fid IN ($fids)");
		$postnum = $db->result($query, 0);

		$query = $db->query("SELECT subject, lastpost, lastposter FROM $table_threads WHERE fid IN ($fids) ORDER BY lastpost DESC LIMIT 0, 1");
		$thread = $db->fetch_array($query);
		$lastpost = addslashes("$thread[subject]\t$thread[lastpost]\t$thread[lastposter]");

		$db->query("UPDATE $table_forums SET threads='$threadnum', posts='$postnum', lastpost='$lastpost' WHERE fid='$forum[fid]'");
	}

	if($processed) {
		cpmsg("重建論壇文章數：正在處理論壇從 $current 到 $next", $nextlink);
	} else {
		$db->query("UPDATE $table_forums SET threads='0', posts='0' WHERE type='group'");
		cpmsg("論壇文章數重建完成。");
	}

} elseif($threadsubmit) {

	if(!$current) {
		$current = 0;
	}
	$pertask = intval($pertask);
	$current = intval($current);
	$next = $current + $pertask;
	$nextlink = "admincp.php?action=counter&current=$next&pertask=$pertask&threadsubmit=1";
	$processed = 0;

	$queryt = $db->query("SELECT tid FROM $table_threads LIMIT $current, $pertask");
	while($threads = $db->fetch_array($queryt)) {
		$processed = 1;
		$query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE tid='$threads[tid]'");
		$replynum = $db->result($query, 0);
		$replynum--;
		$db->query("UPDATE $table_threads SET replies='$replynum' WHERE tid='$threads[tid]'");
	}

	if($processed) {
		cpmsg("重建主題文章數：正在處理主題從 $current 到 $next", $nextlink);
	} else {
		cpmsg("主題文章數重建完成。");
	}

} elseif($membersubmit) {

	if(!$current) {
		$current = 0;
	}
	$pertask = intval($pertask);
	$current = intval($current);
	$next = $current + $pertask;
	$nextlink = "admincp.php?action=counter&current=$next&pertask=$pertask&membersubmit=1";
	$processed = 0;

	$queryt = $db->query("SELECT username FROM $table_members LIMIT $current, $pertask");
	while($mem = $db->fetch_array($queryt)) {
		$processed = 1;
		$username = addslashes($mem[username]);
		$query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE author='$username'");
		$postsnum = $db->result($query, 0);
		$postsnum += $postsnum2;
		$db->query("UPDATE $table_members SET postnum='$postsnum' WHERE username='$username'");
	}

	if($processed) {
		cpmsg("重建用戶發表文章數：正在處理用戶從 $current 到 $next", $nextlink);
	} else {
		cpmsg("用戶發表文章數重建完成。");
	}
}

?>