<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

function convertip($ip){
	$datadir = $GLOBALS['discuz_root'].'./ipdata/';
	$ip_detail = explode('.', $ip);
	if(file_exists($datadir.$ip_detail[0].'.txt')) {
		$ip_fdata = fopen($datadir.$ip_detail[0].'.txt', 'r');
	} else {
		if(!($ip_fdata = fopen($datadir.'0.txt', 'r'))) {
			echo 'IP data file error';
		}
	}
	for ($i = 0; $i <= 3; $i++) {
		$ip_detail[$i] = sprintf('%03d', $ip_detail[$i]);
	}
	$ip = join('.', $ip_detail);
	do {
		$ip_data = fgets($ip_fdata, 200);
		$ip_data_detail = explode("|", $ip_data);
		if($ip >= $ip_data_detail[0] && $ip <= $ip_data_detail[1]) {
			fclose($ip_fdata);
			return $ip_data_detail[2].$ip_data_detail[3];
		}
	} while(!feof($ip_fdata));
	fclose($ip_fdata);
	return 'UNKNOWN';
}

?>