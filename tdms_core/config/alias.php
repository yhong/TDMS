<?php
	/**
	 * 타이밍 함수를 위한 기본 정의
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


// 세션 사용을 초기값 false로 둠
if (!defined('USE_SESSION')) {
	define ('USE_SESSION', 'false');
}


/**
 * 이미지의 웹 디렉토리 경로
 */
if (!defined('IMG_PATH')) {
	define ('IMAGES_URL', 'img/');
}
/**
 * css의 웹 디렉토리 경로
 */
if (!defined('CSS_PATH')) {
	define ('CSS_PATH', 'css/');
}
/**
 * 자바스크립트의 웹 디렉토리 경로
 */
if (!defined('JS_PATH')) {
	define ('JS_PATH', 'js/');
}

if (!defined('TEMP_PATH')) {
	define ('TEMP_PATH', 'temp/');
}

// 첫페이지의 파일명
if (!defined('BASE_PAGE')) {
	define ('BASE_PAGE', 'main');
}

// 사이트 타이틀
if (!defined('PAGE_TITLE')) {
	define ('PAGE_TITLE', 'TDMS');
}

// 환경설정 파일명
if (!defined('CONFIG_FILE_NAME')) {
	define ('CONFIG_FILE_NAME', 'config');
}

// 환경설정 파일명
if (!defined('CONFIG_DIR_NAME')) {
	define ('CONFIG_DIR_NAME', 'config');
}

// 환경설정 파일명
if (!defined('PAGES_DIR_NAME')) {
	define ('PAGES_DIR_NAME', 'pages');
}

// 환경설정 파일명
if (!defined('SQL_DIR_NAME')) {
	define ('SQL_DIR_NAME', 'sql');
}

// 환경설정 파일명
if (!defined('SQL_FILE_NAME')) {
	define ('SQL_FILE_NAME', 'sql');
}

// 환경설정 파일명
if (!defined('SKELECTON_FILE_NAME')) {
	define ('SKELECTON_FILE_NAME', 'skelecton');
}

// 컴포넌트블럭의 라이브러리 디렉토리 명 타이틀
if (!defined('COMPONENT_BLOCK')) {
	define ('COMPONENT_BLOCK', 'ComponentBlock');
}

// 데이터베이스의 라이브러리 디렉토리 명 타이틀
if (!defined('DATABASE')) {
	define ('DATABASE', 'Database');
}

// 게시판의 라이브러리 디렉토리 명 타이틀
if (!defined('BOARD')) {
	define ('BOARD', 'Board');
}

// html블럭의 디렉토리 명 타이틀
if (!defined('HTML_BLOCK')) {
	define ('HTML_BLOCK', 'html_block');
}

// Auth에서 사용하는 세션암호화의 문구
if (!defined('SESSION_SALT')) {
	define ('SESSION_SALT', "tdms_is_made_by_Eric");
}
// 플러그인 디렉토리 명
if (!defined('PLUGIN')) {
	define ('PLUGIN', "plug_in");
}

// 엘레먼트의 디렉토리 명
if (!defined('ELEMENTS')) {
	define ('ELEMENTS', "elements");
}

define('LOGIC_ELEMENT_ROOT', LOGIC_ROOT . D_S . ELEMENTS);

?> 