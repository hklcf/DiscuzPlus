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

$newpmexists = 0;
if($ignorepm == 'yes') {

	$db->query("UPDATE $table_pm SET new='2' WHERE msgto='$discuz_user' AND folder='inbox' AND new='1'");
	$db->query("UPDATE $table_members SET newpm='0' WHERE username='$discuz_user'");

} else {

	$query = $db->query("SELECT pmid, msgfrom, subject, message FROM $table_pm WHERE msgto='$discuz_user' AND folder='inbox' AND new='1'");
	$newpmnum = $db->num_rows($query);
	if($newpmnum) {
		$newpmexists = 1;
		$pmlist = array();
		$pmdetail = '';
		while($pm = $db->fetch_array($query)) {
			$pm['subject'] = wordscut($pm['subject'], 20);
			$pm['message'] = wordscut($pm['message'], 50);
			$pm['msgfromenc'] = rawurlencode($pm['msgfrom']);
			$pmlist[] = $pm;
		}

		$ignorelink = $PHP_SELF.'?ignorepm=yes';
		foreach($HTTP_GET_VARS as $key => $val) {
			$ignorelink .= '&'.$key.'='.$val;
		}
	} else {
		$db->query("UPDATE $table_members SET newpm='0' WHERE username='$discuz_user'");
	}

}

