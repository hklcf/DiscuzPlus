<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';

$discuz_action = 14;

if ($discuz_user){
$ismoderator = modcheck($discuz_user);
}

$query = $db->query("SELECT a.*, t.fid FROM $table_attachments a LEFT JOIN $table_threads t ON a.tid=t.tid WHERE aid='$aid'");
$attach = $db->fetch_array($query);
if($allowgetattach && $attach['creditsrequire'] && $attach['creditsrequire'] > $credit && !$ismoderator) {
	showmessage('attachment_nopermission');
}

$query = $db->query("SELECT message,author,pid FROM $table_posts WHERE pid='$attach[pid]'");
$post = $db->fetch_array($query);
$downisok=0;
if($issupermod || $ismoderator || $isadmin || $isauthor){
        $downisok=1;
}else{
        $postmoney=0;
        $issellpost=0;
        $post[message] = preg_replace("/\[sell=(\d+)\]\s*(.+?)\s*\[\/sell\]/ies", "postsell(\\1,'\\2')", $post[message]);
        if (!$issellpost || $discuz_user==$post[author]){
                $downisok=1;
        }else{
                if ($discuz_user){
                        $query = $db->query("SELECT sum(money)as paymoneys ,count(*) as count FROM $hacktable_postpay WHERE pid='$post[pid]' AND  username='$discuz_user'");
                         $userpay=$db->fetch_array($query);
                        if (($userpay['paymoneys'] >= $postmoney) && ($userpay['count'] >0)){$downisok=1;
                                }else{showmessage("下載本附件需要付費：$postmoney 元。<br>您目前尚未支付，無法下載。");exit;}
                }else{
                        $downisok=0;
                }
        }
}

if(!$downisok){
        showmessage('本軟件為付費軟件，請登陸付費。');
}

function postsell($price, $message) {
        global $postmoney,$issellpost;
        $issellpost =1;
        $price=abs(intval($price));
        $postmoney=$postmoney+$price;
}

# DL Users
$query2 = $db->query("SELECT dl_users FROM $table_attachments WHERE aid='$aid' AND dl_users like '%$discuz_user,%'");
if($db->result($query2, 0)) {
} else {
$db->query("UPDATE $table_attachments SET downloads=downloads+1 WHERE aid='$aid'");
// hack dl users by auntieyi
$dl_user=$attach[dl_users]."\ ".$discuz_user.",";
$db->query("UPDATE $table_attachments SET dl_users='$dl_user' WHERE aid='$aid'");
$dl_user = $attach[dl_users].''.$discuz_user.', ';
$dl_user = addslashes($dl_user);
$db->query("UPDATE $table_attachments SET dl_users='$dl_user' WHERE aid='$aid'");
}# DL Users end
$filename = $discuz_root.$attachdir.'/'.$attach['attachment'];

//fix,plus:附件安全下載以及增強
if(is_readable($filename) && $attach) { 
    $filesize = filesize($filename); 
    ob_end_clean(); 
    header('Content-Encoding: none'); 
    header('Cache-Control: private'); 
    header('Content-Length: '.$filesize); 

    if (stristr($attach['filetype'],"image")){ 
        $imagesize =@getimagesize ("$filename"); 
        if ($imagesize){ 
            header('Content-Disposition: '.(strpos($HTTP_SERVER_VARS['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ').'filename='.$attach['filename']); 
            header('Content-Type: '.$attach['filetype']); 
            readfile($filename); 
        } 
    }else{ 
//        if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE")){ 
//             header("Content-Disposition: attachment;filename=".$attach['filename'].'%20'); // For IE 
//        }else{ 
             header("Content-Disposition: attachment; filename=".$attach['filename']);  
//        } 
        header('Content-Type: '.$attach['filetype']); 
        readfile($filename); 
    } 
//fix,plus:end


} else {
	showmessage('attachment_nonexistence');
}

?>