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
	echo '﹍て癘魁 '.$log;
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
				echo 'ミ戈 '.$name.' ... <font color="#0000EE">Θ</font><br>';
			}
			$db->query($query);
		}
	}
}

function result($result = 1, $output = 1) {
	if($result) {
		$text = '... <font color="#0000EE">Θ</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">ア毖</font><br>';
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
	echo '睲ヘ魁 '.$dir;
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
	<b>舧ㄓ Discuz! Plus Board 杆翾旧杆玡叫灿綷弄 license 郎–灿竊眤絋﹚Ч骸ì Discuz! Plus 甭舦某ぇ秨﹍杆readme 郎矗ㄑΤ闽硁砰杆弧叫眤妓灿綷弄玂靡杆秈祘抖秈︽</b>
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
舦┮Τ (c) 2004HKLCF.COM
玂痙┮Τ舦

    稰谅匡拒 Discuz! Plus 阶韭玻珇辨и矗ㄑ蔼е硉㎝眏 web 阶韭秆∕よ

    Discuz! Plus  HKLCF.COM 縒ミ秨祇场みм砃耴妮 HKLCF.COM ┮Τ

    Discuz! Plus み珹 Discuz! 3.x , 2.x , PHPWind , phpbb , 3Q , IPB の UNet.Boards 单单
EOT;

	$discuz_licence = str_replace('  ', '&nbsp; ', nl2br($discuz_licence));

?>
        <tr> 
          <td><b>讽玡篈</b><font color="#0000EE">Discuz! Plus 穦砛某</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 叫眤叭ゲ灿綷弄砛某</font></b></td>
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
              <input type="submit" name="submit" value="иЧ種" style="height: 25">&nbsp;
              <input type="button" name="exit" value="иぃ種" style="height: 25" onclick="javascript: window.close();">
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
		$config_info = '眤 config.php ぃ 礚猭膥尿杆 叫ノ FTP 盢赣ゅン肚刚';
	} elseif(!$write_error) {
		$config_info = '叫恶糶眤戈畐眀腹癟 硄盽薄猵叫ぃ璶э︹匡兜ず甧';
	} elseif($write_error) {
		$config_info = '杆翾旧礚猭糶皌竚ゅン 叫癸瞷Τ癟 惠э 叫硄筁 FTP 盢э config.php 肚';
	}

?>
        <tr> 
          <td><b>讽玡篈</b><font color="#0000EE">皌竚 config.php</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 浪琩皌竚ゅン篈</font></b></td>
        </tr>
        <tr>
          <td>config.php 浪琩 <?=$fileexists?></td>
        </tr>
        <tr>
          <td>config.php 糶浪琩 <?=$filewriteable?></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 聅凝/絪胯讽玡皌竚</font></b></td>
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
                  <td align="center" width="20%" style="color: #FFFFFF">砞竚匡兜</td>
                  <td align="center" width="35%" style="color: #FFFFFF">讽玡</td>
                  <td align="center" width="45%" style="color: #FFFFFF">爹睦</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;戈畐狝竟:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?=$dbhost?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;戈畐狝竟,  localhost</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;戈畐穦:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?=$dbuser?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;戈畐姐腹穦</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;戈畐盞絏:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?=$dbpw?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;戈畐姐腹盞絏</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;戈畐:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbname" value="<?=$dbname?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;戈畐嘿</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;╰参 Email:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="adminemail" value="<?=$adminemail?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;ノ祇癳祘Α岿粇厨</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;玡后:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="tablepre" value="<?=$tablepre?>" size="30" onClick="javascript: alert('杆翾旧矗ボ:\n\n埃獶眤惠璶戈畐杆 Discuz! \n阶韭,玥,眏疨某眤ぃ璶э玡后.');"></td>
                  <td bgcolor="#E3E3EA">&nbsp;戈畐杆阶韭э跑箇砞</td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="environment">
              <input type="hidden" name="saveconfig" value="1">
              <input type="submit" name="submit" value="玂皌竚癟" style="height: 25">
              <input type="button" name="exit" value="癶杆翾旧" style="height: 25" onclick="javascript: window.close();">
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
                <td align="center" style="color: #FFFFFF">跑秖</td>
                <td align="center" style="color: #FFFFFF">讽玡</td>
                <td align="center" style="color: #FFFFFF">爹睦</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbhost</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbhost?></td>
                <td bgcolor="#E3E3EA" align="center">戈畐狝竟,  localhost</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbuser</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbuser?></td>
                <td bgcolor="#E3E3EA" align="center">戈畐姐腹穦</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbpw</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpw?></td>
                <td bgcolor="#E3E3EA" align="center">戈畐姐腹盞絏</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbname</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbname?></td>
                <td bgcolor="#E3E3EA" align="center">戈畐嘿</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$adminemail</td>
                <td bgcolor="#EEEEF6" align="center"><?=$adminemail?></td>
                <td bgcolor="#E3E3EA" align="center">╰参 Email</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$tablepre</td>
                <td bgcolor="#EEEEF6" align="center"><?=$tablepre?></td>
                <td bgcolor="#E3E3EA" align="center">戈玡后</td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td align="center">
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>">
              <input type="hidden" name="action" value="environment">
              <input type="submit" name="submit" value="瓃皌竚タ絋" style="height: 25">
              <input type="button" name="exit" value="穝俱瞶э挡狦" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_big5'?>?action=config');">
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
              <input type="submit" name="submit" value="穝浪琩砞竚" style="height: 25">
              <input type="button" name="exit" value="癶" style="height: 25" onclick="javascript: window.close();">
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
		$msg .= "<font color=\"#FF0000\">眤 PHP セ 4.0.6 礚猭ㄏノ Discuz! Plus</font>\t";
		$quit = TRUE;
	} elseif($curr_php_version < '4.0.6') {
		$msg .= "<font color=\"#FF0000\">眤 PHP セ 4.0.6 礚猭ㄏノ繷钩へ浪琩㎝ gzip 溃罽</font>\t";
	}

	if(@ini_get(file_uploads)) {
		$max_size = @ini_get(upload_max_filesize);
		$curr_upload_status = "す砛/程へ $max_size";
		$msg .= "眤肚へ $max_size ンゅン.\t";
	} else {
		$curr_upload_status = 'ぃす砛肚ン';
		$msg .= "<font color=\"#FF0000\">パ狝竟綛姜 眤礚猭ㄏノン</font>\t";
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
		$msg .= "<font color=\"#FF0000\">眤 MySQL セ 3.23 Discuz! Plus ㄇ礚猭タ盽ㄏノ</font>\t";
	}

	$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

	if(dir_writeable('./templates')) {
		$curr_tpl_writeable = '糶';
	} else {
		$curr_tpl_writeable = 'ぃ糶';
		$msg .= "<font color=\"#FF0000\">家狾 ./templates ヘ魁妮┦獶 777 ┪礚猭糶 礚猭ㄏノ絬絪胯家狾㎝旧</font>\t";
	}

	if(dir_writeable($attachdir)) {
		$curr_attach_writeable = '糶';
	} else {
		$curr_attach_writeable = 'ぃ糶';
		$msg .= "<font color=\"#FF0000\">ン $attachdir ヘ魁妮┦獶 777 ┪礚猭糶 礚猭ㄏノン</font>\t";
	}

	if(dir_writeable('./customavatars/')) {
		$curr_head_writeable = '糶';
	} else {
		$curr_head_writeable = 'ぃ糶';
		$msg .= "<font color=\"#FF0000\">肚繷钩 ./customavatars ヘ魁妮┦獶 777 ┪礚猭糶 礚猭ㄏノ肚繷钩</font>\t";
	}

	if(dir_writeable('./forumdata/')) {
		$curr_data_writeable = '糶';
	} else {
		$curr_data_writeable = 'ぃ糶';
		$msg .= "<font color=\"#FF0000\">戈 ./forumdata ヘ魁妮┦獶 777 ┪礚猭糶 礚猭ㄏノ称狝竟/阶韭笲︽癘魁单</font>\t";
	}

	if(dir_writeable('./forumdata/templates/')) {
		$curr_template_writeable = '糶';
	} else {
		$curr_template_writeable = 'ぃ糶';
		$msg .= "<font color=\"#FF0000\">家狾 ./forumdata/templates ヘ魁妮┦獶 777 ┪礚猭糶 礚猭杆 Discuz! Plus</font>\t";
		$quit = TRUE;
	}

	if(dir_writeable('./forumdata/cache/')) {
		$curr_cache_writeable = '糶';
	} else {
		$curr_cache_writeable = 'ぃ糶';
		$msg .= "<font color=\"#FF0000\">絯 ./forumdata/cache ヘ魁妮┦獶 777 ┪礚猭糶 礚猭杆 Discuz! Plus</font>\t";
		$quit = TRUE;
	}

	$db->select_db($dbname);
	if($db->error()) {
		$db->query("CREATE DATABASE $dbname");
		if($db->error()) {
			$msg .= "<font color=\"#FF0000\">﹚戈畐 $dbname ぃ ╰参礚猭笆ミ 礚猭杆 Discuz! Plus</font>\t";
			$quit = TRUE;
		} else {
			$db->select_db($dbname);
			$msg .= "﹚戈畐 $dbname ぃ ╰参Θミ 膥尿杆\t";
		}
	}

	$query - $db->query("SELECT COUNT(*) FROM $tablepre"."settings", 1);
	if(!$db->error()) {
		$msg .= "<font color=\"#FF0000\">戈畐い竒杆筁 Discuz! Plus 膥尿杆穦睲Τ戈</font>\t";
		$alert = " onSubmit=\"return confirm('膥尿杆穦睲场Τ戈眤絋﹚璶膥尿盾');\"";
	} else {
		$alert = '';
	}

	if($quit) {
		$msg .= "<font color=\"#FF0000\">パ眤ヘ魁妮┦┪狝竟皌竚, 礚猭膥尿杆 Discuz! Plus 叫灿綷弄杆弧</font>";
	} else {
		$msg .= "眤狝竟杆㎝ㄏノ Discuz! Plus 叫秈˙杆";
	}

?>
        <tr>
          <td><b>讽玡篈</b><font color="#0000EE">浪琩讽玡狝竟吏挂</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> Discuz! Plus ┮惠吏挂㎝讽玡狝竟皌竚癸ゑ</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="80%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center"></td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus ┮惠皌竚</td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus 程ㄎ皌竚</td>
                <td align="center" style="color: #FFFFFF">讽玡狝竟</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">巨╰参</td>
                <td bgcolor="#EEEEF6" align="center">ぃ</td>
                <td bgcolor="#E3E3EA" align="center">UNIX/Linux/FreeBSD</td>
                <td bgcolor="#E3E3EA" align="center"><?=$curr_os?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP セ</td>
                <td bgcolor="#EEEEF6" align="center">4.0.6+</td>
                <td bgcolor="#E3E3EA" align="center">5.0.1+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_php_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP : register_globals</td>
                <td bgcolor="#EEEEF6" align="center">OFF</td>
                <td bgcolor="#E3E3EA" align="center">OFF</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_gobals_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP : magic_quotes_gpc</td>
                <td bgcolor="#EEEEF6" align="center">ON</td>
                <td bgcolor="#E3E3EA" align="center">ON</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_quotes_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">ン肚</td>
                <td bgcolor="#EEEEF6" align="center">ぃ</td>
                <td bgcolor="#E3E3EA" align="center">す砛</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_upload_status?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">MySQL セ</td>
                <td bgcolor="#EEEEF6" align="center">3.23+</td>
                <td bgcolor="#E3E3EA" align="center">4.0.20+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_mysql_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">合盒丁</td>
                <td bgcolor="#EEEEF6" align="center">2M+</td>
                <td bgcolor="#E3E3EA" align="center">100M+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_disk_space?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./templates ヘ魁糶</td>
                <td bgcolor="#EEEEF6" align="center">ぃ</td>
                <td bgcolor="#E3E3EA" align="center">糶</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_tpl_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$attachdir?> ヘ魁糶</td>
                <td bgcolor="#EEEEF6" align="center">ぃ</td>
                <td bgcolor="#E3E3EA" align="center">糶</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_attach_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./customavatars ヘ魁糶</td>
                <td bgcolor="#EEEEF6" align="center">ぃ</td>
                <td bgcolor="#E3E3EA" align="center">糶</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_head_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata ヘ魁糶</td>
                <td bgcolor="#EEEEF6" align="center">ぃ</td>
                <td bgcolor="#E3E3EA" align="center">糶</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_data_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/templates ヘ魁糶</td>
                <td bgcolor="#EEEEF6" align="center">糶</td>
                <td bgcolor="#E3E3EA" align="center">糶</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_template_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/cache ヘ魁糶</td>
                <td bgcolor="#EEEEF6" align="center">糶</td>
                <td bgcolor="#E3E3EA" align="center">糶</td>
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 叫絋粄ЧΘ˙艼</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
              <li>盢溃罽い Discuz! Plus ヘ魁场郎㎝ヘ魁肚狝竟.</li>
              <li>э狝竟 config.php 郎続眤皌竚, Τ闽戈畐姐腹癟叫玹高眤丁狝叭矗ㄑ坝.</li>
              <li>狦眤ㄏノ獶 WINNT ╰参叫э妮┦:<br>&nbsp; &nbsp; <b>./templates</b> ヘ魁 777;&nbsp; &nbsp; <b><?=$attachdir?></b> ヘ魁 777;&nbsp; &nbsp; <b>./customavatars</b> ヘ魁 777;&nbsp; &nbsp; <b>./forumdata</b> ヘ魁 777; <br><b>&nbsp; &nbsp; ./forumdata/cache</b> ヘ魁 777;&nbsp; &nbsp; <b>./forumdata/templates</b> ヘ魁 777;<br></li>
              <li>絋粄 URL い <?=$attachurl?> 砐拜狝竟ヘ魁 <?=$attachdir?> ず甧.</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 杆翾旧矗ボ</font></b></td>
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
            <input type="button" name="refresh" value="穝浪琩砞竚" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_big5'?>?action=environment');">&nbsp;
            <input type="button" name="exit" value="癶" style="height: 25" onclick="javascript: window.close();">
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 砞竚恨瞶眀腹</font></b></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>"<?=$alert?>>
              <table width="300" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;恨瞶姐腹:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="username" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;恨瞶 Email:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="email" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;恨瞶盞絏:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password1" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;狡盞絏:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password2" size="30"></td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="install">
              <input type="submit" name="submit" value="秨﹍杆 Discuz! Plus" style="height: 25" >&nbsp;
              <input type="button" name="exit" value="癶杆翾旧" style="height: 25" onclick="javascript: window.close();">
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
          <td><b>讽玡篈</b><font color="#0000EE">浪琩恨瞶姐腹癟秨﹍杆 Discuz! Plus</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 浪琩恨瞶眀腹</font></b></td>
        </tr>
        <tr>
          <td>浪琩癟猭┦
<?

	$msg = '';
	if($username && $email && $password1 && $password2) {
		if($password1 != $password2) {
			$msg = "ㄢΩ块盞絏ぃ璓";
		} elseif(strlen($username) > 15) {
			$msg = "ノめ禬筁 15 じ";
		} elseif(preg_match("/^$|^c:\\con\\con$||[,\"\s\t\<\>]|^笴|^Guest/is", $username)) {
			$msg = "ノめ┪獶猭じ";
		} elseif(!strstr($email, '@') || $email != stripslashes($email) || $email != htmlspecialchars($email)) {
			$msg = "Email 礚";
		}
	} else {
		$msg = '癟⊿Τ恶糶Ч俱';
	}

	if($msg) { 

?>
            ... <font color="#FF0000">ア毖 <?=$msg?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="э" onclick="javascript: history.go(-1);">
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 匡拒戈畐</font></b></td>
        </tr>
<?
	include './config.php';
	include './include/db_'.$database.'.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$db->select_db($dbname);

echo"        <tr>\n";
echo"          <td>匡拒戈畐 $dbname ".result(1, 0)."</td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> ミ戈</font></b></td>\n";
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

INSERT INTO cdb_usergroups VALUES('1','','Guest','砐','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('2','','IPBanned','ノめIP砆窽ゎ','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('3','','Banned','窽ゎ砐拜','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('4','','PostBanned','窽ゎ祇ē','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('5','','Inactive','单喷靡','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','50','0','0','0','');
INSERT INTO cdb_usergroups VALUES('6','','Moderator','','0','0','8','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','0','0','800','0','2000','80','800','2048000','');
INSERT INTO cdb_usergroups VALUES('7','','SuperMod','禬','0','0','9','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','0','1200','0','3000','90','900','2048000','');
INSERT INTO cdb_usergroups VALUES('8','','Admin','恨瞶','0','0','10','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','1','2000','0','50000','100','10000','4294967295','');
INSERT INTO cdb_usergroups VALUES('9','','Member','き琍フ穦','1600','3500','5','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','300','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('10','','Member','せ琍苝ホ穦','3500','9999999','6','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','400','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('11','','Member','琍独穦','800','1600','4','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','60','0','200','0','0','512000','');
INSERT INTO cdb_usergroups VALUES('12','','Member','阶韭あ','-9999999','0','0','','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('13','','Member','琍蔼穦','300','800','3','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','50','0','150','0','0','256000','');
INSERT INTO cdb_usergroups VALUES('14','','Member','琍穦','50','300','2','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','30','0','100','0','0','0','');
INSERT INTO cdb_usergroups VALUES('15','','Member','琍穝も穦','0','50','1','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','20','0','80','0','0','0','');
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
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> ﹍て笲︽ヘ魁籔郎</font></b></td>\n";
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
            <font color="#FF0000"><b>尺眤Discuz! Plus 杆Θ</font><br>
            恨瞶姐腹:</b><?=$username?><b> 恨瞶盞絏:</b><?=$password1?><br><br>
            <a href="index.php" target="_blank">翴阑硂柑秈阶韭</a>
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
	echo '初始化记录 '.$log;
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
				echo '建立资料表 '.$name.' ... <font color="#0000EE">成功</font><br>';
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
		$text = '... <font color="#FF0000">失败</font><br>';
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
	echo '清空目录 '.$dir;
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
	<b>欢迎来到 Discuz! Plus Board 安装向导，安装前请仔细阅读 license 档的每个细节，在您确定可以完全满足 Discuz! Plus 的授权协议之后才能开始安装。readme 档提供了有关软体安装的说明，请您同样仔细阅读，以保证安装进程的顺利进行。</b>
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
版权所有 (c) 2004，HKLCF.COM
保留所有权利。

    感谢你选择 Discuz! Plus 论坛产品。希望我们的努力能为你提供一个高效快速和强大的 web 论坛解决方案。

    Discuz! Plus 为 HKLCF.COM 独立开发，全部核心技术归属 HKLCF.COM 所有。

    Discuz! Plus 的核心包括了 Discuz! 3.x , 2.x , PHPWind , phpbb , 3Q , IPB 及 UNet.Boards 等等．．．．．．
EOT;

	$discuz_licence = str_replace('  ', '&nbsp; ', nl2br($discuz_licence));

?>
        <tr> 
          <td><b>当前状态：</b><font color="#0000EE">Discuz! Plus 会员许可协议</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 请您务必仔细阅读下面的许可协议</font></b></td>
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
		$config_info = '您的 config.php 不存在， 无法继续安装， 请用 FTP 将该文件上传后再试。';
	} elseif(!$write_error) {
		$config_info = '请在下面填写您的资料库帐号讯息， 通常情况下请不要修改红色选项内容。';
	} elseif($write_error) {
		$config_info = '安装向导无法写入配置文件， 请核对现有讯息， 如需修改， 请通过 FTP 将改好的 config.php 上传。';
	}

?>
        <tr> 
          <td><b>当前状态：</b><font color="#0000EE">配置 config.php</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 检查配置文件状态</font></b></td>
        </tr>
        <tr>
          <td>config.php 存在检查 <?=$fileexists?></td>
        </tr>
        <tr>
          <td>config.php 可写检查 <?=$filewriteable?></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 浏览/编辑当前配置</font></b></td>
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
                  <td align="center" width="20%" style="color: #FFFFFF">设置选项</td>
                  <td align="center" width="35%" style="color: #FFFFFF">当前值</td>
                  <td align="center" width="45%" style="color: #FFFFFF">注释</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;资料库伺服器:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?=$dbhost?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;资料库伺服器地址, 一般为 localhost</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;资料库会员名:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?=$dbuser?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;资料库账号会员名</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;资料库密码:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?=$dbpw?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;资料库账号密码</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;资料库名:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbname" value="<?=$dbname?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;资料库名称</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;系统 Email:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="adminemail" value="<?=$adminemail?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;用于发送程式错误报告</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;表名前缀:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="tablepre" value="<?=$tablepre?>" size="30" onClick="javascript: alert('安装向导提示:\n\n除非您需要在同一资料库安装多个 Discuz! \n论坛,否则,强烈建议您不要修改表名前缀.');"></td>
                  <td bgcolor="#E3E3EA">&nbsp;同一资料库安装多论坛时可改变预设</td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="environment">
              <input type="hidden" name="saveconfig" value="1">
              <input type="submit" name="submit" value="保存配置讯息" style="height: 25">
              <input type="button" name="exit" value="退出安装向导" style="height: 25" onclick="javascript: window.close();">
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
                <td align="center" style="color: #FFFFFF">变量</td>
                <td align="center" style="color: #FFFFFF">当前值</td>
                <td align="center" style="color: #FFFFFF">注释</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbhost</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbhost?></td>
                <td bgcolor="#E3E3EA" align="center">资料库伺服器地址, 一般为 localhost</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbuser</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbuser?></td>
                <td bgcolor="#E3E3EA" align="center">资料库账号会员名</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbpw</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpw?></td>
                <td bgcolor="#E3E3EA" align="center">资料库账号密码</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbname</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbname?></td>
                <td bgcolor="#E3E3EA" align="center">资料库名称</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$adminemail</td>
                <td bgcolor="#EEEEF6" align="center"><?=$adminemail?></td>
                <td bgcolor="#E3E3EA" align="center">系统 Email</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$tablepre</td>
                <td bgcolor="#EEEEF6" align="center"><?=$tablepre?></td>
                <td bgcolor="#E3E3EA" align="center">资料表名前缀</td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td align="center">
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>">
              <input type="hidden" name="action" value="environment">
              <input type="submit" name="submit" value="上述配置正确" style="height: 25">
              <input type="button" name="exit" value="重新整理修改结果" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_gb2312'?>?action=config');">
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
              <input type="submit" name="submit" value="重新检查设置" style="height: 25">
              <input type="button" name="exit" value="癶" style="height: 25" onclick="javascript: window.close();">
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
		$msg .= "<font color=\"#FF0000\">您的 PHP 版本小于 4.0.6， 无法使用 Discuz! Plus。</font>\t";
		$quit = TRUE;
	} elseif($curr_php_version < '4.0.6') {
		$msg .= "<font color=\"#FF0000\">您的 PHP 版本小于 4.0.6， 无法使用头像尺寸检查和 gzip 压缩功能。</font>\t";
	}

	if(@ini_get(file_uploads)) {
		$max_size = @ini_get(upload_max_filesize);
		$curr_upload_status = "允许/最大尺寸 $max_size";
		$msg .= "您可以上传尺寸在 $max_size 以下的附件文件.\t";
	} else {
		$curr_upload_status = '不允许上传附件';
		$msg .= "<font color=\"#FF0000\">由于伺服器遮蔽， 您无法使用附件功能。</font>\t";
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
		$msg .= "<font color=\"#FF0000\">您的 MySQL 版本低于 3.23， Discuz! Plus 的一些功能可能无法正常使用。</font>\t";
	}

	$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

	if(dir_writeable('./templates')) {
		$curr_tpl_writeable = '可写';
	} else {
		$curr_tpl_writeable = '不可写';
		$msg .= "<font color=\"#FF0000\">模板 ./templates 目录属性非 777 或无法写入， 无法使用线上编辑模板和风格导入。</font>\t";
	}

	if(dir_writeable($attachdir)) {
		$curr_attach_writeable = '可写';
	} else {
		$curr_attach_writeable = '不可写';
		$msg .= "<font color=\"#FF0000\">附件 $attachdir 目录属性非 777 或无法写入， 无法使用附件功能。</font>\t";
	}

	if(dir_writeable('./customavatars/')) {
		$curr_head_writeable = '可写';
	} else {
		$curr_head_writeable = '不可写';
		$msg .= "<font color=\"#FF0000\">上传头像 ./customavatars 目录属性非 777 或无法写入， 无法使用上传头像功能。</font>\t";
	}

	if(dir_writeable('./forumdata/')) {
		$curr_data_writeable = '可写';
	} else {
		$curr_data_writeable = '不可写';
		$msg .= "<font color=\"#FF0000\">资料 ./forumdata 目录属性非 777 或无法写入， 无法使用备份到伺服器/论坛运行记录等功能。</font>\t";
	}

	if(dir_writeable('./forumdata/templates/')) {
		$curr_template_writeable = '可写';
	} else {
		$curr_template_writeable = '不可写';
		$msg .= "<font color=\"#FF0000\">模板 ./forumdata/templates 目录属性非 777 或无法写入， 无法安装 Discuz! Plus。</font>\t";
		$quit = TRUE;
	}

	if(dir_writeable('./forumdata/cache/')) {
		$curr_cache_writeable = '可写';
	} else {
		$curr_cache_writeable = '不可写';
		$msg .= "<font color=\"#FF0000\">缓存 ./forumdata/cache 目录属性非 777 或无法写入， 无法安装 Discuz! Plus。</font>\t";
		$quit = TRUE;
	}

	$db->select_db($dbname);
	if($db->error()) {
		$db->query("CREATE DATABASE $dbname");
		if($db->error()) {
			$msg .= "<font color=\"#FF0000\">指定的资料库 $dbname 不存在， 系统也无法自动建立， 无法安装 Discuz! Plus。</font>\t";
			$quit = TRUE;
		} else {
			$db->select_db($dbname);
			$msg .= "指定的资料库 $dbname 不存在， 但系统已成功建立， 可以继续安装。\t";
		}
	}

	$query - $db->query("SELECT COUNT(*) FROM $tablepre"."settings", 1);
	if(!$db->error()) {
		$msg .= "<font color=\"#FF0000\">资料库中已经安装过 Discuz! Plus， 继续安装会清空原有资料。</font>\t";
		$alert = " onSubmit=\"return confirm('继续安装会清空全部原有资料，您确定要继续吗？');\"";
	} else {
		$alert = '';
	}

	if($quit) {
		$msg .= "<font color=\"#FF0000\">由于您目录属性或伺服器配置原因, 无法继续安装 Discuz! Plus， 请仔细阅读安装说明。</font>";
	} else {
		$msg .= "您的伺服器可以安装和使用 Discuz! Plus， 请进入下一步安装。";
	}

?>
        <tr>
          <td><b>当前状态：</b><font color="#0000EE">检查当前伺服器环境</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> Discuz! Plus 所需环境和当前伺服器配置对比</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="80%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center"></td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus 所需配置</td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus 最佳配置</td>
                <td align="center" style="color: #FFFFFF">当前伺服器</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">操作系统</td>
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
                <td bgcolor="#E3E3EA" align="center">附件上传</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">允许</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_upload_status?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">MySQL 版本</td>
                <td bgcolor="#EEEEF6" align="center">3.23+</td>
                <td bgcolor="#E3E3EA" align="center">4.0.20+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_mysql_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">磁碟空间</td>
                <td bgcolor="#EEEEF6" align="center">2M+</td>
                <td bgcolor="#E3E3EA" align="center">100M+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_disk_space?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./templates 目录写入</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">可写</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_tpl_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$attachdir?> 目录写入</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">可写</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_attach_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./customavatars 目录写入</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">可写</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_head_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata 目录写入</td>
                <td bgcolor="#EEEEF6" align="center">不限</td>
                <td bgcolor="#E3E3EA" align="center">可写</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_data_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/templates 目录写入</td>
                <td bgcolor="#EEEEF6" align="center">可写</td>
                <td bgcolor="#E3E3EA" align="center">可写</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_template_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/cache 目录写入</td>
                <td bgcolor="#EEEEF6" align="center">可写</td>
                <td bgcolor="#E3E3EA" align="center">可写</td>
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 请确认已完成如下步骤</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
              <li>将压缩包中 Discuz! Plus 目录下全部档案和目录上传到伺服器.</li>
              <li>修改伺服器上的 config.php 档案以适合您的配置, 有关资料库账号讯息请咨询您的空间服务提供商.</li>
              <li>如果您使用非 WINNT 系统请修改以下属性:<br>&nbsp; &nbsp; <b>./templates</b> 目录 777;&nbsp; &nbsp; <b><?=$attachdir?></b> 目录 777;&nbsp; &nbsp; <b>./customavatars</b> 目录 777;&nbsp; &nbsp; <b>./forumdata</b> 目录 777; <br><b>&nbsp; &nbsp; ./forumdata/cache</b> 目录 777;&nbsp; &nbsp; <b>./forumdata/templates</b> 目录 777;<br></li>
              <li>确认 URL 中 <?=$attachurl?> 可以访问伺服器目录 <?=$attachdir?> 内容.</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 安装向导提示</font></b></td>
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
            <input type="button" name="refresh" value="重新检查设置" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_gb2312'?>?action=environment');">&nbsp;
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 设置管理员帐号</font></b></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>"<?=$alert?>>
              <table width="300" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;管理员账号:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="username" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;管理员 Email:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="email" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;管理员密码:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password1" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;重复密码:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password2" size="30"></td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="install">
              <input type="submit" name="submit" value="开始安装 Discuz! Plus" style="height: 25" >&nbsp;
              <input type="button" name="exit" value="退出安装向导" style="height: 25" onclick="javascript: window.close();">
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
          <td><b>当前状态：</b><font color="#0000EE">检查管理员账号讯息并开始安装 Discuz! Plus。</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 检查管理员帐号</font></b></td>
        </tr>
        <tr>
          <td>检查讯息合法性
<?

	$msg = '';
	if($username && $email && $password1 && $password2) {
		if($password1 != $password2) {
			$msg = "两次输入密码不一致。";
		} elseif(strlen($username) > 15) {
			$msg = "用户名超过 15 个字元限制。";
		} elseif(preg_match("/^$|^c:\\con\\con$|　|[,\"\s\t\<\>]|^游客|^Guest/is", $username)) {
			$msg = "用户名空或包含非法字元。";
		} elseif(!strstr($email, '@') || $email != stripslashes($email) || $email != htmlspecialchars($email)) {
			$msg = "Email 地址无效";
		}
	} else {
		$msg = '你的讯息没有填写完整。';
	}

	if($msg) { 

?>
            ... <font color="#FF0000">失败。 原因：<?=$msg?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="返回上一页修改" onclick="javascript: history.go(-1);">
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> 选择资料库</font></b></td>
        </tr>
<?
	include './config.php';
	include './include/db_'.$database.'.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$db->select_db($dbname);

echo"        <tr>\n";
echo"          <td>选择资料库 $dbname ".result(1, 0)."</td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> 建立资料表</font></b></td>\n";
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

INSERT INTO cdb_usergroups VALUES('1','','Guest','访客','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('2','','IPBanned','用户IP被禁止','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('3','','Banned','禁止访问','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('4','','PostBanned','禁止发言','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('5','','Inactive','等待验证','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','50','0','0','0','');
INSERT INTO cdb_usergroups VALUES('6','','Moderator','版主','0','0','8','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','0','0','800','0','2000','80','800','2048000','');
INSERT INTO cdb_usergroups VALUES('7','','SuperMod','超级版主','0','0','9','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','0','1200','0','3000','90','900','2048000','');
INSERT INTO cdb_usergroups VALUES('8','','Admin','管理员','0','0','10','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','1','2000','0','50000','100','10000','4294967295','');
INSERT INTO cdb_usergroups VALUES('9','','Member','五星白金会员','1600','3500','5','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','300','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('10','','Member','六星钻石会员','3500','9999999','6','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','400','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('11','','Member','四星黄金会员','800','1600','4','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','60','0','200','0','0','512000','');
INSERT INTO cdb_usergroups VALUES('12','','Member','论坛乞丐','-9999999','0','0','','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('13','','Member','三星高级会员','300','800','3','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','50','0','150','0','0','256000','');
INSERT INTO cdb_usergroups VALUES('14','','Member','二星初级会员','50','300','2','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','30','0','100','0','0','0','');
INSERT INTO cdb_usergroups VALUES('15','','Member','一星新手会员','0','50','1','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','20','0','80','0','0','0','');
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
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> 初始化运行目录与档案</font></b></td>\n";
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
            <font color="#FF0000"><b>恭喜您，Discuz! Plus 安装成功！</font><br>
            管理员账号:</b><?=$username?><b> 管理员密码:</b><?=$password1?><br><br>
            <a href="index.php" target="_blank">点击这里进入论坛</a>
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