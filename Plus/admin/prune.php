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

if($action == 'prune') {

	if(!$prunesubmit) {

		require $discuz_root.'./include/forum.php';

?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>特別提示</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>條件刪除文章可自動檢索符合條件的文章並將其刪除，同時可以選擇是否扣除作者發表文章數和積分，用於清理垃圾文章。</ul>
<ul><li>批次刪除主題可刪除全部符合條件的主題，不會扣除作者發表文章數和積分，用於清理論壇舊文章。</ul>
</td></tr></table></td></tr></table>

<br><br><form method="post" action="admincp.php?action=prune&type=filter">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">

<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">

<tr>
<td class="header" colspan="2">條件刪除文章 [此操作不可恢復 請慎重使用！]</td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">刪除文章不減用戶發表文章數和積分：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="checkbox" name="donotupdatemember" value="1"></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">刪除多少天以前的文章(不限制時間請輸入 0)：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="days" size="7"></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">請選擇要批次刪除的論壇：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><select name="forums">
<option value="all">&nbsp;&nbsp;> 全部論壇</option><option value="">&nbsp;</option>
<?=forumselect()?></select></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">按用戶名刪除(多用戶中間請用半形逗號 "," 分割)：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="users" size="40"></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">包含關鍵字(多關鍵字中間請用半形逗號 "," 分割)：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="keywords" size="40"></td>
</tr>

</table></td></tr></table><br>
<center><input type="submit" name="prunesubmit" value="執 行"></center>
</form>

<br><form method="post" action="admincp.php?action=prune&type=batch">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">

<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">

<tr>
<td class="header" colspan="2">批次刪除主題 [此操作不可恢復 請慎重使用！]</td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">保留置頂和精華主題：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="checkbox" name="reserve" value="1" checked></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">刪除多少天內無新回覆的主題：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="days" size="7"></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">刪除被瀏覽次數小於多少的主題：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="views" size="7"></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">刪除被回覆次數小於多少的主題：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="replies" size="7"></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">請選擇要批次刪除的論壇：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><select name="forums">
<option value="all">&nbsp;&nbsp;> 全部論壇</option><option value="">&nbsp;</option>
<?=forumselect()?></select></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">刪除特定用戶發起的主題(多用戶中間請用半形逗號 "," 分割)：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="users" size="40"></td>
</tr>

<tr>
<td bgcolor="<?=ALTBG1?>">標題中包含關鍵字(多關鍵字中間請用半形逗號 "," 分割)：</td>
<td align="right" bgcolor="<?=ALTBG2?>"><input type="text" name="keywords" size="40"></td>
</tr>

</table></td></tr></table><br>
<center><input type="submit" name="prunesubmit" value="執 行"></center>
</form>
<?

	} else {

		if($days == '' || !$forums) {
			cpmsg("您沒有選擇時間範圍或論壇名稱。");
		}

		if($type == 'filter') {

			$sql = "SELECT fid, tid, pid, author FROM $table_posts WHERE 1";

			if($views) {
				$sql .= " AND views<'$views'";
			}

			if($replies) {
				$sql .= " AND replies<'$replies'";
			}

			if($forums != "all") {
				$sql .= " AND fid='$forums'";
			}
			if($days != "0") {
				$prunedate = $timestamp - (86400 * $days);
				$sql .= " AND dateline<='$prunedate'";
			}
			if(trim($keywords)) {
				$sqlkeywords = "";
				$or = "";
				$keywords = explode(",", str_replace(" ", "",$keywords));
				for($i = 0; $i < count($keywords); $i++) {
					$sqlkeywords .= " $or subject LIKE '%".$keywords[$i]."%' OR message LIKE '%".$keywords[$i]."%'";
					$or = "OR";
				}
				$sql .= " AND ($sqlkeywords)";
			}
			if(trim($users)) {
				$sql .= " AND author IN ('".str_replace(",", "', '", str_replace(" ", "", $users))."')";
			}

			$prune = array();
			$tids = $comma1 = $pids = $comma2 = "";
			$query = $db->query($sql);
			while($post = $db->fetch_array($query)) {
				$prune['forumposts'][$post[fid]]++;
				$prune['thread'][$post[tid]]++;
				$prune['user'][addslashes($post[author])]++;

				$tids .= "$comma1'$post[tid]'";
				$comma1 = ", ";

				$pids .= "$comma2'$post[pid]'";
				$comma2 = ", ";
			}

			if($pids) {
				$tidsdelete = $comma = "";
				$query = $db->query("SELECT fid, tid, replies FROM $table_threads WHERE tid IN ($tids)");
				while($thread = $db->fetch_array($query)) {
					if($thread[replies] + 1 <= $prune[thread][$thread[tid]]) {
						$tidsdelete .= "$comma'$thread[tid]'";
						$comma = ", ";
						$prune[forumthreads][$thread[fid]]++;
					}
				}
				if($tidsdelete) {
					$db->query("DELETE FROM $table_threads WHERE tid IN ($tidsdelete)");
				}

				$query = $db->query("SELECT attachment FROM $table_attachments WHERE pid IN ($pids)");
				while($attach = $db->fetch_array($query)) {
					@unlink("$attachdir/$attach[attachment]");
				}

				$query = $db->query("SELECT fid FROM $table_forums");
				while($forum = $db->fetch_array($query)) {
					if($prune[forumthreads][$forum[fid]] || $prune[forumposts][$forum[fid]]) {
						$prune[forumthreads][$forum[fid]] = intval($prune[forumthreads][$forum[fid]]);
						$prune[forumposts][$forum[fid]] = intval($prune[forumposts][$forum[fid]]);
						$querythd = $db->query("SELECT subject, lastpost, lastposter FROM $table_threads WHERE fid='$forum[fid]' ORDER BY lastpost DESC LIMIT 0, 1");
						$thread = $db->fetch_array($querythd);
						$thread[subject] = addslashes($thread[subject]);
						$thread[lastposter] = addslashes($thread[lastposter]);
						$db->query("UPDATE $table_forums SET threads=threads-".$prune[forumthreads][$forum[fid]].", posts=posts-".$prune[forumposts][$forum[fid]].", lastpost='$thread[subject]\t$thread[lastpost]\t$thread[lastposter]' WHERE fid='$forum[fid]'");
					}
				}

				foreach($prune[thread] as $tid => $decrease) {
					$db->query("UPDATE $table_threads SET replies=replies-$decrease WHERE tid='$tid'");
				}
				
				if(!$donotupdatemember) {
					foreach($prune[user] as $username => $decrease) {
						$db->query("UPDATE $table_members SET postnum=postnum-$decrease, credit=credit-$decrease*$postcredits WHERE username='$username'");
					}
				}

				$db->query("DELETE FROM $table_attachments WHERE pid IN ($pids)");
				$db->query("DELETE FROM $table_posts WHERE pid IN ($pids)");

				$num = $db->affected_rows();
			}

			$num = intval($num);
			cpmsg("符合條件的 $num 篇文章被刪除，相關資料成功更新。");

		} elseif($type == 'batch') {

			require_once $discuz_root.'./include/post.php';

			$sql = "SELECT fid, tid FROM $table_threads WHERE 1";

			if($forums != 'all') {
				$sql .= " AND fid='$forums'";
			}

			$prunedate = $timestamp - (86400 * $days);
			$sql .= " AND lastpost<='$prunedate'";

			if(trim($keywords)) {
				$sqlkeywords = $or = '';
				$keywords = explode(',', str_replace(' ', '',$keywords));
				foreach($keywords as $keyword) {
					$sqlkeywords .= " $or subject LIKE '%$keyword%'";
					$or = 'OR';
				}
				$sql .= " AND ($sqlkeywords)";
			}

			if(trim($users)) {
				$sql .= " AND author IN ('".str_replace(',', "', '", str_replace(' ', '', $users))."')";
			}

			if($reserve) {
				$sql .= " AND digest='0' AND topped='0'";
			}

			$fidprune = array();
			$tids = $comma = '';
			$query = $db->query($sql);
			while($thread = $db->fetch_array($query)) {
				$tids .= $comma."'$thread[tid]'";
				$fidprune[] = $thread['fid'];
				$comma = ', ';
			}

			if($tids) {

				$query = $db->query("SELECT attachment FROM $table_attachments WHERE tid IN ($tids)");
				while($attach = $db->fetch_array($query)) {
					@unlink($discuz_root.'./'.$attachdir.'/'.$attach['attachment']);
				}

				$db->query("DELETE FROM $table_attachments WHERE tid IN ($tids)");
				$db->query("DELETE FROM $table_posts WHERE tid IN ($tids)");
				$db->query("DELETE FROM $table_threads WHERE tid IN ($tids)");
				$num = $db->affected_rows();

				foreach(array_unique($fidprune) as $fid) {
					updateforumcount($fid);
				}

			}
			
			$num = intval($num);
			cpmsg("符合條件的 $num 篇主題被刪除，相關資料成功更新。");

		}

	}

} elseif($action == 'pmprune') {

	if(!$prunesubmit) {

?>
<br><br><br><form method="post" action="admincp.php?action=pmprune">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr><td class="header" colspan="2">短訊清理 [此操作不可恢復 請慎重使用！]</td></tr>

<tr><td bgcolor="<?=ALTBG1?>">不刪除未讀訊息：</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="checkbox" name="ignorenew" value="1"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">刪除多少天以前的短訊(不限制時間請輸入 0)：</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="days" size="7"></td></tr>

<tr><td bgcolor="<?=ALTBG1?>">按用戶名清理(用戶名間用半形逗號 "," 分割)：</td>
<td bgcolor="<?=ALTBG2?>" align="right"><input type="text" name="users" size="40"></td></tr>

</table></td></tr></table><br>
<center><input type="submit" name="prunesubmit" value="執 行"></center>
</form>
<?

	} else {

		if($days == "") {
			cpmsg("您沒有輸入要刪除短訊的時間範圍，請返回修改。");
		} else {
			$pruneuser = " AND (";
			$prunenew = "";
			$or = "";

			$prunedate = $timestamp - (86400 * $days);
			$arruser = explode(",", str_replace(" ", "", $users));
			for($i = 0; $i < count($arruser); $i++) {
				$arruser[$i] = trim($arruser[$i]);
				if($arruser[$i]) {
					$pruneuser .= $or."msgto='$arruser[$i]'";
					$or = " OR ";
				}
			}
			if($pruneuser == " AND (") {
				$pruneuser = "";
			} else {
				$pruneuser .= ")";
			}
			if($ignorenew) {
				$prunenew = "AND new='0'";
			}

			$db->query("DELETE FROM $table_pm WHERE dateline<='$prunedate' $pruneuser $prunenew");
			$num = $db->affected_rows();

			cpmsg("符合條件的 $num 條短訊成功刪除。");
		}
	}

}

?>