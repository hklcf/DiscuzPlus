<?php

// Message Pack for Discuz! Plus Version 1.0.0
// Created by HKLCF

$credittitle = '積分';
$creditunit = '點';

$language = array
(
	'digest_nonexistence' => '對不起，沒有找到匹配的精華主題。',

	'undefined_action' => '未定義操作，請返回。',
	'group_nopermission' => '對不起，您所在的用戶組〔{$grouptitle}〕無法進行此操作。',
	'not_loggedin' => '對不起，您還沒有登錄，無法進行此操作。',
	'board_closed' => '對不起，本論壇暫時關閉，詳情請<a href=\"mailto:$adminemail\">聯繫管理員</a>。',

	'login_invalid' => '用戶名無效或密碼錯誤，現在將以訪客身份轉入論壇首頁。',
	'login_succeed' => '歡迎您回來，{$discuz_userss}。現在將轉入登錄前頁面。',
	'logout_succeed' => '您已退出論壇，現在將以訪客身份轉入退出前頁面。',

	'user_banned' => '對不起，本論壇不歡迎您的來訪。',

	'forum_nonexistence' => '指定的論壇不存在，請返回。',
	'forum_passwd_wrong' => '您輸入的密碼不正確，不能訪問這個論壇。',
	'forum_nopermission' => '對不起，本論壇只有特定用戶可以訪問，請返回。',

	'thread_nonexistence' => '指定的主題不存在或已被刪除，請返回。',
	'thread_nopermission' => '對不起，本篇文章要求 $credittitle 高於 $thread[creditsrequire] $creditunit 才可瀏覽，請返回。',
	'thread_poll_closed' => '本主題已關閉，無法繼續投票，請返回。',
	'thread_poll_voted' => '您已參與過這個投票，請返回。',
	'thread_poll_invalid' => '您沒有選擇投票選項，請返回修改。',
	'thread_poll_succeed' => '您的投票成功提交，現在將轉入主題頁。',
	'thread_karma_range_invalid' => '您的給分超過 $minkarmarate 到 $maxkarmarate 的範圍限制。',
	'thread_karma_member_invalid' => '對不起，您不能給自己發表的文章評分，請返回。',
	'thread_karma_ctrl' => '對不起，您最近 24 小時評分數超過 $maxrateperday $creditunit 限制，請返回。',
	'thread_karma_duplicate' => '對不起，您不能對同一個文章重複評分，請返回。',
	'thread_karma_succeed' => '感謝您的參與，".stripslashes($username)." 的 $credittitle $score {$creditunit}。<br>現在將轉入主題頁面。',
	'thread_report_disable' => '對不起，管理員關閉了報告文章功能，請返回。',
	'thread_report_succeed' => '您的意見已經報告給板主和管理員，現在將轉入主題頁面。',

	'attachment_nopermission' => '對不起，本附件要求 $credittitle 高於 $attach[creditsrequire] $creditunit 才可下載，請返回。',
	'attachment_forum_nopermission' => '對不起，只有特定用戶可以下載本論壇的附件，請返回。',
	'attachment_nonexistence' => '附件文件不存在或無法讀入，請與管理員聯繫。',

	'post_hide_nopermission' => '對不起，您沒有權限使用 [hide] 代碼，請返回修改。',
	'post_forum_nopermission' => '對不起，本論壇只有特定用戶組可以發新話題，請返回。',
	'post_thread_closed' => '對不起，本主題已關閉，不再接受新回覆。',
	'post_subject_toolang' => '對不起，您的標題超過 100 個字元，請返回修改標題長度。',
	'post_message_tooshort' => '對不起，您的貼子不能少於 $postnum 個字符，請返回修改。',
	'post_message_toolang' => '對不起，您的文章超過 $maxpostsize 個字元的限制，請返回修改。',
	'post_sm_isnull' => '您沒有輸入標題或內容，請返回填寫。',
	'post_flood_ctrl' => '兩次發表間隔少於 $floodctrl 秒，請不要灌水！<a href=\"forumdisplay.php?fid=$fid\">點擊這裡</a> 繼續。',
	'post_poll_option_toomany' => '對不起，您的投票選項超過 10 個，請返回修改。',
	'post_edit_nopermission' => '對不起，您沒有權力編輯或刪除這個文章，請返回。',
	'post_del_nopermission' => '對不起，您沒有權力刪除這個貼子，請返回。',
	'post_edit_delete_succeed' => '主題刪除成功，現在將轉入主題列表。',
	'post_attachment_toobig' => '對不起，您的附件大小超過論壇限制，請返回修改。',
	'post_attachment_ext_notallowed' => '對不起，不支援上傳此類擴展名的附件，請返回修改。',
	'post_attachment_save_error' => '附件文件無法保存到伺服器，可能是目錄屬性設置問題，請與管理員聯繫。',
	'post_edit_succeed' => '您的文章編輯成功，現在將轉入主題頁。<br><br><a href=\"forumdisplay.php?fid=$fid\">[ 需要轉入主題列表請點擊這裡 ]</a>',
	'post_reply_succeed' => '非常感謝，您的回覆已經發布，現在將轉入主題頁。<br><br><a href=\"forumdisplay.php?fid=$fid\">[ 需要轉入主題列表請點擊這裡 ]</a>',
	'post_newthread_succeed' => '非常感謝，您的文章已經發布，現在將轉入主題頁。<br><br><a href=\"forumdisplay.php?fid=$fid\">[ 需要轉入主題列表請點擊這裡 ]</a>',

	'register_disable' => '對不起，目前論壇禁止新用戶註冊，請返回。',
	'register_ctrl' => '對不起，同一 IP 地址在 ".intval($pk_checktime/3600)." 小時內只能注冊一個賬號。',
	'register_sex' => '請選擇性別。',
	'register_year' => '請選擇出生年份。',
	'register_mouth' => '請選擇出生月份。',
	'register_data' => '請選擇出生日期。',
	'register_nodata' => '你選擇的出生日期不存在。',
	'register_nodata2' => '你選擇的出生日期不存在。',
	'register_succeed' => '非常感謝您的註冊，現在將以會員身份登入論壇。',

	'profile_username_toolang' => '對不起，用戶名必須在 2 - 15 個字符，請返回輸入一個有效的用戶名。',
	'profile_passwd_notmatch' => '兩次輸入的密碼不一致，請返回檢查後重試。',
	'profile_passwd_wrong' => '原密碼不正確，您不能修改密碼！',
	'profile_admin_security_invalid' => '作為管理者之一，您需要填寫安全提問和答案以保障論壇的安全，請返回。',
	'profile_account_duplicate' => '該用戶名或 E-mail 地址已經被註冊了，請返回重新填寫。',
	'profile_email_duplicate' => '該 E-mail 地址已經被註冊了，請返回重新填寫。',
	'profile_username_illegal' => '用戶名包含敏感字元或被系統遮蔽，請返回重新填寫。',
	'profile_passwd_illegal' => '密碼空或包含非法字元，請返回重新填寫。',
	'profile_email_illegal' => 'Email 地址無效，請返回重新填寫。',
	'profile_icq_illegal' => 'ICQ 號碼無效，請返回重新填寫。',
	'profile_oicq_illegal' => 'OICQ 號碼無效，請返回重新填寫。',
	'profile_sig_toolang' => '您的簽名長度超過 $maxsigsize 字元的限制，請返回修改。',
	'profile_avatar_toobig' => '您設置的頭像超過了系統定義的寬 $maxavatarsize 像素，高 $maxavatarsize 像素，請返回重新填寫。',
	'profile_avatardir_nonexistence' => '頭像目錄 ./images/avatars 不存在，請聯繫管理員。',
	'profile_avatar_succeed' => '您頭像設置已成功更新，現在將轉入個人資料頁。',
	'profile_email_identify' => '確認 Email 已經發送，請用郵件中的帳號訊息登入。',
	'profile_succeed' => '您已經成功保存個人資料，現在將轉入控制面板首頁。',

	'buddy_add_invalid' => '$buddy 已經存在於您的好友列表中，請返回。',
	'buddy_add_nonexistence' => '會員 $buddy 不存在，請返回修改。',
	'buddy_add_succeed' => '好友成功加入，現在將轉入控制面板首頁。',
	'buddy_delete_succeed' => '好友成功刪除，現在將轉入控制面板首頁。',

	'redirect_nextnewset_nonexistence' => '沒有比當前更新的主題，請返回。',
	'redirect_nextoldset_nonexistence' => '沒有比當前更早的主題，請返回。',

	'favorite_exists' => '您過去已經收藏過這個主題，請返回。',
	'favorite_add_succeed' => '指定主題已成功增加到收藏夾中，現在將回到上一頁。',
	'favorite_update_succeed' => '收藏夾已成功更新，現在將轉入更新後的收藏夾。',
	'subscription_exists' => '您過去已經訂閱過這個主題，請返回。',
	'subscription_add_succeed' => '您選擇的主題已經成功訂閱，現在將回到上一頁。',
	'subscription_update_succeed' => '訂閱列表已經成功更新，現在將轉入更新後的訂閱列表。',

	'search_ctrl' => '對不起，$searchctrl 秒內只能進行一次搜尋，請返回。',
	'search_invalid' => '您沒有指定要搜尋的關鍵字或用戶名，請返回重新填寫。',
	'search_forum_invalid' => '您沒有指定搜尋論壇的範圍，請返回重新填寫。',

	'member_nonexistence' => '指定的用戶不存在或已被刪除，請返回。',
	'member_list_disable' => '對不起，管理員禁止了會員列表功能。',
	'email_friend_invalid' => '相關項目沒有填寫完整，請返回修改。',
	'email_friend_succeed' => '您的推薦已經通過 E-mail 發給朋友，現在將轉入原文章。',
	'announcement_nonexistence' => '目前沒有公告供查看，請返回。',
	'mark_read_succeed' => '所有論壇已被標記已讀，現在將轉入論壇首頁。',

	'getpasswd_account_notmatch' => '用戶名，Email 地址或安全提問不匹配，請返回修改。',
	'getpasswd_id_illegal' => '您所用的 ID 不存在或已經過期，無法取回密碼。',
	'getpasswd_send_succeed' => '取回密碼的方法已經通過 Email 發送到您的信箱中，<br>請在 3 天之內到論壇修改您的密碼。',
	'getpasswd_succeed' => '您的密碼已重新設置，請使用新密碼登錄。',

	'pm_box_isfull' => '您的信箱已滿，在閱讀短訊前必須刪除一些不用的訊息。',
	'pm_nonexistence' => '對不起，短訊不存在或已被刪除。',
	'pm_send_nonexistence' => '收件人 $msgto 不存在，請返回修改。',
	'pm_send_invalid' => '您的訊息沒有填寫完整，請返回修改。',
	'pm_send_toomany' => '收件人數量超過 $maxpmsend 人，請返回修改。',
	'pm_send_ignore' => '$member[username] 拒絕接受您的短訊，請返回修改。',
	'pm_send_succeed' => '短訊發送成功，現在將轉入訊息列表。',
	'pm_delete_succeed' => '指定訊息成功刪除，現在將轉入訊息列表。',
	'pm_ignore_succeed' => '忽略列表已成功更新，現在將轉入訊息列表。',

	'admin_nopermission' => '對不起，您沒有權限使用管理功能。',
	'admin_delthread_invalid' => '您沒有選擇要刪除的主題，請返回。',
	'admin_delpost_invalid' => '您沒有選擇要刪除的文章，請返回。',
	'admin_move_invalid' => '您沒有選擇目標論壇，請返回修改。',
	'admin_split_invalid' => '這個主題沒有回覆，無法分割，請返回。',
	'admin_split_subject_invalid' => '您沒有輸入標題，請返回填寫。',
	'admin_split_new_invalid' => '您沒有選擇要分割入新主題的文章，請返回檢查。',
	'admin_succeed' => '管理操作成功，現在將轉入主題列表。',
	'profile_uploadavatar_nopremission' => '您沒有足夠的許可權上傳頭像。',
	'profile_uploadavatar_wrongfiletype' => '您上傳的檔格式不正確，請返回重新上傳。',
	'profile_uploadavatar_filetoobig' => '您上傳的頭像檔超過了系統定義的大小，請返回重新上傳。',
	'profile_uploadavatar_toobig' => '您上傳的頭像檔超過了系統定義的寬 $maxavatarsize 圖元，高 $maxavatarsize 圖元，請返回重新上傳。',
	'profile_customavatardir_nonexistence' => '自定義頭像目錄 ./customavatars 不存在，請聯繫管理員。',
	'profile_avatar_upload_succeed' => '您頭像上傳已成功更新，現在將轉入個人資料頁。' 

);

?>