<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

$postlist = array();
$querypost = $db->query("SELECT p.*, a.aid AS aaid, a.creditsrequire, a.filetype, a.filename, a.attachment, a.filesize, a.downloads
				FROM $table_posts p LEFT JOIN $table_attachments a ON p.aid<>'0' AND p.aid=a.aid WHERE p.tid='$tid' ORDER BY dateline");
while($post = $db->fetch_array($querypost)) {
$post['payed'] = 0 ;
	$post['dateline'] = gmdate("$dateformat $timeformat", $post['dateline'] + ($timeoffset * 3600));
	$post['message'] = postify($post['message'], $post['smileyoff'], $post['bbcodeoff'], $forum['allowsmilies'], $forum['allowhtml'], $forum['allowbbcode'], $forum['allowimgcode']);
	if($post['aaid']) {
		require_once $discuz_root.'./include/attachment.php';
		$extension = strtolower(substr(strrchr($post['filename'], "."), 1));
		$post['attachicon'] = attachicon($extension."\t".$post['filetype']);
		if($attachimgpost && ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'jpe' || $extension == 'gif' || $extension == 'png' || $extension == 'bmp')) {
			$post['attachimg'] = 1;
		} else {
			$post['attachimg'] = 0;
			$post['attachsize'] = sizecount($post['filesize']);
		}
	}
	$postlist[] = $post;
}

include template('viewthread_printable');

?>