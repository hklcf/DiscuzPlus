<?php
if(!defined("IN_DISCUZ") || !defined("IN_ADVANCE_CENTER")) {
	exit("Access Denied");
}
if(!submitcheck($savesettings)) {
	require_once($configfile);
	$banksettings['groupname']=$cnteacher_bankgroup;
?>
<br><form method="post" action="admincp.php?action=advcenter&hackname=bank">
<input type="hidden" name="banksettings[version]" value="<?=$banksettings[version]?>">
<?
	if ($banksettings[message]){
		$banksettings[message]=stripcslashes($banksettings[message]);
		$banksettings[message]=str_replace("<br>", "\n", $banksettings[message]);
	}

	showtype('銀行基本信息', "top");
	showsetting('版本(請勿更改)', "readonly",$banksettings[version],"text");

showtype("銀行基本設置");
	showsetting("貨幣單位名稱：", "banksettings[moneyname]",$banksettings[moneyname], "text", "例如：金幣，金豆等。");
	showsetting("銀行存款利率：", "banksettings[accrual]",$banksettings[accrual], "text", "用戶的存款日利率。");
	showsetting("用戶財富分級：", "banksettings[groups]", $banksettings[groups], "radio", "是否使用財富等級功能。");
	showsetting("顯示帖子買賣情況：", "banksettings[showpostpay]", $banksettings[showpostpay], "radio", "是否在銀行中顯示有關帖子買賣的一些信息和統計。<font color='red'><br>注意：如果您沒有安裝本人的帖子買賣插件，請不要嘗試開放此功能，否則你的銀行可能工作不正常。</font>");
	showsetting('','','','');
	showsetting("是否關閉銀行：", "banksettings[close]",$banksettings[close], "radio", "如果關閉銀行則會員不能夠進入，但是不影響管理員使用。");
	showsetting("關閉銀行原因", "banksettings[message]", stripcslashes($banksettings[message]), "textarea", "如果關閉銀行，則顯示給會員此信息。");

showtype("銀行轉帳設置");
	showsetting("是否允許會員轉帳：", "banksettings[allowchange]", $banksettings[allowchange], "radio", "選擇“否”將關閉銀行轉帳功能。");
	showsetting("轉帳存款限制：", "banksettings[minsave]", $banksettings[minsave], "text", "請輸入整數。如果會員存款低于這個數值將無法轉帳。用來限制多次注冊，賺取贈送的錢幣。");
	showsetting("轉帳手續費率：", "banksettings[changetax]", $banksettings[changetax], "text", "每次轉帳向會員收取的交易金額的手續費。不收取手續費請輸入0。");

showtype("積分買賣設置");
	showsetting("是否允許積分買賣：", "banksettings[allowsell]", $banksettings[allowsell], "radio", "選擇“否”將關閉積分買賣功能。");
	showsetting("積分買入價格：", "banksettings[buy]",  $banksettings[buy], "text", "請輸入1-1000的整數,建議10");
	showsetting("積分賣出價格：", "banksettings[sell]", $banksettings[sell], "text", "請輸入1-1000的整數，建議8。千萬不要大于買入的價格");
	showsetting("買賣手續費率：", "banksettings[selltax]", $banksettings[selltax], "text", "每次交易向會員收取交易金額的手續費。不收取手續費請輸入0。");
	showtype("", "bottom");
?>
</table></td></tr></table><br><br>
<center>
<BR><BR>

<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center"><tr><td bgcolor="<?=BORDERCOLOR?>"><table border="0" cellspacing="<?=BORDERWIDTH?>" cellpadding="<?=TABLESPACE?>" width="100%"><tr class="header"><td colspan="4">財富等級設置</td></tr>
<tr class="header" align="center"><td width="45"><input type="checkbox" name="chkall" class="header" onclick="checkall(this.form)">刪</td>
<td>等級名稱</td><td>財富下限</td><td>財富上限</td></tr>
<?
	if ($bankgroup){
		$i=0;
		foreach($bankgroup as $oldgroup) {
		$i++;
		$bankgrouplist .="
		<tr ><td width=\"45\" bgcolor=\"".ALTBG1."\"><input type=\"checkbox\" name=\"delete[$i]\" ></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"oldgroup_name[$i]\" size=\"20\" value=\"{$oldgroup['name']}\"></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"oldgroup_min[$i]\" size=\"20\"  value=\"{$oldgroup['min']}\"></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"oldgroup_max[$i]\" size=\"20\"  value=\"{$oldgroup['max']}\"></td>
		</tr>";
		}
	}
	$bankgrouplist .="
		<tr ><td width=\"45\" bgcolor=\"".ALTBG1."\">添加</td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"newgroup_name\" size=\"20\"></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"newgroup_min\" size=\"20\" ></td>
			<td bgcolor=\"".ALTBG2."\"><input type=\"text\" name=\"newgroup_max\" size=\"20\" ></td>
		</tr>";
	echo $bankgrouplist;
?>

</table></td></tr></table><br><br>
<center><input type="submit" name="savesettings" value="確認修改"></center>
</form>
</td></tr>

<?

} else {

	$banksettings[message]=str_replace("\n", '<br>', $banksettings[message]);
	$banksettings[message]=str_replace("\r", '', $banksettings[message]);
	if(is_array($oldgroup_name)){
		foreach($oldgroup_name as $id => $title) {
				if(!$delete[$id]) {
				    $tempgroup['name']=$title;
					$tempgroup['min']=$oldgroup_min[$id];
					$tempgroup['max']=$oldgroup_max[$id];
					$endgroup[]=$tempgroup;
				}
		}
	}
	if ($newgroup_name){
		$tempgroup['name']=$newgroup_name;
		$tempgroup['min']=$newgroup_min;
		$tempgroup['max']=$newgroup_max;
		$endgroup[]=$tempgroup;
	}

	if($endgroup){
		savesettings("$configfile",'$banksettings='.arrayeval($banksettings).";\n\n".'$bankgroup='.arrayeval($endgroup));
	}else{
		savesettings("$configfile",'$banksettings='.arrayeval($banksettings).";\n\n");
	}
	cpmsg("Discuz! 銀行設置更新成功。");
}

?>