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

$app = 35;

if(!$deletesubmit && !$searchsubmit) {
	require $discuz_root.'./include/forum.php';

	$forumselect = "<select name=\"forumprune\">\n";
	$forumselect .= "<option value=\"all\">�����׾�</option>\n";
	$querycat = $db->query("SELECT * FROM $table_forums WHERE type='forum' OR type='sub' ORDER BY displayorder");
	while($forum = $db->fetch_array($querycat)) {
		$forumselect .= "<option value=\"$forum[fid]\">$forum[name]</option>\n";
	}
	$forumselect .= "</select>";

?>
<br><form method="post" action="admincp.php?action=attachments">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">

<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr><td class="header" colspan="2">�j�M����</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�O���s�b�����ʥ�������G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="checkbox" name="nomatched" value="1"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Ҧb�׾¡G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><select name="forums"><option value="all">&nbsp;&nbsp;> �����׾�</option>
<option value="">&nbsp;</option><?=forumselect()?></select></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">����ؤo�p��(bytes)�G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="sizeless" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">����ؤo�j��(bytes)�G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="sizemore" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Q�U�����Ƥp��G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="dlcountless" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�Q�U�����Ƥj��G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="dlcountmore" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�o���h�֤ѥH�e�G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="daysold" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">������W�]�t�G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="filename" size="40"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">�@�̡G</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="author" size="40"></td></tr>

</table></td></tr></table><br><center>
<input type="submit" name="searchsubmit" value="�j�M����"></center>
</form>
<?

} elseif($searchsubmit) {

	require $discuz_root.'./include/attachment.php';

	$sql = "a.pid=p.pid";

	if($forums && $forums != "all") {
		$sql .= " AND p.fid='$forums'";
	} elseif($forums != "all") {
		cpmsg("�z�S����ܪ���Ҧb�׾¡A�Ъ�^�ק�C");
	}
	if($daysold != "") {
		$sql .= " AND p.dateline<='".($timestamp - (86400 * $daysold))."'";
	}
	if($author != "") {
		$sql .= " AND p.author='$author'";
	}
	if($filename != "") {
		$sql .= " AND a.filename LIKE '%$filename%'";
	}
	if($sizeless != "") {
		$sql .= " AND a.filesize<'$sizeless'";
	}
	if($sizemore != "") {
		$sql .= " AND a.filesize>'$sizemore' ";
	}
	if($dlcountless != "") {
		$sql .= " AND a.downloads<'$dlcountless'";
	}
	if($dlcountmore != "") {
		$sql .= " AND a.downloads>'$dlcountmore'";
	}

	if(!$page) {
		$page = 1;
	}
	$start = ($page - 1) * $app;

	$query = $db->query("SELECT COUNT(*) FROM $table_attachments a, $table_posts p WHERE $sql");
	$num = $db->result($query, 0);
	$multipage = multi($num, $app, $page, "admincp.php?action=attachments&filename=$filename&author=$author&forums=$forums&sizeless=$sizeless&sizemore=$sizemore&dlcountless=$dlcountless&dlcountmore=$dlcountmore&daysold=$daysold&nomatched=$nomatched&searchsubmit=1");
		
	$attachments = "";
	$query = $db->query("SELECT a.*, p.fid, p.author, t.tid, t.tid, t.subject, f.name AS fname FROM $table_attachments a, $table_posts p, $table_threads t, $table_forums f WHERE t.tid=a.tid AND f.fid=p.fid AND $sql LIMIT $start, $app");
	while($attachment = $db->fetch_array($query)) {
		$matched = file_exists("./$attachdir/$attachment[attachment]") ? NULL : "<b>������ʥ�!</b><br>";
		$attachsize = sizecount($attachment[filesize]);
		if(!$nomatched || ($nomatched && $matched)) {
			$attachments .= "<tr><td bgcolor=\"".ALTBG1."\" width=\"45\" align=\"center\" valign=\"middle\"><input type=\"checkbox\" name=\"delete[]\" value=\"$attachment[aid]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\" align=\"center\" width=\"20%\"><b>$attachment[filename]</b><br><a href=\"attachment.php?aid=$attachment[aid]\">[�U���Ӫ���]</a></td>\n".
				"<td bgcolor=\"".ALTBG1."\" align=\"center\" width=\"20%\">$matched<a href=\"$attachurl/$attachment[attachment]\" class=\"smalltxt\">$attachment[attachment]</a></td>\n".
				"<td bgcolor=\"".ALTBG2."\" align=\"center\" width=\"8%\">$attachment[author]</td>\n".
				"<td bgcolor=\"".ALTBG1."\" valign=\"middle\" width=\"25%\"><a href=\"viewthread.php?tid=$attachment[tid]\"><b>".wordscut($attachment[subject], 18)."</b></a><br>�׾¡G<a href=\"forumdisplay.php?fid=$attachment[fid]\">$attachment[fname]</a></td>\n".
				"<td bgcolor=\"".ALTBG2."\" valign=\"middle\" width=\"18%\" align=\"center\">$attachsize</td>\n".
				"<td bgcolor=\"".ALTBG1."\" valign=\"middle\" width=\"7%\" align=\"center\">$attachment[downloads]</td></tr>\n";
		}
	}
?>
<br><form method="post" action="admincp.php?action=attachments">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td class="multi" colspan="7"><?=$multipage?></td></tr>
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%" style="table-layout: fixed;word-break: break-all">
<tr><td class="header" width="6%" align="center"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">�R</td>
<td class="header" width="15%" align="center">����W</td>
<td class="header" width="25%" align="center">�x�s���W</td>
<td class="header" width="14%" align="center">�@��</td>
<td class="header" width="23%" align="center">�Ҧb�D�D</td>
<td class="header" width="8%" align="center">�ؤo</td>
<td class="header" width="8%" align="center">�U��</td></tr>
<?=$attachments?>
</table></td></tr>
<tr><td class="multi" colspan="7"><?=$multipage?></td></tr></table><br>
<center><input type="submit" name="deletesubmit" value="��s�C��"></center></form>
<?

} elseif($deletesubmit) {

	if(is_array($delete)) {
		$ids = $comma = "";
		foreach($delete as $aid) {
			$ids .= "$comma'$aid'";
			$comma = ", ";
		}

		$tids = $pids = $comma1 = $comma2 = "";
		$query = $db->query("SELECT tid, pid, attachment FROM $table_attachments WHERE aid IN ($ids)");
		while($attach = $db->fetch_array($query)) {
			@unlink("$attachdir/$attach[attachment]");
			$tids .= "$comma1'$attach[tid]'";
			$comma1 = ", ";
			$pids .= "$comma2'$attach[pid]'";
			$comma2 = ", ";
		}
		$db->query("DELETE FROM $table_attachments WHERE aid IN ($ids)");
		$db->query("UPDATE $table_posts SET aid='0' WHERE pid IN ($pids)");

		$attachtids = $comma = "";
		$query = $db->query("SELECT tid, filetype FROM $table_attachments WHERE tid IN ($tids) GROUP BY tid ORDER BY pid DESC");
		while($attach = $db->fetch_array($query)) {
			$db->query("UPDATE $table_threads SET attachment='$attach[filetype]' WHERE tid='$attach[tid]'");

			$attachtids .= "$comma'$attach[tid]'";
			$comma = ", ";
		}
		$db->query("UPDATE $table_threads SET attachment='' WHERE tid IN ($tids)".($attachtids ? " AND tid NOT IN ($attachtids)" : NULL));

		cpmsg("����C���\��s�C");
	} else {
		cpmsg("�z�S����ܭn�R��������A�Ъ�^�ק�C");
	}
}

?>