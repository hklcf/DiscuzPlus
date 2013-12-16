<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

$pollopts = unserialize($thread['pollopts']);
$polloptions = array();
foreach($pollopts['options'] as $option) {
	$totalvotes += $option[1];
	$polloptions[] = array(	'option'	=> dhtmlspecialchars($option[0]),
				'votes'		=> $option[1],
				'width'		=> @round($option[1] * 300 / $polloptions['max']) + 2,
				'percent'	=> @sprintf ("%01.2f", $option[1] * 100 / $polloptions['total'])
				);
}

$allowvote = $allowvote && $discuz_user && !in_array($discuz_user, $pollopts['voters']);
$optiontype = $pollopts['multiple'] ? 'checkbox' : 'radio';

?>