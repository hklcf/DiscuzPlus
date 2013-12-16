<?php

// Message Pack for Discuz! Plus Version 1.0.0
// Created by HKLCF

$language = array
(
	'get_passwd_subject' =>		'[Discuz! Plus] 取回密碼郵件',
	'get_passwd_content' =>		'您好 $member[username]，這是 $bbname 系統發送的\n'.
					'取回密碼郵件，請訪問下面的連結修改您的密碼。\n'.
					'{$boardurl}member.php?action=getpasswd&id=$newpass\n'.
					'歡迎您光臨 $bbname\n'.
					'$boardurl',

	'email_verify_subject' =>	'[Discuz! Plus] Email 地址確認郵件',
	'email_verify_content' =>	'您在 $bbname [ $boardurl ] 修改了註冊 Email 地址，\n'.
					'這是系統發送的確認郵件，請用如下資料登入：\n'.
					'用戶名：$discuz_user\n'.
					'密碼：$newpassword\n'.
					'您可以登入後修改此密碼。\n'.
					'非常感謝您對我們的信賴與支援，歡迎您光臨 {$bbname}。',

	'activation_subject' =>		'[Discuz! Plus] 您的帳號已經開通',
	'activation_content' =>		'您在 $bbname [ $boardurl ] 的申請已被接受，請用如下資料登入：\n'.
					'用戶名：$username\n密碼：$password2\n您可以登入後修改此密碼。\n'.
					'非常感謝您對我們的信賴與支援，歡迎您光臨 {$bbname}。',

	'email_notify_subject' =>	'[Discuz! Plus] $thread[subject] 已有新回覆',
	'email_notify_content' =>	'您好，$username 剛剛回覆了您在 $bbname 所訂閱的主題，詳情請訪問：\n'.
					'{$boardurl}viewthread.php?tid=$tid\n'.
					'12 小時之內我們將不再向您發送本主題的回覆通知\n'.
					'歡迎您光臨 $bbname\n'.
					'$boardurl'
);

?>