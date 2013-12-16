<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
require_once './advcenter/bank_config.php';
$discuz_action = 8;
$navtitle = '論壇銀行';
$mastername = "$bankmanager";

if(!$discuz_user) {
	showmessage('not_loggedin');
}

if (!isset($action) or $action=='') {
  $action="showroom";
}
$query = $db->query("SELECT username, avatar, money, bank, credit FROM $table_members WHERE username='$mastername'");
$master=$db->fetch_array($query);
$masterall = $master['money']+$master['bank'];

if(!$isadmin){
	if ($banksettings['close']) showmessage($banksettings['message']);
	if (((!$banksettings['allowchange'] && $action=='change')) || (!$banksettings['allowsell'] && ($action=='sell' || $action=='buy'))) showmessage('對不起，銀行暫停辦理此項業務。');
}

$query = $db->query("
                SELECT avatar,credit,bank,money,savemt FROM $table_members WHERE username='$discuz_user'"); 
$userbank=$db->fetch_array($query);
$userbank['avatar'] = $userbank['avatar'] ? image($userbank['avatar']) : "<br>沒有頭像<br>";
$bank=$actionmoney?intval(trim($actionmoney)):0;
$accrnum=$banksettings['accrual']*100; 
$changetaxnum=$banksettings['changetax']*100; 
$selltaxnum=$banksettings['selltax']*100; 
$changemoney= round(($userbank['bank']-$banksettings['minsave'])/($banksettings['changetax']+1));
if ($changemoney<1) $changemoney =0;

//########################## 存款 #################
if ($action=="save") {
	if ($bank<1 || $bank>$userbank['money']){
		showmessage('金額錯誤！');
	}else{
		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET
	          money=money-$bank,bank=bank+$bank+$lixi,savemt='$timestamp' WHERE username='$discuz_user'");
		   showmessage('現金保存完畢，從現在起，銀行將重新計算你的利息。','bank.php?action=showroom');
	} 
}elseif ($action=="lixi") {  //===============利息結算==================

		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET
	          bank=bank+$lixi,savemt='$timestamp' WHERE username='$discuz_user'");
		   showmessage('你的利息已經清算完畢，同時保存到銀行。','bank.php?action=showroom');
}elseif ($action=="load") {   //===============取款==================
	if ($bank<1 || $bank>$userbank['bank']) {
		showmessage('金額錯誤！取款失敗！');
	}else{
		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET bank=bank-$bank+$lixi,savemt='$timestamp',money=money+$bank WHERE username='$discuz_user'");
		showmessage('現金提取完畢，從現在起，銀行將重新計算你的利息。','bank.php?action=showroom');
	} 
}elseif($action=="buy") {   //===============買入積分==================
	$bank_tax=round($bank * $banksettings['selltax']);
	$yourcash= $bank * $banksettings['buy']+$bank_tax;
	$yourmoney= $userbank['money']-$yourcash;
	if ($bank<1 || $yourcash>$userbank['money']) {
		showmessage('數量錯誤！購買失敗。');
	}else{
		if (!submitcheck($submit)){
			$bankaction='買入積分';
			include template('bank_submit');
			exit;
		}
		$banklog = "$discuz_user\t$onlineip\t買入\t$bank\t$timestamp\n";
		@$fp = fopen($discuz_root.'./forumdata/bankbuylog.php', 'a');
		@flock($fp, 3);
		@fwrite($fp, $banklog);
		@fclose($fp);

		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET credit=credit+$bank, 
	          money=money-$yourcash WHERE username='$discuz_user'");
		   showmessage("購買成功，你的積分增加了 $bank ，總共花費現金 $yourcash 。",'bank.php?action=showroom');
	} 
}elseif ($action=="sell") {   //===============積分出售==================
	$bank_tax=round($bank * $banksettings[sell]*$banksettings['selltax']);
	$yourcash=round( $bank *$banksettings[sell]-$bank_tax);
	if ($bank<1 or $bank>$userbank['credit']){
		showmessage('數量錯誤！您的賣出的積分最少為1分，最多不能超過你的積分！');
	}
	if (!submitcheck($submit)){
		$yourcredit= $userbank['credit']-$bank;
		$bankaction='出售積分';
		include template('bank_submit');
		exit;
	}else{
		$banklog = "$discuz_user\t$onlineip\t賣出\t$bank\t$timestamp\n";
		@$fp = fopen($discuz_root.'./forumdata/bankbuylog.php', 'a');
		@flock($fp, 3);
		@fwrite($fp, $banklog);
		@fclose($fp);
		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET credit=credit-$bank, money=money+$yourcash WHERE username='$discuz_user'");
		showmessage("購買成功，你的現金增加了 $yourcash ，總共花費積分 $bank 。",'bank.php?action=showroom');
	}
	 
}elseif($action=="change") {   //===============銀行轉賬==================
	$changeuser=trim($changeuser);
	$query = $db->query("
                SELECT username,credit,bank,money,savemt FROM $table_members WHERE username='$changeuser'"); 
	$user2bank=$db->fetch_array($query);
	$bank_tax =round($bank*$banksettings['changetax']);
	$changecost =$bank+$bank_tax;
	$yourbank = $userbank['bank']-$changecost;
	if ($bank<1)  {
		showmessage('金額錯誤！您的金額至少要 1 個金幣.');
	}else if($bank>$changemoney) {
		showmessage("金額錯誤！轉賬以后，你的銀行存款至少要保留".$banksettings['minsave']."!");
	}else if($userbank['bank']-$changecost<$banksettings['minsave']) {
		showmessage("金額錯誤！剩下的存款不足以支付你的手續費!");
	}else if($discuz_user==$changeuser) {
		showmessage('干嗎啊，自己轉賬給自己！');
	}else if(!$user2bank[username]) {
		showmessage('用戶名錯誤，當前論壇內沒有這個人！');
	}
	if (!submitcheck($submit)){
			$bankaction='轉賬匯款';
			include template('bank_submit');
			exit;
	}else{
		$banklog = "$discuz_user\t$onlineip\t$bank\t$changeuser\t$timestamp\n";
		@$fp = fopen($discuz_root.'./forumdata/bankchglog.php', 'a');
		@flock($fp, 3);
		@fwrite($fp, $banklog);
		@fclose($fp);
		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET bank=bank-$changecost+$lixi,savemt='$timestamp' WHERE username='$discuz_user'");
		$lixi=checklixi($user2bank);
		$query = $db->query("UPDATE $table_members SET bank=bank+$bank+$lixi,savemt='$timestamp' WHERE username='$changeuser'");
		$subject="<B>[銀行通知]</B> $discuz_user 匯款給你。";
		$money2 = $bank+$lixi+$user2bank[bank];
		$message="尊敬的客戶你好：\n本站會員 $discuz_user 通過銀行匯款 $bank 個金幣給你。\n";
		$message.="你現在的銀行存款為： 原有（$user2bank[bank]）+ 利息（ $lixi ）+匯款（ $bank ）= $money2 \n";
		$message.="請您到銀行核實以上信息！ \n";
		$message.="\n\n--==論壇銀行==--";
		$db->query("INSERT INTO $table_pm (msgto, msgfrom, folder, new, subject, dateline, message)
				VALUES('$changeuser', '$discuz_user', 'inbox', '1', '$subject', '$timestamp', '$message')");
		$db->query("UPDATE $table_members SET newpm='1' WHERE username = '$changeuser'");
		showmessage('轉賬完畢，同時銀行已經給收款人發送了通知。','bank.php?action=showroom');
	}
}elseif ($action=="showroom") {    //===============營業大廳==================     
   $bankaction = "營業大廳";
   $userbankmoney= $userbank[bank];
   $usermoney= $userbank[money];
   $allmoney = $userbank[bank]+$userbank[money];
   $userbanklixi=checklixi($userbank);
   if (!$userbanklixi) $userbanklixi="已經清算完畢";
   $query = $db->query("SELECT COUNT(bank) AS banks FROM $table_members where bank>0"); 
   $allbankpeople=$db->result($query, 0);
   $query = $db->query("SELECT SUM(bank) AS banks FROM $table_members");
   $allbankmoney=$db->result($query, 0);
	
//顯示貼子買賣情況
   if ($banksettings['showpostpay']){
	   $query = $db->query("SELECT count(*) As bcount,SUM(money) AS bmoney FROM $hacktable_postpay where username='$discuz_user'");
	   $buypost=$db->fetch_array($query);
	   $query = $db->query("SELECT count(*) As scount,SUM(money) AS smoney FROM $hacktable_postpay where author='$discuz_user'");
	   $sellpost = $db->fetch_array($query);
	   $buyandsell = $sellpost['smoney'] - $buypost['bmoney'];
	   if ($buyandsell >0 ){
			$buyandsell="<font color=green>".$buyandsell."</font>";
	   }else{
	      $buyandsell="<font color=red>".$buyandsell."</font>";
	   }
	}
	include template('bank');
}elseif ($action=="bankinfo") {
   $bankaction= "財富資訊"; 
   $userbankmoney= $userbank[bank];
   $usermoney= $userbank[money];
   $allmoney = $userbank[bank]+$userbank[money];
   $userbanklixi=checklixi($discuz_user)?checklixi($discuz_user):"已經清算完畢";
   $query = $db->query("SELECT COUNT(bank) AS banks FROM $table_members where bank>0"); 
   $allbankpeople=$db->result($query, 0);
   $query = $db->query("SELECT SUM(bank) AS banks FROM $table_members");
   $allbankmoney=$db->result($query, 0);
 //顯示貼子買賣情況
   if ($banksettings['showpostpay']){
	   $query = $db->query("SELECT count(*) As bcount,SUM(money) AS bmoney FROM $hacktable_postpay where username='$discuz_user'");
	   $buypost=$db->fetch_array($query);
	   $query = $db->query("SELECT count(*) As scount,SUM(money) AS smoney FROM $hacktable_postpay where author='$discuz_user'");
	   $sellpost = $db->fetch_array($query);
	   $buyandsell = $sellpost['smoney'] - $buypost['bmoney'];
	   if ($buyandsell >0 ){
			$buyandsell="<font color=green>".$buyandsell."</font>";
	   }else{
	      $buyandsell="<font color=red>".$buyandsell."</font>";
	   }
   }//顯示貼子買賣情況結束

   $query = $db->query("SELECT username,money FROM $table_members where 1 ORDER BY money DESC Limit 10");
   while($total = $db->fetch_array($query)) {
	$totalmoneylist .="<LI>".$total[username]."&nbsp;&nbsp;&nbsp;".$total['money'];	
   }
   $query = $db->query("SELECT username,bank FROM $table_members where 1 ORDER BY bank DESC Limit 10");
   while($total = $db->fetch_array($query)) {
	$totalbanklist .="<LI>".$total[username]."&nbsp;&nbsp;&nbsp;".$total['bank'];	
   }
   $query = $db->query("SELECT username,(bank+money) as allmoney FROM $table_members where 1 ORDER BY (bank+money) DESC Limit 10");
   while($total = $db->fetch_array($query)) {
	$totalalllist .="<LI>".$total[username]."&nbsp;&nbsp;&nbsp;".$total['allmoney'];	
   }
   $userbankmoney= $userbank[bank];
   $usermoney= $userbank[money];
   $allmoney = $userbank[bank]+$userbank[money];
   $userbanklixi=checklixi($discuz_user)?checklixi($discuz_user):"已經清算完畢";
   $query = $db->query("SELECT COUNT(*) AS count FROM $table_members where bank>'$userbankmoney'"); 
   $bankming=$db->result($query, 0) + 1;
   $query = $db->query("SELECT COUNT(*) AS count FROM $table_members where money>'$usermoney'"); 
   $moneyming=$db->result($query, 0) + 1;
   $query = $db->query("SELECT COUNT(*) AS count FROM $table_members where (bank+money)>'$allmoney'"); 
   $allming=$db->result($query, 0) + 1;
	include template('bank');
}


//########################## functions ##################

function checklixi($userbank) {  //清算利息
   global $banksettings,$timestamp;
	    $userbanklixi=0;
	    $banktime = intval($userbank[savemt]);
	    if ($banktime>0){
		$presenttime=floor(($timestamp-$banktime)/86400);
		if ($presenttime>0){
		   $userbanklixi=floor($userbank[bank]*$presenttime*$banksettings['accrual']);
		}
	    }
   return $userbanklixi;
} 

?>
