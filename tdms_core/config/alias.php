<?php
	/**
	 * Ÿ�̹� �Լ��� ���� �⺻ ����
	 */
	define('SECOND', 1);
	define('MINUTE', 60 * SECOND);
	define('HOUR', 60 * MINUTE);
	define('DAY', 24 * HOUR);
	define('WEEK', 7 * DAY);
	define('MONTH', 30 * DAY);
	define('YEAR', 365 * DAY);


	if (!defined('ROOT')) {
		define ('ROOT', '../');
	}
	if (!defined('WEBROOT_DIR')) {
		define ('WEBROOT_DIR', 'tdms_html');
	}

	define ('CSS', WWW_ROOT.'css'.DS);
	define ('JS', WWW_ROOT.'js'.DS);
	define ('IMAGES', WWW_ROOT.'img'.DS);


// ���� ����� �ʱⰪ false�� ��
if (!defined('USE_SESSION')) {
	define ('USE_SESSION', 'false');
}


/**
 * �̹����� �� ���丮 ���
 */
if (!defined('IMG_PATH')) {
	define ('IMAGES_URL', 'img/');
}
/**
 * css�� �� ���丮 ���
 */
if (!defined('CSS_PATH')) {
	define ('CSS_PATH', 'css/');
}
/**
 * �ڹٽ�ũ��Ʈ�� �� ���丮 ���
 */
if (!defined('JS_PATH')) {
	define ('JS_PATH', 'js/');
}

if (!defined('TEMP_PATH')) {
	define ('TEMP_PATH', 'temp/');
}

// ù�������� ���ϸ�
if (!defined('BASE_PAGE')) {
	define ('BASE_PAGE', 'main');
}

// ����Ʈ Ÿ��Ʋ
if (!defined('PAGE_TITLE')) {
	define ('PAGE_TITLE', 'TDMS');
}

// ȯ�漳�� ���ϸ�
if (!defined('CONFIG_FILE_NAME')) {
	define ('CONFIG_FILE_NAME', 'config');
}

// ȯ�漳�� ���ϸ�
if (!defined('CONFIG_DIR_NAME')) {
	define ('CONFIG_DIR_NAME', 'config');
}

// ȯ�漳�� ���ϸ�
if (!defined('PAGES_DIR_NAME')) {
	define ('PAGES_DIR_NAME', 'pages');
}

// ȯ�漳�� ���ϸ�
if (!defined('SQL_DIR_NAME')) {
	define ('SQL_DIR_NAME', 'sql');
}

// ȯ�漳�� ���ϸ�
if (!defined('SQL_FILE_NAME')) {
	define ('SQL_FILE_NAME', 'sql');
}

// ȯ�漳�� ���ϸ�
if (!defined('SKELECTON_FILE_NAME')) {
	define ('SKELECTON_FILE_NAME', 'skelecton');
}

// ������Ʈ���� ���̺귯�� ���丮 �� Ÿ��Ʋ
if (!defined('COMPONENT_BLOCK')) {
	define ('COMPONENT_BLOCK', 'ComponentBlock');
}

// �����ͺ��̽��� ���̺귯�� ���丮 �� Ÿ��Ʋ
if (!defined('DATABASE')) {
	define ('DATABASE', 'Database');
}

// �Խ����� ���̺귯�� ���丮 �� Ÿ��Ʋ
if (!defined('BOARD')) {
	define ('BOARD', 'Board');
}

// html���� ���丮 �� Ÿ��Ʋ
if (!defined('HTML_BLOCK')) {
	define ('HTML_BLOCK', 'html_block');
}

// Auth���� ����ϴ� ���Ǿ�ȣȭ�� ����
if (!defined('SESSION_SALT')) {
	define ('SESSION_SALT', "tdms_is_made_by_Eric");
}
// �÷����� ���丮 ��
if (!defined('PLUGIN')) {
	define ('PLUGIN', "plug_in");
}

// ������Ʈ�� ���丮 ��
if (!defined('ELEMENTS')) {
	define ('ELEMENTS', "elements");
}

define('LOGIC_ELEMENT_ROOT', LOGIC_ROOT . D_S . ELEMENTS);

?> 