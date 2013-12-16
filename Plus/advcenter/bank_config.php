<?php
//Discuz! 3.1.2 Plus Hack config file, DO NOT modify me!
//Created on Sep 10, 2004, 18:54

$banksettings=Array
	(
	'version' => '1.0.0',
	'moneyname' => '元',
	'accrual' => '0.015',
	'groups' => '1',
	'showpostpay' => '1',
	'close' => '0',
	'message' => '對不起，本銀行現在停業整頓。請稍後再來。',
	'allowchange' => '1',
	'minsave' => '100',
	'changetax' => '0.1',
	'allowsell' => '1',
	'buy' => '10',
	'sell' => '8',
	'selltax' => '0.1'
	);

$bankgroup=Array
	(
	0 => Array
		(
		'name' => '飢寒交迫',
		'min' => '-99999',
		'max' => '10'
		),
	1 => Array
		(
		'name' => '一貧如洗',
		'min' => '10',
		'max' => '50'
		),
	2 => Array
		(
		'name' => '家徒四壁',
		'min' => '50',
		'max' => '200'
		),
	3 => Array
		(
		'name' => '錦衣美食',
		'min' => '200',
		'max' => '300'
		),
	4 => Array
		(
		'name' => '奔向小康',
		'min' => '300',
		'max' => '500'
		),
	5 => Array
		(
		'name' => '大富之家',
		'min' => '500',
		'max' => '1000'
		),
	6 => Array
		(
		'name' => '金玉滿堂',
		'min' => '1000',
		'max' => '5000'
		),
	7 => Array
		(
		'name' => '玉樓銀海',
		'min' => '5000',
		'max' => '10000'
		),
	8 => Array
		(
		'name' => '揮金如土',
		'min' => '10000',
		'max' => '50000'
		),
	9 => Array
		(
		'name' => '富甲天下',
		'min' => '50000',
		'max' => '100000'
		),
	10 => Array
		(
		'name' => '富可敵國',
		'min' => '100000',
		'max' => '99999999'
		),
	11 => Array
		(
		'name' => '印鈔機',
		'min' => '99999999',
		'max' => '999999999999'
		)
	)

?>