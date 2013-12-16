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

if($action == "announcements") {

	if(!$deletesubmit && !$addsubmit && !$edit) {

		$announcements = '';
		$query = $db->query("SELECT * FROM $table_announcements ORDER BY starttime DESC, id DESC");
		while($announce = $db->fetch_array($query)) {
			$announce['author'] = "<a href=\"./viewpro.php?username=".rawurlencode($announce[author])."\" target=\"_blank\">$announce[author]</a>";
			$announce['subject'] = "<a href=\"admincp.php?action=announcements&edit=$announce[id]\">$announce[subject]</a>";
			$announce['starttime'] = $announce['starttime'] ? gmdate("$dateformat", $announce[starttime] + $timeoffset * 3600) : "不限";
			$announce['endtime'] = $announce['endtime'] ? gmdate("$dateformat", $announce[endtime] + $timeoffset * 3600) : "不限";
			$announce['message'] = "<a href=\"admincp.php?action=announcements&edit=$announce[id]\">".wordscut(strip_tags($announce[message]), 20)."</a>";
			$announcements .= "<tr align=\"center\"><td bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[]\" value=\"$announce[id]\"></td>\n".
				"<td bgcolor=\"".ALTBG2."\">$announce[author]</td>\n".
				"<td bgcolor=\"".ALTBG1."\">$announce[subject]</td>\n".
				"<td bgcolor=\"".ALTBG2."\">$announce[message]</td>\n".
				"<td bgcolor=\"".ALTBG1."\">$announce[starttime]</td>\n".
				"<td bgcolor=\"".ALTBG2."\">$announce[endtime]</td></tr>\n";
		}
		$newstarttime = gmdate('Y-n-j', $timestamp + $timeoffset * 3600);

?>
<br><form method="post" action="admincp.php?action=announcements">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="6">論壇公告編輯</td></tr>
<tr align="center" class="category">
<td width="45"><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">刪</td>
<td>發布人</td><td>標題</td><td>內容</td><td>起始時間</td><td>終止時間</td></tr>
<?=$announcements?>
</table></td></tr></table><br><center>
<input type="submit" name="deletesubmit" value="刪除選定公告"></center></form>

<br><form method="post" action="admincp.php?action=announcements">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">增加論壇公告</td></tr>

<tr><td width="21%" bgcolor="<?=ALTBG1?>"><b>標題：</b></td>
<td width="79%" bgcolor="<?=ALTBG2?>"><input type="text" size="45" name="newsubject"></td></tr>

<tr><td width="21%" bgcolor="<?=ALTBG1?>"><b>起始時間：</b><br>格式：yyyy-mm-dd</td>
<td width="79%" bgcolor="<?=ALTBG2?>"><input type="text" size="45" name="newstarttime" value="<?=$newstarttime?>"></td></tr>

<tr><td width="21%" bgcolor="<?=ALTBG1?>"><b>終止時間：</b><br>格式：yyyy-mm-dd</td>
<td width="79%" bgcolor="<?=ALTBG2?>"><input type="text" size="45" name="newendtime"> 留空為不限制</td></tr>

<tr><td width="21%" bgcolor="<?=ALTBG1?>" valign="top"><b>公告內容：</b><br>公告中可以使用 Discuz! 代碼</td>
<td width="79%" bgcolor="<?=ALTBG2?>"><textarea name="newmessage" cols="60" rows="10"></textarea></td></tr>

</table></td></tr></table><br><center><input type="submit" name="addsubmit" value="增加論壇公告">
</form>
<?

	} elseif($edit) {

		if(!$editsubmit) {
			$query = $db->query("SELECT * FROM $table_announcements WHERE id='$edit'");
			if($announce = $db->fetch_array($query)) {
				$announce[starttime] = $announce[starttime] ? gmdate("Y-n-j", $announce[starttime] + $timeoffset * 3600) : "";
				$announce[endtime] = $announce[endtime] ? gmdate("Y-n-j", $announce[endtime] + $timeoffset * 3600) : "";

?>
<br><form method="post" action="admincp.php?action=announcements&edit=<?=$edit?>">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">編輯論壇公告</td></tr>

<tr><td width="21%" bgcolor="<?=ALTBG1?>"><b>標題：</b></td>
<td width="79%" bgcolor="<?=ALTBG2?>"><input type="text" size="45" name="subjectnew" value="<?=htmlspecialchars($announce[subject])?>"></td></tr>

<tr><td width="21%" bgcolor="<?=ALTBG1?>"><b>起始時間：</b><br>格式：yyyy-mm-dd</td>
<td width="79%" bgcolor="<?=ALTBG2?>"><input type="text" size="45" name="starttimenew" value="<?=$announce[starttime]?>"></td></tr>

<tr><td width="21%" bgcolor="<?=ALTBG1?>"><b>終止時間：</b><br>格式：yyyy-mm-dd</td>
<td width="79%" bgcolor="<?=ALTBG2?>"><input type="text" size="45" name="endtimenew" value="<?=$announce[endtime]?>"> 留空為不限制</td></tr>

<tr><td width="21%" bgcolor="<?=ALTBG1?>" valign="top"><b>公告內容：</b><br>公告中可以使用 BB 代碼</td>
<td width="79%" bgcolor="<?=ALTBG2?>"><textarea name="messagenew" cols="60" rows="10"><?=dhtmlspecialchars($announce[message])?></textarea></td></tr>

</table></td></tr></table><br><center><input type="submit" name="editsubmit" value="編輯論壇公告">
</form>
<?
			} else {
				cpmsg("指定的公告不存在，請返回。");
			}
		} else {
			//$newsubject = dhtmlspecialchars($newsubject);
			if(strpos($starttimenew, "-")) {
				$time = explode("-", $starttimenew);
				$starttimenew = gmmktime(0, 0, 0, $time[1], $time[2], $time[0]) - $timeoffset * 3600;
			} else {
				$starttimenew = 0;
			}
			if(strpos($endtimenew, "-")) {
				$time = explode("-", $endtimenew);
				$endtimenew = gmmktime(0, 0, 0, $time[1], $time[2], $time[0]) - $timeoffset * 3600;
			} else {
				$endtimenew = 0;
			}

			if(!$starttimenew) {
				cpmsg("您必須輸入起始時間，請返回修改。");
			} elseif(!trim($subjectnew) || !trim($messagenew)) {
				cpmsg("您必須輸入公告標題和內容，請返回修改。");
			} else {
				$db->query("UPDATE $table_announcements SET subject='$subjectnew', starttime='$starttimenew', endtime='$endtimenew', message='$messagenew' WHERE id='$edit'");
				updatecache("announcements");
				cpmsg("論壇公告成功編輯。");
			}
		}

	} elseif($deletesubmit) {

		if(is_array($delete)) {
			$ids = $comma = "";
			foreach($delete as $id) {
				$ids .= "$comma'$id'";
				$comma = ", ";
			}
			$db->query("DELETE FROM $table_announcements WHERE id IN ($ids)");
		}

		updatecache("announcements");
		cpmsg("指定公告成功刪除");

	} elseif($addsubmit) {

		//$newsubject = dhtmlspecialchars($newsubject);
		if(strpos($newstarttime, "-")) {
			$time = explode("-", $newstarttime);
			$newstarttime = gmmktime(0, 0, 0, $time[1], $time[2], $time[0]) - $timeoffset * 3600;
		} else {
			$newstarttime = 0;
		}
		if(strpos($newendtime, "-")) {
			$time = explode("-", $newendtime);
			$newendtime = gmmktime(0, 0, 0, $time[1], $time[2], $time[0]) - $timeoffset * 3600;
		} else {
			$newendtime = 0;
		}

		if(!$newstarttime) {
			cpmsg("您必須輸入起始時間，請返回修改。");
		} elseif(!trim($newsubject) || !trim($newmessage)) {
			cpmsg("您必須輸入公告標題和內容，請返回修改。");
		} else {
			$db->query("INSERT INTO $table_announcements (author, subject, starttime, endtime, message)
				VALUES ('$discuz_user', '$newsubject', '$newstarttime', '$newendtime', '$newmessage')");
			updatecache("announcements");
			cpmsg("論壇公告成功增加");
		}
	}

}

?>