<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

cpheader();

if($action == "forumadd") {

	if((!$catsubmit && !$forumsubmit)) {
		$groupselect = $forumselect = "<select name=\"fup\">\n<option value=\"0\" selected=\"selected\"> - 無 - </option>\n";
		$query = $db->query("SELECT fid, name, type FROM $table_forums WHERE type<>'sub' ORDER BY displayorder");
		while($fup = $db->fetch_array($query)) {
			if($fup[type] == "group") {
				$groupselect .= "<option value=\"$fup[fid]\">$fup[name]</option>\n";
			} else {
				$forumselect .= "<option value=\"$fup[fid]\">$fup[name]</option>\n";
			}
		}
		$groupselect .= "</select>";
		$forumselect .= "</select>";

?>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>特別提示</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>論壇或分類的名稱可包含並顯示 html 代碼。</ul>
</td></tr></table></td></tr></table>

<br><form method="post" action="admincp.php?action=forumadd&add=category">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="3">增加新分類</td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="15%">分類名稱：</td>
<td bgcolor="<?=ALTBG2?>" width="70%"><input type="text" name="newcat" value="新分類名稱" size="40"></td>
<td bgcolor="<?=ALTBG1?>" width="15%"><input type="submit" name="catsubmit" value="增 加"></td></tr>
</table></td></tr></table></form>

<form method="post" action="admincp.php?action=forumadd&add=forum">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="5">增加新論壇</td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="15%">論壇名稱：</td>
<td bgcolor="<?=ALTBG2?>" width="28%"><input type="text" name="newforum" value="新論壇名稱" size="20"></td>
<td bgcolor="<?=ALTBG1?>" width="15%">上級分類：</td>
<td bgcolor="<?=ALTBG2?>" width="27%"><?=$groupselect?></td>
<td bgcolor="<?=ALTBG1?>" width="15%"><input type="submit" name="forumsubmit" value="增 加"></td></tr>
</table></td></tr></table></form>

<form method="post" action="admincp.php?action=forumadd&add=forum">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="5">增加新幾論壇</td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="15%">幾論壇名稱：</td>
<td bgcolor="<?=ALTBG2?>" width="28%"><input type="text" name="newforum" value="新論壇名稱" size="20"></td>
<td bgcolor="<?=ALTBG1?>" width="15%">上級論壇：</td>
<td bgcolor="<?=ALTBG2?>" width="27%"><?=$forumselect?></td>
<td bgcolor="<?=ALTBG1?>" width="15%"><input type="submit" name="forumsubmit" value="增 加"></td></tr>
</table></td></tr></table></form><br>
<?

	} elseif($catsubmit) {
		$db->query("INSERT INTO $table_forums (type, name, status)
			VALUES ('group', '$newcat', '1')");

		updatecache("forums");
		updatecache('homeforums');
		cpmsg("增加分類 <b>$newcat</b> 成功。");
	} elseif($forumsubmit) {
		$query = $db->query("SELECT type FROM $table_forums WHERE fid='$fup'");
		$type = $db->result($query, 0) == "forum" ? "sub" : "forum";
		$db->query("INSERT INTO $table_forums (fup, type, name, status, allowsmilies, allowbbcode, allowimgcode)
			VALUES ('$fup', '$type', '$newforum', '1', '1', '1', '1')");

		updatecache("forums");
		updatecache('homeforums');
		cpmsg("增加論壇 <b>$newforum</b> 成功。");
	}		

} elseif($action == "forumsedit") {

        if(!$editsubmit) {

?>
<table cellspacing="0" cellpadding="0" border="0" width="<?=TABLEWIDTH?>" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">

<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>論壇編輯 - 多個版主間請用半形逗號 "," 分割</td></tr>
<tr><td bgcolor="<?=ALTBG1?>"><br>
<form method="post" action="admincp.php?action=forumsedit">
<?

                $modsorig = $comma = "";
                $query = $db->query("SELECT fid, type, status, name, fup, displayorder, moderator FROM $table_forums ORDER BY displayorder");
                while($forum = $db->fetch_array($query)) {
                        $forums[] = $forum;
                        $modsorig .= $comma.$forum[moderator];
                }

                for($i = 0; $i < count($forums); $i++) {
                        if($forums[$i][type] == "group") {
                                echo "<ul>";
                                showforum($forums[$i], 1, "group");
                                for($j = 0; $j < count($forums); $j++) {
                                        if($forums[$j][fup] == $forums[$i][fid] && $forums[$j][type] == "forum") {
                                                echo "<ul>";
                                                showforum($forums[$j], 2);
                                                for($k = 0; $k < count($forums); $k++) {
                                                        if($forums[$k][fup] == $forums[$j][fid] && $forums[$k][type] == "sub") {
                                                                echo "<ul>";
                                                                showforum($forums[$k], 3, "sub");
                                                                echo "</ul>";
                                                        }
                                                }
                                                echo "</ul>";
                                        }
                                }
                                echo "</ul>";
                        } elseif(!$forums[$i][fup] && $forums[$i][type] == "forum") {
                                echo "<ul>";
                                showforum($forums[$i], 1);
                                for($j = 0; $j < count($forums); $j++) {
                                        if($forums[$j][fup] == $forums[$i][fid] && $forums[$j][type] == "sub") {
                                                echo "<ul>";
                                                showforum($forums[$j], 2, "sub");
                                                echo "</ul>";
                                        }
                                }
                                echo "</ul>";
                        }
                }
                echo "<input type=\"hidden\" name=\"modsorig\" value=\"$modsorig\"><br><center>\n".
                        "<input type=\"submit\" name=\"editsubmit\" value=\"更新論壇設置\"></center><br></td></tr></table></td></tr></table>\n";

        } else {

                if(is_array($order)) {
                        $modlist = $comma = '';
                        foreach($order as $fid => $value) {
                                $modlist .= $comma.$moderator[$fid];
                                $comma = ',';
                                $db->query("UPDATE $table_forums SET moderator='$moderator[$fid]', displayorder='$order[$fid]' WHERE fid='$fid'");
                        }
                }

                updatecache("forums");
	updatecache('homeforums');
                $modsorig = "'".str_replace(",", "', '", str_replace(" ", "", $modsorig))."'";
                $modlist = "'".str_replace(",", "', '", str_replace(" ", "", $modlist))."'";
                $db->query("UPDATE $table_members SET status='Member' WHERE status<>'Admin' AND status='Moderator' AND username IN ($modsorig)");
                $db->query("UPDATE $table_members SET status='Moderator' WHERE status<>'Admin' AND status<>'SuperMod' AND username IN ($modlist)");

                cpmsg("論壇設置成功更新。");
        }

} elseif($action == "forumsmerge") {

	if(!$mergesubmit || $source == $target || !$source || !$target) {
		$forumselect = "<select name=\"%s\">\n<option value=\"0\" selected=\"selected\"> - 無 - </option>\n";
		$query = $db->query("SELECT fid, name FROM $table_forums WHERE type<>'group' ORDER BY displayorder");
		while($forum = $db->fetch_array($query)) {
			$forumselect .= "<option value=\"$forum[fid]\">$forum[name]</option>\n";
		}
		$forumselect .= "</select>";

?>
<br><br><br><br><br>
<form method="post" action="admincp.php?action=forumsmerge">
<table cellspacing="0" cellpadding="0" border="0" width="85%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="3">合併論壇 - 源論壇的文章全部轉入目標論壇，同時刪除源論壇</td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="40%">源論壇：</td>
<td bgcolor="<?=ALTBG2?>" width="60%"><?=sprintf($forumselect, "source")?></td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="40%">目標論壇：</td>
<td bgcolor="<?=ALTBG2?>" width="60%"><?=sprintf($forumselect, "target")?></td></tr>
</table></td></tr></table><br><center><input type="submit" name="mergesubmit" value="合併論壇"></center></form>
<?

	} else {

	        $query = $db->query("SELECT COUNT(*) FROM $table_forums WHERE fup='$source'");
	        if($db->result($query, 0)) {
        		cpmsg("源論壇下級論壇不為空，請先返回修改相關下級論壇的上級設置。");
        	}

		$db->query("UPDATE $table_threads SET fid='$target' WHERE fid='$source'");
		$db->query("UPDATE $table_posts SET fid='$target' WHERE fid='$source'");

		$query = $db->query("SELECT threads, posts FROM $table_forums WHERE fid='$source'");
		$sourceforum = $db->fetch_array($query);
		$db->query("UPDATE $table_forums SET threads=threads+$sourceforum[threads], posts=posts+$sourceforum[posts] WHERE fid='$target'");
		$db->query("DELETE FROM $table_forums WHERE fid='$source'");

		updatecache("forums");
		updatecache('homeforums');
		cpmsg("論壇合併成功。");
	}

} elseif($action == "forumdetail") {

	$perms = array("viewperm", "postperm", "getattachperm", "postattachperm");

        if(!$detailsubmit) {
        	$query = $db->query("SELECT * FROM $table_forums WHERE fid='$fid'");
        	$forum = $db->fetch_array($query);
        	$forum['name'] = dhtmlspecialchars($forum[name]);

        	echo "<br><form method=\"post\" name=\"perm\" action=\"admincp.php?action=forumdetail&fid=$fid\">\n".
        		"<input type=\"hidden\" name=\"type\" value=\"$forum[type]\">\n";

        	if($forum[type] == "group") {

			showtype("分類名稱設置 - $forum[name]", "top");
			showsetting("分類名稱：", "namenew", $forum[name], "text", "");
			showtype("", "bottom");

        	} else {

			$fupselect = "<select name=\"fupnew\">\n<option value=\"0\" ".(!$forum[fup] ? "selected=\"selected\"" : NULL)."> - 無 - </option>\n";
			$query = $db->query("SELECT fid, name FROM $table_forums WHERE fid<>'$fid' AND type<>'sub' ORDER BY displayorder");
			while($fup = $db->fetch_array($query)) {
				$selected = $fup[fid] == $forum[fup] ? "selected=\"selected\"" : NULL;
				$fupselect .= "<option value=\"$fup[fid]\" $selected>$fup[name]</option>\n";
			}
			$fupselect .= "</select>";
			$query = $db->query("SELECT groupid, grouptitle FROM $table_usergroups");
			while($group = $db->fetch_array($query)) {
				$groups[] = $group;
			}

			$styleselect = '<select name="styleidnew"><option value="0">--使用預設--</option>';
			$query = $db->query("SELECT styleid, name FROM $table_styles");
			while($style = $db->fetch_array($query)) {
				$styleselect .= "<option value=\"$style[styleid]\" ".
					($style['styleid'] == $forum['styleid'] ? 'selected="selected"' : NULL).
					">$style[name]</option>\n";
			}
			$styleselect .= '</select>';

                	foreach($perms as $perm) {
	                	$num = -1;
                		$$perm = "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\" align=\"center\"><tr>";
				foreach($groups as $group) {
					$num++;
					if($num && $num % 4 == 0) {
						$$perm .= "</tr><tr>";
					}
	                        	$checked = strstr($forum[$perm], "\t$group[groupid]\t") ? "checked" : NULL;
                        		$$perm .= "<td><input type=\"checkbox\" name=\"{$perm}[]\" value=\"$group[groupid]\" $checked> $group[grouptitle]</td>\n";
				}
                		$$perm .= "</tr><tr><td colspan=4 align=right>全選<input type=\"checkbox\" name=\"{$perm}all\" onClick=\"select_all('perm', '{$perm}[]', this.name);\"></td></tr></table>";
                	}

			$forum['description'] = str_replace("&lt;", "<", $forum['description']);
			$forum['description'] = str_replace("&gt;", ">", $forum['description']);

			showtype("論壇詳細設置 - $forum[name]", "top");
			showsetting("顯示論壇：", "statusnew", $forum['status'], "radio", "選擇「否」將暫時將論壇隱藏不顯示，但論壇內容仍將保留");
			showsetting("上級論壇：", "", "", $fupselect, "本論壇的上級論壇或分類");
			showsetting("上級論壇：", "", "", $styleselect, '訪問者進入本論壇所使用的風格方案');
			showsetting("論壇名稱：", "namenew", $forum['name'], "text");
			showsetting("論壇名稱顏色", "namecolornew", $forum['namecolor'], "text", "<a href=./templates/default/colorpicker.htm target=_blank>到這裡查看顏色碼</a>");
			showsetting("論壇簡介顏色", "descolornew", $forum['descolor'], "text", "<a href=./templates/default/colorpicker.htm target=_blank>到這裡查看顏色碼</a>");
			showsetting("論壇圖示：", "iconnew", $forum['icon'], "text", "論壇名稱和簡介左側的小圖示，可填寫相對或絕對地址");
			showsetting("論壇簡介：", "descriptionnew", $forum['description'], "textarea", "將顯示於論壇名稱的下面，提供對本論壇的簡短描述");

			showtype("文章選項");
			showsetting("允許使用 html 代碼：", "allowhtmlnew", $forum[allowhtml], "radio", "注意：選擇「是」將不遮蔽文章中的任何代碼，有可能造成不安全因素，請慎用");
			showsetting("允許使用 Discuz! 代碼：", "allowbbcodenew", $forum[allowbbcode], "radio", "Discuz! 代碼是一種簡化和安全的頁面格式代碼，可<a href=\"./faq.php?page=misc#1\" target=\"_blank\">點擊這裡查看本論壇提供的 Discuz! 代碼</a>");
			showsetting("允許使用 [img] 代碼：", "allowimgcodenew", $forum[allowimgcode], "radio", "允許 [img] 代碼作者將可以在文章插入其他網站的圖片並顯示");
			showsetting("允許使用 Smilies：", "allowsmiliesnew", $forum[allowsmilies], "radio", "Smilies 提供對表情符號，如「:)」的解析，使之作為圖片顯示");
			
			showtype("論壇權限 - 全不選則按照預設設置");
			showsetting("訪問密碼：", "passwordnew", $forum[password], "text", "", "15%");
			showsetting("瀏覽論壇許可", "", "", str_replace("cdb_groupname", "viewperm", $viewperm), "", "15%");
			showsetting("發表文章許可", "", "", str_replace("cdb_groupname", "postperm", $postperm), "", "15%");
			showsetting("下載附件許可", "", "", str_replace("cdb_groupname", "getattachperm", $getattachperm), "", "15%");
			showsetting("上傳附件許可", "", "", str_replace("cdb_groupname", "postattachperm", $postattachperm), "", "15%");
			showtype('', 'bottom');

        	}

		echo "<br><br><center><input type=\"submit\" name=\"detailsubmit\" value=\"確認更改\"></form>";

	} else {

		if($type == 'group') {

			if($namenew) {
				$db->query("UPDATE $table_forums SET name='$namenew' WHERE fid='$fid'");
				updatecache("forums");
				updatecache('homeforums');
				cpmsg("分類名稱成功更新。");
			} else {
				cpmsg("您沒有輸入分類名稱，請返回修改。");
			}
			
		} else {

			foreach($perms as $perm) {
				if(is_array($$perm)) {
					${$perm."new"} = "\t";
					foreach($$perm as $groupid) {
						${$perm."new"} .= "\t$groupid";
					}
					${$perm."new"} .= "\t\t";
				}
			}

			$query = $db->query("SELECT type FROM $table_forums WHERE fid='$fupnew'");
			$fuptype = $db->result($query, 0);
			$typenew = $fuptype == "forum" ? "sub" : "forum";
			$db->query("UPDATE $table_forums SET type='$typenew', status='$statusnew', fup='$fupnew', name='$namenew', icon='$iconnew', namecolor='$namecolornew', descolor='$descolornew',
				description='$descriptionnew', styleid='$styleidnew', allowhtml='$allowhtmlnew', allowbbcode='$allowbbcodenew',
				allowimgcode='$allowimgcodenew', allowsmilies='$allowsmiliesnew', password='$passwordnew', viewperm='$viewpermnew',
				postperm='$postpermnew', getattachperm='$getattachpermnew', postattachperm='$postattachpermnew' WHERE fid='$fid'");

			updatecache("forums");
			updatecache('homeforums');
			cpmsg("論壇設置成功更新。");
		}

	}

} elseif($action == "forumdelete") {

        $query = $db->query("SELECT COUNT(*) FROM $table_forums WHERE fup='$fid'");
        if($db->result($query, 0)) {
        	cpmsg("下級論壇不為空，請先返回刪除本分類或論壇的下級論壇。");
        }

        if(!$confirmed) {
		cpmsg("本操作不可恢復，您確定要刪除該論壇，清除其中文章<br>和附件並處理相關會員的發表文章和積分資料嗎？", "admincp.php?action=forumdelete&fid=$fid", "form");
        } else {
       		require $discuz_root.'./include/post.php';
        	$query = $db->query("SELECT COUNT(*) AS postnum, author FROM $table_posts WHERE fid='$fid' GROUP BY author");
        	while($post = $db->fetch_array($query)) {
        		updatemember('-', $post['author'], $post['postnum']);
        	}

        	$query = $db->query("SELECT pid FROM $table_posts WHERE aid<>'0' AND fid='$fid'");
        	$aid = $comma = "";
        	while($post = $db->fetch_array($query)) {
        		$aid .= "$comma'$post[aid]'";
        		$comma = ", ";
        	}

        	if($aid) {
        		$query = $db->query("SELECT filename FROM $table_attachments WHERE aid IN ($aid)");
        		while($attach = $db->fetch_array($query)) {
        			@unlink("$attachdir/$attach[filename]");
        		}
			$db->query("DELETE FROM $table_attachments WHERE aid IN ($aid)");
        	}

		$db->query("DELETE FROM $table_threads WHERE fid='$fid'");
		$db->query("DELETE FROM $table_posts WHERE fid='$fid'");
		$db->query("DELETE FROM $table_forums WHERE fid='$fid'");

		updatecache("forums");
		updatecache('homeforums');
		cpmsg("論壇成功刪除。");
        }

}

?>