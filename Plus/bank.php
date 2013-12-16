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
$navtitle = '�׾»Ȧ�';
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
	if (((!$banksettings['allowchange'] && $action=='change')) || (!$banksettings['allowsell'] && ($action=='sell' || $action=='buy'))) showmessage('�藍�_�A�Ȧ�Ȱ���z�����~�ȡC');
}

$query = $db->query("
                SELECT avatar,credit,bank,money,savemt FROM $table_members WHERE username='$discuz_user'"); 
$userbank=$db->fetch_array($query);
$userbank['avatar'] = $userbank['avatar'] ? image($userbank['avatar']) : "<br>�S���Y��<br>";
$bank=$actionmoney?intval(trim($actionmoney)):0;
$accrnum=$banksettings['accrual']*100; 
$changetaxnum=$banksettings['changetax']*100; 
$selltaxnum=$banksettings['selltax']*100; 
$changemoney= round(($userbank['bank']-$banksettings['minsave'])/($banksettings['changetax']+1));
if ($changemoney<1) $changemoney =0;

//########################## �s�� #################
if ($action=="save") {
	if ($bank<1 || $bank>$userbank['money']){
		showmessage('���B���~�I');
	}else{
		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET
	          money=money-$bank,bank=bank+$bank+$lixi,savemt='$timestamp' WHERE username='$discuz_user'");
		   showmessage('�{���O�s�����A�q�{�b�_�A�Ȧ�N���s�p��A���Q���C','bank.php?action=showroom');
	} 
}elseif ($action=="lixi") {  //===============�Q������==================

		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET
	          bank=bank+$lixi,savemt='$timestamp' WHERE username='$discuz_user'");
		   showmessage('�A���Q���w�g�M�⧹���A�P�ɫO�s��Ȧ�C','bank.php?action=showroom');
}elseif ($action=="load") {   //===============����==================
	if ($bank<1 || $bank>$userbank['bank']) {
		showmessage('���B���~�I���ڥ��ѡI');
	}else{
		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET bank=bank-$bank+$lixi,savemt='$timestamp',money=money+$bank WHERE username='$discuz_user'");
		showmessage('�{�����������A�q�{�b�_�A�Ȧ�N���s�p��A���Q���C','bank.php?action=showroom');
	} 
}elseif($action=="buy") {   //===============�R�J�n��==================
	$bank_tax=round($bank * $banksettings['selltax']);
	$yourcash= $bank * $banksettings['buy']+$bank_tax;
	$yourmoney= $userbank['money']-$yourcash;
	if ($bank<1 || $yourcash>$userbank['money']) {
		showmessage('�ƶq���~�I�ʶR���ѡC');
	}else{
		if (!submitcheck($submit)){
			$bankaction='�R�J�n��';
			include template('bank_submit');
			exit;
		}
		$banklog = "$discuz_user\t$onlineip\t�R�J\t$bank\t$timestamp\n";
		@$fp = fopen($discuz_root.'./forumdata/bankbuylog.php', 'a');
		@flock($fp, 3);
		@fwrite($fp, $banklog);
		@fclose($fp);

		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET credit=credit+$bank, 
	          money=money-$yourcash WHERE username='$discuz_user'");
		   showmessage("�ʶR���\�A�A���n���W�[�F $bank �A�`�@��O�{�� $yourcash �C",'bank.php?action=showroom');
	} 
}elseif ($action=="sell") {   //===============�n���X��==================
	$bank_tax=round($bank * $banksettings[sell]*$banksettings['selltax']);
	$yourcash=round( $bank *$banksettings[sell]-$bank_tax);
	if ($bank<1 or $bank>$userbank['credit']){
		showmessage('�ƶq���~�I�z����X���n���̤֬�1���A�̦h����W�L�A���n���I');
	}
	if (!submitcheck($submit)){
		$yourcredit= $userbank['credit']-$bank;
		$bankaction='�X��n��';
		include template('bank_submit');
		exit;
	}else{
		$banklog = "$discuz_user\t$onlineip\t��X\t$bank\t$timestamp\n";
		@$fp = fopen($discuz_root.'./forumdata/bankbuylog.php', 'a');
		@flock($fp, 3);
		@fwrite($fp, $banklog);
		@fclose($fp);
		$lixi=checklixi($userbank);
		$query = $db->query("UPDATE $table_members SET credit=credit-$bank, money=money+$yourcash WHERE username='$discuz_user'");
		showmessage("�ʶR���\�A�A���{���W�[�F $yourcash �A�`�@��O�n�� $bank �C",'bank.php?action=showroom');
	}
	 
}elseif($action=="change") {   //===============�Ȧ����==================
	$changeuser=trim($changeuser);
	$query = $db->query("
                SELECT username,credit,bank,money,savemt FROM $table_members WHERE username='$changeuser'"); 
	$user2bank=$db->fetch_array($query);
	$bank_tax =round($bank*$banksettings['changetax']);
	$changecost =$bank+$bank_tax;
	$yourbank = $userbank['bank']-$changecost;
	if ($bank<1)  {
		showmessage('���B���~�I�z�����B�ܤ֭n 1 �Ӫ���.');
	}else if($bank>$changemoney) {
		showmessage("���B���~�I���H�Z�A�A���Ȧ�s�ڦܤ֭n�O�d".$banksettings['minsave']."!");
	}else if($userbank['bank']-$changecost<$banksettings['minsave']) {
		showmessage("���B���~�I�ѤU���s�ڤ����H��I�A������O!");
	}else if($discuz_user==$changeuser) {
		showmessage('�z�ܰڡA�ۤv��㵹�ۤv�I');
	}else if(!$user2bank[username]) {
		showmessage('�Τ�W���~�A��e�׾¤��S���o�ӤH�I');
	}
	if (!submitcheck($submit)){
			$bankaction='���״�';
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
		$subject="<B>[�Ȧ�q��]</B> $discuz_user �״ڵ��A�C";
		$money2 = $bank+$lixi+$user2bank[bank];
		$message="�L�q���Ȥ�A�n�G\n�����|�� $discuz_user �q�L�Ȧ�״� $bank �Ӫ������A�C\n";
		$message.="�A�{�b���Ȧ�s�ڬ��G �즳�]$user2bank[bank]�^+ �Q���] $lixi �^+�״ڡ] $bank �^= $money2 \n";
		$message.="�бz��Ȧ�ֹ�H�W�H���I \n";
		$message.="\n\n--==�׾»Ȧ�==--";
		$db->query("INSERT INTO $table_pm (msgto, msgfrom, folder, new, subject, dateline, message)
				VALUES('$changeuser', '$discuz_user', 'inbox', '1', '$subject', '$timestamp', '$message')");
		$db->query("UPDATE $table_members SET newpm='1' WHERE username = '$changeuser'");
		showmessage('��㧹���A�P�ɻȦ�w�g�����ڤH�o�e�F�q���C','bank.php?action=showroom');
	}
}elseif ($action=="showroom") {    //===============��~�j�U==================     
   $bankaction = "��~�j�U";
   $userbankmoney= $userbank[bank];
   $usermoney= $userbank[money];
   $allmoney = $userbank[bank]+$userbank[money];
   $userbanklixi=checklixi($userbank);
   if (!$userbanklixi) $userbanklixi="�w�g�M�⧹��";
   $query = $db->query("SELECT COUNT(bank) AS banks FROM $table_members where bank>0"); 
   $allbankpeople=$db->result($query, 0);
   $query = $db->query("SELECT SUM(bank) AS banks FROM $table_members");
   $allbankmoney=$db->result($query, 0);
	
//��ܶK�l�R�污�p
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
   $bankaction= "�]�I��T"; 
   $userbankmoney= $userbank[bank];
   $usermoney= $userbank[money];
   $allmoney = $userbank[bank]+$userbank[money];
   $userbanklixi=checklixi($discuz_user)?checklixi($discuz_user):"�w�g�M�⧹��";
   $query = $db->query("SELECT COUNT(bank) AS banks FROM $table_members where bank>0"); 
   $allbankpeople=$db->result($query, 0);
   $query = $db->query("SELECT SUM(bank) AS banks FROM $table_members");
   $allbankmoney=$db->result($query, 0);
 //��ܶK�l�R�污�p
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
   }//��ܶK�l�R�污�p����

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
   $userbanklixi=checklixi($discuz_user)?checklixi($discuz_user):"�w�g�M�⧹��";
   $query = $db->query("SELECT COUNT(*) AS count FROM $table_members where bank>'$userbankmoney'"); 
   $bankming=$db->result($query, 0) + 1;
   $query = $db->query("SELECT COUNT(*) AS count FROM $table_members where money>'$usermoney'"); 
   $moneyming=$db->result($query, 0) + 1;
   $query = $db->query("SELECT COUNT(*) AS count FROM $table_members where (bank+money)>'$allmoney'"); 
   $allming=$db->result($query, 0) + 1;
	include template('bank');
}


//########################## functions ##################

function checklixi($userbank) {  //�M��Q��
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
