<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
require $discuz_root.'./include/discuzcode.php';

$discuz_action = 101;

$query = $db->query("SELECT *, u.specifiedusers LIKE '%\t".addcslashes($discuz_user, '%_')."\t%' AS specifieduser, r.ranktitle, r.rankstar, r.rankcolor FROM $table_members m LEFT JOIN $table_rank r ON m.postnum>=r.posthigher LEFT JOIN $table_usergroups u ON u.specifiedusers LIKE '%\t".addcslashes($discuz_user, '%_')."\t%' OR (u.status=m.status AND ((u.creditshigher='0' AND u.creditslower='0' AND u.specifiedusers='') OR (m.credit>=u.creditshigher AND m.credit<u.creditslower))) WHERE username='$username' ORDER BY specifieduser, r.rankstar DESC");
$memberinfo = $db->fetch_array($query);

if(!isset($username) || empty($memberinfo)) {
	showmessage('member_nonexistence');
}

$memberinfo['usernameenc'] = rawurlencode(stripslashes($memberinfo['username']));
$memberinfo['regdate'] = gmdate($dateformat, $memberinfo['regdate']);
$memberinfo['site'] = $memberinfo['site'] ? 'http://'.str_replace('http://', '', $memberinfo['site']) : '';
$memberinfo['avatar'] = $memberinfo['avatar'] ? image($memberinfo['avatar']) : "<br><br><br>";
$memberinfo['lastvisit'] = gmdate("$dateformat $timeformat", $memberinfo['lastvisit'] + ($timeoffset * 3600));
$memberinfo['bio'] = nl2br($memberinfo[bio]);
$memberinfo['signature'] = postify($memberinfo['signature'], 0, 0, 0, 0, $memberinfo['allowsigbbcode'], $memberinfo['allowsigimgcode']);

$query = $db->query("SELECT COUNT(*) FROM $table_posts");
$posts = $db->result($query, 0);
@$percent = round($memberinfo['postnum'] * 100 / $posts, 2);
$memberinfo[ranktitle]=!empty($memberinfo[rankcolor]) ? "<font color=\"$memberinfo[rankcolor]\">$memberinfo[ranktitle]</font>" : $memberinfo[ranktitle];
for($i=1; $i<=$memberinfo[rankstar]; $i++){
	$rankstar.="<img src=\"".IMGDIR."/star.gif\">";
}

$stars = '';
for($i = 0; $i < $memberinfo['stars']; $i++) {
	$stars .= "<img src=\"".IMGDIR."/star.gif\">";
}

$postperday = round(24 * 3600 * $memberinfo['postnum'] / ($timestamp - $memberinfo['regdate']), 2);

$birthday = explode('-', $memberinfo['bday']);
$memberinfo['bday'] = $dateformat;
$memberinfo['bday'] = str_replace('n', $birthday[1], $memberinfo['bday']);
$memberinfo['bday'] = str_replace('j', $birthday[2], $memberinfo['bday']);
$memberinfo['bday'] = str_replace('Y', $birthday[0], $memberinfo['bday']);
$memberinfo['bday'] = str_replace('y', substr($birthday[0], 2, 4), $memberinfo['bday']);

if($isadmin) {
	require $discuz_root.'./include/misc.php';
	$memberinfo['iplocation'] = convertip($memberinfo['regip']);
}

include template('viewpro');

?>