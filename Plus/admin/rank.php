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

if(!submitcheck($ranksubmit)){
	$query=$db->query("SELECT * FROM $table_rank WHERE 1 ORDER BY posthigher ASC");
	while($rankinfo=$db->fetch_array($query)){
		$ranktable.="<tr align=center>";
		$ranktable.="<td bgcolor=\"".ALTBG1."\"><input type=checkbox name=\"delete[{$rankinfo[rid]}]\" value=\"$rankinfo[rid]\"></td>";
		$ranktable.="<td bgcolor=\"".ALTBG2."\"><input type=text size=12 name=\"ranktitle[{$rankinfo[rid]}]\" value=\"$rankinfo[ranktitle]\"></td>";
		$ranktable.="<td bgcolor=\"".ALTBG1."\"><input type=text size=12 name=\"posthigher[{$rankinfo[rid]}]\" value=\"$rankinfo[posthigher]\"></td>";
		$ranktable.="<td bgcolor=\"".ALTBG2."\"><input type=text size=12 name=\"rankstar[{$rankinfo[rid]}]\" value=\"$rankinfo[rankstar]\">";
		$ranktable.="<td bgcolor=\"".ALTBG1."\"><input type=text size=20 name=\"rankcolor[{$rankinfo[rid]}]\" value=\"$rankinfo[rankcolor]\">";
		$ranktable.="</tr>";
	}

?>
<form method="post" action="admincp.php?action=ranks">
<table cellspacing="0" cellpadding="0" border="0" width="95%" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="5">�o���ŧO�s��</td></tr>
<tr class="category" align="center">
<td width="45"><input type="checkbox" name="chkall" class="category" onclick="checkall(this.form)">�R</td>
<td>�ŧO�W��</td>
<td>�o���Ƥj��</td>
<td>�P�P��</td>
<td>�ŧO�C��</td>
</tr>
<?=$ranktable?>
<tr height="1" bgcolor="<?=ALTBG2?>"><td colspan="5"></td></tr>
<tr align="center" bgcolor="<?=ALTBG1?>"><td>�s�W:</td>
<td><input type="text" size="12" name="ranktitlenew"></td>
<td><input type="text" size="12" name="posthighernew"></td>
<td><input type="text" size="12" name="rankstarnew"></td>
<td><input type="text" size="20" name="rankcolornew"></td>
</tr></table></td></tr></table>
<br><center>
<input type="submit" name="ranksubmit" value="�T�{�s��">&nbsp;
<input type="button" name="reset" value="��_�w�]" onClick="top.main.location.href='admincp.php?action=ranks&reset=yes&rankssubmit=yes';"></center></form>
<?

} else{

	if($reset != "yes") {
		if($ranktitlenew && $posthighernew) {
			$db->query("INSERT INTO $table_rank (ranktitle, posthigher, rankstar, rankcolor) VALUES ('$ranktitlenew', '$posthighernew', '$rankstarnew', '$rankcolornew')");
		}
		if(is_array($ranktitle)) {
			$ids = $comma = "";
			foreach($ranktitle as $id => $title) {
				if($delete[$id]) {
					$ids .= "$comma'$id'";
					$comma = ', ';
				} else {
					$db->query("UPDATE $table_rank SET ranktitle='$ranktitle[$id]', posthigher='$posthigher[$id]', rankstar='$rankstar[$id]', rankcolor='$rankcolor[$id]' WHERE rid='$id'");
				}
			}
		}
		if($ids) {
			$db->query("DELETE FROM $table_rank WHERE rid IN ($ids)");
		}
	} else {
		if(!$confirmed) {
			cpmsg("���ާ@���i��_�A�z�T�w�n�M���{��<br>�O���}��Τ�ճ]�w��_�w�]�ܡH", "admincp.php?action=ranks&reset=yes&rankssubmit=yes", "form");
		} else {
			$db->query("DELETE FROM $table_rank WHERE 1");
			$db->query("INSERT INTO $table_rank ('', 'Beginner', '0', '1', '')");
			$db->query("INSERT INTO $table_rank ('', 'Poster', '50', '2', '')");
			$db->query("INSERT INTO $table_rank ('', 'Cool Poster', '300', '3', '')");
			$db->query("INSERT INTO $table_rank ('', 'Writer', '1000', '4', '')");
			$db->query("INSERT INTO $table_rank ('', 'Excellent Writer', '3000', '5', '')");
			cpmsg("�o���ŧO���\��_�C");
		}
	}

	cpmsg("�o���ŧO���\��s�C");

}
?>