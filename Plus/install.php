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
	echo '��l�ưO�� '.$log;
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
				echo '�إ߸�ƪ� '.$name.' ... <font color="#0000EE">���\</font><br>';
			}
			$db->query($query);
		}
	}
}

function result($result = 1, $output = 1) {
	if($result) {
		$text = '... <font color="#0000EE">���\</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">����</font><br>';
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
	echo '�M�ťؿ� '.$dir;
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
	<b>�w��Ө� Discuz! Plus Board �w���Q�ɡA�w�˫e�ХJ�Ӿ\Ū license �ɪ��C�ӲӸ`�A�b�z�T�w�i�H�������� Discuz! Plus �����v��ĳ����~��}�l�w�ˡCreadme �ɴ��ѤF�����n��w�˪������A�бz�P�˥J�Ӿ\Ū�A�H�O�Ҧw�˶i�{�����Q�i��C</b>
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
���v�Ҧ� (c) 2004�AHKLCF.COM
�O�d�Ҧ��v�Q�C

    �P�§A��� Discuz! Plus �׾²��~�C�Ʊ�ڭ̪��V�O�ର�A���Ѥ@�Ӱ��ħֳt�M�j�j�� web �׾¸ѨM��סC

    Discuz! Plus �� HKLCF.COM �W�߶}�o�A�����֤ߧ޳N�k�� HKLCF.COM �Ҧ��C

    Discuz! Plus ���֤ߥ]�A�F Discuz! 3.x , 2.x , PHPWind , phpbb , 3Q , IPB �� UNet.Boards �����D�D�D�D�D�D
EOT;

	$discuz_licence = str_replace('  ', '&nbsp; ', nl2br($discuz_licence));

?>
        <tr> 
          <td><b>��e���A�G</b><font color="#0000EE">Discuz! Plus �|���\�i��ĳ</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> �бz�ȥ��J�Ӿ\Ū�U�����\�i��ĳ</font></b></td>
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
              <input type="submit" name="submit" value="�ڧ����P�N" style="height: 25">&nbsp;
              <input type="button" name="exit" value="�ڤ���P�N" style="height: 25" onclick="javascript: window.close();">
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
		$config_info = '�z�� config.php ���s�b�A �L�k�~��w�ˡA �Х� FTP �N�Ӥ��W�ǫ�A�աC';
	} elseif(!$write_error) {
		$config_info = '�Цb�U����g�z����Ʈw�b���T���A �q�`���p�U�Ф��n�ק����ﶵ���e�C';
	} elseif($write_error) {
		$config_info = '�w���Q�ɵL�k�g�J�t�m���A �Юֹ�{���T���A �p�ݭק�A �гq�L FTP �N��n�� config.php �W�ǡC';
	}

?>
        <tr> 
          <td><b>��e���A�G</b><font color="#0000EE">�t�m config.php</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> �ˬd�t�m��󪬺A</font></b></td>
        </tr>
        <tr>
          <td>config.php �s�b�ˬd <?=$fileexists?></td>
        </tr>
        <tr>
          <td>config.php �i�g�ˬd <?=$filewriteable?></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> �s��/�s���e�t�m</font></b></td>
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
                  <td align="center" width="20%" style="color: #FFFFFF">�]�m�ﶵ</td>
                  <td align="center" width="35%" style="color: #FFFFFF">��e��</td>
                  <td align="center" width="45%" style="color: #FFFFFF">����</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;��Ʈw���A��:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?=$dbhost?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;��Ʈw���A���a�}, �@�묰 localhost</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;��Ʈw�|���W:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?=$dbuser?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;��Ʈw�㸹�|���W</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;��Ʈw�K�X:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?=$dbpw?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;��Ʈw�㸹�K�X</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;��Ʈw�W:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbname" value="<?=$dbname?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;��Ʈw�W��</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;�t�� Email:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="adminemail" value="<?=$adminemail?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;�Ω�o�e�{�����~���i</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;��W�e��:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="tablepre" value="<?=$tablepre?>" size="30" onClick="javascript: alert('�w���Q�ɴ���:\n\n���D�z�ݭn�b�P�@��Ʈw�w�˦h�� Discuz! \n�׾�,�_�h,�j�P��ĳ�z���n�ק��W�e��.');"></td>
                  <td bgcolor="#E3E3EA">&nbsp;�P�@��Ʈw�w�˦h�׾®ɥi���ܹw�]</td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="environment">
              <input type="hidden" name="saveconfig" value="1">
              <input type="submit" name="submit" value="�O�s�t�m�T��" style="height: 25">
              <input type="button" name="exit" value="�h�X�w���Q��" style="height: 25" onclick="javascript: window.close();">
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
                <td align="center" style="color: #FFFFFF">�ܶq</td>
                <td align="center" style="color: #FFFFFF">��e��</td>
                <td align="center" style="color: #FFFFFF">����</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbhost</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbhost?></td>
                <td bgcolor="#E3E3EA" align="center">��Ʈw���A���a�}, �@�묰 localhost</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbuser</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbuser?></td>
                <td bgcolor="#E3E3EA" align="center">��Ʈw�㸹�|���W</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbpw</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpw?></td>
                <td bgcolor="#E3E3EA" align="center">��Ʈw�㸹�K�X</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbname</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbname?></td>
                <td bgcolor="#E3E3EA" align="center">��Ʈw�W��</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$adminemail</td>
                <td bgcolor="#EEEEF6" align="center"><?=$adminemail?></td>
                <td bgcolor="#E3E3EA" align="center">�t�� Email</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$tablepre</td>
                <td bgcolor="#EEEEF6" align="center"><?=$tablepre?></td>
                <td bgcolor="#E3E3EA" align="center">��ƪ�W�e��</td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td align="center">
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>">
              <input type="hidden" name="action" value="environment">
              <input type="submit" name="submit" value="�W�z�t�m���T" style="height: 25">
              <input type="button" name="exit" value="���s��z�קﵲ�G" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_big5'?>?action=config');">
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
              <input type="submit" name="submit" value="���s�ˬd�]�m" style="height: 25">
              <input type="button" name="exit" value="�h�X" style="height: 25" onclick="javascript: window.close();">
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
		$msg .= "<font color=\"#FF0000\">�z�� PHP �����p�� 4.0.6�A �L�k�ϥ� Discuz! Plus�C</font>\t";
		$quit = TRUE;
	} elseif($curr_php_version < '4.0.6') {
		$msg .= "<font color=\"#FF0000\">�z�� PHP �����p�� 4.0.6�A �L�k�ϥ��Y���ؤo�ˬd�M gzip ���Y�\��C</font>\t";
	}

	if(@ini_get(file_uploads)) {
		$max_size = @ini_get(upload_max_filesize);
		$curr_upload_status = "���\/�̤j�ؤo $max_size";
		$msg .= "�z�i�H�W�Ǥؤo�b $max_size �H�U��������.\t";
	} else {
		$curr_upload_status = '�����\�W�Ǫ���';
		$msg .= "<font color=\"#FF0000\">�ѩ���A���B���A �z�L�k�ϥΪ���\��C</font>\t";
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
		$msg .= "<font color=\"#FF0000\">�z�� MySQL �����C�� 3.23�A Discuz! Plus ���@�ǥ\��i��L�k���`�ϥΡC</font>\t";
	}

	$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

	if(dir_writeable('./templates')) {
		$curr_tpl_writeable = '�i�g';
	} else {
		$curr_tpl_writeable = '���i�g';
		$msg .= "<font color=\"#FF0000\">�ҪO ./templates �ؿ��ݩʫD 777 �εL�k�g�J�A �L�k�ϥνu�W�s��ҪO�M����ɤJ�C</font>\t";
	}

	if(dir_writeable($attachdir)) {
		$curr_attach_writeable = '�i�g';
	} else {
		$curr_attach_writeable = '���i�g';
		$msg .= "<font color=\"#FF0000\">���� $attachdir �ؿ��ݩʫD 777 �εL�k�g�J�A �L�k�ϥΪ���\��C</font>\t";
	}

	if(dir_writeable('./customavatars/')) {
		$curr_head_writeable = '�i�g';
	} else {
		$curr_head_writeable = '���i�g';
		$msg .= "<font color=\"#FF0000\">�W���Y�� ./customavatars �ؿ��ݩʫD 777 �εL�k�g�J�A �L�k�ϥΤW���Y���\��C</font>\t";
	}

	if(dir_writeable('./forumdata/')) {
		$curr_data_writeable = '�i�g';
	} else {
		$curr_data_writeable = '���i�g';
		$msg .= "<font color=\"#FF0000\">��� ./forumdata �ؿ��ݩʫD 777 �εL�k�g�J�A �L�k�ϥγƥ�����A��/�׾¹B��O�����\��C</font>\t";
	}

	if(dir_writeable('./forumdata/templates/')) {
		$curr_template_writeable = '�i�g';
	} else {
		$curr_template_writeable = '���i�g';
		$msg .= "<font color=\"#FF0000\">�ҪO ./forumdata/templates �ؿ��ݩʫD 777 �εL�k�g�J�A �L�k�w�� Discuz! Plus�C</font>\t";
		$quit = TRUE;
	}

	if(dir_writeable('./forumdata/cache/')) {
		$curr_cache_writeable = '�i�g';
	} else {
		$curr_cache_writeable = '���i�g';
		$msg .= "<font color=\"#FF0000\">�w�s ./forumdata/cache �ؿ��ݩʫD 777 �εL�k�g�J�A �L�k�w�� Discuz! Plus�C</font>\t";
		$quit = TRUE;
	}

	$db->select_db($dbname);
	if($db->error()) {
		$db->query("CREATE DATABASE $dbname");
		if($db->error()) {
			$msg .= "<font color=\"#FF0000\">���w����Ʈw $dbname ���s�b�A �t�Τ]�L�k�۰ʫإߡA �L�k�w�� Discuz! Plus�C</font>\t";
			$quit = TRUE;
		} else {
			$db->select_db($dbname);
			$msg .= "���w����Ʈw $dbname ���s�b�A ���t�Τw���\�إߡA �i�H�~��w�ˡC\t";
		}
	}

	$query - $db->query("SELECT COUNT(*) FROM $tablepre"."settings", 1);
	if(!$db->error()) {
		$msg .= "<font color=\"#FF0000\">��Ʈw���w�g�w�˹L Discuz! Plus�A �~��w�˷|�M�ŭ즳��ơC</font>\t";
		$alert = " onSubmit=\"return confirm('�~��w�˷|�M�ť����즳��ơA�z�T�w�n�~��ܡH');\"";
	} else {
		$alert = '';
	}

	if($quit) {
		$msg .= "<font color=\"#FF0000\">�ѩ�z�ؿ��ݩʩΦ��A���t�m��], �L�k�~��w�� Discuz! Plus�A �ХJ�Ӿ\Ū�w�˻����C</font>";
	} else {
		$msg .= "�z�����A���i�H�w�˩M�ϥ� Discuz! Plus�A �жi�J�U�@�B�w�ˡC";
	}

?>
        <tr>
          <td><b>��e���A�G</b><font color="#0000EE">�ˬd��e���A������</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> Discuz! Plus �һ����ҩM��e���A���t�m���</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="80%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center"></td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus �һݰt�m</td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus �̨ΰt�m</td>
                <td align="center" style="color: #FFFFFF">��e���A��</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">�ާ@�t��</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">UNIX/Linux/FreeBSD</td>
                <td bgcolor="#E3E3EA" align="center"><?=$curr_os?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP ����</td>
                <td bgcolor="#EEEEF6" align="center">4.0.6+</td>
                <td bgcolor="#E3E3EA" align="center">5.0.1+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_php_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP ���O: register_globals</td>
                <td bgcolor="#EEEEF6" align="center">OFF</td>
                <td bgcolor="#E3E3EA" align="center">OFF</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_gobals_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP ���O: magic_quotes_gpc</td>
                <td bgcolor="#EEEEF6" align="center">ON</td>
                <td bgcolor="#E3E3EA" align="center">ON</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_quotes_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">����W��</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">���\</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_upload_status?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">MySQL ����</td>
                <td bgcolor="#EEEEF6" align="center">3.23+</td>
                <td bgcolor="#E3E3EA" align="center">4.0.20+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_mysql_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">�ϺЪŶ�</td>
                <td bgcolor="#EEEEF6" align="center">2M+</td>
                <td bgcolor="#E3E3EA" align="center">100M+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_disk_space?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./templates �ؿ��g�J</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">�i�g</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_tpl_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$attachdir?> �ؿ��g�J</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">�i�g</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_attach_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./customavatars �ؿ��g�J</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">�i�g</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_head_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata �ؿ��g�J</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">�i�g</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_data_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/templates �ؿ��g�J</td>
                <td bgcolor="#EEEEF6" align="center">�i�g</td>
                <td bgcolor="#E3E3EA" align="center">�i�g</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_template_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/cache �ؿ��g�J</td>
                <td bgcolor="#EEEEF6" align="center">�i�g</td>
                <td bgcolor="#E3E3EA" align="center">�i�g</td>
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> �нT�{�w�����p�U�B�J</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
              <li>�N���Y�]�� Discuz! Plus �ؿ��U�����ɮשM�ؿ��W�Ǩ���A��.</li>
              <li>�ק���A���W�� config.php �ɮץH�A�X�z���t�m, ������Ʈw�㸹�T���Ыt�߱z���Ŷ��A�ȴ��Ѱ�.</li>
              <li>�p�G�z�ϥΫD WINNT �t�νЭק�H�U�ݩ�:<br>&nbsp; &nbsp; <b>./templates</b> �ؿ� 777;&nbsp; &nbsp; <b><?=$attachdir?></b> �ؿ� 777;&nbsp; &nbsp; <b>./customavatars</b> �ؿ� 777;&nbsp; &nbsp; <b>./forumdata</b> �ؿ� 777; <br><b>&nbsp; &nbsp; ./forumdata/cache</b> �ؿ� 777;&nbsp; &nbsp; <b>./forumdata/templates</b> �ؿ� 777;<br></li>
              <li>�T�{ URL �� <?=$attachurl?> �i�H�X�ݦ��A���ؿ� <?=$attachdir?> ���e.</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> �w���Q�ɴ���</font></b></td>
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
            <input type="button" name="refresh" value="���s�ˬd�]�m" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_big5'?>?action=environment');">&nbsp;
            <input type="button" name="exit" value="�h�X" style="height: 25" onclick="javascript: window.close();">
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> �]�m�޲z���b��</font></b></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_big5'?>"<?=$alert?>>
              <table width="300" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;�޲z���㸹:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="username" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;�޲z�� Email:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="email" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;�޲z���K�X:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password1" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;���ƱK�X:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password2" size="30"></td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="install">
              <input type="submit" name="submit" value="�}�l�w�� Discuz! Plus" style="height: 25" >&nbsp;
              <input type="button" name="exit" value="�h�X�w���Q��" style="height: 25" onclick="javascript: window.close();">
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
          <td><b>��e���A�G</b><font color="#0000EE">�ˬd�޲z���㸹�T���ö}�l�w�� Discuz! Plus�C</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> �ˬd�޲z���b��</font></b></td>
        </tr>
        <tr>
          <td>�ˬd�T���X�k��
<?

	$msg = '';
	if($username && $email && $password1 && $password2) {
		if($password1 != $password2) {
			$msg = "�⦸��J�K�X���@�P�C";
		} elseif(strlen($username) > 15) {
			$msg = "�Τ�W�W�L 15 �Ӧr������C";
		} elseif(preg_match("/^$|^c:\\con\\con$|�@|[,\"\s\t\<\>]|^�C��|^Guest/is", $username)) {
			$msg = "�Τ�W�ũΥ]�t�D�k�r���C";
		} elseif(!strstr($email, '@') || $email != stripslashes($email) || $email != htmlspecialchars($email)) {
			$msg = "Email �a�}�L��";
		}
	} else {
		$msg = '�A���T���S����g����C';
	}

	if($msg) { 

?>
            ... <font color="#FF0000">���ѡC ��]�G<?=$msg?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="��^�W�@���ק�" onclick="javascript: history.go(-1);">
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ��ܸ�Ʈw</font></b></td>
        </tr>
<?
	include './config.php';
	include './include/db_'.$database.'.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$db->select_db($dbname);

echo"        <tr>\n";
echo"          <td>��ܸ�Ʈw $dbname ".result(1, 0)."</td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> �إ߸�ƪ�</font></b></td>\n";
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

INSERT INTO cdb_usergroups VALUES('1','','Guest','�X��','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('2','','IPBanned','�Τ�IP�Q�T��','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('3','','Banned','�T��X��','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('4','','PostBanned','�T��o��','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('5','','Inactive','��������','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','50','0','0','0','');
INSERT INTO cdb_usergroups VALUES('6','','Moderator','���D','0','0','8','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','0','0','800','0','2000','80','800','2048000','');
INSERT INTO cdb_usergroups VALUES('7','','SuperMod','�W�Ū��D','0','0','9','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','0','1200','0','3000','90','900','2048000','');
INSERT INTO cdb_usergroups VALUES('8','','Admin','�޲z��','0','0','10','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','1','2000','0','50000','100','10000','4294967295','');
INSERT INTO cdb_usergroups VALUES('9','','Member','���P�ժ��|��','1600','3500','5','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','300','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('10','','Member','���P�p�۷|��','3500','9999999','6','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','400','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('11','','Member','�|�P�����|��','800','1600','4','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','60','0','200','0','0','512000','');
INSERT INTO cdb_usergroups VALUES('12','','Member','�׾¤^��','-9999999','0','0','','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('13','','Member','�T�P���ŷ|��','300','800','3','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','50','0','150','0','0','256000','');
INSERT INTO cdb_usergroups VALUES('14','','Member','�G�P��ŷ|��','50','300','2','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','30','0','100','0','0','0','');
INSERT INTO cdb_usergroups VALUES('15','','Member','�@�P�s��|��','0','50','1','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','20','0','80','0','0','0','');
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
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> ��l�ƹB��ؿ��P�ɮ�</font></b></td>\n";
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
            <font color="#FF0000"><b>���߱z�ADiscuz! Plus �w�˦��\�I</font><br>
            �޲z���㸹:</b><?=$username?><b> �޲z���K�X:</b><?=$password1?><br><br>
            <a href="index.php" target="_blank">�I���o�̶i�J�׾�</a>
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
	echo '��ʼ����¼ '.$log;
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
				echo '�������ϱ� '.$name.' ... <font color="#0000EE">�ɹ�</font><br>';
			}
			$db->query($query);
		}
	}
}

function result($result = 1, $output = 1) {
	if($result) {
		$text = '... <font color="#0000EE">�ɹ�</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">ʧ��</font><br>';
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
	echo '���Ŀ¼ '.$dir;
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
	<b>��ӭ���� Discuz! Plus Board ��װ�򵼣���װǰ����ϸ�Ķ� license ����ÿ��ϸ�ڣ�����ȷ��������ȫ���� Discuz! Plus ����ȨЭ��֮����ܿ�ʼ��װ��readme ���ṩ���й����尲װ��˵��������ͬ����ϸ�Ķ����Ա�֤��װ���̵�˳�����С�</b>
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
��Ȩ���� (c) 2004��HKLCF.COM
��������Ȩ����

    ��л��ѡ�� Discuz! Plus ��̳��Ʒ��ϣ�����ǵ�Ŭ����Ϊ���ṩһ����Ч���ٺ�ǿ��� web ��̳���������

    Discuz! Plus Ϊ HKLCF.COM ����������ȫ�����ļ������� HKLCF.COM ���С�

    Discuz! Plus �ĺ��İ����� Discuz! 3.x , 2.x , PHPWind , phpbb , 3Q , IPB �� UNet.Boards �ȵȣ�����������
EOT;

	$discuz_licence = str_replace('  ', '&nbsp; ', nl2br($discuz_licence));

?>
        <tr> 
          <td><b>��ǰ״̬��</b><font color="#0000EE">Discuz! Plus ��Ա���Э��</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ���������ϸ�Ķ���������Э��</font></b></td>
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
              <input type="submit" name="submit" value="����ȫͬ��" style="height: 25">&nbsp;
              <input type="button" name="exit" value="�Ҳ���ͬ��" style="height: 25" onclick="javascript: window.close();">
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
		$config_info = '���� config.php �����ڣ� �޷�������װ�� ���� FTP �����ļ��ϴ������ԡ�';
	} elseif(!$write_error) {
		$config_info = '����������д�������Ͽ��ʺ�ѶϢ�� ͨ��������벻Ҫ�޸ĺ�ɫѡ�����ݡ�';
	} elseif($write_error) {
		$config_info = '��װ���޷�д�������ļ��� ��˶�����ѶϢ�� �����޸ģ� ��ͨ�� FTP ���ĺõ� config.php �ϴ���';
	}

?>
        <tr> 
          <td><b>��ǰ״̬��</b><font color="#0000EE">���� config.php</font></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ��������ļ�״̬</font></b></td>
        </tr>
        <tr>
          <td>config.php ���ڼ�� <?=$fileexists?></td>
        </tr>
        <tr>
          <td>config.php ��д��� <?=$filewriteable?></td>
        </tr>
        <tr> 
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ���/�༭��ǰ����</font></b></td>
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
                  <td align="center" width="20%" style="color: #FFFFFF">����ѡ��</td>
                  <td align="center" width="35%" style="color: #FFFFFF">��ǰֵ</td>
                  <td align="center" width="45%" style="color: #FFFFFF">ע��</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;���Ͽ��ŷ���:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?=$dbhost?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;���Ͽ��ŷ�����ַ, һ��Ϊ localhost</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;���Ͽ��Ա��:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?=$dbuser?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;���Ͽ��˺Ż�Ա��</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;���Ͽ�����:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?=$dbpw?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;���Ͽ��˺�����</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;���Ͽ���:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbname" value="<?=$dbname?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;���Ͽ�����</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;ϵͳ Email:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="adminemail" value="<?=$adminemail?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;���ڷ��ͳ�ʽ���󱨸�</td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;����ǰ׺:</td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="tablepre" value="<?=$tablepre?>" size="30" onClick="javascript: alert('��װ����ʾ:\n\n��������Ҫ��ͬһ���Ͽⰲװ��� Discuz! \n��̳,����,ǿ�ҽ�������Ҫ�޸ı���ǰ׺.');"></td>
                  <td bgcolor="#E3E3EA">&nbsp;ͬһ���Ͽⰲװ����̳ʱ�ɸı�Ԥ��</td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="environment">
              <input type="hidden" name="saveconfig" value="1">
              <input type="submit" name="submit" value="��������ѶϢ" style="height: 25">
              <input type="button" name="exit" value="�˳���װ��" style="height: 25" onclick="javascript: window.close();">
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
                <td align="center" style="color: #FFFFFF">����</td>
                <td align="center" style="color: #FFFFFF">��ǰֵ</td>
                <td align="center" style="color: #FFFFFF">ע��</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbhost</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbhost?></td>
                <td bgcolor="#E3E3EA" align="center">���Ͽ��ŷ�����ַ, һ��Ϊ localhost</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbuser</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbuser?></td>
                <td bgcolor="#E3E3EA" align="center">���Ͽ��˺Ż�Ա��</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbpw</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbpw?></td>
                <td bgcolor="#E3E3EA" align="center">���Ͽ��˺�����</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbname</td>
                <td bgcolor="#EEEEF6" align="center"><?=$dbname?></td>
                <td bgcolor="#E3E3EA" align="center">���Ͽ�����</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$adminemail</td>
                <td bgcolor="#EEEEF6" align="center"><?=$adminemail?></td>
                <td bgcolor="#E3E3EA" align="center">ϵͳ Email</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$tablepre</td>
                <td bgcolor="#EEEEF6" align="center"><?=$tablepre?></td>
                <td bgcolor="#E3E3EA" align="center">���ϱ���ǰ׺</td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td align="center">
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>">
              <input type="hidden" name="action" value="environment">
              <input type="submit" name="submit" value="����������ȷ" style="height: 25">
              <input type="button" name="exit" value="���������޸Ľ��" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_gb2312'?>?action=config');">
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
              <input type="submit" name="submit" value="���¼������" style="height: 25">
              <input type="button" name="exit" value="�h�X" style="height: 25" onclick="javascript: window.close();">
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
		$msg .= "<font color=\"#FF0000\">���� PHP �汾С�� 4.0.6�� �޷�ʹ�� Discuz! Plus��</font>\t";
		$quit = TRUE;
	} elseif($curr_php_version < '4.0.6') {
		$msg .= "<font color=\"#FF0000\">���� PHP �汾С�� 4.0.6�� �޷�ʹ��ͷ��ߴ���� gzip ѹ�����ܡ�</font>\t";
	}

	if(@ini_get(file_uploads)) {
		$max_size = @ini_get(upload_max_filesize);
		$curr_upload_status = "����/���ߴ� $max_size";
		$msg .= "�������ϴ��ߴ��� $max_size ���µĸ����ļ�.\t";
	} else {
		$curr_upload_status = '�������ϴ�����';
		$msg .= "<font color=\"#FF0000\">�����ŷ����ڱΣ� ���޷�ʹ�ø������ܡ�</font>\t";
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
		$msg .= "<font color=\"#FF0000\">���� MySQL �汾���� 3.23�� Discuz! Plus ��һЩ���ܿ����޷�����ʹ�á�</font>\t";
	}

	$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

	if(dir_writeable('./templates')) {
		$curr_tpl_writeable = '��д';
	} else {
		$curr_tpl_writeable = '����д';
		$msg .= "<font color=\"#FF0000\">ģ�� ./templates Ŀ¼���Է� 777 ���޷�д�룬 �޷�ʹ�����ϱ༭ģ��ͷ���롣</font>\t";
	}

	if(dir_writeable($attachdir)) {
		$curr_attach_writeable = '��д';
	} else {
		$curr_attach_writeable = '����д';
		$msg .= "<font color=\"#FF0000\">���� $attachdir Ŀ¼���Է� 777 ���޷�д�룬 �޷�ʹ�ø������ܡ�</font>\t";
	}

	if(dir_writeable('./customavatars/')) {
		$curr_head_writeable = '��д';
	} else {
		$curr_head_writeable = '����д';
		$msg .= "<font color=\"#FF0000\">�ϴ�ͷ�� ./customavatars Ŀ¼���Է� 777 ���޷�д�룬 �޷�ʹ���ϴ�ͷ���ܡ�</font>\t";
	}

	if(dir_writeable('./forumdata/')) {
		$curr_data_writeable = '��д';
	} else {
		$curr_data_writeable = '����д';
		$msg .= "<font color=\"#FF0000\">���� ./forumdata Ŀ¼���Է� 777 ���޷�д�룬 �޷�ʹ�ñ��ݵ��ŷ���/��̳���м�¼�ȹ��ܡ�</font>\t";
	}

	if(dir_writeable('./forumdata/templates/')) {
		$curr_template_writeable = '��д';
	} else {
		$curr_template_writeable = '����д';
		$msg .= "<font color=\"#FF0000\">ģ�� ./forumdata/templates Ŀ¼���Է� 777 ���޷�д�룬 �޷���װ Discuz! Plus��</font>\t";
		$quit = TRUE;
	}

	if(dir_writeable('./forumdata/cache/')) {
		$curr_cache_writeable = '��д';
	} else {
		$curr_cache_writeable = '����д';
		$msg .= "<font color=\"#FF0000\">���� ./forumdata/cache Ŀ¼���Է� 777 ���޷�д�룬 �޷���װ Discuz! Plus��</font>\t";
		$quit = TRUE;
	}

	$db->select_db($dbname);
	if($db->error()) {
		$db->query("CREATE DATABASE $dbname");
		if($db->error()) {
			$msg .= "<font color=\"#FF0000\">ָ�������Ͽ� $dbname �����ڣ� ϵͳҲ�޷��Զ������� �޷���װ Discuz! Plus��</font>\t";
			$quit = TRUE;
		} else {
			$db->select_db($dbname);
			$msg .= "ָ�������Ͽ� $dbname �����ڣ� ��ϵͳ�ѳɹ������� ���Լ�����װ��\t";
		}
	}

	$query - $db->query("SELECT COUNT(*) FROM $tablepre"."settings", 1);
	if(!$db->error()) {
		$msg .= "<font color=\"#FF0000\">���Ͽ����Ѿ���װ�� Discuz! Plus�� ������װ�����ԭ�����ϡ�</font>\t";
		$alert = " onSubmit=\"return confirm('������װ�����ȫ��ԭ�����ϣ���ȷ��Ҫ������');\"";
	} else {
		$alert = '';
	}

	if($quit) {
		$msg .= "<font color=\"#FF0000\">������Ŀ¼���Ի��ŷ�������ԭ��, �޷�������װ Discuz! Plus�� ����ϸ�Ķ���װ˵����</font>";
	} else {
		$msg .= "�����ŷ������԰�װ��ʹ�� Discuz! Plus�� �������һ����װ��";
	}

?>
        <tr>
          <td><b>��ǰ״̬��</b><font color="#0000EE">��鵱ǰ�ŷ�������</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> Discuz! Plus ���軷���͵�ǰ�ŷ������öԱ�</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="80%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center"></td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus ��������</td>
                <td align="center" style="color: #FFFFFF">Discuz! Plus �������</td>
                <td align="center" style="color: #FFFFFF">��ǰ�ŷ���</td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">����ϵͳ</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">UNIX/Linux/FreeBSD</td>
                <td bgcolor="#E3E3EA" align="center"><?=$curr_os?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP �汾</td>
                <td bgcolor="#EEEEF6" align="center">4.0.6+</td>
                <td bgcolor="#E3E3EA" align="center">5.0.1+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_php_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP ָ��: register_globals</td>
                <td bgcolor="#EEEEF6" align="center">OFF</td>
                <td bgcolor="#E3E3EA" align="center">OFF</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_gobals_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">PHP ָ��: magic_quotes_gpc</td>
                <td bgcolor="#EEEEF6" align="center">ON</td>
                <td bgcolor="#E3E3EA" align="center">ON</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_quotes_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">�����ϴ�</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">����</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_upload_status?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">MySQL �汾</td>
                <td bgcolor="#EEEEF6" align="center">3.23+</td>
                <td bgcolor="#E3E3EA" align="center">4.0.20+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_mysql_version?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">�ŵ��ռ�</td>
                <td bgcolor="#EEEEF6" align="center">2M+</td>
                <td bgcolor="#E3E3EA" align="center">100M+</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_disk_space?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./templates Ŀ¼д��</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">��д</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_tpl_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?=$attachdir?> Ŀ¼д��</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">��д</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_attach_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./customavatars Ŀ¼д��</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">��д</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_head_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata Ŀ¼д��</td>
                <td bgcolor="#EEEEF6" align="center">����</td>
                <td bgcolor="#E3E3EA" align="center">��д</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_data_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/templates Ŀ¼д��</td>
                <td bgcolor="#EEEEF6" align="center">��д</td>
                <td bgcolor="#E3E3EA" align="center">��д</td>
                <td bgcolor="#EEEEF6" align="center"><?=$curr_template_writeable?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./forumdata/cache Ŀ¼д��</td>
                <td bgcolor="#EEEEF6" align="center">��д</td>
                <td bgcolor="#E3E3EA" align="center">��д</td>
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ��ȷ����������²���</font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
              <li>��ѹ������ Discuz! Plus Ŀ¼��ȫ��������Ŀ¼�ϴ����ŷ���.</li>
              <li>�޸��ŷ����ϵ� config.php �������ʺ���������, �й����Ͽ��˺�ѶϢ����ѯ���Ŀռ�����ṩ��.</li>
              <li>�����ʹ�÷� WINNT ϵͳ���޸���������:<br>&nbsp; &nbsp; <b>./templates</b> Ŀ¼ 777;&nbsp; &nbsp; <b><?=$attachdir?></b> Ŀ¼ 777;&nbsp; &nbsp; <b>./customavatars</b> Ŀ¼ 777;&nbsp; &nbsp; <b>./forumdata</b> Ŀ¼ 777; <br><b>&nbsp; &nbsp; ./forumdata/cache</b> Ŀ¼ 777;&nbsp; &nbsp; <b>./forumdata/templates</b> Ŀ¼ 777;<br></li>
              <li>ȷ�� URL �� <?=$attachurl?> ���Է����ŷ���Ŀ¼ <?=$attachdir?> ����.</li>
            </ol>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ��װ����ʾ</font></b></td>
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
            <input type="button" name="refresh" value="���¼������" style="height: 25" onclick="javascript: window.location=('<?=$PHP_SELF&$language='chinese_gb2312'?>?action=environment');">&nbsp;
            <input type="button" name="exit" value="�˳�" style="height: 25" onclick="javascript: window.close();">
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ���ù���Ա�ʺ�</font></b></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="<?=$PHP_SELF&$language='chinese_gb2312'?>"<?=$alert?>>
              <table width="300" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;����Ա�˺�:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="username" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;����Ա Email:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="text" name="email" value="" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;����Ա����:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password1" size="30"></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" width="40%">&nbsp;�ظ�����:</td>
                  <td bgcolor="#EEEEF6" width="60%"><input type="password" name="password2" size="30"></td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="install">
              <input type="submit" name="submit" value="��ʼ��װ Discuz! Plus" style="height: 25" >&nbsp;
              <input type="button" name="exit" value="�˳���װ��" style="height: 25" onclick="javascript: window.close();">
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
          <td><b>��ǰ״̬��</b><font color="#0000EE">������Ա�˺�ѶϢ����ʼ��װ Discuz! Plus��</font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr> 
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ������Ա�ʺ�</font></b></td>
        </tr>
        <tr>
          <td>���ѶϢ�Ϸ���
<?

	$msg = '';
	if($username && $email && $password1 && $password2) {
		if($password1 != $password2) {
			$msg = "�����������벻һ�¡�";
		} elseif(strlen($username) > 15) {
			$msg = "�û������� 15 ����Ԫ���ơ�";
		} elseif(preg_match("/^$|^c:\\con\\con$|��|[,\"\s\t\<\>]|^�ο�|^Guest/is", $username)) {
			$msg = "�û����ջ�����Ƿ���Ԫ��";
		} elseif(!strstr($email, '@') || $email != stripslashes($email) || $email != htmlspecialchars($email)) {
			$msg = "Email ��ַ��Ч";
		}
	} else {
		$msg = '���ѶϢû����д������';
	}

	if($msg) { 

?>
            ... <font color="#FF0000">ʧ�ܡ� ԭ��<?=$msg?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="������һҳ�޸�" onclick="javascript: history.go(-1);">
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
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> ѡ�����Ͽ�</font></b></td>
        </tr>
<?
	include './config.php';
	include './include/db_'.$database.'.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$db->select_db($dbname);

echo"        <tr>\n";
echo"          <td>ѡ�����Ͽ� $dbname ".result(1, 0)."</td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td>\n";
echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
echo"          </td>\n";
echo"        </tr>\n";
echo"        <tr>\n";
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> �������ϱ�</font></b></td>\n";
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

INSERT INTO cdb_usergroups VALUES('1','','Guest','�ÿ�','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('2','','IPBanned','�û�IP����ֹ','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('3','','Banned','��ֹ����','0','0','0','','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('4','','PostBanned','��ֹ����','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('5','','Inactive','�ȴ���֤','0','0','0','','0','0','1','1','0','0','0','0','0','0','0','0','0','1','0','0','0','0','0','0','0','50','0','0','0','');
INSERT INTO cdb_usergroups VALUES('6','','Moderator','����','0','0','8','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','0','0','800','0','2000','80','800','2048000','');
INSERT INTO cdb_usergroups VALUES('7','','SuperMod','��������','0','0','9','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','0','1200','0','3000','90','900','2048000','');
INSERT INTO cdb_usergroups VALUES('8','','Admin','����Ա','0','0','10','','1','2','1','1','1','1','1','1','1','2','1','1','1','1','1','1','1','1','1','2000','0','50000','100','10000','4294967295','');
INSERT INTO cdb_usergroups VALUES('9','','Member','���ǰ׽��Ա','1600','3500','5','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','300','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('10','','Member','������ʯ��Ա','3500','9999999','6','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','80','0','400','0','0','1024000','');
INSERT INTO cdb_usergroups VALUES('11','','Member','���ǻƽ��Ա','800','1600','4','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','60','0','200','0','0','512000','');
INSERT INTO cdb_usergroups VALUES('12','','Member','��̳��ؤ','-9999999','0','0','','0','0','1','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','');
INSERT INTO cdb_usergroups VALUES('13','','Member','���Ǹ߼���Ա','300','800','3','','1','2','1','1','1','1','1','1','1','2','0','1','1','1','1','1','0','0','0','50','0','150','0','0','256000','');
INSERT INTO cdb_usergroups VALUES('14','','Member','���ǳ�����Ա','50','300','2','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','30','0','100','0','0','0','');
INSERT INTO cdb_usergroups VALUES('15','','Member','һ�����ֻ�Ա','0','50','1','','0','1','1','1','1','1','1','1','1','1','0','1','1','1','1','1','0','0','0','20','0','80','0','0','0','');
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
echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> ��ʼ������Ŀ¼�뵵��</font></b></td>\n";
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
            <font color="#FF0000"><b>��ϲ����Discuz! Plus ��װ�ɹ���</font><br>
            ����Ա�˺�:</b><?=$username?><b> ����Ա����:</b><?=$password1?><br><br>
            <a href="index.php" target="_blank">������������̳</a>
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