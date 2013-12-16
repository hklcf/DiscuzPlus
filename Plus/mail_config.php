<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

$sendmail_silent = 1;		// 遮蔽郵件發送中的全部錯誤提示(推薦)， 1=是， 0=否
$mailsend = 1;			// 郵件發送方式	0=不發送任何郵件
				//		1=通過 PHP 函數及 UNIX sendmail 發送(推薦此方式)
				//		2=通過 SOCKET 連結 SMTP 伺服器發送(支援 ESMTP 驗證)
				//		3=通過 PHP 函數 SMTP 發送 Email(僅 win32 下有效, 不支援 ESMTP)
				//
				// 可通過 utilities/testmail.php 測試您的系統支援哪種郵件發送方式

if($mailsend == 1) {

	// 通過 PHP 函數及 UNIX sendmail 發信(無需配置)

} elseif($mailsend == 2) {	// 通過 Discuz! SMTP 模塊發信

	$mailcfg['server'] = 'smtp.21cn.com';			// SMTP 伺服器
	$mailcfg['port'] = '25';				// SMTP 端口, 預設不需修改
	$mailcfg['auth'] = 1;					// 是否需要 AUTH LOGIN 驗證， 1=是, 0=否
	$mailcfg['from'] = 'Discuz <myaccount@21cn.com>';	// 發信人地址 (如果需要驗證，必須為本伺服器地址)
	$mailcfg['auth_username'] = 'myaccount';		// 驗證用戶名
	$mailcfg['auth_password'] = 'password';			// 驗證密碼

} elseif($mailsend == 3) {	// 通過 PHP 函數及 SMTP 伺服器發信

	$mailcfg['server'] = 'smtp.your.com';		// SMTP 伺服器， 以下設置僅對 WIN32 系統有效
	$mailcfg['port'] = '25';			// SMTP 端口, 預設不需修改

}

?>