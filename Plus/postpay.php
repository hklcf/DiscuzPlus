<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
$discuz_action = 220;
$navtitle = '- �R�橫�l';

if(!$discuz_user) {
    showmessage('not_loggedin');
}

if (!$tid || !$pid || !$sellcount) showmessage('undefined_action ');

if ($action =="pay"){
    $payaction="�Τ�I�O";
    $money =abs(intval($money));
    $query = $db->query("
                SELECT avatar,credit,bank,money,savemt FROM $table_members WHERE username='$discuz_user'"); 
    $userbank=$db->fetch_array($query);
    $usermoney=$userbank[money]-$money;
    if ($money > $userbank[money]) showmessage('�{�������A�L�k�I�O');
    if (submitcheck($paysubmit)){
        $query = $db->query("SELECT author FROM $table_posts where pid='$pid'");
        $post=$db->fetch_array($query);
        $postuser = $post['author'];
        $db->query("INSERT INTO $hacktable_postpay (tid,pid,sellcount,author,username,money,dateline) VALUES('$tid', '$pid','$sellcount','$postuser','$discuz_user', '$money','$timestamp')");
        if ($money>0){
            $query = $db->query("UPDATE $table_members SET  money=money-$money WHERE username='$discuz_user'");
            if ($postuser){
                $query = $db->query("UPDATE $table_members SET  money=money+$money WHERE username='$postuser'");
            }
        }
        showmessage('�I�O���\�I',"viewthread.php?tid=$tid&page=$page&pid=$pid#pid$pid");
    }else{
        include template('postpay_submit');
    }
}

if ($action =="showpayuser"){
    if(!$page) {
        $page = 1;
        }
    $start = ($page - 1) * $cnteacher_paylist_perpage;
    $query = $db->query("SELECT COUNT(*) as paycount,SUM(money) as allmoney FROM $hacktable_postpay where tid='$tid'and pid='$pid' and sellcount='$sellcount'");
    $pay = $db->fetch_array($query);
    if ($pay['paycount']<1){
        showmessage('�S�����ӶK���ʶR�O���I�Ъ�^�C');
    }
    $multipage = multi($pay['paycount'], $cnteacher_paylist_perpage, $page, "postpay.php?action=showpayuser&tid=$tid&pid=$pid&sellcount=$sellcount");
    $onlinelist = array();
    $query = $db->query("SELECT username,money,dateline FROM $hacktable_postpay where pid='$pid' and sellcount='$sellcount' ORDER BY dateline DESC LIMIT $start, $cnteacher_paylist_perpage");
    $karmanum=($page - 1) * $cnteacher_paylist_perpage;
    while($userpay = $db->fetch_array($query)){
        $karmanum++;
        $userpay['id']= $karmanum;
        $userpay['dateline'] = gmdate("$dateformat $timeformat", $userpay['dateline'] + ($timeoffset * 3600));
        $userpay['userlink'] =  rawurlencode($userpay['username']) ;
        $userpaylist[] = $userpay;
    }
    include template('postpay_users');
}

?>