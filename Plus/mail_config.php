<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

$sendmail_silent = 1;		// �B���l��o�e�����������~����(����)�A 1=�O�A 0=�_
$mailsend = 1;			// �l��o�e�覡	0=���o�e����l��
				//		1=�q�L PHP ��Ƥ� UNIX sendmail �o�e(���˦��覡)
				//		2=�q�L SOCKET �s�� SMTP ���A���o�e(�䴩 ESMTP ����)
				//		3=�q�L PHP ��� SMTP �o�e Email(�� win32 �U����, ���䴩 ESMTP)
				//
				// �i�q�L utilities/testmail.php ���ձz���t�Τ䴩���ضl��o�e�覡

if($mailsend == 1) {

	// �q�L PHP ��Ƥ� UNIX sendmail �o�H(�L�ݰt�m)

} elseif($mailsend == 2) {	// �q�L Discuz! SMTP �Ҷ��o�H

	$mailcfg['server'] = 'smtp.21cn.com';			// SMTP ���A��
	$mailcfg['port'] = '25';				// SMTP �ݤf, �w�]���ݭק�
	$mailcfg['auth'] = 1;					// �O�_�ݭn AUTH LOGIN ���ҡA 1=�O, 0=�_
	$mailcfg['from'] = 'Discuz <myaccount@21cn.com>';	// �o�H�H�a�} (�p�G�ݭn���ҡA�����������A���a�})
	$mailcfg['auth_username'] = 'myaccount';		// ���ҥΤ�W
	$mailcfg['auth_password'] = 'password';			// ���ұK�X

} elseif($mailsend == 3) {	// �q�L PHP ��Ƥ� SMTP ���A���o�H

	$mailcfg['server'] = 'smtp.your.com';		// SMTP ���A���A �H�U�]�m�ȹ� WIN32 �t�Φ���
	$mailcfg['port'] = '25';			// SMTP �ݤf, �w�]���ݭק�

}

?>