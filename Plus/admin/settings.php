<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

if(!defined("IN_DISCUZ")) {
	exit("Access Denied");
}

cpheader();
if(!$settingsubmit) {

	$query = $db->query("SELECT * FROM $table_settings");
	$settings = $db->fetch_array($query);

	$stylelist = "<select name=\"styleidnew\">\n";
	$query = $db->query("SELECT styleid, name FROM $table_styles");
	while($style = $db->fetch_array($query)) {
		$selected = $style[styleid] == $settings[styleid] ? "selected=\"selected\"" : NULL;
		$stylelist .= "<option value=\"$style[styleid]\" $selected>$style[name]</option>\n";
	}
	$stylelist .= "</select>";

	$settings[moddisplay] == "selectbox" ? $modselectbox = "checked" : $modflat = "checked";
	$settings[timeformat] == "H:i" ? $check24 = "checked" : $check12 = "checked";

	$settings[dateformat] = str_replace("n", "mm", $settings[dateformat]);
	$settings[dateformat] = str_replace("j", "dd", $settings[dateformat]);
	$settings[dateformat] = str_replace("y", "yy", $settings[dateformat]);
	$settings[dateformat] = str_replace("Y", "yyyy", $settings[dateformat]);

	if($settings[avastatus]) {
		$avataron = 'checked';
	} elseif($avastatus == 'list') {
		$avatarlist = 'checked';
	} else {
		$avataroff = 'checked';
	}

	$checkattach = array($settings['attachsave'] => 'checked');

?>
<tr bgcolor="<?=ALTBG2?>">
<td align="center">
<br>
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center">
<tr><td align="center">[<a href="#基本設置">基本設置</a>] - [<a href="#用戶註冊與訪問控制">用戶註冊與訪問控制</a>] - 
[<a href="#界面與顯示方式">界面與顯示方式</a>] - [<a href="#論壇功能">論壇功能</a>] - 
[<a href="#用戶權限">用戶權限</a>] - [<a href="#其他設置">其他設置</a>]</td></tr>
</table></td></tr></table>

<br><form method="post" action="admincp.php?action=settings">
<input type="hidden" name="chcodeorig" value="<?=$settings[chcode]?>">
<?
	showtype("基本設置", "top");
	showsetting('論壇最大線上人數：', 'maxonlinesnew', $settings['maxonlines'], 'text', "請設置合理的數值，範圍 10~65535，建議設置為平均線上人數的 10 倍左右");
	showsetting("論壇名稱：", "bbnamenew", $settings[bbname], "text", "論壇名稱，將顯示在導航條和標題中");
	showsetting("網站名稱：", "sitenamenew", $settings[sitename], "text", "網站名稱，將顯示在頁面底部的聯繫方式處");
	showsetting("網站 URL：", "siteurlnew", $settings[siteurl], "text", "網站 URL，將作為連結顯示在頁面底部");
	showsetting("論壇關閉：", "bbclosednew", $settings[bbclosed], "radio", "暫時將論壇關閉，其他人無法訪問，但不影響管理員訪問");
	showsetting("論壇關閉的原因", "closedreasonnew", $settings[closedreason], "textarea", "論壇關閉時出現的提示訊息");

	showtype("用戶註冊與訪問控制");
	showsetting("允許新用戶註冊：", "regstatusnew", $settings[regstatus], "radio", "選擇“否”將禁止遊客註冊成為會員，但不影響過去已註冊的會員的使用");
	showsetting("保留用戶名：", "censorusernew", $settings[censoruser], "text", "系統保留的用戶名稱，新用戶將無法以這些名字註冊。多個用戶名間請用半形逗號 \",\" 分割");
	showsetting("允許同一 Email 註冊不同用戶：", "doubleenew", $settings[doublee], "radio", "選擇“否”將只允許一個 Email 地址只能註冊一個用戶名");
	showsetting("新用戶註冊需發郵件驗證 Email 地址：", "regverifynew", $settings['regverify'], "radio", "選擇“是”將向用戶註冊 Email 發送一封驗證郵件以確認郵箱的有效性，用戶收到郵件並啟動帳號後才能擁有正常的權限");
	showsetting("隱藏無權訪問的論壇：", "hideprivatenew", $settings[hideprivate], "radio", "如果用戶權限達不到某個論壇的訪問要求，系統將這些論壇隱藏不顯示");
	showsetting("新用戶註冊發送短訊：", "welcommsgnew", $settings[welcommsg], "radio", "新用戶註冊後系統自動發送一條歡迎短訊");
	showsetting("歡迎短訊內容：", "welcommsgtxtnew", $settings[welcommsgtxt], "textarea", "歡迎短訊的詳細內容");
	showsetting('註冊時顯示許可協議：', "bbrulesnew", $settings[bbrules], "radio", '新用戶註冊時顯示許可協議，同意後才能繼續註冊');
	showsetting('許可協議內容：', "bbrulestxtnew", $settings[bbrulestxt], "textarea", '註冊許可協議的詳細內容');

	showtype("界面與顯示方式");
	showsetting("預設論壇風格：", "", "", $stylelist, "論壇預設的界面風格，遊客和使用預設風格的會員將以此風格顯示");
	showsetting("每頁顯示主題數：", "topicperpagenew", $settings[topicperpage], "text", "注意：修改以下三項設置只影響遊客和新註冊的會員，老會員仍按自身的設置顯示");
	showsetting("每頁顯示文章數：", "postperpagenew", $settings[postperpage], "text");
	showsetting("每頁顯示會員數：", "memberperpagenew", $settings[memberperpage], "text");
	showsetting("熱門話題最低文章數：", "hottopicnew", $settings[hottopic], "text", "超過一定文章數的話題將顯示為熱門話題");
	showsetting("版主顯示方式：", "", "", "<input type=\"radio\" name=\"moddisplaynew\" value=\"flat\" $modflat> 平面顯示 &nbsp; <input type=\"radio\" name=\"moddisplaynew\" value=\"selectbox\" $modselectbox> 下拉菜單</td>", "首頁論壇列表中版主顯示方式");
	showsetting("快速發表文章：", "fastpostnew", $settings[fastpost], "radio", "瀏覽論壇和文章頁面底部顯示快速發表文章表單");
	showsetting("版主快捷管理：", 'modshortcutnew', $settings['modshortcut'], 'radio', "在版主和管理員的主題列表頁面顯示 刪關移... 等快捷管理連結");

	showtype("論壇功能");
	showsetting("使用論壇流量統計：", "statstatusnew", $settings[statstatus], "radio", "選擇“是”將打開論壇統計功能，提供詳細的論壇訪問統計訊息，此功能可能會影響效率");
	showsetting("顯示程式運行訊息：", "debugnew", $settings[debug], "radio", "選擇“是”將在頁腳處顯示程式運行時間和資料庫查詢次數");
	showsetting("頁面 Gzip 壓縮：", "gzipcompressnew", $settings[gzipcompress], "radio", "將頁面內容以 gzip 壓縮後傳輸，可以加快傳輸速度，需 PHP 4.0.4 以上才能使用");
	showsetting("記錄並顯示線上用戶：", "whosonlinenew", $settings[whosonlinestatus], "radio", "在首頁和論壇列表頁顯示在線會員列表");
	showsetting("文章中顯示作者狀態：", "vtonlinestatusnew", $settings[vtonlinestatus], "radio", "瀏覽文章時顯示作者在線狀態");
	showsetting("本人發起或回覆的主題顯示加點圖示：", "dotfoldersnew", $settings[dotfolders], "radio", "在瀏覽者發起或恢復的主題中顯示加點圖示，此功能非常影響效率");
	showsetting('附件保存方式：', '', '', "<input type=\"radio\" name=\"attachsavenew\" value=\"0\" $checkattach[0]> 標準(全部存入同一目錄)<br><input type=\"radio\" name=\"attachsavenew\" value=\"1\" $checkattach[1]> 按論壇存入不同目錄<br><input type=\"radio\" name=\"attachsavenew\" value=\"2\" $checkattach[2]> 按文件類型存入不同目錄<br><input type=\"radio\" name=\"attachsavenew\" value=\"3\" $checkattach[3]> 按月份存入不同目錄<br><input type=\"radio\" name=\"attachsavenew\" value=\"4\" $checkattach[4]> 按天存入不同目錄</td>", "本設置只影響新上傳的附件，設置更改之前的附件仍存放在原來位置。如使用非標準的保存方式，請確認 mkdir() 函數可正常使用，否則將出現附件無法保存的問題");
	showsetting("每次上線增加積分：", 'logincreditsnew', $settings['logincredits'], 'text', "會員每次上線增加的積分數，範圍為 0～255 內的整數");
	showsetting("發表文章增加積分：", "postcreditsnew", $settings[postcredits], "text", "每發表一篇文章作者增加積分數，範圍為 0～255 內的整數，建議設置為 0(發表文章不加積分) 或 1(發表文章積分加 1)。如果修改本項設置，全部會員的積分將與發表文章數相對應重新計算");
	showsetting("被收入精華增加積分：", "digestcreditsnew", $settings[digestcredits], "text", "文章被收入精華區作者增加積分數，範圍為 0～255 內的整數");
	showsetting("預防灌水時間(秒)：", "floodctrlnew", $settings[floodctrl], "text", "會員兩次發表文章間隔不得小於此設置，否則認為是灌水而被禁止");
	showsetting("兩次搜尋最小間隔(秒)：", "searchctrlnew", $settings[searchctrl], "text", "為防止惡意訪問，兩次搜尋間隔不得小於此時間設置，0 為不限制");

	showtype("用戶權限");
	showsetting("允許查看會員列表：", "memliststatusnew", $settings[memliststatus], "radio", "允許會員和遊客查看會員列表和相關訊息");
	showsetting("允許向版主反應文章：", "reportpostnew", $settings[reportpost], "radio", "允許會員通過短訊像版主和管理員反應文章");
	showsetting("文章最大字數：", "maxpostsizenew", $settings[maxpostsize], "text", "會員發表文章長度不能超過此字數設置，管理員不受限制");
	showsetting("頭像最大尺寸(像素)：", "maxavatarsizenew", $settings[maxavatarsize], "text", "會員頭像文件的長寬不能超過此尺寸設置，需 PHP 4.0.5 以上，否則請設置為 0");

	showtype("其他設置");
	showsetting("時間格式：", "", "", "<input type=\"radio\" name=\"timeformatnew\" value=\"24\" $check24> 24 小時制 <input type=\"radio\" name=\"timeformatnew\" value=\"12\" $check12> 12 小時制</td>", "注意：修改以下三項設置只影響遊客和新註冊的會員，老會員仍按自身的設置顯示");
	showsetting("日期格式：", "dateformatnew", $settings[dateformat], "text", "請用 yyyy(yy)、mm、dd 表示，如格式 yyyy-mm-dd 為 2004-01-01");
	showsetting("系統時差：", "timeoffsetnew", $settings[timeoffset], "text", "論壇時間與 GMT 標準時間的時差，香港時間請設置為 +8，除非伺服器時間不准，否則無需更改預設設定");
	showsetting("編輯文章附加編輯記錄：", "editedbynew", $settings[editedby], "radio", "60 秒後編輯文章附加“本篇文章由...於...最後編輯”的記錄，但管理員不會被記錄");
	showsetting("文章中顯示圖片/動畫附件：", "attachimgpostnew", $settings[attachimgpost], "radio", "在文章中直接將圖片或動畫附件顯示出來，而不需要點擊附件連結");
	showsetting("發表文章頁面 Discuz! 代碼輔助：", "bbinsertnew", $settings[bbinsert], "radio", "發表文章頁面包含 Discuz! 代碼高級插入工具，可以簡化代碼和文章的編寫");
	showsetting("發表文章時 Smilies 代碼輔助：", "smileyinsertnew", $settings[smileyinsert], "radio", "發表文章頁面包含 Smilies 快捷工具，點擊圖示即可插入 Smilies");
	showsetting("每行顯示 Smilies 個數：", "smcolsnew", $settings[smcols], "text", "發表文章頁面每行顯示 Smilies 的個數");
	showtype("", "bottom");
?>

</table></td></tr></table><br><br>
<center><input type="submit" name="settingsubmit" value="確認修改"></center>
</form>

</td></tr>

<?

} else {

	if(PHP_VERSION < "4.0.4" && $gzipcompressnew) {
		cpmsg("您的 PHP 版本低於 4.0.4，無法使用 gzip 壓縮功能，請返回修改。");
	}

	if(PHP_VERSION < "4.0.5" && $maxavatarsizenew) {
		cpmsg("您的 PHP 版本低於 4.0.5，無法限制頭像大小，請返回修改。");
	}

	if($maxonlinesnew > 65535 || !is_numeric($maxonlinesnew)) {
		cpmsg("您設置的最大線上人數超過 65535，請返回修改。");
	}

	$timeformatnew = $timeformatnew == '24' ? 'H:i' : 'h:i A';

	$bbnamenew = dhtmlspecialchars($bbnamenew);
	$welcommsgtxtnew = dhtmlspecialchars($welcommsgtxtnew);

	$dateformatnew = str_replace("mm", "n", $dateformatnew);
	$dateformatnew = str_replace("dd", "j", $dateformatnew);
	$dateformatnew = str_replace("yyyy", "Y", $dateformatnew);
	$dateformatnew = str_replace("yy", "y", $dateformatnew);

	$query = $db->query("SELECT postcredits FROM $table_settings");
	$postcredits = $db->result($query, 0);
	if($postcredits != $postcreditsnew) {
		$db->query("UPDATE $table_members SET credit=credit+(postnum*($postcreditsnew-$postcredits))");
	}

	$db->query("UPDATE $table_settings SET bbname='$bbnamenew', regstatus='$regstatusnew', censoruser='$censorusernew',
		doublee='$doubleenew', regverify='$regverifynew', bbrules='$bbrulesnew', bbrulestxt='$bbrulestxtnew',
		welcommsg='$welcommsgnew', welcommsgtxt='$welcommsgtxtnew', bbclosed='$bbclosednew', closedreason='$closedreasonnew',
		sitename='$sitenamenew', siteurl='$siteurlnew', styleid='$styleidnew', moddisplay='$moddisplaynew',
		maxonlines='$maxonlinesnew', floodctrl='$floodctrlnew', searchctrl='$searchctrlnew',
		hottopic='$hottopicnew', topicperpage='$topicperpagenew', postperpage='$postperpagenew', memberperpage='$memberperpagenew',
		maxpostsize='$maxpostsizenew', maxavatarsize='$maxavatarsizenew', smcols='$smcolsnew', whosonlinestatus='$whosonlinenew',
		vtonlinestatus='$vtonlinestatusnew', gzipcompress='$gzipcompressnew', logincredits='$logincreditsnew',
		postcredits='$postcreditsnew', digestcredits='$digestcreditsnew', hideprivate='$hideprivatenew', fastpost='$fastpostnew',
		modshortcut='$modshortcutnew', memliststatus='$memliststatusnew', statstatus='$statstatusnew', debug='$debugnew',
		reportpost='$reportpostnew', bbinsert='$bbinsertnew', smileyinsert='$smileyinsertnew', editedby='$editedbynew',
		dotfolders='$dotfoldersnew', attachsave='$attachsavenew', attachimgpost='$attachimgpostnew', timeformat='$timeformatnew',
		dateformat='$dateformatnew', timeoffset='$timeoffsetnew'");

	$db->query("ALTER TABLE $table_sessions MAX_ROWS=$maxonlinesnew");

	updatecache("settings");
	cpmsg("Discuz! 常規選項成功更新。");
}

?>