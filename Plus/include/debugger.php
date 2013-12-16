<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

function timer() {
	global $_timer;

	if(empty($_timer['start'])) {
		$mtime = explode(' ', microtime());
		$_timer['start'] = $mtime[1] + $mtime[0];
		$_timer['count'] ++;
	} else {
		$mtime = explode(' ', microtime());
		echo '<br>Discuz! timer #'.$_timer['count'].': '.number_format(($mtime[1] + $mtime[0] - $_timer['start']), 6);
		unset($_timer['start']);
	}
}

function serverload() {
	if(@$fp = fopen('/proc/loadavg','r')) {
		$loadavg = explode(' ', @fread($fp, 6));
		@fclose($fp);
		return trim($loadavg[0]);
	} else {
		return FALSE;
	}
}

?>