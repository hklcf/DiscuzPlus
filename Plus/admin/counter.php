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
<tr class="header"><td colspan="2">���إD�D�峹��</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">�C�Ӵ`����s���D�D�ơG &nbsp; &nbsp; <input type="text" name="pertask" value="300"></td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="threadsubmit" value="�� �s"> &nbsp;
<input type="reset" value="�� �m"></center></form><br>

<form method="post" action="admincp.php?action=counter">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">���إΤ�o��峹��</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">�C�Ӵ`����s���Τ�ơG &nbsp; &nbsp; <input type="text" name="pertask" value="30"></td>
</tr></table></td></tr></table><br><center>
<input type="submit" name="membersubmit" value="�� �s"> &nbsp;
<input type="reset" value="�� �m"></center></form><br>

<form method="post" action="admincp.php?action=counter">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">���ؽ׾¤峹��</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">�C�Ӵ`����s���׾¼ơG &nbsp; &nbsp; <input type="text" name="pertask" value="15"></td>
</tr></table></td></tr></table><br><center>
<input type="submit" name="forumsubmit" value="�� �s"> &nbsp;
<input type="reset" value="�� �m"></center></form><br>
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
		cpmsg("���ؽ׾¤峹�ơG���b�B�z�׾±q $current �� $next", $nextlink);
	} else {
		$db->query("UPDATE $table_forums SET threads='0', posts='0' WHERE type='group'");
		cpmsg("�׾¤峹�ƭ��ا����C");
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
		cpmsg("���إD�D�峹�ơG���b�B�z�D�D�q $current �� $next", $nextlink);
	} else {
		cpmsg("�D�D�峹�ƭ��ا����C");
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
		cpmsg("���إΤ�o��峹�ơG���b�B�z�Τ�q $current �� $next", $nextlink);
	} else {
		cpmsg("�Τ�o��峹�ƭ��ا����C");
	}
}

?>