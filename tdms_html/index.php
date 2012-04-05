<?php
	if (!defined('D_S')) {
		define('D_S', DIRECTORY_SEPARATOR);
	}
	if (!defined('ROOT')) {
		define('ROOT', dirname(dirname(dirname(__FILE__))));
	}
	if (!defined('CORE_ROOT')) {
		define('CORE_ROOT', ROOT.D_S .'htdocs'.D_S.'tdms_core');
	}

	if (!defined('LOGIC_ROOT')) {
		define('LOGIC_ROOT', ROOT. D_S.'htdocs' . D_S . 'tdms_logic');
	}

	if (!defined('WWW_ROOT')) {
		define('WWW_ROOT', ROOT. D_S.'htdocs' . D_S . 'tdms_html');
	}

	define('CORE_LIB_ROOT',CORE_ROOT .D_S .'libs');
	define('CORE_EXT_LIB_ROOT',CORE_ROOT .D_S .'ext_libs');

	$session_tmp_dir = WWW_ROOT.D_S."temp";
	session_save_path ($session_tmp_dir);

	// function     (include    )
	require_once CORE_ROOT . D_S . 'libs' . D_S . 'func.php';

	require_once LOGIC_ROOT . D_S . 'config' . D_S . 'alias.php';
	require_once CORE_ROOT . D_S . 'config' . D_S . 'alias.php';

	require_once LOGIC_ROOT . D_S . 'config' . D_S . 'init.php';
	require_once CORE_ROOT . D_S . 'config' . D_S . 'init.php';

	require_once CORE_ROOT . D_S . 'libs' . D_S . 'config.php';
	require_once CORE_ROOT . D_S . 'libs' . D_S . 'object.php';
	require_once CORE_ROOT . D_S . 'libs' . D_S . 'Element.php';
	
	require_once ROOT . D_S . 'htdocs' . D_S . 'tdms_core' . D_S . 'switchpage.php';

	$Dispatcher = new SwitchPage();
	$Dispatcher->SwitchPage($url);
?>
