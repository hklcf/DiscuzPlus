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
		$groupselect = $forumselect = "<select name=\"fup\">\n<option value=\"0\" selected=\"selected\"> - �L - </option>\n";
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
<tr class="header"><td>�S�O����</td></tr>
<tr bgcolor="<?=ALTBG1?>"><td>
<br><ul><li>�׾©Τ������W�٥i�]�t����� html �N�X�C</ul>
</td></tr></table></td></tr></table>

<br><form method="post" action="admincp.php?action=forumadd&add=category">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="3">�W�[�s����</td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="15%">�����W�١G</td>
<td bgcolor="<?=ALTBG2?>" width="70%"><input type="text" name="newcat" value="�s�����W��" size="40"></td>
<td bgcolor="<?=ALTBG1?>" width="15%"><input type="submit" name="catsubmit" value="�W �["></td></tr>
</table></td></tr></table></form>

<form method="post" action="admincp.php?action=forumadd&add=forum">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="5">�W�[�s�׾�</td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="15%">�׾¦W�١G</td>
<td bgcolor="<?=ALTBG2?>" width="28%"><input type="text" name="newforum" value="�s�׾¦W��" size="20"></td>
<td bgcolor="<?=ALTBG1?>" width="15%">�W�Ť����G</td>
<td bgcolor="<?=ALTBG2?>" width="27%"><?=$groupselect?></td>
<td bgcolor="<?=ALTBG1?>" width="15%"><input type="submit" name="forumsubmit" value="�W �["></td></tr>
</table></td></tr></table></form>

<form method="post" action="admincp.php?action=forumadd&add=forum">
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="5">�W�[�s�X�׾�</td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="15%">�X�׾¦W�١G</td>
<td bgcolor="<?=ALTBG2?>" width="28%"><input type="text" name="newforum" value="�s�׾¦W��" size="20"></td>
<td bgcolor="<?=ALTBG1?>" width="15%">�W�Ž׾¡G</td>
<td bgcolor="<?=ALTBG2?>" width="27%"><?=$forumselect?></td>
<td bgcolor="<?=ALTBG1?>" width="15%"><input type="submit" name="forumsubmit" value="�W �["></td></tr>
</table></td></tr></table></form><br>
<?

	} elseif($catsubmit) {
		$db->query("INSERT INTO $table_forums (type, name, status)
			VALUES ('group', '$newcat', '1')");

		updatecache("forums");
		updatecache('homeforums');
		cpmsg("�W�[���� <b>$newcat</b> ���\�C");
	} elseif($forumsubmit) {
		$query = $db->query("SELECT type FROM $table_forums WHERE fid='$fup'");
		$type = $db->result($query, 0) == "forum" ? "sub" : "forum";
		$db->query("INSERT INTO $table_forums (fup, type, name, status, allowsmilies, allowbbcode, allowimgcode)
			VALUES ('$fup', '$type', '$newforum', '1', '1', '1', '1')");

		updatecache("forums");
		updatecache('homeforums');
		cpmsg("�W�[�׾� <b>$newforum</b> ���\�C");
	}		

} elseif($action == "forumsedit") {

        if(!$editsubmit) {

?>
<table cellspacing="0" cellpadding="0" border="0" width="<?=TABLEWIDTH?>" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">

<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td>�׾½s�� - �h�Ӫ��D���ХΥb�γr�� "," ����</td></tr>
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
                        "<input type=\"submit\" name=\"editsubmit\" value=\"��s�׾³]�m\"></center><br></td></tr></table></td></tr></table>\n";

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

                cpmsg("�׾³]�m���\��s�C");
        }

} elseif($action == "forumsmerge") {

	if(!$mergesubmit || $source == $target || !$source || !$target) {
		$forumselect = "<select name=\"%s\">\n<option value=\"0\" selected=\"selected\"> - �L - </option>\n";
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
<tr class="header"><td colspan="3">�X�ֽ׾� - ���׾ª��峹������J�ؼн׾¡A�P�ɧR�����׾�</td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="40%">���׾¡G</td>
<td bgcolor="<?=ALTBG2?>" width="60%"><?=sprintf($forumselect, "source")?></td></tr>
<tr align="center"><td bgcolor="<?=ALTBG1?>" width="40%">�ؼн׾¡G</td>
<td bgcolor="<?=ALTBG2?>" width="60%"><?=sprintf($forumselect, "target")?></td></tr>
</table></td></tr></table><br><center><input type="submit" name="mergesubmit" value="�X�ֽ׾�"></center></form>
<?

	} else {

	        $query = $db->query("SELECT COUNT(*) FROM $table_forums WHERE fup='$source'");
	        if($db->result($query, 0)) {
        		cpmsg("���׾¤U�Ž׾¤����šA�Х���^�ק�����U�Ž׾ª��W�ų]�m�C");
        	}

		$db->query("UPDATE $table_threads SET fid='$target' WHERE fid='$source'");
		$db->query("UPDATE $table_posts SET fid='$target' WHERE fid='$source'");

		$query = $db->query("SELECT threads, posts FROM $table_forums WHERE fid='$source'");
		$sourceforum = $db->fetch_array($query);
		$db->query("UPDATE $table_forums SET threads=threads+$sourceforum[threads], posts=posts+$sourceforum[posts] WHERE fid='$target'");
		$db->query("DELETE FROM $table_forums WHERE fid='$source'");

		updatecache("forums");
		updatecache('homeforums');
		cpmsg("�׾¦X�֦��\�C");
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

			showtype("�����W�ٳ]�m - $forum[name]", "top");
			showsetting("�����W�١G", "namenew", $forum[name], "text", "");
			showtype("", "bottom");

        	} else {

			$fupselect = "<select name=\"fupnew\">\n<option value=\"0\" ".(!$forum[fup] ? "selected=\"selected\"" : NULL)."> - �L - </option>\n";
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

			$styleselect = '<select name="styleidnew"><option value="0">--�ϥιw�]--</option>';
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
                		$$perm .= "</tr><tr><td colspan=4 align=right>����<input type=\"checkbox\" name=\"{$perm}all\" onClick=\"select_all('perm', '{$perm}[]', this.name);\"></td></tr></table>";
                	}

			$forum['description'] = str_replace("&lt;", "<", $forum['description']);
			$forum['description'] = str_replace("&gt;", ">", $forum['description']);

			showtype("�׾¸Բӳ]�m - $forum[name]", "top");
			showsetting("��ܽ׾¡G", "statusnew", $forum['status'], "radio", "��ܡu�_�v�N�ȮɱN�׾����ä���ܡA���׾¤��e���N�O�d");
			showsetting("�W�Ž׾¡G", "", "", $fupselect, "���׾ª��W�Ž׾©Τ���");
			showsetting("�W�Ž׾¡G", "", "", $styleselect, '�X�ݪ̶i�J���׾©ҨϥΪ�������');
			showsetting("�׾¦W�١G", "namenew", $forum['name'], "text");
			showsetting("�׾¦W���C��", "namecolornew", $forum['namecolor'], "text", "<a href=./templates/default/colorpicker.htm target=_blank>��o�̬d���C��X</a>");
			showsetting("�׾�²���C��", "descolornew", $forum['descolor'], "text", "<a href=./templates/default/colorpicker.htm target=_blank>��o�̬d���C��X</a>");
			showsetting("�׾¹ϥܡG", "iconnew", $forum['icon'], "text", "�׾¦W�٩M²���������p�ϥܡA�i��g�۹�ε���a�}");
			showsetting("�׾�²���G", "descriptionnew", $forum['description'], "textarea", "�N��ܩ�׾¦W�٪��U���A���ѹ糧�׾ª�²�u�y�z");

			showtype("�峹�ﶵ");
			showsetting("���\�ϥ� html �N�X�G", "allowhtmlnew", $forum[allowhtml], "radio", "�`�N�G��ܡu�O�v�N���B���峹��������N�X�A���i��y�����w���]���A�зV��");
			showsetting("���\�ϥ� Discuz! �N�X�G", "allowbbcodenew", $forum[allowbbcode], "radio", "Discuz! �N�X�O�@��²�ƩM�w���������榡�N�X�A�i<a href=\"./faq.php?page=misc#1\" target=\"_blank\">�I���o�̬d�ݥ��׾´��Ѫ� Discuz! �N�X</a>");
			showsetting("���\�ϥ� [img] �N�X�G", "allowimgcodenew", $forum[allowimgcode], "radio", "���\ [img] �N�X�@�̱N�i�H�b�峹���J��L�������Ϥ������");
			showsetting("���\�ϥ� Smilies�G", "allowsmiliesnew", $forum[allowsmilies], "radio", "Smilies ���ѹ���Ÿ��A�p�u:)�v���ѪR�A�Ϥ��@���Ϥ����");
			
			showtype("�׾��v�� - ������h���ӹw�]�]�m");
			showsetting("�X�ݱK�X�G", "passwordnew", $forum[password], "text", "", "15%");
			showsetting("�s���׾³\�i", "", "", str_replace("cdb_groupname", "viewperm", $viewperm), "", "15%");
			showsetting("�o��峹�\�i", "", "", str_replace("cdb_groupname", "postperm", $postperm), "", "15%");
			showsetting("�U������\�i", "", "", str_replace("cdb_groupname", "getattachperm", $getattachperm), "", "15%");
			showsetting("�W�Ǫ���\�i", "", "", str_replace("cdb_groupname", "postattachperm", $postattachperm), "", "15%");
			showtype('', 'bottom');

        	}

		echo "<br><br><center><input type=\"submit\" name=\"detailsubmit\" value=\"�T�{���\"></form>";

	} else {

		if($type == 'group') {

			if($namenew) {
				$db->query("UPDATE $table_forums SET name='$namenew' WHERE fid='$fid'");
				updatecache("forums");
				updatecache('homeforums');
				cpmsg("�����W�٦��\��s�C");
			} else {
				cpmsg("�z�S����J�����W�١A�Ъ�^�ק�C");
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
			cpmsg("�׾³]�m���\��s�C");
		}

	}

} elseif($action == "forumdelete") {

        $query = $db->query("SELECT COUNT(*) FROM $table_forums WHERE fup='$fid'");
        if($db->result($query, 0)) {
        	cpmsg("�U�Ž׾¤����šA�Х���^�R���������ν׾ª��U�Ž׾¡C");
        }

        if(!$confirmed) {
		cpmsg("���ާ@���i��_�A�z�T�w�n�R���ӽ׾¡A�M���䤤�峹<br>�M����óB�z�����|�����o��峹�M�n����ƶܡH", "admincp.php?action=forumdelete&fid=$fid", "form");
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
		cpmsg("�׾¦��\�R���C");
        }

}

?>