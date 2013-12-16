<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

function attachicon($type) {
	if(preg_match("/image|^(jpg|gif|png|bmp)\t/", $type)) {
		$attachicon = 'image.gif';
	} elseif(preg_match("/flash|^(swf|fla|swi)\t/", $type)) {
		$attachicon = 'flash.gif';
	} elseif(preg_match("/audio|video|^(wav|mid|mp3|m3u|wma|asf|asx|vqf|mpg|mpeg|avi|wmv)\t/", $type)) {
		$attachicon = 'av.gif';
	} elseif(preg_match("/real|^(ra|rm|rv)\t/", $type)) {
		$attachicon = 'real.gif';
	} elseif(preg_match("/htm|^(php|js|pl|cgi|asp)\t/", $type)) {
		$attachicon = 'html.gif';
	} elseif(preg_match("/text|^(txt|rtf|wri|chm)\t/", $type)) {
		$attachicon = 'text.gif';
	} elseif(preg_match("/word|^(doc)\t/", $type)) {
		$attachicon = 'word.gif';
	} elseif(preg_match("/powerpoint|^(ppt)\t/", $type)) {
		$attachicon = 'powerpoint.gif';
	} elseif(preg_match("/^rar\t/", $type)) {
		$attachicon = 'rar.gif';
	} elseif(preg_match("/compressed|^(zip|arj|arc|cab|lzh|lha|tar|gz)\t/", $type)) {
		$attachicon = 'zip.gif';
	} elseif(preg_match("/octet-stream|^(exe|com|bat|dll)\t/", $type)) {
		$attachicon = 'binary.gif';
        } elseif(preg_match("/BitTorrent|^(torrent)\t/", $type)) {
                $attachicon = 'bt.gif';
	} else {
		$attachicon = 'other.gif';
	}
	$attachicon = "<img src=\"images/attachicon/$attachicon\" align=\"absmiddle\" border=\"0\">";
	return $attachicon;
}

function sizecount($filesize) {
	if($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' G';
	} elseif($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 . ' M';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' K';
	} else {
		$filesize = $filesize . ' bytes';
	}
	return $filesize;
}

function attachtype($type) {
        if(preg_match("/image|^(jpg|gif|png|bmp)\t/", $type)) {
                $attachtype = '圖片';
        } elseif(preg_match("/flash|^(swf|fla|swi)\t/", $type)) {
                $attachtype = 'flash';
        } elseif(preg_match("/audio|video|^(wav|mid|mp3|m3u|wma|asf|asx|vqf|mpg|mpeg|avi|wmv)\t/", $type)) {
                $attachtype = '影音';
        } elseif(preg_match("/real|^(ra|rm|rv)\t/", $type)) {
                $attachtype = 'Realplay';
        } elseif(preg_match("/htm|^(php|js|pl|cgi|asp)\t/", $type)) {
                $attachtype = '代碼';
        } elseif(preg_match("/text|^(txt|rtf|wri|chm)\t/", $type)) {
                $attachtype = '文本';
        } elseif(preg_match("/word|^(doc)\t/", $type)) {
                $attachtype = 'word文檔';
        } elseif(preg_match("/powerpoint|^(ppt)\t/", $type)) {
                $attachtype = 'powerpoint文檔';
        } elseif(preg_match("/^rar\t/", $type)) {
                $attachtype = 'RAR壓縮檔';
        } elseif(preg_match("/compressed|^(zip|arj|arc|cab|lzh|lha|tar|gz)\t/", $type)) {
                $attachtype = 'ZIP壓縮檔';
        } elseif(preg_match("/octet-stream|^(exe|com|bat|dll)\t/", $type)) {
                $attachtype = '可執行文件';
        } else {
                $attachtype = '其他';
        }
        return $attachtype;
}

?>