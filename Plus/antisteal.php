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
include "./advcenter/antisteal_config.php";

if(!$antisteal['close']){
if($antisteal[method]==1){
	if(!empty($antisteal[black])){
		$blacklist = explode(",",$antisteal[black]);
		$allow=1;
		for($i=0;$i<count($blacklist);$i++){
			if (stristr($_SERVER['HTTP_REFERER'], $blacklist[$i]))
				$allow=0;
		}
	} else $allow=1;
} else{
	if(!empty($antisteal[white])){
		$whitelist = explode(",",$antisteal[white]);
		$allow=0;
		for($i=0;$i<count($whitelist);$i++){
			if (stristr($_SERVER['HTTP_REFERER'], $whitelist[$i]))
				$allow=1;
		}
	} else $allow=0;
}
if(!$allow){
	show_warningpic($antisteal[pic]);
	exit;
}
}
function show_warningpic($pic){
    $filesize = filesize($pic);
    ob_end_clean(); 
    header('Content-Encoding: none'); 
    header('Cache-Control: private'); 
    header('Content-Length: '.$filesize); 
    header("Content-Disposition: attachment; filename=".basename($pic));  
    header('Content-Type: image/gif'); 
    readfile($pic); 
}
?>