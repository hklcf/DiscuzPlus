<?php

define('PK_DEBUG', TRUE); //�ոն}���A�b�z���T�w�|�X�{����M�I���p���ɭԡA�Ф��n��������(�}�Y�[//)�A�M��B��A�h������N���|�u���R������ƾڡA�ӥu���X����

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

if(defined("PK_DEBUG")) $pkdebug_info='<font color="red"> �B��DEBUG�Ҧ�</font>';
if(!submitcheck($pmdel_submit) && !submitcheck($favoritedel_submit) && !submitcheck($subscriptiondel_submit) && !$attachmentdel_submit && !submitcheck($none_post_thread_del_submit)) {
	require_once($configfile);
	$minpostdateline = $db->result($db->query("SELECT min(dateline) FROM $table_posts"), 0);
?>
<br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep">
<input type="hidden" name="toolsettings[version]" value="<?=$toolsettings[version]?>">
<?

	showtype('�ƾڲM�z�򥻫H��', "top");
	showsetting('����(�Фŧ��)', "readonly",$toolsettings[version],"text");
	showsetting('���󻡩�', "readonly","","�ާ@������ɡA�̦n�������׾�");

showtype("", "bottom");
?>
</table></td></tr></table><br><br>
<center>
<BR><BR>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=pm">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�M�z���s�b�Τ᪺�u����<?=$pkdebug_info?></td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">�R���w�g���s�b��ID������c�M�o��c�����u�����G &nbsp; &nbsp;
</td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="pmdel_submit" value=" �M�z�u���� "> &nbsp;
</center></form>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=fav">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�M�z���s�b�Τ᪺���åD�D</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">�R���w�g���s�b��ID���ùL���D�D�G &nbsp; &nbsp; </td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="favoritedel_submit" value=" �M�z���åD�D "> &nbsp;
</center></form>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=sst">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�M�z���s�b�Τ᪺�q�\�D�D</td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">�R���w�g���s�b��ID�q�\�L���D�D�G &nbsp; &nbsp; </td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="subscriptiondel_submit" value=" �M�z�q�\�D�D "> &nbsp;
</center></form>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=attachment">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�M�z���s�b�Τ�W�ǹL������<?=$pkdebug_info?></td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">�R���w�g���s�b��ID�W�ǹL������ &nbsp; &nbsp;
<br>�C���B�z���ѼơG <input type="text" name="perpostdateline" value="15" size="10" maxlength="4">
<br><font color="red">�`�N�G�Ӿާ@�b�K�l�ƶq���j���׾¤W�N�Q���w�C�A�j�P��ĳ�����׾¤������</font>
</td></tr>
</table></td></tr></table><br><center>
<input type="hidden" name="minpostdateline" value="<?=$minpostdateline?>">
<input type="submit" name="attachmentdel_submit" value=" �M�z�W�Ǫ��� "> &nbsp;
</center></form>

<br><br><form method="post" action="admincp.php?action=advcenter&hackname=datasweep&delete=threads">
<table cellspacing="0" cellpadding="0" border="0" width="500" align="center">
<tr><td bgcolor="<?=BORDERCOLOR?>">
<table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%">
<tr class="header"><td colspan="2">�M�z�S���K�l���D�D<?=$pkdebug_info?></td></tr>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">�R���S���K�l���D�D &nbsp; &nbsp;
<br><font color="red">�`�N�G�Ӿާ@���|��s�����O�����`�D�D�A�Цb���ާ@�᭫�s�έp�U�O���o�K��</font>
</td></tr>
</table></td></tr></table><br><center>
<input type="submit" name="none_post_thread_del_submit" value=" �M�z�L�K�D�D "> &nbsp;
</center></form>
<?php
} else {
	if($pmdel_submit) {
		if(!defined("PK_DEBUG")) {
			//�u�����M�z
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
			cpmsg("�w�R�����u�����ƶq�O $noavapm (�w�g�R��)");
		} else {
			//�u�����M�zDEBUG�Ҧ�
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
			if ($pk_tmplng) $pk_tmpmsg="�A�i�H�bphpmyadmin������p�U�y�y�ӹ�ݳo��pm�����e<br><textarea rows=\"7\" cols=\"90\">SELECT * FROM $table_pm WHERE pmid IN ($pmids)</textarea>";
			else $pk_tmpmsg='';
			cpmsg("�ŦX�R�����󪺪��u�����ƶq�O $pk_tmplng (���R��)<br>"."$pk_tmpmsg");
		}
	} elseif($favoritedel_submit) {
		//�ӥ\��LDEBUG�Ҧ�
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
		cpmsg("�w�R�������åD�D�ƶq�O $noavafav (�w�g�R��)");
	} elseif($subscriptiondel_submit) {
		//�ӥ\��LDEBUG�Ҧ�
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
		cpmsg("�w�R�����q�\�D�D�ƶq�O $noavasst (�w�g�R��)");
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
			cpmsg("�w�R�����L�K�D�D�ƶq�O $noavathd (�w�g�R��)");
		} else {
			//DEBUG�Ҧ�
			$query=$db->query("SELECT t.tid as ttid, p.tid FROM $table_threads t LEFT JOIN  $table_posts p ON t.tid=p.tid WHERE p.tid IS NULL AND (t.closed NOT LIKE 'moved|%')");
			$tids = $comma = $pk_tmpmsg = '';
			$pk_tmplng=0;
			while ($qd=$db->fetch_array($query)){
				$tids .= $comma."'$qd[ttid]'";
				$comma = ',';
				$pk_tmplng++;
				$pk_tmpmsg .= "<br><a href=\"viewthread.php?tid=$qd[ttid] target=\"_blank\">tid=$qd[ttid]</a>";
			}

			if ($pk_tmplng) $pk_tmpmsg="�A�i�H�I���p�U�s���ӹ�ݳo�ǥD�D(�@ $pk_tmplng �g)�G<br>".$pk_tmpmsg;
			cpmsg("�ŦX�R�����󪺵L�K�D�D�ƶq�O $pk_tmplng (���R��)<br>".$pk_tmpmsg);
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
				cpmsg("�R������G���b�B�z�q $current �� $next", $nextlink);
			} else {
				cpmsg("�j���ŦX�R�����󪺪���G���b�B�z�q $current �� $next", $nextlink);
			}
		} else {
			if(!defined("PK_DEBUG")) {
				cpmsg("����M�z�����C<br>�@�R���F $totaldeal �Ӫ���A�çR��������������O���B�]�m�����K�l���L����");
			} else {
				cpmsg("����j�������C<br>�@���ŦX�R������ $totaldeal ��");
			}
		}
	}
}
?>