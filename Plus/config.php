<?php

/*
        Version: 1.1.3(BUG Fixed)
        Author: HKLCF (admin@hklcf.com)
        Copyright: HKLCF (www.hklcf.com)
        Last Modified: 2004/11/08
*/

// ==================== 以下變量需根據您的伺服器說明檔修改 ====================
//        注意：         如果資料庫連結有問題,請不要向我們詢問具體參數設置,請與空間商聯繫,
//                因為我們也無法告訴您這些變量應該設置為何值

        $dbhost = 'localhost';                        // 數據庫服務器
        $dbuser = 'dbuser';                                // 數據庫用戶名
        $dbpw = 'dbpw';                                // 數據庫密碼
        $dbname = 'plus';                        // 數據庫名
        $adminemail = '';                // 論壇系統 Email

// ============================================================================



// =================================== 論壇設置 =================================

        $postnum = 10;                                // 文章最小字元
        $bankmanager = 'admin';                        // 銀行行長
        $showdetails = 1;                                // 在線列表開/關 (0=關，1=開)
        $reseller = 10;                                // 推薦人可以增加的積分
        $date = '08-Nov-2004';                        // 開壇日期
        $IP = 1;                                        // 同一IP在 24 小時內註冊 ID 的數量
        $karma_view = 1;                                // 察看評分記錄開/關  (0=關，1=開)
        $karma_adminview = 1;                        // 版主察看評分記錄開/關  (0=關，1=開)
        $karma_perpage = 20;                        // 每頁顯示多少個評分人的記錄

// ============================================================================



// ============= 如您對 cookie 作用範圍有特殊要求,請修改下面變量 ==============

        $cookiepath = '/';                                // cookie 作用路徑 (如出現登入問題請修改此項)
        $cookiedomain = '';                         // cookie 作用域 (如出現登入問題請修改此項)

// ============================================================================



// ============= Discuz! 外掛，配置和使用方法詳情請參考 plugin.txt ============

$plugins[] = array (        'name'   => '插件中心',
                        'script' => '',
                        'url'    => '',
                        'cpurl'  => 'admincp.php?action=advcenter'        );

$plugins[] = array (        'name'   => '管理團隊',
                        'script' => '',
                        'url'    => 'disadmin.php',
                        'cpurl'  => ''        );

$plugins[] = array (        'name'   => '論壇銀行',
                        'script' => '',
                        'url'    => 'bank.php',
                        'cpurl'  => 'admincp.php?action=advcenter&hackname=bank'        );

$plugins[] = array (        'name'   => '改名中心',
                        'script' => '',
                        'url'    => 'chname.php',
                        'cpurl'  => 'admincp.php?action=advcenter&hackname=chname'        );

$plugins[] = array (        'name'   => '聯盟申請',
                        'script' => '',
                        'url'    => 'link.php',
                        'cpurl'  => 'admincp.php?action=advcenter&hackname=link'        );

$plugins[] = array (        'name'   => '數據清理',
                        'script' => '',
                        'url'    => '',
                        'cpurl'  => 'admincp.php?action=advcenter&hackname=datasweep'        );

$plugins[] = array (        'name'   => '反盜連設置',
                        'script' => '',
                        'url'    => '',
                        'cpurl'  => 'admincp.php?action=advcenter&hackname=antisteal'        );

// ============================================================================



// ================= 以下變量為特別選項，一般情況下沒有必要修改 ================

        $headercharset = 0;                                // 強制設置字元集, 0=否, 1=是. 只亂碼時使用
        $onlinehold = 600;                                // 在線保持時間(秒)


        // 論壇投入使用後不能修改的變量

        $tablepre = 'cdb_';                                   // 表名前綴, 同一資料庫安裝多個論壇請修改此處
        $attachdir = './attachments';                        // 附件保存位置 (伺服器路徑, 屬性 777, 必須
                                                // 為 web 可訪問到的目錄， 不加 "/")
        $attachurl = 'attachments';                        // 附件路徑 URL 地址 (可為當前 URL 下的相對地址或 http:// 開頭的絕對地址, 不加 "/")


        // 切勿修改以下變量,僅供程序開發調試用!

        $database = 'mysql';                        // MySQL 版本請設置 'mysql', PgSQL 版本請設置 'pgsql'
        $tplrefresh = 1;                                // 模版自動重新整理開關 0=關閉， 1=打開
        $pconnect = 0;                                // 數據庫持久連接 0=關閉， 1=打開

// ============================================================================



// =============================== 貼子買賣配置 ===============================

$hacktable_postpay = 'cdb_postpay';
$allowpostpay = 1;
$cnteacher_postsell_maxprice = 10000;
$cnteacher_paylist_perpage = 20;

// ============================================================================



// =============================== 改名中心配置 ===============================

$table_chname = 'cdb_chname';

// ============================================================================
