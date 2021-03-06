<?php

/*
	Version: 1.1.4(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/11/07
*/

ob_start('ob_gzhandler');
error_reporting(7);
set_magic_quotes_runtime(0);
define('IN_DISCUZ', TRUE);

$action = ($HTTP_POST_VARS['action']) ? $HTTP_POST_VARS['action'] : $HTTP_GET_VARS['action'];
$language = ($_POST['language']) ? $_POST['language'] : $_GET['language'];
$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME'];

if (function_exists('set_time_limit') == 1 && @ini_get('safe_mode') == 0) {
	@set_time_limit(1000);
}

@include './config.php';

$version = '1.1.4';

if($language == 'chinese_big5') {
	header('Content-Type: text/html; charset=big5');
} elseif($language == 'chinese_gb2312') {
	header('Content-Type: text/html; charset=gb2312');
}
?>
<html>
<head>
<title>Discuz! Plus Installation Wizard</title>
<style>
A:visited	{COLOR: #3A4273; TEXT-DECORATION: none}
A:link		{COLOR: #3A4273; TEXT-DECORATION: none}
A:hover		{COLOR: #3A4273; TEXT-DECORATION: underline}
body,table,td	{COLOR: #3A4273; FONT-FAMILY: Tahoma, Verdana, Arial; FONT-SIZE: 12px; LINE-HEIGHT: 20px; scrollbar-base-color: #E3E3EA; scrollbar-arrow-color: #5C5C8D}
input		{COLOR: #085878; FONT-FAMILY: Tahoma, Verdana, Arial; FONT-SIZE: 12px; background-color: #3A4273; color: #FFFFFF; scrollbar-base-color: #E3E3EA; scrollbar-arrow-color: #5C5C8D}
.install	{FONT-FAMILY: Arial, Verdana; FONT-SIZE: 20px; FONT-WEIGHT: bold; COLOR: #000000}
</style>
</head>
<?
if (!$language) {
?>
<body bgcolor="#FFFFFF">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" align="center">
<tr><td valign="middle" align="center">

<table cellpadding="0" cellspacing="0" border="0" align="center">
  <tr align="center" valign="middle">
    <td bgcolor="#000000">
    <table cellpadding="10" cellspacing="1" border="0" width="500" height="100%" align="center">
    <tr>
      <td valign="middle" align="center" bgcolor="#EBEBEB">
        <br><b>Discuz! Plus Installation Wizard</b><br><br>Please choose your prefered language<br><br><center><a href="?language=chinese_gb2312">[Simplfied Chinese]</a> &nbsp; <a href="?language=chinese_big5">[Traditional Chinese]</a><br><br>
      </td>
    </tr>
    </table>
    </td>
  </tr>
</table>

</td></td></table>
</body>
</html>
<?
} elseif($language == 'chinese_big5'){

function loginit($log) {
	echo '初始化記錄 '.$log;
	$fp = @fopen('./forumdata/illegallog.php');
	@fwrite($fp, "<?PHP exit(\"Access Denied\"); ?>\n");
	@fclose($fp);
	result();
}

function runquery($sql) {
	global $tablepre, $db;

	$sql = str_replace("\r", "\n", str_replace(' cdb_', ' '.$tablepre, $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' ? NULL : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				echo '建立資料表 '.$name.' ... <font color="#0000EE">成功</font><br>';
			}
			$db->query($query);
		}
	}
}

function result($result = 1, $output = 1) {
	if($result) {
		$text = '... <font color="#0000EE">成功</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">失敗</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	}
}

function dir_writeable($dir) {
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.test", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.test");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function dir_clear($dir) {
	echo '清空目錄 '.$dir;
	$directory = dir($dir);
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename)) {
			@unlink($filename);
		}
	}
	$directory->close();
	result();
}

?>
<body bgcolor="#3A4273" text="#000000">
<table width="95%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
  <tr>
    <td>
      <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td class="install" height="30" valign="bottom"><font color="#FF0000">&gt;&gt;</font> 
            Discuz! Plus Installation Wizard</td>
        </tr>
        <tr>
          <td> 
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
	 <td align="center">
	<b>歡迎來到 Discuz! Plus Board 安裝嚮導，安裝前請仔細閱讀 license 檔的每個細節，在您確定可以完全滿足 Discuz! Plus 的授權協議之後才能開始安裝。readme 檔提供了有關軟體安裝的說明，請您同樣仔細閱讀，以保證安裝進程的順利進行。</b>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
<?

if(!$action) {

	$discuz_licence = <<<EOT
版權所有 (c) 2004，HKLCF.COM
保留所有權利。

    感謝你選擇 Discuz! Plus 論壇產品。希望我們的努力能為你提供一個高效快速和強大的 web 論壇解決方案。

    Discuz! Plus 為 HKLCF.COM 獨立開發，全部核心技術歸屬 HKLCF.COM 所有。

    Discuz! Plus 的核心包括了 Discuz! 3.x , 2.x , PHPWind , phpbb , 3Q , IPB 及 UNet.Boards 等等．．．．．．
EOT;

	$discuz_licence = str_replace('  ', '&nbsp; ', nl2br($discuz_licence));

?>
        <tr> 
          <td><b>當前狀態：</b><font color="#0000EE">Discuz! Plus 會員許可協議</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 請您務必仔細閱讀下面的許可協議</font></b></td>
        </tr>
        <tr>
          <td><br>
            <table width="90%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr>
                <td bgcolor="#E3E3EA">
                  <table width="99%" cellspacing="1" border="0" align="center">
                    <tr>
                      <td>
                        <?=$discuz_licence?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="我完全同意" style="height: 25">&nbsp;
              <input type="button" name="exit" value="我不能同意" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?

} elseif($action == 'config') {

	$exist_error = FALSE;
	$write_error = FALSE;
	if(file_exists('./config.php')) {
		$fileexists = result(1, 0);
	} else {
		$fileexists = result(0, 0);
		$exist_error = TRUE;
	}
	if(is_writeable('./config.php')) {
		$filewriteable = result(1, 0);
	} else {
		$filewriteable = result(0, 0);
		$write_error = TRUE;
	}
	if($exist_error) {
		$config_info = '您的 config.php 不存在， 無法繼續安裝， 請用 FTP 將該文件上傳後再試。';
	} elseif(!$write_error) {
		$config_info = '請在下面填寫您的資料庫帳號訊息， 通常情況下請不要修改紅色選項內容。';
	} elseif($write_error) {
		$config_info = '安裝嚮導無法寫入配置文件， 請核對現有訊息， 如需修改， 請通過 FTP 將改好的 config.php 上傳。';
	}

?>
        <tr> 
          <td><b>當前狀態：</b><font color="#0000EE">配置 config.php</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 檢查配置文件狀態</font></b></td>
        </tr>
        <tr>
          <td>config.php 存在檢查 <?=$fileexists?></td>
        </tr>
        <tr>
          <td>config.php 可寫檢查 <?=$filewriteable?></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 瀏覽/編輯當前配置</font></b></td>
        </tr>
        <tr>
          <td align="center"><br><?=$config_info?></td>
        </tr>
<?

	if(!$exist_error) {

		if(!$write_error) {

			$dbhost = 'localhost';
			$dbuser = 'dbuser';
			$dbpw = 'dbpw';
			$dbname = 'plus';
			$adminemail = 'admin@your.com';
			$tablepre = 'cdb_';

			@include './config.php';

?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>">
              <table width="500" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr bgcolor="#3A4273">
                  <td align="center" width="20%" style="color: #FFFFFF">設置選項</td>
                  <td align="center" width="35%" style="color: #FFFFFF">當前值</td>
                  <td align="center" width="45%" style="color: #FFFFFF">註釋</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;資料庫伺服器:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?=$dbhost?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;資料庫伺服器地址, 一般為 localhost</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;資料庫會員名:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?=$dbuser?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;資料庫賬號會員名</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;資料庫密碼:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?=$dbpw?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;資料庫賬號密碼</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;資料庫名:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbname" value="<?=$dbname?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;資料庫名稱</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;系統 Email:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="adminemail" value="<?=$adminemail?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;用於發送程式錯誤報告</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;表名前綴:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="tablepre" value="<?=$tablepre?>" size="30" onClick="javascript: alert('安裝嚮導提示:\n\n除非您需要在同一資料庫安裝多個 Discuz! \n論壇,否則,強烈建議您不要修改表名前綴.');"></td>
                  <td bgcolor="#E3E3EA">&nbsp;同一資料庫安裝多論壇時可改變預設</td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="environment">
              <input type="hidden" name="saveconfig" value="1">
              <input type="submit" name="submit" value="保存配置訊息" style="height: 25">
              <input type="button" name="exit" value="退出安裝嚮導" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?

		} else {

			@include './config.php';

?>
        <tr>
          <td>
            <br>
            <table width="60%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center" style="color: #FFFFFF">變量</td>
                <td align="center" style="color: #FFFFFF">當前值</td>
                <td align="center" style="color: #FFFFFF">註釋</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbhost</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbhost?></td>
                <td bgcolor="#E3E3EA" align="center">資料庫伺服器地址, 一般為 localhost</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbuser</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbuser?></td>
                <td bgcolor="#E3E3EA" align="center">資料庫賬號會員名</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbpw</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpw?></td>
                <td bgcolor="#E3E3EA" align="center">資料庫賬號密碼</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbname</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbname?></td>
                <td bgcolor="#E3E3EA" align="center">資料庫名稱</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$adminemail</td>
                <td bgcolor="#EEEEF6" align="center"><?=$adminemail?></td>
                <td bgcolor="#E3E3EA" align="center">系統 Email</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$tablepre</td>
                <td bgcolor="#EEEEF6" align="center"><?=$tablepre?></td>
                <td bgcolor="#E3E3EA" align="center">資料表名前綴</td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td align="center">
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>">
              <input type="hidden" name="action" value="environment">
              <input type="submit" name="submit" value="上述配置正確" style="height: 25">
              <input type="button" name="exit" value="重新整理修改結果" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_big5'?>?action=config');">
            </form>
          </td>
        </tr>
<?

		}

	} else {

?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="重新檢查設置" style="height: 25">
              <input type="button" name="exit" value="退出" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?

	}

} elseif($action == 'environment') {

	if($HTTP_POST_VARS['saveconfig'] && is_writeable('./config.php')) {

		$dbhost = $HTTP_POST_VARS['dbhost'];
		$dbuser = $HTTP_POST_VARS['dbuser'];
		$dbpw = $HTTP_POST_VARS['dbpw'];
		$dbname = $HTTP_POST_VARS['dbname'];
		$adminemail = $HTTP_POST_VARS['adminemail'];
		$tablepre = $HTTP_POST_VARS['tablepre'];

		$fp = fopen('./config.php', 'r');
		$configfile = fread($fp, filesize('./config.php'));
		fclose($fp);

		$configfile = preg_replace("/[$]dbhost\s*\=\s*[\"'].*?[\"']/is", "\$dbhost = '$dbhost'", $configfile);
		$configfile = preg_replace("/[$]dbuser\s*\=\s*[\"'].*?[\"']/is", "\$dbuser = '$dbuser'", $configfile);
		$configfile = preg_replace("/[$]dbpw\s*\=\s*[\"'].*?[\"']/is", "\$dbpw = '$dbpw'", $configfile);
		$configfile = preg_replace("/[$]dbname\s*\=\s*[\"'].*?[\"']/is", "\$dbname = '$dbname'", $configfile);
		$configfile = preg_replace("/[$]adminemail\s*\=\s*[\"'].*?[\"']/is", "\$adminemail = '$adminemail'", $configfile);
		$configfile = preg_replace("/[$]tablepre\s*\=\s*[\"'].*?[\"']/is", "\$tablepre = '$tablepre'", $configfile);

		$fp = fopen('./config.php', 'w');
		fwrite($fp, trim($configfile));
		fclose($fp);

	}

	include './config.php';
	include './include/db_'.$database.'.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

	$msg = '';
	$quit = FALSE;

	$curr_os = PHP_OS;

	$curr_php_version = PHP_VERSION;
	if($curr_php_version < '4.0.6') {
		$msg .= "<font color=\"#FF0000\">您的 PHP 版本小於 4.0.6， 無法使用 Discuz! Plus。</font>\t";
		$quit = TRUE;
	} elseif($curr_php_version < '4.0.6') {
		$msg .= "<font color=\"#FF0000\">您的 PHP 版本小於 4.0.6， 無法使用頭像尺寸檢查和 gzip 壓縮功能。</font>\t";
	}

	if(@ini_get(file_uploads)) {
		$max_size = @ini_get(upload_max_filesize);
		$curr_upload_status = "允許/最大尺寸 $max_size";
		$msg .= "您可以上傳尺寸在 $max_size 以下的附件文件.\t";
	} else {
		$curr_upload_status = '不允許上傳附件';
		$msg .= "<font color=\"#FF0000\">由於伺服器遮蔽， 您無法使用附件功能。</font>\t";
	}

	$curr_gobals_status = @ini_get(register_globals);
	if($curr_gobals_status > '0') {
		$curr_gobals_writeable = 'ON';
	} elseif($curr_gobals_status < '1') {
		$curr_gobals_writeable = 'OFF';
	}

	$curr_quotes_status = get_magic_quotes_gpc();
	if($curr_quotes_status > '0') {
		$curr_quotes_writeable = 'ON';
	} elseif($curr_quotes_status < '1') {
		$curr_quotes_writeable = 'OFF';
		$msg .="<font color=\"#FF0000\">Please set magic_quotes_gpc = On in your php.ini.</font>\t";
		$quit = TRUE;
	}

	$query = $db->query("SELECT VERSION()");
	$curr_mysql_version = $db->result($query, 0);
	if($curr_mysql_version < '3.23') {
		$msg .= "<font color=\"#FF0000\">您的 MySQL 版本低於 3.23， Discuz! Plus 的一些功能可能無法正常使用。</font>\t";
	}

	$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

	if(dir_writeable('./templates')) {
		$curr_tpl_writeable = '可寫';
	} else {
		$curr_tpl_writeable = '不可寫';
		$msg .= "<font color=\"#FF0000\">模板 ./templates 目錄屬性非 777 或無法寫入， 無法使用線上編輯模板和風格導入。</font>\t";
	}

	if(dir_writeable($attachdir)) {
		$curr_attach_writeable = '可寫';
	} else {
		$curr_attach_writeable = '不可寫';
		$msg .= "<font color=\"#FF0000\">附件 $attachdir 目錄屬性非 777 或無法寫入， 無法使用附件功能。</font>\t";
	}

	if(dir_writeable('./customavatars/')) {
		$curr_head_writeable = '可寫';
	} else {
		$curr_head_writeable = '不可寫';
		$msg .= "<font color=\"#FF0000\">上傳頭像 ./customavatars 目錄屬性非 777 或無法寫入， 無法使用上傳頭像功能。</font>\t";
	}

	if(dir_writeable('./forumdata/')) {
		$curr_data_writeable = '可寫';
	} else {
		$curr_data_writeable = '不可寫';
		$msg .= "<font color=\"#FF0000\">資料 ./forumdata 目錄屬性非 777 或無法寫入， 無法使用備份到伺服器/論壇運行記錄等功能。</font>\t";
	}

	if(dir_writeable('./forumdata/templates/')) {
		$curr_template_writeable = '可寫';
	} else {
		$curr_template_writeable = '不可寫';
		$msg .= "<font color=\"#FF0000\">模板 ./forumdata/templates 目錄屬性非 777 或無法寫入， 無法安裝 Discuz! Plus。</font>\t";
		$quit = TRUE;
	}

	if(dir_writeable('./forumdata/cache/')) {
		$curr_cache_writeable = '可寫';
	} else {
		$curr_cache_writeable = '不可寫';
		$msg .= "<font color=\"#FF0000\">緩存 ./forumdata/cache 目錄屬性非 777 或無法寫入， 無法安裝 Discuz! Plus。</font>\t";
		$quit = TRUE;
	}

	$db->select_db($dbname);
	if($db->error()) {
		$db->query("CREATE DATABASE $dbname");
		if($db->error()) {
			$msg .= "<font color=\"#FF0000\">指定的資料庫 $dbname 不存在， 系統也無法自動建立， 無法安裝 Discuz! Plus。</font>\t";
			$quit = TRUE;
		} else {
			$db->select_db($dbname);
			$msg .= "指定的資料庫 $dbname 不存在， 但系統已成功建立， 可以繼續安裝。\t";
		}
	}

	$query - $db->query("SELECT COUNT(*) FROM $tablepre"."settings", 1);
	if(!$db->error()) {
		$msg .= "<font color=\"#FF0000\">資料庫中已經安裝過 Discuz! Plus， 繼續安裝會清空原有資料。</font>\t";
		$alert = " onSubmit=\"return confirm('繼續安裝會清空全部原有資料，您確定要繼續嗎？');\"";
	} else {
		$alert = '';
	}

	if($quit) {
		$msg .= "<font color=\"#FF0000\">由於您目錄屬性或伺服器配置原因, 無法繼續安裝 Discuz! Plus， 請仔細閱讀安裝說明。</font>";
	} else {
		$msg .= "您的伺服器可以安裝和使用 Discuz! Plus， 請進入下一步安裝。";
	}

?>
        <tr>
          <td><b>當前狀態：</b><font color="#0000EE">檢查當前伺服器環境</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> Discuz! Plus 所需環境和當前伺服器配置對比</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="80%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center"></td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus 所需配置</td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus 最佳配置</td>
                <td align="center" style="color: #FFFFFF">當前伺服器</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">操作系統</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">UNIX/Linux/FreeBSD</td>
                <td bgcolor="#E3E3EA" align="center"><?=$curr_os?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP 版本</td>
                <td bgcolor="#EEEEF6" align="center">4.0.6+</td>
                <td bgcolor="#E3E3EA" align="center">5.0.1+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_php_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP 指令: register_globals</td>
                <td bgcolor="#EEEEF6" align="center">OFF</td>
                <td bgcolor="#E3E3EA" align="center">OFF</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_gobals_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP 指令: magic_quotes_gpc</td>
                <td bgcolor="#EEEEF6" align="center">ON</td>
                <td bgcolor="#E3E3EA" align="center">ON</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_quotes_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">附件上傳</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">允許</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_upload_status?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">MySQL 版本</td>
                <td bgcolor="#EEEEF6" align="center">3.23+</td>
                <td bgcolor="#E3E3EA" align="center">4.0.20+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_mysql_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">磁碟空間</td>
                <td bgcolor="#EEEEF6" align="center">2M+</td>
                <td bgcolor="#E3E3EA" align="center">100M+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_disk_space?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./templates 目錄寫入</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">可寫</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_tpl_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$attachdir?> 目錄寫入</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">可寫</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_attach_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./customavatars 目錄寫入</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">可寫</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_head_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata 目錄寫入</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">可寫</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_data_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/templates 目錄寫入</td>
                <td bgcolor="#EEEEF6" align="center">可寫</td>
                <td bgcolor="#E3E3EA" align="center">可寫</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_template_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/cache 目錄寫入</td>
                <td bgcolor="#EEEEF6" align="center">可寫</td>
                <td bgcolor="#E3E3EA" align="center">可寫</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_cache_writeable?></td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 請確認已完成如下步驟</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
              <li>將壓縮包中 Discuz! Plus 目錄下全部檔案和目錄上傳到伺服器.</li>
              <li>修改伺服器上的 config.php 檔案以適合您的配置, 有關資料庫賬號訊息請咨詢您的空間服務提供商.</li>
              <li>如果您使用非 WINNT 系統請修改以下屬性:<br>&nbsp; &nbsp; <b>./templates</b> 目錄 777;&nbsp; &nbsp; <b><?=$attachdir?></b> 目錄 777;&nbsp; &nbsp; <b>./customavatars</b> 目錄 777;&nbsp; &nbsp; <b>./forumdata</b> 目錄 777; <br><b>&nbsp; &nbsp; ./forumdata/cache</b> 目錄 777;&nbsp; &nbsp; <b>./forumdata/templates</b> 目錄 777;<br></li>
              <li>確認 URL 中 <?=$attachurl?> 可以訪問伺服器目錄 <?=$attachdir?> 內容.</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 安裝嚮導提示</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
<?

	$msgs = explode("\t", $msg);
	unset($msg);
	for($i = 0; $i < count($msgs); $i++) {
		echo "              <li>".$msgs[$i]."</li>\n";
	}
	echo"            </ol>\n";

	if($quit) {

?>
            <center>
            <input type="button" name="refresh" value="重新檢查設置" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_big5'?>?action=environment');">&nbsp;
            <input type="button" name="exit" value="退出" style="height: 25" onclick="javascript: window.close();">
            </center>
<?

	} else {

?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 設置管理員帳號</font></b></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>"<?=$alert?>>
              <table width="300" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;管理員賬號:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="username" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;管理員 Email:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="email" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;管理員密碼:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password1" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;重複密碼:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password2" size="30"></td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="install">
              <input type="submit" name="submit" value="開始安裝 Discuz! Plus" style="height: 25" >&nbsp;
              <input type="button" name="exit" value="退出安裝嚮導" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>

<?

	}

} elseif($action == 'install') {

	$username = $HTTP_POST_VARS['username'];
	$email = $HTTP_POST_VARS['email'];
	$password1 = $HTTP_POST_VARS['password1'];
	$password2 = $HTTP_POST_VARS['password2'];

?>
        <tr>
          <td><b>當前狀態：</b><font color="#0000EE">檢查管理員賬號訊息並開始安裝 Discuz! Plus。</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 檢查管理員帳號</font></b></td>
        </tr>
        <tr>
          <td>檢查訊息合法性
<?

	$msg = '';
	if($username && $email && $password1 && $password2) {
		if($password1 != $password2) {
			$msg = "兩次輸入密碼不一致。";
		} elseif(strlen($username) > 15) {
			$msg = "用戶名超過 15 個字元限制。";
		} elseif(preg_match("/^$|^c:\\con\\con$|　|[,\"\s\t\<\>]|^遊客|^Guest/is", $username)) {
			$msg = "用戶名空或包含非法字元。";
		} elseif(!strstr($email, '@') || $email != stripslashes($email) || $email != htmlspecialchars($email)) {
			$msg = "Email 地址無效";
		}
	} else {
		$msg = '你的訊息沒有填寫完整。';
	}

	if($msg) { 

?>
            ... <font color="#FF0000">失敗。 原因：<?=$msg?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="返回上一頁修改" onclick="javascript: history.go(-1);">
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td align="center">
            <b style="font-size: 11px">Powered by <a href="http://hklcf.com/" target="_blank">Discuz! Plus <?=$version?></a> , &nbsp; Copyright &copy; <a href="http://hklcf.com" target=\"_blank\">HKLCF Studio</a>, 2004</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
</body>
</html>

<?

		exit;
	} else {
		echo result(1, 0)."</td>\n";
		echo"        </tr>\n";
	}

?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 選擇資料庫</font></b></td>
        </tr>
<?
	include './config.php';
	include './include/db_'.$database.'.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$db->select_db($dbname);

echo"        <tr>\n";
echo"          <td>選擇資料庫 $dbname ".result(1, 0)."</td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> 建立資料表</font></b></td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";

	$sql = <<<EOT
DROP TABLE IF EXISTS cdb_announcements;
CREATE TABLE cdb_announcements (
  id smallint(6) unsigned NOT NULL auto_increment,
  author varchar(15) NOT NULL default '',
  subject varchar(250) NOT NULL default '',
  starttime int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  message text NOT NULL,
  PRIMARY KEY  (id)
);

DROP TABLE IF EXISTS cdb_attachments;
CREATE TABLE cdb_attachments (
  aid mediumint(8) unsigned NOT NULL auto_increment,
  tid mediumint(8) unsigned NOT NULL default '0',
  pid int(10) unsigned NOT NULL default '0',
  creditsrequire smallint(6) unsigned NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  filetype varchar(50) NOT NULL default '',
  filesize int(12) unsigned NOT NULL default '0',
  attachment varchar(255) NOT NULL default '',
  downloads smallint(6) NOT NULL default '0',
  PRIMARY KEY  (aid)
);

DROP TABLE IF EXISTS cdb_banned;
CREATE TABLE cdb_banned (
  id smallint(6) unsigned NOT NULL auto_increment,
  ip1 smallint(3) NOT NULL default '0',
  ip2 smallint(3) NOT NULL default '0',
  ip3 smallint(3) NOT NULL default '0',
  ip4 smallint(3) NOT NULL default '0',
  admin varchar(15) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ip1 (ip1),
  KEY ip2 (ip2),
  KEY ip3 (ip3),
  KEY ip4 (ip1)
);

DROP TABLE IF EXISTS cdb_buddys;
CREATE TABLE cdb_buddys (
  username varchar(15) NOT NULL default '',
  buddyname varchar(15) NOT NULL default ''
);

DROP TABLE IF EXISTS cdb_chname;
CREATE TABLE cdb_chname (
  id int(10) unsigned NOT NULL auto_increment,
  newname varchar(15) NOT NULL default '',
  oldname varchar(15) NOT NULL default '',
  reason text NOT NULL,
  dateline int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS cdb_favorites;
CREATE TABLE cdb_favorites (
  tid mediumint(8) unsigned NOT NULL default '0',
  username varchar(15) NOT NULL default '',
  KEY tid (tid)
);

DROP TABLE IF EXISTS cdb_forumlinks;
CREATE TABLE cdb_forumlinks (
  id smallint(6) unsigned NOT NULL auto_increment,
  displayorder tinyint(3) NOT NULL default '0',
  name varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  note varchar(200) NOT NULL default '',
  logo varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
);

INSERT INTO cdb_forumlinks VALUES (1, 0, 'Discuz! Plus', 'http://discuz.hklcf.com/', 'Discuz! Plus official website, provide latest product news, downloading and technical supports, etc.', 'images/logo.gif');

DROP TABLE IF EXISTS cdb_forums;
CREATE TABLE cdb_forums (
  fid smallint(6) unsigned NOT NULL auto_increment,
  fup smallint(6) unsigned NOT NULL default '0',
  type enum('group','forum','sub') NOT NULL default 'forum',
  icon varchar(100) NOT NULL default '',
  name varchar(50) NOT NULL default '',
  description text NOT NULL,
  status tinyint(1) NOT NULL default '0',
  displayorder tinyint(3) NOT NULL default '0',
  moderator tinytext NOT NULL,
  styleid smallint(6) unsigned NOT NULL default '0',
  threads smallint(6) unsigned NOT NULL default '0',
  posts mediumint(8) unsigned NOT NULL default '0',
  lastpost varchar(130) NOT NULL default '',
  allowsmilies tinyint(1) NOT NULL default '0',
  allowhtml tinyint(1) NOT NULL default '0',
  allowbbcode tinyint(1) NOT NULL default '0',
  allowimgcode tinyint(1) NOT NULL default '0',
  password varchar(12) NOT NULL default '',
  postcredits tinyint(1) NOT NULL default '-1',
  viewperm tinytext NOT NULL,
  postperm tinytext NOT NULL,
  getattachperm tinytext NOT NULL,
  postattachperm tinytext NOT NULL,
  PRIMARY KEY  (fid),
  KEY status (status)
);

INSERT INTO cdb_forums VALUES (1, 0, 'forum', '', 'Main Forum', '', 1, 0, '', 0, 0, 0, '', 1, 0, 1, 1, '', 0, '', '', '', '');

DROP TABLE IF EXISTS cdb_karmalog;
CREATE TABLE cdb_karmalog (
  username varchar(15) NOT NULL default '',
  pid int(10) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  score tinyint(3) unsigned NOT NULL default '0'
);

DROP TABLE IF EXISTS cdb_members;
CREATE TABLE cdb_members (
  uid mediumint(8) unsigned NOT NULL auto_increment,
  username varchar(15) NOT NULL default '',
  password varchar(40) NOT NULL default '',
  gender tinyint(1) NOT NULL default '0',
  status enum('Member','Admin','SuperMod','Moderator','Banned','PostBanned','Inactive','vip') NOT NULL default 'Member',
  regip varchar(15) NOT NULL default '',
  regdate int(10) unsigned NOT NULL default '0',
  lastvisit int(10) unsigned NOT NULL default '0',
  postnum smallint(6) unsigned NOT NULL default '0',
  credit int(10) UNSIGNED NOT NULL default '0',
  charset varchar(10) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  site varchar(75) NOT NULL default '',
  icq varchar(12) NOT NULL default '',
  oicq varchar(12) NOT NULL default '',
  yahoo varchar(40) NOT NULL default '',
  msn varchar(40) NOT NULL default '',
  location varchar(30) NOT NULL default '',
  bday date NOT NULL default '0000-00-00',
  bio text NOT NULL,
  avatar varchar(100) NOT NULL default '',
  signature text NOT NULL,
  customstatus varchar(20) NOT NULL default '',
  tpp tinyint(3) unsigned NOT NULL default '0',
  ppp tinyint(3) unsigned NOT NULL default '0',
  styleid smallint(6) unsigned NOT NULL default '0',
  dateformat varchar(10) NOT NULL default '',
  timeformat varchar(5) NOT NULL default '',
  showemail tinyint(1) NOT NULL default '0',
  newsletter tinyint(1) NOT NULL default '0',
  timeoffset char(3) NOT NULL default '',
  ignorepm text NOT NULL,
  newpm tinyint(1) NOT NULL default '0',
  pwdrecover varchar(30) NOT NULL default '',
  pwdrcvtime int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (uid),
  KEY username (username)
);

DROP TABLE IF EXISTS cdb_memo;
CREATE TABLE cdb_memo (
  id int(10) unsigned NOT NULL auto_increment,
  username varchar(15) NOT NULL default '',
  type enum('address','notebook','collections') NOT NULL default 'address',
  dateline int(10) unsigned NOT NULL default '0',
  var1 varchar(50) NOT NULL default '',
  var2 varchar(100) NOT NULL default '',
  var3 tinytext NOT NULL,
  PRIMARY KEY  (id),
  KEY username (username),
  KEY type (type)
);

DROP TABLE IF EXISTS cdb_pm;
CREATE TABLE cdb_pm (
  pmid int(10) unsigned NOT NULL auto_increment,
  msgto varchar(15) NOT NULL default '',
  msgfrom varchar(15) NOT NULL default '',
  folder enum('inbox','outbox') NOT NULL default 'inbox',
  new tinyint(1) NOT NULL default '0',
  subject varchar(75) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  message text NOT NULL,
  PRIMARY KEY  (pmid),
  KEY msgto (msgto)
);

DROP TABLE IF EXISTS cdb_poll;
CREATE TABLE cdb_poll (
  pollid mediumint(8) unsigned NOT NULL auto_increment,
  tid mediumint(8) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  multiple tinyint(1) NOT NULL default '0',
  options text NOT NULL,
  voters text NOT NULL,
  maxvotes smallint(6) unsigned NOT NULL default '0',
  totalvotes smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (pollid),
  KEY tid (tid)
);

DROP TABLE IF EXISTS cdb_posts;
CREATE TABLE cdb_posts (
  fid smallint(6) unsigned NOT NULL default '0',
  tid mediumint(8) unsigned NOT NULL default '0',
  pid int(10) unsigned NOT NULL auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  icon varchar(30) NOT NULL default '',
  author varchar(15) NOT NULL default '',
  subject varchar(100) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  message mediumtext NOT NULL,
  useip varchar(15) NOT NULL default '',
  usesig tinyint(1) NOT NULL default '0',
  bbcodeoff tinyint(1) NOT NULL default '0',
  smileyoff tinyint(1) NOT NULL default '0',
  parseurloff tinyint(1) NOT NULL default '0',
  rate smallint(6) NOT NULL default '0',
  ratetimes tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (pid),
  KEY fid (fid),
  KEY tid (tid,dateline),
  KEY dateline (dateline)
);

DROP TABLE IF EXISTS cdb_postpay;
CREATE TABLE cdb_postpay (
  id int(12) NOT NULL auto_increment,
  tid mediumint(8) NOT NULL default '0',
  pid int(10) unsigned NOT NULL default '0',
  sellcount smallint(3) unsigned NOT NULL default '0',
  author varchar(25) NOT NULL default '',
  username varchar(25) NOT NULL default '',
  money smallint(6) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY tid (tid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS cdb_rank;
CREATE TABLE cdb_rank (
  rid int(10) unsigned NOT NULL auto_increment,
  ranktitle varchar(50) NOT NULL default '',
  posthigher int(20) NOT NULL default '0',
  rankstar int(10) unsigned NOT NULL default '1',
  rankcolor varchar(10) NOT NULL default '',
  PRIMARY KEY  (rid)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

INSERT INTO cdb_rank VALUES (1, 'Beginner', 0, 1, '');
INSERT INTO cdb_rank VALUES (2, 'Poster', 50, 2, '');
INSERT INTO cdb_rank VALUES (3, 'Cool Poster', 300, 3, '');
INSERT INTO cdb_rank VALUES (4, 'Writer', 1000, 4, '');
INSERT INTO cdb_rank VALUES (5, 'Excellent Writer', 3000, 5, '');

DROP TABLE IF EXISTS cdb_searchindex;
CREATE TABLE cdb_searchindex (
  keywords varchar(200) NOT NULL default '',
  results int(10) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  KEY dateline (dateline)
);

DROP TABLE IF EXISTS cdb_sessions;
CREATE TABLE cdb_sessions (
  sid varchar(8) binary NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  ipbanned tinyint(1) NOT NULL default '0',
  status enum('Guest','Member','Admin','SuperMod','Moderator','Banned','IPBanned','PostBanned','Inactive','vip') NOT NULL default 'Guest',
  username varchar(15) NOT NULL default '',
  lastactivity int(10) unsigned NOT NULL default '0',
  groupid smallint(6) unsigned NOT NULL default '0',
  styleid smallint(6) unsigned NOT NULL default '0',
  action tinyint(1) unsigned NOT NULL default '0',
  fid smallint(6) unsigned NOT NULL default '0',
  tid mediumint(8) unsigned NOT NULL default '0',
  KEY sid (sid)
) TYPE=HEAP MAX_ROWS=1000;

DROP TABLE IF EXISTS cdb_settings;
CREATE TABLE cdb_settings (
  bbname varchar(50) NOT NULL default '',
  regstatus tinyint(1) NOT NULL default '0',
  censoruser text NOT NULL,
  doublee tinyint(1) NOT NULL default '0',
  regverify tinyint(1) NOT NULL default '0',
  bbrules tinyint(1) NOT NULL default '0',
  bbrulestxt text NOT NULL,
  welcommsg tinyint(1) NOT NULL default '0',
  welcommsgtxt text NOT NULL,
  bbclosed tinyint(1) NOT NULL default '0',
  closedreason text NOT NULL,
  sitename varchar(50) NOT NULL default '',
  siteurl varchar(60) NOT NULL default '',
  moddisplay enum('flat','selectbox') NOT NULL default 'flat',
  styleid smallint(6) unsigned NOT NULL default '0',
  maxonlines smallint(6) unsigned NOT NULL default '0',
  floodctrl smallint(6) unsigned NOT NULL default '0',
  searchctrl smallint(6) unsigned NOT NULL default '0',
  hottopic tinyint(3) unsigned NOT NULL default '0',
  topicperpage tinyint(3) unsigned NOT NULL default '0',
  postperpage tinyint(3) unsigned NOT NULL default '0',
  memberperpage tinyint(3) unsigned NOT NULL default '0',
  maxpostsize mediumint(8) unsigned NOT NULL default '0',
  maxavatarsize tinyint(3) unsigned NOT NULL default '0',
  smcols tinyint(3) unsigned NOT NULL default '0',
  logincredits tinyint(3) unsigned NOT NULL default '0',
  postcredits tinyint(3) unsigned NOT NULL default '0',
  digestcredits tinyint(3) unsigned NOT NULL default '0',
  whosonlinestatus tinyint(1) NOT NULL default '0',
  vtonlinestatus tinyint(1) NOT NULL default '0',
  gzipcompress tinyint(1) NOT NULL default '0',
  hideprivate tinyint(1) NOT NULL default '0',
  fastpost tinyint(1) NOT NULL default '0',
  modshortcut tinyint(1) NOT NULL default '0',
  memliststatus tinyint(1) NOT NULL default '0',
  statstatus tinyint(1) NOT NULL default '0',
  debug tinyint(1) NOT NULL default '0',
  reportpost tinyint(1) NOT NULL default '0',
  bbinsert tinyint(1) NOT NULL default '0',
  smileyinsert tinyint(1) NOT NULL default '0',
  editedby tinyint(1) NOT NULL default '0',
  dotfolders tinyint(1) NOT NULL default '0',
  attachsave tinyint(1) NOT NULL default '0',
  attachimgpost tinyint(1) NOT NULL default '0',
  timeoffset varchar(5) NOT NULL default '',
  timeformat varchar(5) NOT NULL default '',
  dateformat varchar(10) NOT NULL default '',
  version varchar(100) NOT NULL default '',
  onlinerecord varchar(30) NOT NULL default '',
  totalmembers smallint(6) unsigned NOT NULL default '0',
  lastmember varchar(15) NOT NULL default ''
);

INSERT INTO cdb_settings VALUES ('Discuz! Plus', 1, '', 1, 0, 0, '', 0, '', 0, '', 'HKLCF Studio', 'http://www.hklcf.com/', 'flat', 1, 1000, 15, 5, 10, 20, 10, 25, 10000, 0, 3, 0, 1, 10, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 0, 1, '8', 'h:i A', 'Y-n-j', '1.1.4', '1	1040034649', 1, 'HKLCF');

DROP TABLE IF EXISTS cdb_smilies;
CREATE TABLE cdb_smilies (
  id smallint(6) unsigned NOT NULL auto_increment,
  type enum('smiley','picon') NOT NULL default 'smiley',
  code varchar(10) NOT NULL default '',
  url varchar(30) NOT NULL default '',
  PRIMARY KEY  (id)
);

INSERT INTO cdb_smilies VALUES('1','smiley',':)','smile.gif');
INSERT INTO cdb_smilies VALUES('2','smiley',':(','sad.gif');
INSERT INTO cdb_smilies VALUES('3','smiley',':D','biggrin.gif');
INSERT INTO cdb_smilies VALUES('4','smiley',';)','wink.gif');
INSERT INTO cdb_smilies VALUES('5','smiley',':cool:','cool.gif');
INSERT INTO cdb_smilies VALUES('6','smiley',':mad:','mad.gif');
INSERT INTO cdb_smilies VALUES('7','smiley',':o','shocked.gif');
INSERT INTO cdb_smilies VALUES('8','smiley',':P','tongue.gif');
INSERT INTO cdb_smilies VALUES('9','smiley',':lol:','lol.gif');
INSERT INTO cdb_smilies VALUES('10','picon','','icon1.gif');
INSERT INTO cdb_smilies VALUES('11','picon','','icon2.gif');
INSERT INTO cdb_smilies VALUES('12','picon','','icon3.gif');
INSERT INTO cdb_smilies VALUES('13','picon','','icon4.gif');
INSERT INTO cdb_smilies VALUES('14','picon','','icon5.gif');
INSERT INTO cdb_smilies VALUES('15','picon','','icon6.gif');
INSERT INTO cdb_smilies VALUES('16','picon','','icon7.gif');
INSERT INTO cdb_smilies VALUES('17','picon','','icon8.gif');
INSERT INTO cdb_smilies VALUES('18','picon','','icon9.gif');

DROP TABLE IF EXISTS cdb_stats;
CREATE TABLE cdb_stats (
  type varchar(20) NOT NULL default '',
  var varchar(20) NOT NULL default '',
  count int(10) unsigned NOT NULL default '0',
  KEY type (type),
  KEY var (var)
);

INSERT INTO cdb_stats VALUES ('total', 'hits', 0);
INSERT INTO cdb_stats VALUES ('total', 'members', 0);
INSERT INTO cdb_stats VALUES ('total', 'guests', 0);
INSERT INTO cdb_stats VALUES ('os', 'Windows', 0);
INSERT INTO cdb_stats VALUES ('os', 'Mac', 0);
INSERT INTO cdb_stats VALUES ('os', 'Linux', 0);
INSERT INTO cdb_stats VALUES ('os', 'FreeBSD', 0);
INSERT INTO cdb_stats VALUES ('os', 'SunOS', 0);
INSERT INTO cdb_stats VALUES ('os', 'BeOS', 0);
INSERT INTO cdb_stats VALUES ('os', 'OS/2', 0);
INSERT INTO cdb_stats VALUES ('os', 'AIX', 0);
INSERT INTO cdb_stats VALUES ('os', 'Other', 0);
INSERT INTO cdb_stats VALUES ('browser', 'MSIE', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Netscape', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Mozilla', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Lynx', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Opera', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Konqueror', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Other', 0);
INSERT INTO cdb_stats VALUES ('week', '0', 0);
INSERT INTO cdb_stats VALUES ('week', '1', 0);
INSERT INTO cdb_stats VALUES ('week', '2', 0);
INSERT INTO cdb_stats VALUES ('week', '3', 0);
INSERT INTO cdb_stats VALUES ('week', '4', 0);
INSERT INTO cdb_stats VALUES ('week', '5', 0);
INSERT INTO cdb_stats VALUES ('week', '6', 0);
INSERT INTO cdb_stats VALUES ('hour', '00', 0);
INSERT INTO cdb_stats VALUES ('hour', '01', 0);
INSERT INTO cdb_stats VALUES ('hour', '02', 0);
INSERT INTO cdb_stats VALUES ('hour', '03', 0);
INSERT INTO cdb_stats VALUES ('hour', '04', 0);
INSERT INTO cdb_stats VALUES ('hour', '05', 0);
INSERT INTO cdb_stats VALUES ('hour', '06', 0);
INSERT INTO cdb_stats VALUES ('hour', '07', 0);
INSERT INTO cdb_stats VALUES ('hour', '08', 0);
INSERT INTO cdb_stats VALUES ('hour', '09', 0);
INSERT INTO cdb_stats VALUES ('hour', '10', 0);
INSERT INTO cdb_stats VALUES ('hour', '11', 0);
INSERT INTO cdb_stats VALUES ('hour', '12', 0);
INSERT INTO cdb_stats VALUES ('hour', '13', 0);
INSERT INTO cdb_stats VALUES ('hour', '14', 0);
INSERT INTO cdb_stats VALUES ('hour', '15', 0);
INSERT INTO cdb_stats VALUES ('hour', '16', 0);
INSERT INTO cdb_stats VALUES ('hour', '17', 0);
INSERT INTO cdb_stats VALUES ('hour', '18', 0);
INSERT INTO cdb_stats VALUES ('hour', '19', 0);
INSERT INTO cdb_stats VALUES ('hour', '20', 0);
INSERT INTO cdb_stats VALUES ('hour', '21', 0);
INSERT INTO cdb_stats VALUES ('hour', '22', 0);
INSERT INTO cdb_stats VALUES ('hour', '23', 0);

DROP TABLE IF EXISTS cdb_styles;
CREATE TABLE cdb_styles (
  styleid smallint(6) unsigned NOT NULL auto_increment,
  name varchar(20) NOT NULL default '',
  available tinyint(1) NOT NULL default '1',
  templateid smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (styleid),
  KEY themename (name)
);

INSERT INTO cdb_styles VALUES (1, 'Default Style', 1, 1);

DROP TABLE IF EXISTS cdb_stylevars;
CREATE TABLE cdb_stylevars (
  stylevarid smallint(6) unsigned NOT NULL auto_increment,
  styleid smallint(6) unsigned NOT NULL default '0',
  variable text NOT NULL,
  substitute text NOT NULL,
  PRIMARY KEY  (stylevarid),
  KEY styleid (styleid)
);

INSERT INTO cdb_stylevars VALUES (1, 1, 'bgcolor', '#9EB6D8');
INSERT INTO cdb_stylevars VALUES (2, 1, 'altbg1', '#F8F8F8');
INSERT INTO cdb_stylevars VALUES (3, 1, 'altbg2', '#FFFFFF');
INSERT INTO cdb_stylevars VALUES (4, 1, 'link', '#003366');
INSERT INTO cdb_stylevars VALUES (5, 1, 'bordercolor', '#698CC3');
INSERT INTO cdb_stylevars VALUES (6, 1, 'headercolor', '#698CC3');
INSERT INTO cdb_stylevars VALUES (7, 1, 'headertext', '#FFFFFF');
INSERT INTO cdb_stylevars VALUES (8, 1, 'catcolor', '#EFEFEF');
INSERT INTO cdb_stylevars VALUES (9, 1, 'tabletext', '#000000');
INSERT INTO cdb_stylevars VALUES (10, 1, 'text', '#000000');
INSERT INTO cdb_stylevars VALUES (11, 1, 'borderwidth', '1');
INSERT INTO cdb_stylevars VALUES (12, 1, 'tablewidth', '98%');
INSERT INTO cdb_stylevars VALUES (13, 1, 'tablespace', '4');
INSERT INTO cdb_stylevars VALUES (14, 1, 'font', 'Tahoma, Verdana');
INSERT INTO cdb_stylevars VALUES (15, 1, 'fontsize', '12px');
INSERT INTO cdb_stylevars VALUES (16, 1, 'nobold', '0');
INSERT INTO cdb_stylevars VALUES (17, 1, 'boardimg', 'logo.gif');
INSERT INTO cdb_stylevars VALUES (18, 1, 'imgdir', 'images/default');
INSERT INTO cdb_stylevars VALUES (19, 1, 'smdir', 'images/smilies');
INSERT INTO cdb_stylevars VALUES (20, 1, 'cattext', '#000000');
INSERT INTO cdb_stylevars VALUES (21, 1, 'smfontsize', '11px');
INSERT INTO cdb_stylevars VALUES (22, 1, 'smfont', 'Tahoma, Verdana');

DROP TABLE IF EXISTS cdb_subscriptions;
CREATE TABLE cdb_subscriptions (
  username varchar(15) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  tid mediumint(8) unsigned NOT NULL default '0',
  lastnotify int(10) unsigned NOT NULL default '0',
  KEY username (username),
  KEY tid (tid)
);

DROP TABLE IF EXISTS cdb_templates;
CREATE TABLE cdb_templates (
  templateid smallint(6) unsigned NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  charset varchar(30) NOT NULL default '',
  directory varchar(100) NOT NULL default '',
  copyright varchar(100) NOT NULL default '',
  PRIMARY KEY  (templateid)
);

INSERT INTO cdb_templates VALUES (1, 'Default', 'big5', './templates/default', 'Designed by HKLCF(hklcf.com)');

DROP TABLE IF EXISTS cdb_threads;
CREATE TABLE cdb_threads (
  tid mediumint(8) unsigned NOT NULL auto_increment,
  fid smallint(6) NOT NULL default '0',
  creditsrequire smallint(6) unsigned NOT NULL default '0',
  icon varchar(30) NOT NULL default '',
  author varchar(15) NOT NULL default '',
  subject varchar(100) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  lastpost int(10) unsigned NOT NULL default '0',
  lastposter varchar(15) NOT NULL default '',
  views smallint(6) unsigned NOT NULL default '0',
  replies smallint(6) unsigned NOT NULL default '0',
  topped tinyint(1) NOT NULL default '0',
  digest tinyint(1) NOT NULL default '0',
  closed varchar(15) NOT NULL default '',
  pollopts text NOT NULL,
  attachment varchar(50) NOT NULL default '',
  PRIMARY KEY  (tid),
  KEY lastpost (topped,lastpost,fid)
);

DROP TABLE IF EXISTS cdb_usergroups;
CREATE TABLE cdb_usergroups (
  groupid smallint(6) unsigned NOT NULL auto_increment,
  specifiedusers text NOT NULL,
  status enum('Guest','Member','Admin','SuperMod','Moderator','Banned','IPBanned','PostBanned','Inactive','vip') NOT NULL default 'Member',
  grouptitle varchar(30) NOT NULL default '',
  creditshigher int(10) NOT NULL default '0',
  creditslower int(10) NOT NULL default '0',
  stars tinyint(3) NOT NULL default '0',
  groupavatar varchar(60) NOT NULL default '',
  allowcstatus tinyint(1) NOT NULL default '0',
  allowavatar tinyint(1) NOT NULL default '0',
  allowvisit tinyint(1) NOT NULL default '0',
  allowview tinyint(1) NOT NULL default '0',
  allowpost tinyint(1) NOT NULL default '0',
  allowpostpoll tinyint(1) NOT NULL default '0',
  allowgetattach tinyint(1) NOT NULL default '0',
  allowpostattach tinyint(1) NOT NULL default '0',
  allowvote tinyint(1) NOT NULL default '0',
  allowsearch tinyint(1) NOT NULL default '0',
  allowkarma tinyint(1) NOT NULL default '0',
  allowsetviewperm tinyint(1) NOT NULL default '0',
  allowsetattachperm tinyint(1) NOT NULL default '0',
  allowsigbbcode tinyint(1) NOT NULL default '0',
  allowsigimgcode tinyint(1) NOT NULL default '0',
  allowviewstats tinyint(1) NOT NULL default '0',
  ismoderator tinyint(1) NOT NULL default '0',
  issupermod tinyint(1) NOT NULL default '0',
  isadmin tinyint(1) NOT NULL default '0',
  maxpmnum smallint(6) unsigned NOT NULL default '0',
  maxmemonum smallint(6) unsigned NOT NULL default '0',
  maxsigsize smallint(6) unsigned NOT NULL default '0',
  maxkarmarate tinyint(3) unsigned NOT NULL default '0',
  maxrateperday smallint(6) unsigned NOT NULL default '0',
  maxattachsize int(10) unsigned NOT NULL default '0',
  attachextensions tinytext NOT NULL,
  PRIMARY KEY  (groupid),
  KEY status (status),
  KEY creditshigher (creditshigher),
  KEY creditslower (creditslower)
);

INSERT INTO cdb_usergroups VALUES('1','','Guest','訪客','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('2','','IPBanned','用戶IP被禁止','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('3','','Banned','禁止訪問','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('4','','PostBanned','禁止發言','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('5','','Inactive','等待驗證','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','50','0','0','0','');
INSERT INTO cdb_usergroups VALUES('6','','Moderator','版主','0','0','8','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','0','0','800','0','2000','80','800','2048000','');
INSERT INTO cdb_usergroups VALUES('7','','SuperMod','超級版主','0','0','9','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','0','1200','0','3000','90','900','2048000','');
INSERT INTO cdb_usergroups VALUES('8','','Admin','管理員','0','0','10','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','1','2000','0','50000','100','10000','4294967295','');
INSERT INTO cdb_usergroups VALUES('9','','Member','五星白金會員','1600','3500','5','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','300','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('10','','Member','六星鑽石會員','3500','9999999','6','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','400','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('11','','Member','四星黃金會員','800','1600','4','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','60','0','200','0','0','512000','');
INSERT INTO cdb_usergroups VALUES('12','','Member','論壇乞丐','-9999999','0','0','','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('13','','Member','三星高級會員','300','800','3','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','50','0','150','0','0','256000','');
INSERT INTO cdb_usergroups VALUES('14','','Member','二星初級會員','50','300','2','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','30','0','100','0','0','0','');
INSERT INTO cdb_usergroups VALUES('15','','Member','一星新手會員','0','50','1','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','20','0','80','0','0','0','');
INSERT INTO cdb_usergroups VALUES('16','','vip','VIP','0','0','7','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','600','0','1000','0','0','1024000','');

DROP TABLE IF EXISTS cdb_words;
CREATE TABLE cdb_words (
  id smallint(6) unsigned NOT NULL auto_increment,
  find varchar(60) NOT NULL default '',
  replacement varchar(60) NOT NULL default '',
  PRIMARY KEY  (id)
);

ALTER TABLE `$tablepre
members` ADD `money` INT(10) DEFAULT '100' NOT NULL, ADD `bank` INT(10) DEFAULT '0' NOT NULL, ADD `savemt` INT(10) DEFAULT '0' NOT NULL ;
ALTER TABLE `$tablepre
attachments` ADD `dl_users` TEXT NOT NULL ;
ALTER TABLE `$tablepre
attachments` ADD FULLTEXT (`dl_users`) ;
ALTER TABLE `$tablepre
threads` ADD `highlight` tinyint(1) default '0' NOT NULL ;
ALTER TABLE `$tablepre
members` CHANGE `credit` `credit` INT( 10 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `$tablepre
sessions` ADD `invisible` TINYINT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `$tablepre
members` ADD `secques` VARCHAR(8) NOT NULL ;
ALTER TABLE `$tablepre
forums` ADD namecolor varchar(7) NOT NULL default '#000000' ;
ALTER TABLE `$tablepre
forums` ADD descolor varchar(7) NOT NULL default '#000000' ;
ALTER TABLE `$tablepre
karmalog` CHANGE `score` `score` TINYINT( 3 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `$tablepre
members` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Member' NOT NULL ;
ALTER TABLE `$tablepre
usergroups` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Member' NOT NULL ;
ALTER TABLE `$tablepre
sessions` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Guest' NOT NULL ;

EOT;

	runquery($sql);
	$db->query("DELETE FROM {$tablepre}members");
	$db->query("INSERT INTO {$tablepre}members (username, password, status, regip, regdate, lastvisit, email, dateformat, timeformat, showemail, newsletter, timeoffset)
		VALUES ('$username', '".md5($password1)."', 'Admin', 'hidden', '".time()."', '".time()."', '$email', 'Y-n-j', 'h:i A', '1', '1', '8');");

echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> 初始化運行目錄與檔案</font></b></td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";

loginit('karmalog');
loginit('illegallog');
loginit('modslog');
loginit('cplog');
dir_clear('./forumdata/templates');
dir_clear('./forumdata/cache');

?>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <font color="#FF0000"><b>恭喜您，Discuz! Plus 安裝成功！</font><br>
            管理員賬號:</b><?=$username?><b> 管理員密碼:</b><?=$password1?><br><br>
            <a href="index.php" target="_blank">點擊這裡進入論壇</a>
          </td>
        </tr>
<?
}
?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td align="center">
            <b style="font-size: 11px">Powered by <a href="http://hklcf.com/" target="_blank">Discuz! Plus <?=$version?></a> , &nbsp; Copyright &copy; <a href="http://hklcf.com" target=\"_blank\">HKLCF Studio</a>, 2004</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<?
} elseif($language == 'chinese_gb2312'){

function loginit($log) {
	echo '場宎趙暮翹 '.$log;
	$fp = @fopen('./forumdata/illegallog.php');
	@fwrite($fp, "<?PHP exit(\"Access Denied\"); ?>\n");
	@fclose($fp);
	result();
}

function runquery($sql) {
	global $tablepre, $db;

	$sql = str_replace("\r", "\n", str_replace(' cdb_', ' '.$tablepre, $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' ? NULL : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				echo '膘蕾訧蹋桶 '.$name.' ... <font color="#0000EE">傖髡</font><br>';
			}
			$db->query($query);
		}
	}
}

function result($result = 1, $output = 1) {
	if($result) {
		$text = '... <font color="#0000EE">傖髡</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">囮啖</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	}
}

function dir_writeable($dir) {
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.test", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.test");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function dir_clear($dir) {
	echo 'ь諾醴翹 '.$dir;
	$directory = dir($dir);
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename)) {
			@unlink($filename);
		}
	}
	$directory->close();
	result();
}

?>
<body bgcolor="#3A4273" text="#000000">
<table width="95%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
  <tr>
    <td>
      <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr> 
          <td class="install" height="30" valign="bottom"><font color="#FF0000">&gt;&gt;</font> 
            Discuz! Plus Installation Wizard</td>
        </tr>
        <tr>
          <td> 
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
	 <td align="center">
	<b>辣茩懂善 Discuz! Plus Board 假蚾砃絳ㄛ假蚾ヶ③豝牉堐黍 license 紫腔藩跺牉誹ㄛ婓蠟�毓阮奿奜糔威�逋 Discuz! Plus 腔忨�佬倡橠捏馦鼴傺羌摯終陛ψeadme 紫枑鼎賸衄壽�篱撠終做騰腕驐甭踽�肮欴豝牉堐黍ㄛ眕悵痐假蚾輛最腔佼瞳輛俴﹝</b>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
<?

if(!$action) {

	$discuz_licence = <<<EOT
唳�佯齾� (c) 2004ㄛHKLCF.COM
悵隱垀衄�阱�﹝

    覜郅斕恁寁 Discuz! Plus 蹦抭莉こ﹝洷咡扂蠅腔贗薯夔峈斕枑鼎珨跺詢虴辦厒睿Ч湮腔 web 蹦抭賤樵源偶﹝

    Discuz! Plus 峈 HKLCF.COM 黃蕾羲楷ㄛ�垓蕩刵躁暱鼮樨� HKLCF.COM 垀衄﹝

    Discuz! Plus 腔瞄陑婦嬤賸 Discuz! 3.x , 2.x , PHPWind , phpbb , 3Q , IPB 摯 UNet.Boards 脹脹ㄝㄝㄝㄝㄝㄝ
EOT;

	$discuz_licence = str_replace('  ', '&nbsp; ', nl2br($discuz_licence));

?>
        <tr> 
          <td><b>絞ヶ袨怓ㄩ</b><font color="#0000EE">Discuz! Plus 頗埜勍褫衪祜</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ③蠟昢斛豝牉堐黍狟醱腔勍褫衪祜</font></b></td>
        </tr>
        <tr>
          <td><br>
            <table width="90%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr>
                <td bgcolor="#E3E3EA">
                  <table width="99%" cellspacing="1" border="0" align="center">
                    <tr>
                      <td>
                        <?=$discuz_licence?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="扂俇�屍皆�" style="height: 25">&nbsp;
              <input type="button" name="exit" value="扂祥夔肮砩" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?

} elseif($action == 'config') {

	$exist_error = FALSE;
	$write_error = FALSE;
	if(file_exists('./config.php')) {
		$fileexists = result(1, 0);
	} else {
		$fileexists = result(0, 0);
		$exist_error = TRUE;
	}
	if(is_writeable('./config.php')) {
		$filewriteable = result(1, 0);
	} else {
		$filewriteable = result(0, 0);
		$write_error = TRUE;
	}
	if($exist_error) {
		$config_info = '蠟腔 config.php 祥湔婓ㄛ 拸楊樟哿假蚾ㄛ ③蚚 FTP 蔚蜆恅璃奻換綴婬彸﹝';
	} elseif(!$write_error) {
		$config_info = '③婓狟醱沓迡蠟腔訧蹋踱梛瘍捅洘ㄛ 籵都①錶狟③祥猁党蜊綻伎恁砐囀�搳�';
	} elseif($write_error) {
		$config_info = '假蚾砃絳拸楊迡�蹁馺襞躁�ㄛ ③瞄勤珋衄捅洘ㄛ �覣駗瑏耀� ③籵徹 FTP 蔚蜊疑腔 config.php 奻換﹝';
	}

?>
        <tr> 
          <td><b>絞ヶ袨怓ㄩ</b><font color="#0000EE">饜离 config.php</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 潰脤饜离恅璃袨怓</font></b></td>
        </tr>
        <tr>
          <td>config.php 湔婓潰脤 <?=$fileexists?></td>
        </tr>
        <tr>
          <td>config.php 褫迡潰脤 <?=$filewriteable?></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 銡擬/晤憮絞ヶ饜离</font></b></td>
        </tr>
        <tr>
          <td align="center"><br><?=$config_info?></td>
        </tr>
<?

	if(!$exist_error) {

		if(!$write_error) {

			$dbhost = 'localhost';
			$dbuser = 'dbuser';
			$dbpw = 'dbpw';
			$dbname = 'plus';
			$adminemail = 'admin@your.com';
			$tablepre = 'cdb_';

			@include './config.php';

?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>">
              <table width="500" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr bgcolor="#3A4273">
                  <td align="center" width="20%" style="color: #FFFFFF">扢离恁砐</td>
                  <td align="center" width="35%" style="color: #FFFFFF">絞ヶ硉</td>
                  <td align="center" width="45%" style="color: #FFFFFF">蛁庋</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;訧蹋踱侜督ん:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?=$dbhost?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;訧蹋踱侜督ん華硊, 珨啜峈 localhost</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;訧蹋踱頗埜靡:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?=$dbuser?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;訧蹋踱梖瘍頗埜靡</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;訧蹋踱躇鎢:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?=$dbpw?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;訧蹋踱梖瘍躇鎢</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;訧蹋踱靡:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbname" value="<?=$dbname?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;訧蹋踱靡備</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;炵苀 Email:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="adminemail" value="<?=$adminemail?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;蚚衾楷冞最宒渣昫惆豢</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;桶靡ヶ袟:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="tablepre" value="<?=$tablepre?>" size="30" onClick="javascript: alert('假蚾砃絳枑尨:\n\n壺準蠟剒猁婓肮珨訧蹋踱假蚾嗣跺 Discuz! \n蹦抭,瘁寀,Ч轄膘祜蠟祥猁党蜊桶靡ヶ袟.');"></td>
                  <td bgcolor="#E3E3EA">&nbsp;肮珨訧蹋踱假蚾嗣蹦抭奀褫蜊曹啎扢</td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="environment">
              <input type="hidden" name="saveconfig" value="1">
              <input type="submit" name="submit" value="悵湔饜离捅洘" style="height: 25">
              <input type="button" name="exit" value="豖堤假蚾砃絳" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?

		} else {

			@include './config.php';

?>
        <tr>
          <td>
            <br>
            <table width="60%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center" style="color: #FFFFFF">曹講</td>
                <td align="center" style="color: #FFFFFF">絞ヶ硉</td>
                <td align="center" style="color: #FFFFFF">蛁庋</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbhost</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbhost?></td>
                <td bgcolor="#E3E3EA" align="center">訧蹋踱侜督ん華硊, 珨啜峈 localhost</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbuser</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbuser?></td>
                <td bgcolor="#E3E3EA" align="center">訧蹋踱梖瘍頗埜靡</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbpw</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpw?></td>
                <td bgcolor="#E3E3EA" align="center">訧蹋踱梖瘍躇鎢</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbname</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbname?></td>
                <td bgcolor="#E3E3EA" align="center">訧蹋踱靡備</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$adminemail</td>
                <td bgcolor="#EEEEF6" align="center"><?=$adminemail?></td>
                <td bgcolor="#E3E3EA" align="center">炵苀 Email</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$tablepre</td>
                <td bgcolor="#EEEEF6" align="center"><?=$tablepre?></td>
                <td bgcolor="#E3E3EA" align="center">訧蹋桶靡ヶ袟</td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td align="center">
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>">
              <input type="hidden" name="action" value="environment">
              <input type="submit" name="submit" value="奻扴饜离淏��" style="height: 25">
              <input type="button" name="exit" value="笭陔淕燴党蜊賦彆" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_gb2312'?>?action=config');">
            </form>
          </td>
        </tr>
<?

		}

	} else {

?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="笭陔潰脤扢离" style="height: 25">
              <input type="button" name="exit" value="退出" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?

	}

} elseif($action == 'environment') {

	if($HTTP_POST_VARS['saveconfig'] && is_writeable('./config.php')) {

		$dbhost = $HTTP_POST_VARS['dbhost'];
		$dbuser = $HTTP_POST_VARS['dbuser'];
		$dbpw = $HTTP_POST_VARS['dbpw'];
		$dbname = $HTTP_POST_VARS['dbname'];
		$adminemail = $HTTP_POST_VARS['adminemail'];
		$tablepre = $HTTP_POST_VARS['tablepre'];

		$fp = fopen('./config.php', 'r');
		$configfile = fread($fp, filesize('./config.php'));
		fclose($fp);

		$configfile = preg_replace("/[$]dbhost\s*\=\s*[\"'].*?[\"']/is", "\$dbhost = '$dbhost'", $configfile);
		$configfile = preg_replace("/[$]dbuser\s*\=\s*[\"'].*?[\"']/is", "\$dbuser = '$dbuser'", $configfile);
		$configfile = preg_replace("/[$]dbpw\s*\=\s*[\"'].*?[\"']/is", "\$dbpw = '$dbpw'", $configfile);
		$configfile = preg_replace("/[$]dbname\s*\=\s*[\"'].*?[\"']/is", "\$dbname = '$dbname'", $configfile);
		$configfile = preg_replace("/[$]adminemail\s*\=\s*[\"'].*?[\"']/is", "\$adminemail = '$adminemail'", $configfile);
		$configfile = preg_replace("/[$]tablepre\s*\=\s*[\"'].*?[\"']/is", "\$tablepre = '$tablepre'", $configfile);

		$fp = fopen('./config.php', 'w');
		fwrite($fp, trim($configfile));
		fclose($fp);

	}

	include './config.php';
	include './include/db_'.$database.'.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

	$msg = '';
	$quit = FALSE;

	$curr_os = PHP_OS;

	$curr_php_version = PHP_VERSION;
	if($curr_php_version < '4.0.6') {
		$msg .= "<font color=\"#FF0000\">蠟腔 PHP 唳掛苤衾 4.0.6ㄛ 拸楊妏蚚 Discuz! Plus﹝</font>\t";
		$quit = TRUE;
	} elseif($curr_php_version < '4.0.6') {
		$msg .= "<font color=\"#FF0000\">蠟腔 PHP 唳掛苤衾 4.0.6ㄛ 拸楊妏蚚芛砉喜渡潰脤睿 gzip 揤坫髡夔﹝</font>\t";
	}

	if(@ini_get(file_uploads)) {
		$max_size = @ini_get(upload_max_filesize);
		$curr_upload_status = "埰勍/郔湮喜渡 $max_size";
		$msg .= "蠟褫眕奻換喜渡婓 $max_size 眕狟腔蜇璃恅璃.\t";
	} else {
		$curr_upload_status = '祥埰勍奻換蜇璃';
		$msg .= "<font color=\"#FF0000\">蚕衾侜督ん殑敖ㄛ 蠟拸楊妏蚚蜇璃髡夔﹝</font>\t";
	}

	$curr_gobals_status = @ini_get(register_globals);
	if($curr_gobals_status > '0') {
		$curr_gobals_writeable = 'ON';
	} elseif($curr_gobals_status < '1') {
		$curr_gobals_writeable = 'OFF';
	}

	$curr_quotes_status = get_magic_quotes_gpc();
	if($curr_quotes_status > '0') {
		$curr_quotes_writeable = 'ON';
	} elseif($curr_quotes_status < '1') {
		$curr_quotes_writeable = 'OFF';
		$msg .="<font color=\"#FF0000\">Please set magic_quotes_gpc = On in your php.ini.</font>\t";
		$quit = TRUE;
	}

	$query = $db->query("SELECT VERSION()");
	$curr_mysql_version = $db->result($query, 0);
	if($curr_mysql_version < '3.23') {
		$msg .= "<font color=\"#FF0000\">蠟腔 MySQL 唳掛腴衾 3.23ㄛ Discuz! Plus 腔珨虳髡夔褫夔拸楊淏都妏蚚﹝</font>\t";
	}

	$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

	if(dir_writeable('./templates')) {
		$curr_tpl_writeable = '褫迡';
	} else {
		$curr_tpl_writeable = '祥褫迡';
		$msg .= "<font color=\"#FF0000\">耀啣 ./templates 醴翹扽俶準 777 麼拸楊迡�諴� 拸楊妏蚚盄奻晤憮耀啣睿瑞跡絳�諢�</font>\t";
	}

	if(dir_writeable($attachdir)) {
		$curr_attach_writeable = '褫迡';
	} else {
		$curr_attach_writeable = '祥褫迡';
		$msg .= "<font color=\"#FF0000\">蜇璃 $attachdir 醴翹扽俶準 777 麼拸楊迡�諴� 拸楊妏蚚蜇璃髡夔﹝</font>\t";
	}

	if(dir_writeable('./customavatars/')) {
		$curr_head_writeable = '褫迡';
	} else {
		$curr_head_writeable = '祥褫迡';
		$msg .= "<font color=\"#FF0000\">奻換芛砉 ./customavatars 醴翹扽俶準 777 麼拸楊迡�諴� 拸楊妏蚚奻換芛砉髡夔﹝</font>\t";
	}

	if(dir_writeable('./forumdata/')) {
		$curr_data_writeable = '褫迡';
	} else {
		$curr_data_writeable = '祥褫迡';
		$msg .= "<font color=\"#FF0000\">訧蹋 ./forumdata 醴翹扽俶準 777 麼拸楊迡�諴� 拸楊妏蚚掘爺善侜督ん/蹦抭堍俴暮翹脹髡夔﹝</font>\t";
	}

	if(dir_writeable('./forumdata/templates/')) {
		$curr_template_writeable = '褫迡';
	} else {
		$curr_template_writeable = '祥褫迡';
		$msg .= "<font color=\"#FF0000\">耀啣 ./forumdata/templates 醴翹扽俶準 777 麼拸楊迡�諴� 拸楊假蚾 Discuz! Plus﹝</font>\t";
		$quit = TRUE;
	}

	if(dir_writeable('./forumdata/cache/')) {
		$curr_cache_writeable = '褫迡';
	} else {
		$curr_cache_writeable = '祥褫迡';
		$msg .= "<font color=\"#FF0000\">遣湔 ./forumdata/cache 醴翹扽俶準 777 麼拸楊迡�諴� 拸楊假蚾 Discuz! Plus﹝</font>\t";
		$quit = TRUE;
	}

	$db->select_db($dbname);
	if($db->error()) {
		$db->query("CREATE DATABASE $dbname");
		if($db->error()) {
			$msg .= "<font color=\"#FF0000\">硌隅腔訧蹋踱 $dbname 祥湔婓ㄛ 炵苀珩拸楊赻雄膘蕾ㄛ 拸楊假蚾 Discuz! Plus﹝</font>\t";
			$quit = TRUE;
		} else {
			$db->select_db($dbname);
			$msg .= "硌隅腔訧蹋踱 $dbname 祥湔婓ㄛ 筍炵苀眒傖髡膘蕾ㄛ 褫眕樟哿假蚾﹝\t";
		}
	}

	$query - $db->query("SELECT COUNT(*) FROM $tablepre"."settings", 1);
	if(!$db->error()) {
		$msg .= "<font color=\"#FF0000\">訧蹋踱笢眒冪假蚾徹 Discuz! Plusㄛ 樟哿假蚾頗ь諾埻衄訧蹋﹝</font>\t";
		$alert = " onSubmit=\"return confirm('樟哿假蚾頗ь諾�垓諮個倜岏洷珀��毓例盲昐讕艞�');\"";
	} else {
		$alert = '';
	}

	if($quit) {
		$msg .= "<font color=\"#FF0000\">蚕衾蠟醴翹扽俶麼侜督ん饜离埻秪, 拸楊樟哿假蚾 Discuz! Plusㄛ ③豝牉堐黍假蚾佽隴﹝</font>";
	} else {
		$msg .= "蠟腔侜督ん褫眕假蚾睿妏蚚 Discuz! Plusㄛ ③輛�輴觴輔蔑終陛�";
	}

?>
        <tr>
          <td><b>絞ヶ袨怓ㄩ</b><font color="#0000EE">潰脤絞ヶ侜督ん遠噫</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> Discuz! Plus 垀剒遠噫睿絞ヶ侜督ん饜离勤掀</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="80%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center"></td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus 垀剒饜离</td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus 郔槽饜离</td>
                <td align="center" style="color: #FFFFFF">絞ヶ侜督ん</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">紱釬炵苀</td>
                <td bgcolor="#EEEEF6" align="center">祥癹</td>
                <td bgcolor="#E3E3EA" align="center">UNIX/Linux/FreeBSD</td>
                <td bgcolor="#E3E3EA" align="center"><?=$curr_os?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP 唳掛</td>
                <td bgcolor="#EEEEF6" align="center">4.0.6+</td>
                <td bgcolor="#E3E3EA" align="center">5.0.1+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_php_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP 硌鍔: register_globals</td>
                <td bgcolor="#EEEEF6" align="center">OFF</td>
                <td bgcolor="#E3E3EA" align="center">OFF</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_gobals_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP 硌鍔: magic_quotes_gpc</td>
                <td bgcolor="#EEEEF6" align="center">ON</td>
                <td bgcolor="#E3E3EA" align="center">ON</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_quotes_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">蜇璃奻換</td>
                <td bgcolor="#EEEEF6" align="center">祥癹</td>
                <td bgcolor="#E3E3EA" align="center">埰勍</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_upload_status?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">MySQL 唳掛</td>
                <td bgcolor="#EEEEF6" align="center">3.23+</td>
                <td bgcolor="#E3E3EA" align="center">4.0.20+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_mysql_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">棠詠諾潔</td>
                <td bgcolor="#EEEEF6" align="center">2M+</td>
                <td bgcolor="#E3E3EA" align="center">100M+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_disk_space?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./templates 醴翹迡��</td>
                <td bgcolor="#EEEEF6" align="center">祥癹</td>
                <td bgcolor="#E3E3EA" align="center">褫迡</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_tpl_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$attachdir?> 醴翹迡��</td>
                <td bgcolor="#EEEEF6" align="center">祥癹</td>
                <td bgcolor="#E3E3EA" align="center">褫迡</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_attach_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./customavatars 醴翹迡��</td>
                <td bgcolor="#EEEEF6" align="center">祥癹</td>
                <td bgcolor="#E3E3EA" align="center">褫迡</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_head_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata 醴翹迡��</td>
                <td bgcolor="#EEEEF6" align="center">祥癹</td>
                <td bgcolor="#E3E3EA" align="center">褫迡</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_data_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/templates 醴翹迡��</td>
                <td bgcolor="#EEEEF6" align="center">褫迡</td>
                <td bgcolor="#E3E3EA" align="center">褫迡</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_template_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/cache 醴翹迡��</td>
                <td bgcolor="#EEEEF6" align="center">褫迡</td>
                <td bgcolor="#E3E3EA" align="center">褫迡</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_cache_writeable?></td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ③�溜玾敊窸圮覤簡誰�</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
              <li>蔚揤坫婦笢 Discuz! Plus 醴翹狟�垓艙粥蛹苃螞暴炴奏誼韁�ん.</li>
              <li>党蜊侜督ん奻腔 config.php 紫偶眕巠磁蠟腔饜离, 衄壽訧蹋踱梖瘍捅洘③訰戙蠟腔諾潔督昢枑鼎妀.</li>
              <li>�蝜�蠟妏蚚準 WINNT 炵苀③党蜊眕狟扽俶:<br>&nbsp; &nbsp; <b>./templates</b> 醴翹 777;&nbsp; &nbsp; <b><?=$attachdir?></b> 醴翹 777;&nbsp; &nbsp; <b>./customavatars</b> 醴翹 777;&nbsp; &nbsp; <b>./forumdata</b> 醴翹 777; <br><b>&nbsp; &nbsp; ./forumdata/cache</b> 醴翹 777;&nbsp; &nbsp; <b>./forumdata/templates</b> 醴翹 777;<br></li>
              <li>�溜� URL 笢 <?=$attachurl?> 褫眕溼恀侜督ん醴翹 <?=$attachdir?> 囀��.</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 假蚾砃絳枑尨</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
<?

	$msgs = explode("\t", $msg);
	unset($msg);
	for($i = 0; $i < count($msgs); $i++) {
		echo "              <li>".$msgs[$i]."</li>\n";
	}
	echo"            </ol>\n";

	if($quit) {

?>
            <center>
            <input type="button" name="refresh" value="笭陔潰脤扢离" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_gb2312'?>?action=environment');">&nbsp;
            <input type="button" name="exit" value="豖堤" style="height: 25" onclick="javascript: window.close();">
            </center>
<?

	} else {

?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 扢离奪燴埜梛瘍</font></b></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>"<?=$alert?>>
              <table width="300" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;奪燴埜梖瘍:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="username" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;奪燴埜 Email:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="email" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;奪燴埜躇鎢:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password1" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;笭葩躇鎢:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password2" size="30"></td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="install">
              <input type="submit" name="submit" value="羲宎假蚾 Discuz! Plus" style="height: 25" >&nbsp;
              <input type="button" name="exit" value="豖堤假蚾砃絳" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>

<?

	}

} elseif($action == 'install') {

	$username = $HTTP_POST_VARS['username'];
	$email = $HTTP_POST_VARS['email'];
	$password1 = $HTTP_POST_VARS['password1'];
	$password2 = $HTTP_POST_VARS['password2'];

?>
        <tr>
          <td><b>絞ヶ袨怓ㄩ</b><font color="#0000EE">潰脤奪燴埜梖瘍捅洘甜羲宎假蚾 Discuz! Plus﹝</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 潰脤奪燴埜梛瘍</font></b></td>
        </tr>
        <tr>
          <td>潰脤捅洘磁楊俶
<?

	$msg = '';
	if($username && $email && $password1 && $password2) {
		if($password1 != $password2) {
			$msg = "謗棒怀�踼僉貒閡閥癒�";
		} elseif(strlen($username) > 15) {
			$msg = "蚚誧靡閉徹 15 跺趼啋癹秶﹝";
		} elseif(preg_match("/^$|^c:\\con\\con$|﹛|[,\"\s\t\<\>]|^蚔諦|^Guest/is", $username)) {
			$msg = "蚚誧靡諾麼婦漪準楊趼啋﹝";
		} elseif(!strstr($email, '@') || $email != stripslashes($email) || $email != htmlspecialchars($email)) {
			$msg = "Email 華硊拸虴";
		}
	} else {
		$msg = '斕腔捅洘羶衄沓迡俇淕﹝';
	}

	if($msg) { 

?>
            ... <font color="#FF0000">囮啖﹝ 埻秪ㄩ<?=$msg?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="殿隙奻珨珜党蜊" onclick="javascript: history.go(-1);">
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td align="center">
            <b style="font-size: 11px">Powered by <a href="http://hklcf.com/" target="_blank">Discuz! Plus <?=$version?></a> , &nbsp; Copyright &copy; <a href="http://hklcf.com" target=\"_blank\">HKLCF Studio</a>, 2004</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
</body>
</html>

<?

		exit;
	} else {
		echo result(1, 0)."</td>\n";
		echo"        </tr>\n";
	}

?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 恁寁訧蹋踱</font></b></td>
        </tr>
<?
	include './config.php';
	include './include/db_'.$database.'.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$db->select_db($dbname);

echo"        <tr>\n";
echo"          <td>恁寁訧蹋踱 $dbname ".result(1, 0)."</td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> 膘蕾訧蹋桶</font></b></td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";

	$sql = <<<EOT
DROP TABLE IF EXISTS cdb_announcements;
CREATE TABLE cdb_announcements (
  id smallint(6) unsigned NOT NULL auto_increment,
  author varchar(15) NOT NULL default '',
  subject varchar(250) NOT NULL default '',
  starttime int(10) unsigned NOT NULL default '0',
  endtime int(10) unsigned NOT NULL default '0',
  message text NOT NULL,
  PRIMARY KEY  (id)
);

DROP TABLE IF EXISTS cdb_attachments;
CREATE TABLE cdb_attachments (
  aid mediumint(8) unsigned NOT NULL auto_increment,
  tid mediumint(8) unsigned NOT NULL default '0',
  pid int(10) unsigned NOT NULL default '0',
  creditsrequire smallint(6) unsigned NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  filetype varchar(50) NOT NULL default '',
  filesize int(12) unsigned NOT NULL default '0',
  attachment varchar(255) NOT NULL default '',
  downloads smallint(6) NOT NULL default '0',
  PRIMARY KEY  (aid)
);

DROP TABLE IF EXISTS cdb_banned;
CREATE TABLE cdb_banned (
  id smallint(6) unsigned NOT NULL auto_increment,
  ip1 smallint(3) NOT NULL default '0',
  ip2 smallint(3) NOT NULL default '0',
  ip3 smallint(3) NOT NULL default '0',
  ip4 smallint(3) NOT NULL default '0',
  admin varchar(15) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY ip1 (ip1),
  KEY ip2 (ip2),
  KEY ip3 (ip3),
  KEY ip4 (ip1)
);

DROP TABLE IF EXISTS cdb_buddys;
CREATE TABLE cdb_buddys (
  username varchar(15) NOT NULL default '',
  buddyname varchar(15) NOT NULL default ''
);

DROP TABLE IF EXISTS cdb_chname;
CREATE TABLE cdb_chname (
  id int(10) unsigned NOT NULL auto_increment,
  newname varchar(15) NOT NULL default '',
  oldname varchar(15) NOT NULL default '',
  reason text NOT NULL,
  dateline int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS cdb_favorites;
CREATE TABLE cdb_favorites (
  tid mediumint(8) unsigned NOT NULL default '0',
  username varchar(15) NOT NULL default '',
  KEY tid (tid)
);

DROP TABLE IF EXISTS cdb_forumlinks;
CREATE TABLE cdb_forumlinks (
  id smallint(6) unsigned NOT NULL auto_increment,
  displayorder tinyint(3) NOT NULL default '0',
  name varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  note varchar(200) NOT NULL default '',
  logo varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
);

INSERT INTO cdb_forumlinks VALUES (1, 0, 'Discuz! Plus', 'http://discuz.hklcf.com/', 'Discuz! Plus official website, provide latest product news, downloading and technical supports, etc.', 'images/logo.gif');

DROP TABLE IF EXISTS cdb_forums;
CREATE TABLE cdb_forums (
  fid smallint(6) unsigned NOT NULL auto_increment,
  fup smallint(6) unsigned NOT NULL default '0',
  type enum('group','forum','sub') NOT NULL default 'forum',
  icon varchar(100) NOT NULL default '',
  name varchar(50) NOT NULL default '',
  description text NOT NULL,
  status tinyint(1) NOT NULL default '0',
  displayorder tinyint(3) NOT NULL default '0',
  moderator tinytext NOT NULL,
  styleid smallint(6) unsigned NOT NULL default '0',
  threads smallint(6) unsigned NOT NULL default '0',
  posts mediumint(8) unsigned NOT NULL default '0',
  lastpost varchar(130) NOT NULL default '',
  allowsmilies tinyint(1) NOT NULL default '0',
  allowhtml tinyint(1) NOT NULL default '0',
  allowbbcode tinyint(1) NOT NULL default '0',
  allowimgcode tinyint(1) NOT NULL default '0',
  password varchar(12) NOT NULL default '',
  postcredits tinyint(1) NOT NULL default '-1',
  viewperm tinytext NOT NULL,
  postperm tinytext NOT NULL,
  getattachperm tinytext NOT NULL,
  postattachperm tinytext NOT NULL,
  PRIMARY KEY  (fid),
  KEY status (status)
);

INSERT INTO cdb_forums VALUES (1, 0, 'forum', '', 'Main Forum', '', 1, 0, '', 0, 0, 0, '', 1, 0, 1, 1, '', 0, '', '', '', '');

DROP TABLE IF EXISTS cdb_karmalog;
CREATE TABLE cdb_karmalog (
  username varchar(15) NOT NULL default '',
  pid int(10) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  score tinyint(3) unsigned NOT NULL default '0'
);

DROP TABLE IF EXISTS cdb_members;
CREATE TABLE cdb_members (
  uid mediumint(8) unsigned NOT NULL auto_increment,
  username varchar(15) NOT NULL default '',
  password varchar(40) NOT NULL default '',
  gender tinyint(1) NOT NULL default '0',
  status enum('Member','Admin','SuperMod','Moderator','Banned','PostBanned','Inactive','vip') NOT NULL default 'Member',
  regip varchar(15) NOT NULL default '',
  regdate int(10) unsigned NOT NULL default '0',
  lastvisit int(10) unsigned NOT NULL default '0',
  postnum smallint(6) unsigned NOT NULL default '0',
  credit int(10) UNSIGNED NOT NULL default '0',
  charset varchar(10) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  site varchar(75) NOT NULL default '',
  icq varchar(12) NOT NULL default '',
  oicq varchar(12) NOT NULL default '',
  yahoo varchar(40) NOT NULL default '',
  msn varchar(40) NOT NULL default '',
  location varchar(30) NOT NULL default '',
  bday date NOT NULL default '0000-00-00',
  bio text NOT NULL,
  avatar varchar(100) NOT NULL default '',
  signature text NOT NULL,
  customstatus varchar(20) NOT NULL default '',
  tpp tinyint(3) unsigned NOT NULL default '0',
  ppp tinyint(3) unsigned NOT NULL default '0',
  styleid smallint(6) unsigned NOT NULL default '0',
  dateformat varchar(10) NOT NULL default '',
  timeformat varchar(5) NOT NULL default '',
  showemail tinyint(1) NOT NULL default '0',
  newsletter tinyint(1) NOT NULL default '0',
  timeoffset char(3) NOT NULL default '',
  ignorepm text NOT NULL,
  newpm tinyint(1) NOT NULL default '0',
  pwdrecover varchar(30) NOT NULL default '',
  pwdrcvtime int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (uid),
  KEY username (username)
);

DROP TABLE IF EXISTS cdb_memo;
CREATE TABLE cdb_memo (
  id int(10) unsigned NOT NULL auto_increment,
  username varchar(15) NOT NULL default '',
  type enum('address','notebook','collections') NOT NULL default 'address',
  dateline int(10) unsigned NOT NULL default '0',
  var1 varchar(50) NOT NULL default '',
  var2 varchar(100) NOT NULL default '',
  var3 tinytext NOT NULL,
  PRIMARY KEY  (id),
  KEY username (username),
  KEY type (type)
);

DROP TABLE IF EXISTS cdb_pm;
CREATE TABLE cdb_pm (
  pmid int(10) unsigned NOT NULL auto_increment,
  msgto varchar(15) NOT NULL default '',
  msgfrom varchar(15) NOT NULL default '',
  folder enum('inbox','outbox') NOT NULL default 'inbox',
  new tinyint(1) NOT NULL default '0',
  subject varchar(75) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  message text NOT NULL,
  PRIMARY KEY  (pmid),
  KEY msgto (msgto)
);

DROP TABLE IF EXISTS cdb_poll;
CREATE TABLE cdb_poll (
  pollid mediumint(8) unsigned NOT NULL auto_increment,
  tid mediumint(8) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  multiple tinyint(1) NOT NULL default '0',
  options text NOT NULL,
  voters text NOT NULL,
  maxvotes smallint(6) unsigned NOT NULL default '0',
  totalvotes smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (pollid),
  KEY tid (tid)
);

DROP TABLE IF EXISTS cdb_posts;
CREATE TABLE cdb_posts (
  fid smallint(6) unsigned NOT NULL default '0',
  tid mediumint(8) unsigned NOT NULL default '0',
  pid int(10) unsigned NOT NULL auto_increment,
  aid mediumint(8) unsigned NOT NULL default '0',
  icon varchar(30) NOT NULL default '',
  author varchar(15) NOT NULL default '',
  subject varchar(100) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  message mediumtext NOT NULL,
  useip varchar(15) NOT NULL default '',
  usesig tinyint(1) NOT NULL default '0',
  bbcodeoff tinyint(1) NOT NULL default '0',
  smileyoff tinyint(1) NOT NULL default '0',
  parseurloff tinyint(1) NOT NULL default '0',
  rate smallint(6) NOT NULL default '0',
  ratetimes tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (pid),
  KEY fid (fid),
  KEY tid (tid,dateline),
  KEY dateline (dateline)
);

DROP TABLE IF EXISTS cdb_postpay;
CREATE TABLE cdb_postpay (
  id int(12) NOT NULL auto_increment,
  tid mediumint(8) NOT NULL default '0',
  pid int(10) unsigned NOT NULL default '0',
  sellcount smallint(3) unsigned NOT NULL default '0',
  author varchar(25) NOT NULL default '',
  username varchar(25) NOT NULL default '',
  money smallint(6) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY tid (tid)
) TYPE=MyISAM;

DROP TABLE IF EXISTS cdb_rank;
CREATE TABLE cdb_rank (
  rid int(10) unsigned NOT NULL auto_increment,
  ranktitle varchar(50) NOT NULL default '',
  posthigher int(20) NOT NULL default '0',
  rankstar int(10) unsigned NOT NULL default '1',
  rankcolor varchar(10) NOT NULL default '',
  PRIMARY KEY  (rid)
) TYPE=MyISAM AUTO_INCREMENT=6 ;

INSERT INTO cdb_rank VALUES (1, 'Beginner', 0, 1, '');
INSERT INTO cdb_rank VALUES (2, 'Poster', 50, 2, '');
INSERT INTO cdb_rank VALUES (3, 'Cool Poster', 300, 3, '');
INSERT INTO cdb_rank VALUES (4, 'Writer', 1000, 4, '');
INSERT INTO cdb_rank VALUES (5, 'Excellent Writer', 3000, 5, '');

DROP TABLE IF EXISTS cdb_searchindex;
CREATE TABLE cdb_searchindex (
  keywords varchar(200) NOT NULL default '',
  results int(10) unsigned NOT NULL default '0',
  dateline int(10) unsigned NOT NULL default '0',
  KEY dateline (dateline)
);

DROP TABLE IF EXISTS cdb_sessions;
CREATE TABLE cdb_sessions (
  sid varchar(8) binary NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  ipbanned tinyint(1) NOT NULL default '0',
  status enum('Guest','Member','Admin','SuperMod','Moderator','Banned','IPBanned','PostBanned','Inactive','vip') NOT NULL default 'Guest',
  username varchar(15) NOT NULL default '',
  lastactivity int(10) unsigned NOT NULL default '0',
  groupid smallint(6) unsigned NOT NULL default '0',
  styleid smallint(6) unsigned NOT NULL default '0',
  action tinyint(1) unsigned NOT NULL default '0',
  fid smallint(6) unsigned NOT NULL default '0',
  tid mediumint(8) unsigned NOT NULL default '0',
  KEY sid (sid)
) TYPE=HEAP MAX_ROWS=1000;

DROP TABLE IF EXISTS cdb_settings;
CREATE TABLE cdb_settings (
  bbname varchar(50) NOT NULL default '',
  regstatus tinyint(1) NOT NULL default '0',
  censoruser text NOT NULL,
  doublee tinyint(1) NOT NULL default '0',
  regverify tinyint(1) NOT NULL default '0',
  bbrules tinyint(1) NOT NULL default '0',
  bbrulestxt text NOT NULL,
  welcommsg tinyint(1) NOT NULL default '0',
  welcommsgtxt text NOT NULL,
  bbclosed tinyint(1) NOT NULL default '0',
  closedreason text NOT NULL,
  sitename varchar(50) NOT NULL default '',
  siteurl varchar(60) NOT NULL default '',
  moddisplay enum('flat','selectbox') NOT NULL default 'flat',
  styleid smallint(6) unsigned NOT NULL default '0',
  maxonlines smallint(6) unsigned NOT NULL default '0',
  floodctrl smallint(6) unsigned NOT NULL default '0',
  searchctrl smallint(6) unsigned NOT NULL default '0',
  hottopic tinyint(3) unsigned NOT NULL default '0',
  topicperpage tinyint(3) unsigned NOT NULL default '0',
  postperpage tinyint(3) unsigned NOT NULL default '0',
  memberperpage tinyint(3) unsigned NOT NULL default '0',
  maxpostsize mediumint(8) unsigned NOT NULL default '0',
  maxavatarsize tinyint(3) unsigned NOT NULL default '0',
  smcols tinyint(3) unsigned NOT NULL default '0',
  logincredits tinyint(3) unsigned NOT NULL default '0',
  postcredits tinyint(3) unsigned NOT NULL default '0',
  digestcredits tinyint(3) unsigned NOT NULL default '0',
  whosonlinestatus tinyint(1) NOT NULL default '0',
  vtonlinestatus tinyint(1) NOT NULL default '0',
  gzipcompress tinyint(1) NOT NULL default '0',
  hideprivate tinyint(1) NOT NULL default '0',
  fastpost tinyint(1) NOT NULL default '0',
  modshortcut tinyint(1) NOT NULL default '0',
  memliststatus tinyint(1) NOT NULL default '0',
  statstatus tinyint(1) NOT NULL default '0',
  debug tinyint(1) NOT NULL default '0',
  reportpost tinyint(1) NOT NULL default '0',
  bbinsert tinyint(1) NOT NULL default '0',
  smileyinsert tinyint(1) NOT NULL default '0',
  editedby tinyint(1) NOT NULL default '0',
  dotfolders tinyint(1) NOT NULL default '0',
  attachsave tinyint(1) NOT NULL default '0',
  attachimgpost tinyint(1) NOT NULL default '0',
  timeoffset varchar(5) NOT NULL default '',
  timeformat varchar(5) NOT NULL default '',
  dateformat varchar(10) NOT NULL default '',
  version varchar(100) NOT NULL default '',
  onlinerecord varchar(30) NOT NULL default '',
  totalmembers smallint(6) unsigned NOT NULL default '0',
  lastmember varchar(15) NOT NULL default ''
);

INSERT INTO cdb_settings VALUES ('Discuz! Plus', 1, '', 1, 0, 0, '', 0, '', 0, '', 'HKLCF Studio', 'http://www.hklcf.com/', 'flat', 1, 1000, 15, 5, 10, 20, 10, 25, 10000, 0, 3, 0, 1, 10, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 0, 1, '8', 'h:i A', 'Y-n-j', '1.1.4', '1	1040034649', 1, 'HKLCF');

DROP TABLE IF EXISTS cdb_smilies;
CREATE TABLE cdb_smilies (
  id smallint(6) unsigned NOT NULL auto_increment,
  type enum('smiley','picon') NOT NULL default 'smiley',
  code varchar(10) NOT NULL default '',
  url varchar(30) NOT NULL default '',
  PRIMARY KEY  (id)
);

INSERT INTO cdb_smilies VALUES('1','smiley',':)','smile.gif');
INSERT INTO cdb_smilies VALUES('2','smiley',':(','sad.gif');
INSERT INTO cdb_smilies VALUES('3','smiley',':D','biggrin.gif');
INSERT INTO cdb_smilies VALUES('4','smiley',';)','wink.gif');
INSERT INTO cdb_smilies VALUES('5','smiley',':cool:','cool.gif');
INSERT INTO cdb_smilies VALUES('6','smiley',':mad:','mad.gif');
INSERT INTO cdb_smilies VALUES('7','smiley',':o','shocked.gif');
INSERT INTO cdb_smilies VALUES('8','smiley',':P','tongue.gif');
INSERT INTO cdb_smilies VALUES('9','smiley',':lol:','lol.gif');
INSERT INTO cdb_smilies VALUES('10','picon','','icon1.gif');
INSERT INTO cdb_smilies VALUES('11','picon','','icon2.gif');
INSERT INTO cdb_smilies VALUES('12','picon','','icon3.gif');
INSERT INTO cdb_smilies VALUES('13','picon','','icon4.gif');
INSERT INTO cdb_smilies VALUES('14','picon','','icon5.gif');
INSERT INTO cdb_smilies VALUES('15','picon','','icon6.gif');
INSERT INTO cdb_smilies VALUES('16','picon','','icon7.gif');
INSERT INTO cdb_smilies VALUES('17','picon','','icon8.gif');
INSERT INTO cdb_smilies VALUES('18','picon','','icon9.gif');

DROP TABLE IF EXISTS cdb_stats;
CREATE TABLE cdb_stats (
  type varchar(20) NOT NULL default '',
  var varchar(20) NOT NULL default '',
  count int(10) unsigned NOT NULL default '0',
  KEY type (type),
  KEY var (var)
);

INSERT INTO cdb_stats VALUES ('total', 'hits', 0);
INSERT INTO cdb_stats VALUES ('total', 'members', 0);
INSERT INTO cdb_stats VALUES ('total', 'guests', 0);
INSERT INTO cdb_stats VALUES ('os', 'Windows', 0);
INSERT INTO cdb_stats VALUES ('os', 'Mac', 0);
INSERT INTO cdb_stats VALUES ('os', 'Linux', 0);
INSERT INTO cdb_stats VALUES ('os', 'FreeBSD', 0);
INSERT INTO cdb_stats VALUES ('os', 'SunOS', 0);
INSERT INTO cdb_stats VALUES ('os', 'BeOS', 0);
INSERT INTO cdb_stats VALUES ('os', 'OS/2', 0);
INSERT INTO cdb_stats VALUES ('os', 'AIX', 0);
INSERT INTO cdb_stats VALUES ('os', 'Other', 0);
INSERT INTO cdb_stats VALUES ('browser', 'MSIE', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Netscape', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Mozilla', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Lynx', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Opera', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Konqueror', 0);
INSERT INTO cdb_stats VALUES ('browser', 'Other', 0);
INSERT INTO cdb_stats VALUES ('week', '0', 0);
INSERT INTO cdb_stats VALUES ('week', '1', 0);
INSERT INTO cdb_stats VALUES ('week', '2', 0);
INSERT INTO cdb_stats VALUES ('week', '3', 0);
INSERT INTO cdb_stats VALUES ('week', '4', 0);
INSERT INTO cdb_stats VALUES ('week', '5', 0);
INSERT INTO cdb_stats VALUES ('week', '6', 0);
INSERT INTO cdb_stats VALUES ('hour', '00', 0);
INSERT INTO cdb_stats VALUES ('hour', '01', 0);
INSERT INTO cdb_stats VALUES ('hour', '02', 0);
INSERT INTO cdb_stats VALUES ('hour', '03', 0);
INSERT INTO cdb_stats VALUES ('hour', '04', 0);
INSERT INTO cdb_stats VALUES ('hour', '05', 0);
INSERT INTO cdb_stats VALUES ('hour', '06', 0);
INSERT INTO cdb_stats VALUES ('hour', '07', 0);
INSERT INTO cdb_stats VALUES ('hour', '08', 0);
INSERT INTO cdb_stats VALUES ('hour', '09', 0);
INSERT INTO cdb_stats VALUES ('hour', '10', 0);
INSERT INTO cdb_stats VALUES ('hour', '11', 0);
INSERT INTO cdb_stats VALUES ('hour', '12', 0);
INSERT INTO cdb_stats VALUES ('hour', '13', 0);
INSERT INTO cdb_stats VALUES ('hour', '14', 0);
INSERT INTO cdb_stats VALUES ('hour', '15', 0);
INSERT INTO cdb_stats VALUES ('hour', '16', 0);
INSERT INTO cdb_stats VALUES ('hour', '17', 0);
INSERT INTO cdb_stats VALUES ('hour', '18', 0);
INSERT INTO cdb_stats VALUES ('hour', '19', 0);
INSERT INTO cdb_stats VALUES ('hour', '20', 0);
INSERT INTO cdb_stats VALUES ('hour', '21', 0);
INSERT INTO cdb_stats VALUES ('hour', '22', 0);
INSERT INTO cdb_stats VALUES ('hour', '23', 0);

DROP TABLE IF EXISTS cdb_styles;
CREATE TABLE cdb_styles (
  styleid smallint(6) unsigned NOT NULL auto_increment,
  name varchar(20) NOT NULL default '',
  available tinyint(1) NOT NULL default '1',
  templateid smallint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (styleid),
  KEY themename (name)
);

INSERT INTO cdb_styles VALUES (1, 'Default Style', 1, 1);

DROP TABLE IF EXISTS cdb_stylevars;
CREATE TABLE cdb_stylevars (
  stylevarid smallint(6) unsigned NOT NULL auto_increment,
  styleid smallint(6) unsigned NOT NULL default '0',
  variable text NOT NULL,
  substitute text NOT NULL,
  PRIMARY KEY  (stylevarid),
  KEY styleid (styleid)
);

INSERT INTO cdb_stylevars VALUES (1, 1, 'bgcolor', '#9EB6D8');
INSERT INTO cdb_stylevars VALUES (2, 1, 'altbg1', '#F8F8F8');
INSERT INTO cdb_stylevars VALUES (3, 1, 'altbg2', '#FFFFFF');
INSERT INTO cdb_stylevars VALUES (4, 1, 'link', '#003366');
INSERT INTO cdb_stylevars VALUES (5, 1, 'bordercolor', '#698CC3');
INSERT INTO cdb_stylevars VALUES (6, 1, 'headercolor', '#698CC3');
INSERT INTO cdb_stylevars VALUES (7, 1, 'headertext', '#FFFFFF');
INSERT INTO cdb_stylevars VALUES (8, 1, 'catcolor', '#EFEFEF');
INSERT INTO cdb_stylevars VALUES (9, 1, 'tabletext', '#000000');
INSERT INTO cdb_stylevars VALUES (10, 1, 'text', '#000000');
INSERT INTO cdb_stylevars VALUES (11, 1, 'borderwidth', '1');
INSERT INTO cdb_stylevars VALUES (12, 1, 'tablewidth', '98%');
INSERT INTO cdb_stylevars VALUES (13, 1, 'tablespace', '4');
INSERT INTO cdb_stylevars VALUES (14, 1, 'font', 'Tahoma, Verdana');
INSERT INTO cdb_stylevars VALUES (15, 1, 'fontsize', '12px');
INSERT INTO cdb_stylevars VALUES (16, 1, 'nobold', '0');
INSERT INTO cdb_stylevars VALUES (17, 1, 'boardimg', 'logo.gif');
INSERT INTO cdb_stylevars VALUES (18, 1, 'imgdir', 'images/default');
INSERT INTO cdb_stylevars VALUES (19, 1, 'smdir', 'images/smilies');
INSERT INTO cdb_stylevars VALUES (20, 1, 'cattext', '#000000');
INSERT INTO cdb_stylevars VALUES (21, 1, 'smfontsize', '11px');
INSERT INTO cdb_stylevars VALUES (22, 1, 'smfont', 'Tahoma, Verdana');

DROP TABLE IF EXISTS cdb_subscriptions;
CREATE TABLE cdb_subscriptions (
  username varchar(15) NOT NULL default '',
  email varchar(60) NOT NULL default '',
  tid mediumint(8) unsigned NOT NULL default '0',
  lastnotify int(10) unsigned NOT NULL default '0',
  KEY username (username),
  KEY tid (tid)
);

DROP TABLE IF EXISTS cdb_templates;
CREATE TABLE cdb_templates (
  templateid smallint(6) unsigned NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  charset varchar(30) NOT NULL default '',
  directory varchar(100) NOT NULL default '',
  copyright varchar(100) NOT NULL default '',
  PRIMARY KEY  (templateid)
);

INSERT INTO cdb_templates VALUES (1, 'Default', 'gb2312', './templates/default', 'Designed by HKLCF(hklcf.com)');

DROP TABLE IF EXISTS cdb_threads;
CREATE TABLE cdb_threads (
  tid mediumint(8) unsigned NOT NULL auto_increment,
  fid smallint(6) NOT NULL default '0',
  creditsrequire smallint(6) unsigned NOT NULL default '0',
  icon varchar(30) NOT NULL default '',
  author varchar(15) NOT NULL default '',
  subject varchar(100) NOT NULL default '',
  dateline int(10) unsigned NOT NULL default '0',
  lastpost int(10) unsigned NOT NULL default '0',
  lastposter varchar(15) NOT NULL default '',
  views smallint(6) unsigned NOT NULL default '0',
  replies smallint(6) unsigned NOT NULL default '0',
  topped tinyint(1) NOT NULL default '0',
  digest tinyint(1) NOT NULL default '0',
  closed varchar(15) NOT NULL default '',
  pollopts text NOT NULL,
  attachment varchar(50) NOT NULL default '',
  PRIMARY KEY  (tid),
  KEY lastpost (topped,lastpost,fid)
);

DROP TABLE IF EXISTS cdb_usergroups;
CREATE TABLE cdb_usergroups (
  groupid smallint(6) unsigned NOT NULL auto_increment,
  specifiedusers text NOT NULL,
  status enum('Guest','Member','Admin','SuperMod','Moderator','Banned','IPBanned','PostBanned','Inactive','vip') NOT NULL default 'Member',
  grouptitle varchar(30) NOT NULL default '',
  creditshigher int(10) NOT NULL default '0',
  creditslower int(10) NOT NULL default '0',
  stars tinyint(3) NOT NULL default '0',
  groupavatar varchar(60) NOT NULL default '',
  allowcstatus tinyint(1) NOT NULL default '0',
  allowavatar tinyint(1) NOT NULL default '0',
  allowvisit tinyint(1) NOT NULL default '0',
  allowview tinyint(1) NOT NULL default '0',
  allowpost tinyint(1) NOT NULL default '0',
  allowpostpoll tinyint(1) NOT NULL default '0',
  allowgetattach tinyint(1) NOT NULL default '0',
  allowpostattach tinyint(1) NOT NULL default '0',
  allowvote tinyint(1) NOT NULL default '0',
  allowsearch tinyint(1) NOT NULL default '0',
  allowkarma tinyint(1) NOT NULL default '0',
  allowsetviewperm tinyint(1) NOT NULL default '0',
  allowsetattachperm tinyint(1) NOT NULL default '0',
  allowsigbbcode tinyint(1) NOT NULL default '0',
  allowsigimgcode tinyint(1) NOT NULL default '0',
  allowviewstats tinyint(1) NOT NULL default '0',
  ismoderator tinyint(1) NOT NULL default '0',
  issupermod tinyint(1) NOT NULL default '0',
  isadmin tinyint(1) NOT NULL default '0',
  maxpmnum smallint(6) unsigned NOT NULL default '0',
  maxmemonum smallint(6) unsigned NOT NULL default '0',
  maxsigsize smallint(6) unsigned NOT NULL default '0',
  maxkarmarate tinyint(3) unsigned NOT NULL default '0',
  maxrateperday smallint(6) unsigned NOT NULL default '0',
  maxattachsize int(10) unsigned NOT NULL default '0',
  attachextensions tinytext NOT NULL,
  PRIMARY KEY  (groupid),
  KEY status (status),
  KEY creditshigher (creditshigher),
  KEY creditslower (creditslower)
);

INSERT INTO cdb_usergroups VALUES('1','','Guest','溼諦','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('2','','IPBanned','蚚誧IP掩輦砦','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('3','','Banned','輦砦溼恀','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('4','','PostBanned','輦砦楷晟','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('5','','Inactive','脹渾桄痐','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','50','0','0','0','');
INSERT INTO cdb_usergroups VALUES('6','','Moderator','唳翋','0','0','8','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','0','0','800','0','2000','80','800','2048000','');
INSERT INTO cdb_usergroups VALUES('7','','SuperMod','閉撰唳翋','0','0','9','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','0','1200','0','3000','90','900','2048000','');
INSERT INTO cdb_usergroups VALUES('8','','Admin','奪燴埜','0','0','10','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','1','2000','0','50000','100','10000','4294967295','');
INSERT INTO cdb_usergroups VALUES('9','','Member','拻陎啞踢頗埜','1600','3500','5','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','300','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('10','','Member','鞠陎郰坒頗埜','3500','9999999','6','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','400','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('11','','Member','侐陎酴踢頗埜','800','1600','4','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','60','0','200','0','0','512000','');
INSERT INTO cdb_usergroups VALUES('12','','Member','蹦抭ゎ堣','-9999999','0','0','','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('13','','Member','��陎詢撰頗埜','300','800','3','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','50','0','150','0','0','256000','');
INSERT INTO cdb_usergroups VALUES('14','','Member','媼陎場撰頗埜','50','300','2','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','30','0','100','0','0','0','');
INSERT INTO cdb_usergroups VALUES('15','','Member','珨陎陔忒頗埜','0','50','1','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','20','0','80','0','0','0','');
INSERT INTO cdb_usergroups VALUES('16','','vip','VIP','0','0','7','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','600','0','1000','0','0','1024000','');

DROP TABLE IF EXISTS cdb_words;
CREATE TABLE cdb_words (
  id smallint(6) unsigned NOT NULL auto_increment,
  find varchar(60) NOT NULL default '',
  replacement varchar(60) NOT NULL default '',
  PRIMARY KEY  (id)
);

ALTER TABLE `$tablepre
members` ADD `money` INT(10) DEFAULT '100' NOT NULL, ADD `bank` INT(10) DEFAULT '0' NOT NULL, ADD `savemt` INT(10) DEFAULT '0' NOT NULL ;
ALTER TABLE `$tablepre
attachments` ADD `dl_users` TEXT NOT NULL ;
ALTER TABLE `$tablepre
attachments` ADD FULLTEXT (`dl_users`) ;
ALTER TABLE `$tablepre
threads` ADD `highlight` tinyint(1) default '0' NOT NULL ;
ALTER TABLE `$tablepre
members` CHANGE `credit` `credit` INT( 10 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `$tablepre
sessions` ADD `invisible` TINYINT( 1 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `$tablepre
members` ADD `secques` VARCHAR(8) NOT NULL ;
ALTER TABLE `$tablepre
forums` ADD namecolor varchar(7) NOT NULL default '#000000' ;
ALTER TABLE `$tablepre
forums` ADD descolor varchar(7) NOT NULL default '#000000' ;
ALTER TABLE `$tablepre
karmalog` CHANGE `score` `score` TINYINT( 3 ) DEFAULT '0' NOT NULL ;
ALTER TABLE `$tablepre
members` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Member' NOT NULL ;
ALTER TABLE `$tablepre
usergroups` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Member' NOT NULL ;
ALTER TABLE `$tablepre
sessions` CHANGE `status` `status` ENUM( 'Member', 'Admin', 'SuperMod', 'Moderator', 'Banned', 'PostBanned', 'Inactive', 'vip', 'IpBanned', 'Guest' ) DEFAULT 'Guest' NOT NULL ;

EOT;

	runquery($sql);
	$db->query("DELETE FROM {$tablepre}members");
	$db->query("INSERT INTO {$tablepre}members (username, password, status, regip, regdate, lastvisit, email, dateformat, timeformat, showemail, newsletter, timeoffset)
		VALUES ('$username', '".md5($password1)."', 'Admin', 'hidden', '".time()."', '".time()."', '$email', 'Y-n-j', 'h:i A', '1', '1', '8');");

echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> 場宎趙堍俴醴翹迵紫偶</font></b></td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";

loginit('karmalog');
loginit('illegallog');
loginit('modslog');
loginit('cplog');
dir_clear('./forumdata/templates');
dir_clear('./forumdata/cache');

?>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <font color="#FF0000"><b>鳩炰蠟ㄛDiscuz! Plus 假蚾傖髡ㄐ</font><br>
            奪燴埜梖瘍:</b><?=$username?><b> 奪燴埜躇鎢:</b><?=$password1?><br><br>
            <a href="index.php" target="_blank">萸僻涴爵輛�踶袽�</a>
          </td>
        </tr>
<?
}
?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td align="center">
            <b style="font-size: 11px">Powered by <a href="http://hklcf.com/" target="_blank">Discuz! Plus <?=$version?></a> , &nbsp; Copyright &copy; <a href="http://hklcf.com" target=\"_blank\">HKLCF Studio</a>, 2004</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
<?
}
?>
</body>
</html>