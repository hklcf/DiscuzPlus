<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

function censor($message) {
	return $GLOBALS['_DCACHE']['censor'] ? preg_replace($GLOBALS['_DCACHE']['censor']['find'], $GLOBALS['_DCACHE']['censor']['replace'], $message) : $message;
}

function credithide($creditsrequire, $message) {
	if($GLOBALS['credit'] < $creditsrequire && !$GLOBALS['issupermod']) {
		$GLOBALS['show_attach']=0;
		return "<b><font color=red>**** 隱藏對積分少於 $creditsrequire 分的會員 ****</font></b>";
	} else {
		return "<span class=\"bold\">這標題要積分 $creditsrequire 分以上才可閱讀</span><br>==============================<br><br>".stripslashes($message)."<br><br>==============================";
	}
}

function codedisp($code) {
	global $thisbg, $codecount, $post_codecount, $codehtml;
	$post_codecount++;
	$code = htmlspecialchars(str_replace("\\\"", "\"", preg_replace("/^[\n\r]*(.+?)[\n\r]*$/is", "\\1", $code)));
	$codehtml[$post_codecount] = "<br><br><center><table border=\"0\" width=\"90%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"smalltxt\">&nbsp;&nbsp;代碼:</td><td align=\"right\"><a href=\"###\" class=\"smalltxt\" onclick=\"copycode(findobj('code$codecount'));\">[複製至剪貼簿]</a>&nbsp;&nbsp;</td></tr><tr><td colspan=\"2\"><table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"".BORDERCOLOR."\"><tr><td width=\"100%\" bgcolor=\"".ALTBG2."\" style=\"word-break:break-all\" id=\"code$codecount\">$code</td></tr></table></td></tr></table></center><br>";
	$codecount++;
	return "[\tDISCUZ_CODE_$post_codecount\t]";
}

function parseurl($message) {
	return preg_replace(	array(
					"/(?<=[^\]A-Za-z0-9-=\"'\\/])(https?|ftp|gopher|news|telnet|mms){1}:\/\/([A-Za-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/is",
					"/([\n\s])www\.([a-z0-9\-]+)\.([A-Za-z0-9\/\-_+=.~!%@?#%&;:$\[\]\\()|]+)((?:[^\x7f-\xff,\s]*)?)/is",
					"/(?<=[^\]A-Za-z0-9\/\-_.~?=:.])([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4}))/si"
				), array(
					"[url]\\1://\\2[/url]",
					"\\1[url]www.\\2.\\3\\4[/url]",
					"[email]\\0[/email]"
				), ' '.$message);
}

function postify($message, $smileyoff, $bbcodeoff, $allowsmilies = 1, $allowhtml = 0, $allowbbcode = 1, $allowimgcode = 1) {
	global $credit, $tid, $discuz_user, $codehtml, $post_codecount, $thisbg, $highlight, $table_posts, $db, $searcharray, $replacearray,$sellmessage, $ismoderator,$post_sellcount, $phpcodehtml, $post_phpcodecount;
	$post_sellcount=0;
	$post_codecount = -1;
	$post_phpcodecount = -1;
	$message = preg_replace("/\[sell=(\d+)\]\s*(.+?)\s*\[\/sell\]/ies", "postsell(\\1,'\\2')", $message);

	if(!$bbcodeoff && $allowbbcode) {
		$message = preg_replace("/\s*\[code\](.+?)\[\/code\]\s*/ies", "codedisp('\\1')", $message);
	}

	if(!$allowhtml) {
		$message = dhtmlspecialchars($message);
	}

	if(!$smileyoff && $allowsmilies) {
		if(is_array($GLOBALS['_DCACHE']['smilies'])) {
			foreach($GLOBALS['_DCACHE']['smilies'] as $smiliey) {
				$message = str_replace($smiliey['code'], "<img src=\"".SMDIR."/$smiliey[url]\" align=\"absmiddle\" border=\"0\">",$message);
			}
		}
	}

	if(!$bbcodeoff && $allowbbcode) {

		if(!$searcharray['bbcode'] || !$replacearray['bbcode']) {
			$nests = 2;
			$searcharray['bbcode'] = array(
				"/\s*\[quote\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s*/is",
				"/\[url\]\s*(www.|https?:\/\/|ftp:\/\/|gopher:\/\/|news:\/\/|telnet:\/\/|rtsp:\/\/|mms:\/\/){1}([^\[]+?)\s*\[\/url\]/ie",
				"/\[url=www.([^\[]+?)\](.+?)\[\/url\]/is",
				"/\[url=(https?|ftp|gopher|news|telnet|rtsp|mms){1}:\/\/([^\[]+?)\](.+?)\[\/url\]/is",
				"/\[email\]\s*([A-Za-z0-9\-_.]+)@([A-Za-z0-9\-_]+[.][A-Za-z0-9\-_.]+)\s*\[\/email\]/i",
				"/\[email=([A-Za-z0-9\-_.]+)@([A-Za-z0-9\-_]+[.][A-Za-z0-9\-_.]+)\](.+?)\[\/email\]/is",
				"/\[color=([^\[]+?)\]/i",
				"/\[size=([^\[]+?)\]/i",
				"/\[font=([^\[]+?)\]/i",
				"/\[align=([^\[]+?)\]/i"
			);
			$replacearray['bbcode'] = array(
				"<br><br><center><table border=\"0\" width=\"90%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td>&nbsp;&nbsp;引用:</td></tr><tr><td><table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"".BORDERCOLOR."\"><tr><td width=\"100%\" bgcolor=\"".ALTBG2."\">\\1</td></tr></table></td></tr></table></center><br>",
				"urlcut('\\1\\2')",
				"<a href=\"http://www.\\1\" target=\"_blank\">\\2</a>",
				"<a href=\\1://\\2 target=\"_blank\">\\3</a>",
				"<a href=\"mailto:\\1@\\2\">\\1@\\2</a>",
				"<a href=\"mailto:\\1@\\2\">\\3</a>",
				"<font color=\"\\1\">",
				"<font size=\"\\1\">",
				"<font face=\"\\1\">",
				"<p align=\"\\1\">",
			);

			for($i = (count($searcharray['bbcode']) - 1) * $nests; $i >= 0; $i -= $nests) {
				for($j = $i; $j > $i - $nests; $j--) {
					$searcharray['bbcode'][$j] = $searcharray['bbcode'][(($i + 1) / $nests)];
					$replacearray['bbcode'][$j] = $replacearray['bbcode'][(($i + 1) / $nests)];
				}
			}

		}
		$message = preg_replace($searcharray['bbcode'], $replacearray['bbcode'], $message);

		$message = str_replace('[/color]', '</font>', $message);
		$message = str_replace('[/size]', '</font>', $message);
		$message = str_replace('[/font]', '</font>', $message);
		$message = str_replace('[/align]', '</p>', $message);
		$message = str_replace('[b]', '<b>', $message);
		$message = str_replace('[/b]', '</b>', $message);
		$message = str_replace('[i]', '<i>', $message);
		$message = str_replace('[/i]', '</i>', $message);
		$message = str_replace('[u]', '<u>', $message);
		$message = str_replace('[/u]', '</u>', $message);
		$message = str_replace('[center]', '<center>', $message); 
		$message = str_replace('[/center]', '</center>', $message);
		$message = str_replace('[fly]', '<marquee width="90%" behavior="alternate" scrollamount="3">', $message);
		$message = str_replace('[/fly]', '</marquee>', $message);
		$message = str_replace('[list]', '<ul>', $message);
		$message = str_replace('[list=1]', '<ol type=1>', $message);
		$message = str_replace('[list=a]', '<ol type=a>', $message);
		$message = str_replace('[list=A]', '<ol type=A>', $message);
		$message = str_replace('[*]', '<li>', $message);
		$message = str_replace('[/list]', '</ul></ol>', $message);

		if(preg_match("/\[hide=?\d*\].+?\[\/hide\]/is", $message)) {
			if(stristr($message, '[hide]') ) {
				$query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE tid='$tid' AND author='$discuz_user'");
				if($ismoderator || $db->result($query, 0)) {
					$message = preg_replace("/\[hide\]\s*(.+?)\s*\[\/hide\]/is", "<span class=\"bold\"><font color=red>以下訊息只有回覆者才能看到</font></span><br>==============================<br><br>\\1<br><br>==============================", $message);
				} else {
					$GLOBALS['show_attach']=0;
					$message = preg_replace("/\[hide\](.+?)\[\/hide\]/is", "<b><font color=red>**** 部分訊息必須回覆後才可以查看 ****</font></b>", $message);
				}
			}
			$message = preg_replace("/\[hide=(\d+)\]\s*(.+?)\s*\[\/hide\]/ies", "credithide(\\1,'\\2')", $message);
		}
	}

	if(!$bbcodeoff && $allowimgcode) {
		if(empty($searcharray['imgcode']) || empty($replacearray['imgcode'])) {
			$searcharray['imgcode'] = array(
				"/\[swf\]\s*([^\[]+?)\s*\[\/swf\]/ies",
				"/\[img\]\s*([^\[]+?)\s*\[\/img\]/ies",
				"/\[img=(\d{1,3})[x|\,](\d{1,3})\]\s*([^\[]+?)\s*\[\/img\]/ies",
			);
			$replacearray['imgcode'] = array(
				"bbcodeurl('\\1', '<a href=\"%s\" target=\"_blank\">[在新視窗顯示]</a><br><embed width=\"550\" height=\"375\" src=\"%s\" type=\"application/x-shockwave-flash\"></embed>')",
				"bbcodeurl('\\1', '<img src=\"%s\" border=\"0\" alt=\'點擊查看全圖\' onload=\"if(this.width>screen.width-333) {this.width=screen.width-333;}\" onmouseover=\"if(this.alt) this.style.cursor=\'hand\';\" onclick=\"window.open(\'%s\');\">')",
				"bbcodeurl('\\3', '<img width=\"\\1\" height=\"\\2\" src=\"%s\" alt=\'點擊查看全圖\' border=\"0\" onload=\"if(this.width>screen.width-333) {this.width=screen.width-333;}\" onmouseover=\"if(this.alt) this.style.cursor=\'hand\';\" onclick=\"window.open(\'%s\');\">')",
			);
		}
		$message = preg_replace($searcharray['imgcode'], $replacearray['imgcode'], $message);
	}

for($si = 0; $si <= $post_sellcount; $si++) {
	$message = str_replace("|\tDISCUZ_SELL_$si\t|", $sellmessage[$si], $message);
}
unset ($sellmessage);

	for($i = 0; $i <= $post_codecount; $i++) {
		$message = str_replace("[\tDISCUZ_CODE_$i\t]", $codehtml[$i], $message);
	}

	if($highlight) {
		foreach(explode('+', $highlight) as $ret) {
			$ret = addcslashes($ret, "/()[]|.:!=<>?^\$");
			if($ret) {
				$message = preg_replace("/([ \f\r\t\n]|^)$ret([ \f\r\t\n]|$)/is", "\\1<u><b><font color=\"#FF0000\">$ret</font></b></u>\\2", $message);
			}
		}
	}

	$message = nl2br($message);
	$message = str_replace("\t", '&nbsp; &nbsp; &nbsp; &nbsp; ', $message);
	$message = str_replace('   ', '&nbsp; &nbsp;', $message);
	$message = str_replace('  ', '&nbsp;&nbsp;', $message);

	return $message;
}

function urlcut($url) {
	$length = 65;
	$url = trim($url);
	$str = substr($url,strlen($url)-3,3);
	if ($str == "gif" || $str == "jpg")
	$urllink = "<a href=\"".$url."\" target=\"_blank\"><img src=\"".$url."\" border=\"0\" alt=\"點擊查看全圖\" onload=\"javascript:if(this.width>screen.width-333)  this.width=screen.width-333\"></a>";
	else
	{
	$urllink = "<a href=\"".(substr(strtolower($url), 0, 4) == 'www.' ? "http://$url" : $url)."\" target=\"_blank\">";
	if(strlen($url) > $length) {
	$url = substr($url, 0, intval($length * 0.5)).' ... '.substr($url, - intval($length * 0.3));
	}
	$urllink .= $url.'</a>';
	}
	return $urllink;
}

function bbcodeurl($url, $tags) {
	if(!preg_match("/<.+?>/s",$url)) {
		if(!in_array(strtolower(substr($url, 0, 6)), array('http:/', 'ftp://', 'rtsp:/', 'mms://'))) {
			$url = 'http://'.$url;
		}
		$url = str_replace(array('>', '<', '"','submit=','subscriptions','favorites'),array('', '', "&quot;",'','','' ),  urldecode($url));
		return sprintf($tags, $url, $url);
	} else {
		return '&nbsp;'.$url;
	}
}

function postsell($price, $message) {
	global $thisbg, $post_sellcount,$db,$post, $sellmessage,$tid,$table_posts,$hacktable_postpay,$discuz_user,$issupermod,$ismoderator,$isadmin,$usermoney,$page;
	//$message=stripslashes($message);
$message = str_replace("\\\"", "\"", preg_replace("/^[\n\r]*(.+?)[\n\r]*$/is", "\\1",
$message));
	$post_sellcount++;
	$price=abs(intval($price));
	$post['needmoney']=$post['needmoney']+$price;
	$query = $db->query("SELECT COUNT(*) FROM $hacktable_postpay WHERE pid='$post[pid]' AND sellcount='$post_sellcount' ");
	$payusercount=$db->result($query, 0);
	if ($discuz_user){
		if ($usermoney >= $price){
		$paymessage="你的現金是：$usermoney ,可以<a href=\"postpay.php?action=pay&tid=$tid&pid=$post[pid]&sellcount=$post_sellcount&money=$price&page=$page\">[立即付費]</a>。";
	}else{
		$paymessage="你的現金是：$usermoney ,無法付費。去[<a href='bank.php'>銀行</a>]取款或者賣些積分吧。";
	}
	}else{
		$paymessage= "非本站會員無權購買，請[<a href='logging.php?action=login'>登入</a>],或者[<a href='register.php'>註冊</a>]";
	}

	$sm1="<br><center><table border=\"0\" width=\"98%\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"smalltxt\">  收費信息($post_sellcount): 本條信息收費:<font color=red>$price</font>元 <a href=\"postpay.php?action=showpayuser&tid=$tid&pid=$post[pid]&sellcount=$post_sellcount\" title=\"察看詳細名單\">[<font color=red>目前: $payusercount 人付費</font>]</a></td><td align=\"right\">  <font color=red>詐騙錢財者處以10倍罰款</font></td></tr><tr><td colspan=\"2\"><table border=\"0\" width=\"100%\" cellspacing=\"1\" cellpadding=\"10\" bgcolor=\"".BORDERCOLOR."\"><tr><td width=\"100%\" bgcolor=\"".ALTBG2."\" style=\"word-break:break-all\">您尚未付款，無法觀看相關內容。$paymessage</td></tr></table></td></tr></table></center><br>";

	$sm2=str_pad('',35,'*')."<br><span class=\"smalltxt\">  付費內容($post_sellcount)(費用:<font color=red>$price</font>元, <a href=\"postpay.php?action=showpayuser&tid=$tid&pid=$post[pid]&sellcount=$post_sellcount\" title=\"察看詳細名單\"><font color=red>付費人數: $payusercount </font></a>)</span><br>".str_pad('',35,'*')."<br>";

if ($discuz_user){
	$query = $db->query("SELECT COUNT(*) FROM $table_posts WHERE pid='$post[pid]' AND author='$discuz_user'");
	$isauthor = $db->result($query, 0);
	if($issupermod || $ismoderator || $isadmin || $isauthor ){
		$post['paymoney']=$post['needmoney'];
		$post['payed']=2;
		$sellmessage[$post_sellcount]=$sm2;
	} else {
		$query = $db->query("SELECT COUNT(*) FROM $hacktable_postpay WHERE pid='$post[pid]' AND  sellcount='$post_sellcount' and money='$price' and username='$discuz_user'");
		if($db->result($query, 0)) {
			$post['paymoney']=$post['paymoney']+$price;
			$post['payed']=2;
			$sellmessage[$post_sellcount]=$sm2;
		}else{
			$post['payed']=1;
			$message='';
			$sellmessage[$post_sellcount]=$sm1;
		}
	}
		}else{
			$post['payed']=1;
			$message='';
			$sellmessage[$post_sellcount]=$sm1;
	}
	return "|\tDISCUZ_SELL_$post_sellcount\t| ".$message;
}

?>