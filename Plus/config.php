<?php

/*
	Version: 1.1.3(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/11/08
*/

// ==================== �H�U�ܶq�ݮھڱz�����A�������ɭק� ====================
//	�`�N�G 	�p�G��Ʈw�s�������D,�Ф��n�V�ڭ̸߰ݨ���ѼƳ]�m,�лP�Ŷ����pô,
//		�]���ڭ̤]�L�k�i�D�z�o���ܶq���ӳ]�m�����

	$dbhost = 'localhost';			// �ƾڮw�A�Ⱦ�
	$dbuser = 'dbuser';				// �ƾڮw�Τ�W
	$dbpw = 'dbpw';				// �ƾڮw�K�X
	$dbname = 'plus';			// �ƾڮw�W
	$adminemail = '';		// �׾¨t�� Email

// ============================================================================



// =================================== �׾³]�m =================================

	$postnum ='10';				// �峹�̤p�r��
	$bankmanager ='lai107';			// �Ȧ���
	$showdetails ="1";				// �b�u�C��}/�� (0=���A1=�})
	$reseller ='10';				// ���ˤH�i�H�W�[���n��
	$date ='08-Nov-2004';			// �}�¤��
	$IP ='1';					// �P�@IP�b 24 �p�ɤ����U ID ���ƶq
	$karma_view = 1;				// ��ݵ����O���}/��  (0=���A1=�})
	$karma_adminview = 1;			// ���D��ݵ����O���}/��  (0=���A1=�})
	$karma_perpage = 20;			// �C����ܦh�֭ӵ����H���O��

// ============================================================================



// ============= �p�z�� cookie �@�νd�򦳯S��n�D,�Эק�U���ܶq ==============

	$cookiepath = '/';				// cookie �@�θ��| (�p�X�{�n�J���D�Эק惡��)
	$cookiedomain = ''; 			// cookie �@�ΰ� (�p�X�{�n�J���D�Эק惡��)

// ============================================================================



// ============= Discuz! �~���A�t�m�M�ϥΤ�k�Ա��аѦ� plugin.txt ============

$plugins[] = array (	'name'   => '���󤤤�',
			'script' => '',
			'url'    => '',
			'cpurl'  => 'admincp.php?action=advcenter'	);

$plugins[] = array (	'name'   => '�޲z�ζ�',
			'script' => '',
			'url'    => 'disadmin.php',
			'cpurl'  => ''	);

$plugins[] = array (	'name'   => '�׾»Ȧ�',
			'script' => '',
			'url'    => 'bank.php',
			'cpurl'  => 'admincp.php?action=advcenter&hackname=bank'	);

$plugins[] = array (	'name'   => '��W����',
			'script' => '',
			'url'    => 'chname.php',
			'cpurl'  => 'admincp.php?action=advcenter&hackname=chname'	);

$plugins[] = array (	'name'   => '�p���ӽ�',
			'script' => '',
			'url'    => 'link.php',
			'cpurl'  => 'admincp.php?action=advcenter&hackname=link'	);

$plugins[] = array (	'name'   => '�ƾڲM�z',
			'script' => '',
			'url'    => '',
			'cpurl'  => 'admincp.php?action=advcenter&hackname=datasweep'	);

$plugins[] = array (	'name'   => '�ϵs�s�]�m',
			'script' => '',
			'url'    => '',
			'cpurl'  => 'admincp.php?action=advcenter&hackname=antisteal'	);

// ============================================================================



// ================= �H�U�ܶq���S�O�ﶵ�A�@�뱡�p�U�S�����n�ק� ================

	$headercharset = 0;				// �j��]�m�r����, 0=�_, 1=�O. �u�ýX�ɨϥ�
	$onlinehold = 600;				// �b�u�O���ɶ�(��)


	// �׾§�J�ϥΫᤣ��ק諸�ܶq

	$tablepre = 'cdb_';   				// ��W�e��, �P�@��Ʈw�w�˦h�ӽ׾½Эק惡�B
	$attachdir = './attachments';			// ����O�s��m (���A�����|, �ݩ� 777, ����
						// �� web �i�X�ݨ쪺�ؿ��A ���[ "/")
	$attachurl = 'attachments';			// ������| URL �a�} (�i����e URL �U���۹�a�}�� http:// �}�Y������a�}, ���[ "/")


	// ���ŭק�H�U�ܶq,�Ȩѵ{�Ƕ}�o�ոե�!

	$database = 'mysql';			// MySQL �����г]�m 'mysql', PgSQL �����г]�m 'pgsql'
	$tplrefresh = 1;				// �Ҫ��۰ʭ��s��z�}�� 0=�����A 1=���}
	$pconnect = 0;				// �ƾڮw���[�s�� 0=�����A 1=���}

// ============================================================================



// =============================== �K�l�R��t�m ===============================

$hacktable_postpay = 'cdb_postpay';
$allowpostpay =1;
$cnteacher_postsell_maxprice =10000;
$cnteacher_paylist_perpage =20;

// ============================================================================



// =============================== ��W���߰t�m ===============================

$table_chname='cdb_chname';

// ============================================================================