<?php
session_start();


/**
 * 5.0이하에서 사용할 함수 선언
 */
if (!function_exists('clone')) {
	if (version_compare(PHP_VERSION, '5.0') < 0) {
		eval ('
		function clone($object)
		{
			return $object;
		}');
	}
}

/**
 * 파라메터를 통해 관련 라이브러리를 로드한다.
 *
 * 사용예:
 * loadLibrary('Archive/Zip', 'Board/FormList');
 *
 * @param .php를 뺀 이름만 넣는다.
 */
function LOAD_LIBRARY() {
	$params = func_get_args();
	foreach ($params as $filename) {
		//require_once(CORE_LIB_ROOT. D_S . strtolower($filename) . '.php');
		require_once(CORE_LIB_ROOT. D_S . $filename . '.php');

	}
}


function LOAD_MODULE($module_name) {
	$target_chr = array(".", "-", "@", "#");
	$replace   = '_';

	$modulename = str_replace($target_chr, $replace, $module_name);
	return new $modulename();

}

// 환경설정 객체 반환
function GET_CONFIG($index){
	// 환경설정 읽어오기
	$arrTemp = explode('/', $_GET["url"]);
	

	if(strtoupper($arrTemp[0]) != $arrTemp[0] || !$arrTemp[0]){
		$siteRoot	 = LOGIC_ROOT;
		if(!$arrTemp[0] || count($arrTemp) <= 1)
			$ElementName = "main";
		else{
			$ElementDir  = strtolower($arrTemp[0]);
			$ElementName = strtolower($arrTemp[0]);
		}
		$plug_element_prefix = "";
	}else{
		$Class = LOGIC_ROOT.D_S.BASE_PAGE.".php";
		require_once($Class);
		$arrTemp[0] = strtolower($arrTemp[0]);
		$ElementDir  = strtolower($arrTemp[1]);

		if(!$arrTemp[0] || count($arrTemp) <= 1){
			$ElementName = "main";
		}else{
			$ElementName = strtolower($arrTemp[1]);
		}
		$siteRoot = LOGIC_ROOT.D_S.PLUGIN.D_S.$arrTemp[0];
		$plug_element_prefix = "plugin_";
		$msg = "-PLUGIN";
	}

	if(count($arrTemp) <= 1 || $_GET["url"] == "/"){
		$config_file = $siteRoot.D_S.'config'.D_S.CONFIG_FILE_NAME.".xml";
	}else{
		$config_file = $siteRoot.D_S.ELEMENTS.D_S.$ElementDir.D_S.'config'.D_S.CONFIG_FILE_NAME.".xml";
	}

	if(file_exists($config_file)){
		//require_once($config_file);

		//$config_classname = "config_".$plug_element_prefix.strtolower($ElementName);

		//$objConfig =& new $config_classname();
		$objConfig =& new Config($config_file);
		
	}else{
		echo "[Error".$msg."] ".$arrTemp[0]."/config 에 환경설정 파일이 없습니다.";
		exit;
	}

	if(isset($index)){
		if(!$objConfig->{$index} && $objConfig->{$index} != null){
			echo "[ERROR".$msg."]환경설정에 ".$index."항목이 없습니다. 확인해주세요!";
			exit;
		}
		return $objConfig->{$index};
	}else{
		return $objConfig;
	}

}

/**
 * 환경설정에서 테이블의 이름을 가져온다.
 *
 * @param  string $ID 환경설정의 변수명
 * @return array 테이블 정보 배열
 */
function GET_FIELD_NAME($ID){
	$arr_tb_field = GET_CONFIG("arrTbField");
	return $arr_tb_field[$ID][0];
}

/**
 * 환경설정에서 테이블의 타입을 가져온다.
 *
 * @param  string $ID 환경설정의 변수명
 * @return array 테이블 정보 배열
 */
function GET_FIELD_TYPE($ID){
	$arr_tb_field = GET_CONFIG("arrTbField");
	return $arr_tb_field[$ID][3];
}

/**
 * HTML 블럭을 가져와서 포함시킨다.
 *
 * @param  string $ID 환경설정의 변수명
 * @return array 테이블 정보 배열
 */
function GET_HTML_BLOCK($filename){
	include LOGIC_ROOT.D_S.HTML_BLOCK.D_S.$filename.".php";
}


/**
 * 환경설정 정보를 가져온다.
 *
 * @param  string $key 환경설정의 변수명
 * @return string 환경설정 내용
 */
	function ENV($key) {
		if ($key == 'HTTPS') {
			if (isset($_SERVER) && !empty($_SERVER)) {
				return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
			}
			return (strpos(env('SCRIPT_URI'), 'https://') === 0);
		}

		if ($key == 'SCRIPT_NAME') {
			if (env('CGI_MODE') && isset($_ENV['SCRIPT_URL'])) {
				$key = 'SCRIPT_URL';
			}
		}

		$val = null;
		if (isset($_SERVER[$key])) {
			$val = $_SERVER[$key];
		} elseif (isset($_ENV[$key])) {
			$val = $_ENV[$key];
		} elseif (getenv($key) !== false) {
			$val = getenv($key);
		}

		if ($key == 'REMOTE_ADDR' && $val == env('SERVER_ADDR')) {
			$addr = env('HTTP_PC_REMOTE_ADDR');
			if ($addr != null) {
				$val = $addr;
			}
		}

		if ($val !== null) {
			return $val;
		}

		switch ($key) {
			case 'SCRIPT_FILENAME':
				if (defined('SERVER_IIS') && SERVER_IIS === true){
					return str_replace('\\\\', '\\', env('PATH_TRANSLATED') );
				}
			break;
			case 'DOCUMENT_ROOT':
				$offset = 0;
				if (!strpos(env('SCRIPT_NAME'), '.php')) {
					$offset = 4;
				}
				return substr(env('SCRIPT_FILENAME'), 0, strlen(env('SCRIPT_FILENAME')) - (strlen(env('SCRIPT_NAME')) + $offset));
			break;
			case 'PHP_SELF':
				return r(env('DOCUMENT_ROOT'), '', env('SCRIPT_FILENAME'));
			break;
			case 'CGI_MODE':
				return (PHP_SAPI == 'cgi');
			break;
			case 'HTTP_BASE':
				$host = env('HTTP_HOST');
				if (substr_count($host, '.') != 1) {
					return preg_replace ('/^([^.])*/i', null, env('HTTP_HOST'));
				}
			return '.' . $host;
			break;
		}
		return null;
}

/**
 *  url prefix 설정
 */
if (!defined('FULL_BASE_URL')) {
	$s = null;
	if (ENV('HTTPS')) {
		$s ='s';
	}

	$httpHost = ENV('HTTP_HOST');

	if (isset($httpHost)) {
		define('FULL_BASE_URL', 'http'.$s.'://'.$httpHost);
	}
	unset($httpHost, $s);
}

/**
 * 인클루드 파일을 위한 파일 존재탐색
 *
 * @param string $file 찾을 파일
 * @return 파일이 있는지 여부
 */
 /*
	function FILE_EXISTS_IN_PATH($file) {
		$paths = explode(PATH_SEPARATOR, ini_get('include_path'));
		foreach ($paths as $path) {
			$fullPath = $path . DIRECTORY_SEPARATOR . $file;

			if (file_exists($fullPath)) {
				return $fullPath;
			} elseif (file_exists($file)) {
				return $file;
			}
		}
		return false;
	}
	*/

/**
 * 마이크로시간을 얻는다.
 *
 * @return float 마이크로 시간
 */
 /*
	function GET_MICROTIME() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
}
*/


?>