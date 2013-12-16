<?php

define('PK_DEBUG', TRUE); //調試開關，在您不確定會出現任何危險情況的時候，請不要註釋本行(開頭加//)，然後運行，則本插件將不會真正刪除任何數據，而只給出提示

if(!defined("IN_DISCUZ") || !defined("IN_ADVANCE_CENTER")) {
	exit("Access Denied");
}
$max_allowed_packet=1024000;
$max_allowed_packet_safe=$max_allowed_packet-4000;

$query=$db->query("SHOW VARIABLES");
while ($qd=$db->fetch_array($query)) {
	if ($qd['Variable_name']=='max_allowed_packet') {
		$max_allowed_packet=intval($qd['Value']);
		$max_allowed_packet_safe=$max_allowed_packet-4000;
		break;
	}
}

if(defined("PK_DEBUG")) $pkdebug_info='<font color="red"> 處於DEBUG模式</font>';
if(!submitcheck($pmdel_submit) && !submitcheck($favoritedel_submit) && !submitcheck($subscriptiondel_submit) && !$attachmentdel_submit && !submitcheck($none_post_thread_del_submit)) {
	require_once($configfile);
	$minpostdateline = $db->result($db->query("SELECT min(dateline) FROM $table_posts"), 0);
?>
<br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep">
<input type="hidden" name="toolsettings[version]" value="<?=$toolsettings[version]?>">
<?

	showtype('數據清理基本信息', "top");
	showsetting('版本(請勿更改)', "readonly",$toolsettings[version],"text");
	showsetting('插件說明', "readonly","","操作本插件時，最好先關閉論壇");

showtype("", "bottom");
?>
</table></td></tr></table><br><br>
<center>
<BR><BR>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=pm">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">清理不存在用戶的短消息<?=$pkdebug_info?></td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">刪除已經不存在的ID的收件箱和發件箱中的短消息： &nbsp; &nbsp;
</td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="pmdel_submit" value=" 清理短消息 "> &nbsp;
</center></form>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=fav">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">清理不存在用戶的收藏主題</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">刪除已經不存在的ID收藏過的主題： &nbsp; &nbsp; </td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="favoritedel_submit" value=" 清理收藏主題 "> &nbsp;
</center></form>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=sst">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">清理不存在用戶的訂閱主題</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">刪除已經不存在的ID訂閱過的主題： &nbsp; &nbsp; </td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="subscriptiondel_submit" value=" 清理訂閱主題 "> &nbsp;
</center></form>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=attachment">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">清理不存在用戶上傳過的附件<?=$pkdebug_info?></td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">刪除已經不存在的ID上傳過的附件 &nbsp; &nbsp;
<br>每次處理的天數： <input type="text" name="perpostdateline" value="15" size="10" maxlength="4">
<br><font color="red">注意：該操作在貼子數量巨大的論壇上將十分緩慢，強烈建議關閉論壇之後執行</font>
</td></tr>
</table></td></tr></table><br><center>
<input type="hidden" name="minpostdateline" value="<?=$minpostdateline?>">
<input type="submit" name="attachmentdel_submit" value=" 清理上傳附件 "> &nbsp;
</center></form>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=threads">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">清理沒有貼子的主題<?=$pkdebug_info?></td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">刪除沒有貼子的主題 &nbsp; &nbsp;
<br><font color="red">注意：該操作不會更新相關板塊的總主題，請在此操作後重新統計各板塊發貼數</font>
</td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="none_post_thread_del_submit" value=" 清理無貼主題 "> &nbsp;
</center></form>
<?php
} else {
	if($pmdel_submit) {
		if(!defined("PK_DEBUG")) {
			//短消息清理
			$noavapm=0;
			$query=$db->query("SELECT p.pmid, m.username FROM $table_pm p LEFT JOIN $table_members m ON p.msgto=m.username WHERE m.username IS NULL");
			$pmids=$comma='';
			while ($qd=$db->fetch_array($query)) {
				$pmids .= $comma . "'".$qd['pmid']."'";
				$comma = ',';
				if (strlen($pmids) > $max_allowed_packet_safe) {
					$querytmp = $db->query("DELETE FROM $table_pm WHERE pmid IN ($pmids)");
					$noavapm += $db->affected_rows($querytmp);
					$pmids=$comma='';
				}
			}
			if ($pmids) {
				$querytmp = $db->query("DELETE FROM $table_pm WHERE pmid IN ($pmids)");
				$noavapm += $db->affected_rows($querytmp);
			}
			$query=$db->query("SELECT p.pmid, m.username FROM $table_pm p LEFT JOIN $table_members m ON  p.msgfrom=m.username WHERE m.username IS NULL AND p.folder='outbox'");
			$pmids=$comma='';
			while ($qd=$db->fetch_array($query)) {
				$pmids .= $comma . "'".$qd['pmid']."'";
				$comma = ',';
				if (strlen($pmids) > $max_allowed_packet_safe) {
					$querytmp = $db->query("DELETE FROM $table_pm WHERE pmid IN ($pmids)");
					$noavapm += $db->affected_rows($querytmp);
					$pmids=$comma='';
				}
			}
			if ($pmids) {
				$querytmp = $db->query("DELETE FROM $table_pm WHERE pmid IN ($pmids)");
				$noavapm += $db->affected_rows($querytmp);
			}
			cpmsg("已刪除的短消息數量是 $noavapm (已經刪除)");
		} else {
			//短消息清理DEBUG模式
			$query=$db->query("SELECT p.pmid, m.username FROM $table_pm p LEFT JOIN $table_members m ON p.msgto=m.username WHERE m.username IS NULL");
			$pmids=$comma='';
			$pk_tmplng=0;
			while ($qd=$db->fetch_array($query)) {
				$pmids .= $comma . "'".$qd['pmid']."'";
				$comma = ',';
				$pk_tmplng++;
			}
			$query=$db->query("SELECT p.pmid, m.username FROM $table_pm p LEFT JOIN $table_members m ON  p.msgfrom=m.username WHERE m.username IS NULL AND p.folder='outbox'");
			while ($qd=$db->fetch_array($query)) {
				$pmids .= $comma . "'".$qd['pmid']."'";
				$comma = ',';
				$pk_tmplng++;
			}
			if ($pk_tmplng) $pk_tmpmsg="你可以在phpmyadmin中執行如下語句來察看這些pm的內容<br><textarea rows=\"7\" cols=\"90\">SELECT * FROM $table_pm WHERE pmid IN ($pmids)</textarea>";
			else $pk_tmpmsg='';
			cpmsg("符合刪除條件的的短消息數量是 $pk_tmplng (未刪除)<br>"."$pk_tmpmsg");
		}
	} elseif($favoritedel_submit) {
		//該功能無DEBUG模式
		$query=$db->query("SELECT f.tid, m.username FROM $table_favorites f LEFT JOIN $table_members m ON f.username=m.username WHERE m.username IS NULL");
		$tids=$comma='';
		$noavafav=0;
		while ($qd=$db->fetch_array($query)) {
			$tids .= $comma . "'".$qd['tid']."'";
			$comma = ',';
			if (strlen($tids) > $max_allowed_packet_safe) {
				$querytmp = $db->query("DELETE FROM $table_favorites WHERE tid IN ($tids)");
				$noavafav += $db->affected_rows($querytmp);
				$tids=$comma='';
			}
		}
		if ($tids) {
			$querytmp = $db->query("DELETE FROM $table_favorites WHERE tid IN ($tids)");
			$noavafav += $db->affected_rows($querytmp);
		}
		cpmsg("已刪除的收藏主題數量是 $noavafav (已經刪除)");
	} elseif($subscriptiondel_submit) {
		//該功能無DEBUG模式
		$query=$db->query("SELECT s.username AS sname, m.username FROM $table_subscriptions s LEFT JOIN $table_members m ON s.username=m.username WHERE m.username IS NULL");
		$users=$comma='';
		$noavasst=0;
		while ($qd=$db->fetch_array($query)) {
			$users .= $comma . "'".addslashes($qd['sname'])."'";
			$comma = ',';
			if (strlen($users) > $max_allowed_packet_safe) {
				$querytmp = $db->query("DELETE FROM $table_subscriptions WHERE username IN ($users)");
				$noavasst += $db->affected_rows($querytmp);
				$users=$comma='';
			}
		}
		if ($users) {
			$querytmp = $db->query("DELETE FROM $table_subscriptions WHERE username IN ($users)");
			$noavasst += $db->affected_rows($querytmp);
		}
		cpmsg("已刪除的訂閱主題數量是 $noavasst (已經刪除)");
	} elseif($none_post_thread_del_submit) {
		if(!defined("PK_DEBUG")) {
			$query=$db->query("SELECT t.tid as ttid, p.tid FROM $table_threads t LEFT JOIN  $table_posts p ON t.tid=p.tid WHERE p.tid IS NULL AND (t.closed NOT LIKE 'moved|%')");
			$tids = $comma = '';
			$noavathd=0;
			while ($qd=$db->fetch_array($query)){
				$tids .= $comma."'$qd[ttid]'";
				$comma = ',';
				if (strlen($tids) > $max_allowed_packet_safe) {
					$querytmp = $db->query("DELETE FROM $table_threads WHERE tid IN ($tids)");
					$noavathd += $db->affected_rows($querytmp);
					$tids=$comma='';
				}
			}
			if ($tids) {
				$querytmp = $db->query("DELETE FROM $table_threads WHERE tid IN ($tids)");
				$noavathd += $db->affected_rows($querytmp);
			}
			cpmsg("已刪除的無貼主題數量是 $noavathd (已經刪除)");
		} else {
			//DEBUG模式
			$query=$db->query("SELECT t.tid as ttid, p.tid FROM $table_threads t LEFT JOIN  $table_posts p ON t.tid=p.tid WHERE p.tid IS NULL AND (t.closed NOT LIKE 'moved|%')");
			$tids = $comma = $pk_tmpmsg = '';
			$pk_tmplng=0;
			while ($qd=$db->fetch_array($query)){
				$tids .= $comma."'$qd[ttid]'";
				$comma = ',';
				$pk_tmplng++;
				$pk_tmpmsg .= "<br><a href=\"viewthread.php?tid=$qd[ttid] target=\"_blank\">tid=$qd[ttid]</a>";
			}

			if ($pk_tmplng) $pk_tmpmsg="你可以點擊如下連接來察看這些主題(共 $pk_tmplng 篇)：<br>".$pk_tmpmsg;
			cpmsg("符合刪除條件的無貼主題數量是 $pk_tmplng (未刪除)<br>".$pk_tmpmsg);
		}
	} elseif($attachmentdel_submit) {
		$perpostdateline = $perpostdateline ? intval($perpostdateline) : 0;
		$current = $current ? intval($current) : 0;
		$next = $current + $perpostdateline;
		$totaldeal = $totaldeal ? intval($totaldeal) : 0;
		$aftdateline = $timestamp-$current*86400;
		$predateline = $timestamp-($perpostdateline+$current)*86400;
		if ($predateline < $minpostdateline) $predateline=$minpostdateline;
		if ($aftdateline < $minpostdateline) $aftdateline=$minpostdateline;
		$query=$db->query("SELECT p.tid,p.pid,p.aid,a.attachment,m.username FROM $table_posts p LEFT JOIN $table_members m ON p.author=m.username, $table_attachments a WHERE p.aid=a.aid AND p.aid>0 AND m.username IS NULL AND p.dateline>$predateline AND p.dateline<$aftdateline AND p.dateline>$minpostdateline");
		$pids = $aids = $comma = '';
		$aidrows = 0;
		while ($qd=$db->fetch_array($query)){
			$aidrows++;
			if(!defined("PK_DEBUG")) {
				$pids .= $comma."'$qd[pid]'";
				$aids .= $comma."'$qd[aid]'";
				$comma = ',';
				@unlink("$attachdir/$qd[attachment]");
			}
		}
		$totaldeal += $aidrows;
		if(!defined("PK_DEBUG")) {
			if ($aids) $db->query("DELETE FROM $table_attachments WHERE aid IN ($aids)");
			if ($pids) $db->query("UPDATE $table_posts set aid='0' WHERE pid IN ($pids)");
		}
		$nextlink = "admincp.php?action=advcenter&hackname=datasweep&delete=attachment&current=$next&perpostdateline=$perpostdateline&totaldeal=$totaldeal&minpostdateline=$minpostdateline&attachmentdel_submit=1";
		if($predateline!=$minpostdateline) {
			if(!defined("PK_DEBUG")) {
				cpmsg("刪除附件：正在處理從 $current 到 $next", $nextlink);
			} else {
				cpmsg("搜索符合刪除條件的附件：正在處理從 $current 到 $next", $nextlink);
			}
		} else {
			if(!defined("PK_DEBUG")) {
				cpmsg("附件清理完成。<br>共刪除了 $totaldeal 個附件，並刪除附件表中的對應記錄、設置相關貼子為無附件");
			} else {
				cpmsg("附件搜索完成。<br>共找到符合刪除條件的 $totaldeal 個");
			}
		}
	}
}
?>