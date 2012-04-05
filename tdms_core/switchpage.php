<?php
class SwitchPage extends Object {
/**
 * 기본 URL
 *
 * @var string
 * @access public
 */
	public $base = false;

/**
 * 현재 URL
 *
 * @var string
 * @access public
 */
	public $pageself = false;

	var $params = null;
/**
 * 생성자
 */
	function __construct($url = null, $base = false) {
		
		if ($url !== null) {
			return $this->SwitchPage($url);
		}
	}

	
	function SwitchPage($url = null, $cfgParams = array()) {
			$parse = true;

			if ($this->base == false) {
				$this->base = $this->baseUrl();
			}

			if ($url !== null) {
				$_GET['url'] = $url;
			}

			$url = $this->getUrl();
			$this->pageself = $this->base . '/' . $url;

			if ($parse) {
				$this->params = array_merge($this->parseParams($url), $cfgParams);
			}

			$arrTemp = explode('/', $url);

			// 접속하는 엘레먼트의 명칭이 대문자로 이루어져있으면 플러그인이다.
			if(strtoupper($arrTemp[0]) != $arrTemp[0] || !$arrTemp[0]){ // 플러그인이 아닐때
				$siteRoot = LOGIC_ROOT;
				$plug_element_prefix = "";
				$PlugInName	 = null;

				if(count($arrTemp) <= 1){
					// 메인 화면 출력
					$FileName	 = BASE_PAGE;
					$Element	 = BASE_PAGE;
					$ElementName = BASE_PAGE;

					if(!$arrTemp[0]){
						$pageName = BASE_PAGE; // 초기값 index로 고정
					}else{
						$pageName = $arrTemp[0];
					}
					$Class = $siteRoot.D_S.$FileName.".php";
					$config_file = $siteRoot.D_S.'config'.D_S.CONFIG_FILE_NAME.".xml";
				
				}else{
					$FileName	 = $arrTemp[0];
					$Element	 = $plug_element_prefix.$arrTemp[0];
					$ElementName = $arrTemp[0];
					$ElementDir  = $arrTemp[0];
					$pageName	 = $arrTemp[1];

					$config_file = $siteRoot.D_S.ELEMENTS.D_S.$ElementDir.D_S.'config'.D_S.CONFIG_FILE_NAME.".xml";
					$Class = $siteRoot.D_S.ELEMENTS.D_S.$ElementDir.D_S.$FileName.".php";

				}
				
				
			}else{	// 플러그인 일 때
				$Class = LOGIC_ROOT.D_S.BASE_PAGE.".php";
				require_once($Class);
				$arrTemp[0] = strtolower($arrTemp[0]);
				$siteRoot = LOGIC_ROOT.D_S.PLUGIN.D_S.$arrTemp[0];
				$msg = "-PLUGIN";
				$plug_element_prefix = "plugin_";

				$PlugInName	 = strtoupper($arrTemp[0]);

				if(count($arrTemp) <= 1){
					// 메인 화면 출력
					$FileName	 = BASE_PAGE;
					$Element	 = $plug_element_prefix.BASE_PAGE;
					$ElementName = $plug_element_prefix.BASE_PAGE;

					
					if(!$arrTemp[1]){
						$pageName = BASE_PAGE; // 초기값 index로 고정
					}else{
						$pageName = $arrTemp[1];
					}

					$Class = $siteRoot.D_S.$FileName.".php";
					$config_file = $siteRoot.D_S.'config'.D_S.CONFIG_FILE_NAME.".xml";
				}else{
					
					$FileName	 = $arrTemp[1];
					$Element     = $plug_element_prefix.$arrTemp[1];
					$ElementName = $arrTemp[1];
					$ElementDir  = $arrTemp[1];
					$pageName	 = $arrTemp[2];
					$config_file = $siteRoot.D_S.ELEMENTS.D_S.$ElementDir.D_S.'config'.D_S.CONFIG_FILE_NAME.".xml";
					$Class = $siteRoot.D_S.ELEMENTS.D_S.$ElementDir.D_S.$FileName.".php";
				}
			}


			// 환경설정 읽어오기
			if(file_exists($config_file)){
				//require_once($config_file);
				//$config_classname = "config_".$Element;
				//$objConfig =& new $config_classname();

				$objConfig =& new Config($config_file);
			}else{
				echo "[Error".$msg."] ".$ElementName."/config 에 환경설정 파일이 없습니다.";
				exit;
			}
			

			if(file_exists($Class)){
				// 클래스를 임포트 시킨다.
				require_once($Class);

				if (class_exists($Element)) {
					$element =& new $Element();

					if(method_exists ($element , $pageName)){
						$element->pageurl		= $url;
						$element->params		= &$this->params;
						$element->element		= $ElementName;
						$element->page			= $pageName;
						$element->siteRoot		= $siteRoot;
						$element->plugin		= $PlugInName;

						$this->params['element']	= $FileName;
						$this->params['page']		= $pageName;
						$element->params['config']	= $objConfig;

						

						if (array_key_exists('layout', $this->params)) {
							$element->layout = $this->params['layout'];
						}
					}else{
						echo "[Error".$msg."] 클래스 ".$ElementName."에 ".$pageName."가 정의되지 않았습니다.";
						exit;
					}
				}
			}else{
				
				echo "[Error".$msg."] ".$ElementName." 클래스 파일이 없습니다";
				exit;
			}

		return $this->PageShow($element, $this->params);
	}
/*
	변수를 설정하고 템플릿으로 계층 구조화 해 화면에 보여주며
	옵션에 따라 보여줄지 값으로 값으로 리턴할지 결정한다.
*/
	function PageShow(&$element, $params) {

		// 해당하는 메소드를 실행시킨다.
		if($params['page'] == "main"){
			$element->index();
		}else{
			$element->{$params['page']}();
		}

		if(($params['element'] == BASE_PAGE) and ($params['page'] == BASE_PAGE)){
			$subpage_filename = $element->siteRoot.D_S.'pages'.D_S.$params['page'].".php";
		}else{
			$subpage_filename =$element->siteRoot.D_S.ELEMENTS.D_S.$params['element'].D_S.'pages'.D_S.$params['page'].".php";
		}

		// 서브페이지가 없을 경우에는 실행하지 않는다.(컨트롤러만 실행)
		if(file_exists($subpage_filename)){

			// 환경설정 파일 포함
			$element->tplvar["CONFIG"]		= $element->params['config'];
			$element->tplvar["PAGEURL"]		= $element->pageurl;
			$element->tplvar["ELEMENT"]		= $element->element;
			$element->tplvar["SITEROOT"]	= $element->siteRoot;
			$element->tplvar["PLUGIN"]		= $element->plugin;

			// 하위 페이지 템플릿 가져오기
			if(is_array($element->tplvar)){
				extract($element->tplvar, EXTR_OVERWRITE);
			}

			ob_start(); 
				require_once($subpage_filename);
				$element->output = ob_get_contents();
			ob_get_clean();

			if(is_array($element->tplvar)){
				$element->tplvar = array_merge($element->tplvar ,array("MAIN_CONTENT" => $element->output));
			}else{
				$element->tplvar = array("MAIN_CONTENT" => $element->output);
			}

			// 레이아웃 템플릿 가져오기
			extract($element->tplvar, EXTR_REFS);
			ob_start(); 
			if(($params['element'] == BASE_PAGE) and ($params['page'] == BASE_PAGE)){
				include $element->siteRoot.D_S.'layout'.D_S.BASE_PAGE.'_layout.php';
			}else{
				include $element->siteRoot.D_S.'layout'.D_S.$element->layout.'_layout.php';
			}

			$contents = ob_get_contents();
			ob_get_clean();

			//만일 retout이 설정되어있으면 true면 내용을 리턴한다.
			if (isset($params['retout'])) {
				return $contents;
			}else{
				echo $contents;
			}

		}
		
	}

/**
 * Returns array of GET and POST parameters. GET parameters are taken from given URL.
 *
 * @param string $fromUrl URL to mine for parameter information.
 * @return array Parameters found in POST and GET.
 * @access public
 */
	function parseParams($fromUrl) {
		$params = array();
/*
		if (isset($_POST)) {
			$params['form'] = $_POST;
			//if (ini_get('magic_quotes_gpc') == 1) {
			//	$params['form'] = stripslashes_deep($params['form']);
			//}
			if (env('HTTP_X_HTTP_METHOD_OVERRIDE')) {
				$params['form']['_method'] = env('HTTP_X_HTTP_METHOD_OVERRIDE');
			}
			if (isset($params['form']['_method'])) {
				if (isset($_SERVER) && !empty($_SERVER)) {
					$_SERVER['REQUEST_METHOD'] = $params['form']['_method'];
				} else {
					$_ENV['REQUEST_METHOD'] = $params['form']['_method'];
				}
				unset($params['form']['_method']);
			}
		}
		//extract(Router::getNamedExpressions());
		//include CONFIGS . 'routes.php';
		//$params = array_merge(Router::parse($fromUrl), $params);

		if (empty($params['action'])) {
			$params['action'] = 'index';
		}

		if (isset($params['form']['data'])) {
			$params['data'] = Router::stripEscape($params['form']['data']);
			unset($params['form']['data']);
		}
*/
		if (isset($_GET)) {
			/*
			if (ini_get('magic_quotes_gpc') == 1) {
				$url = forStripSlashes($_GET);
			} else {
				$url = $_GET;
			}*/
			if (isset($params['url'])) {
				$params['url'] = array_merge($params['url'], $url);
			} else {
				$params['url'] = $url;
			}
		}
/*
		foreach ($_FILES as $name => $data) {
			if ($name != 'data') {
				$params['form'][$name] = $data;
			}
		}

		if (isset($_FILES['data'])) {
			foreach ($_FILES['data'] as $key => $data) {
				foreach ($data as $model => $fields) {
					foreach ($fields as $field => $value) {
						if (is_array($value)) {
							foreach ($value as $k => $v) {
								$params['data'][$model][$field][$k][$key] = $v;
							}
						} else {
							$params['data'][$model][$field][$key] = $value;
						}
					}
				}
			}
		}
*/
		return $params;
	}
/**
 * Returns a base URL and sets the proper webroot
 *
 * @return string Base URL
 * @access public
 */
	function baseUrl() {
		$dir = $webroot = null;
		//$config = Configure::read('App');
		//extract($config);

		if (!$base) {
			$base = $this->base;
		}

		if ($base !== false) {
			$this->webroot = $base . '/';
			return $this->base = $base;
		}

		if (!$baseUrl) {
			$base = dirname(env('PHP_SELF'));

			if ($webroot === 'webroot' && $webroot === basename($base)) {
				$base = dirname($base);
			}
			if ($dir === 'app' && $dir === basename($base)) {
				$base = dirname($base);
			}

			if ($base === DS || $base === '.') {
				$base = '';
			}

			$this->webroot = $base .'/';
			return $base;
		}
		$file = null;

		if ($baseUrl) {
			$file = '/' . basename($baseUrl);
			$base = dirname($baseUrl);

			if ($base === DS || $base === '.') {
				$base = '';
			}
			$this->webroot = $base .'/';

			if (strpos($this->webroot, $dir) === false) {
				$this->webroot .= $dir . '/' ;
			}
			if (strpos($this->webroot, $webroot) === false) {
				$this->webroot .= $webroot . '/';
			}
			return $base . $file;
		}
		return false;
	}

/**
 * Returns the REQUEST_URI from the server environment, or, failing that,
 * constructs a new one, using the PHP_SELF constant and other variables.
 *
 * @return string URI
 * @access public
 */
	function uri() {
		foreach (array('HTTP_X_REWRITE_URL', 'REQUEST_URI', 'argv') as $var) {
			if ($uri = env($var)) {
				if ($var == 'argv') {
					$uri = $uri[0];
				}
				break;
			}
		}
		//$base = preg_replace('/^\//', '', '' . Configure::read('App.baseUrl'));

		if ($base) {
			$uri = preg_replace('/^(?:\/)?(?:' . preg_quote($base, '/') . ')?(?:url=)?/', '', $uri);
		}

		if (PHP_SAPI == 'isapi') {
			$uri = preg_replace('/^(?:\/)?(?:\/)?(?:\?)?(?:url=)?/', '', $uri);
		}

		if (!empty($uri)) {
			if (key($_GET) && strpos(key($_GET), '?') !== false) {
				unset($_GET[key($_GET)]);
			}
			$uri = preg_split('/\?/', $uri, 2);

			if (isset($uri[1])) {
				parse_str($uri[1], $_GET);
			}
			$uri = $uri[0];
		} elseif (empty($uri) && is_string(env('QUERY_STRING'))) {
			$uri = env('QUERY_STRING');
		}

		if (strpos($uri, 'index.php') !== false) {
			list(, $uri) = explode('index.php', $uri, 2);
		}

		if (empty($uri) || $uri == '/' || $uri == '//') {
			return '';
		}
		return str_replace('//', '/', '/' . $uri);
	}
/**
 * Returns and sets the $_GET[url] derived from the REQUEST_URI
 *
 * @param string $uri Request URI
 * @param string $base Base path
 * @return string URL
 * @access public
 */
	function getUrl($uri = null, $base = null) {
		if (empty($_GET['url'])) {
			if ($uri == null) {
				$uri = $this->uri();
			}

			if ($base == null) {
				$base = $this->base;
			}
			$url = null;
			$tmpUri = preg_replace('/^(?:\?)?(?:\/)?/', '', $uri);
			$baseDir = preg_replace('/^\//', '', dirname($base)) . '/';

			if ($tmpUri === '/' || $tmpUri == $baseDir || $tmpUri == $base) {
				$url = $_GET['url'] = '/';
			} else {
				if ($base && strpos($uri, $base) !== false) {
					$element = explode($base, $uri);
				} elseif (preg_match('/^[\/\?\/|\/\?|\?\/]/', $uri)) {
					$element = array(1 => preg_replace('/^[\/\?\/|\/\?|\?\/]/', '', $uri));
				} else {
					$element = array();
				}

				if (!empty($element[1])) {
					$_GET['url'] = $element[1];
					$url = $element[1];
				} else {
					$url = $_GET['url'] = '/';
				}

				if (strpos($url, '/') === 0 && $url != '/') {
					$url = $_GET['url'] = substr($url, 1);
				}
			}
		} else {
			$url = $_GET['url'];
		}

		if ($url{0} == '/') {
			$url = substr($url, 1);
		}
		return $url;
	}
/**
 * Outputs cached dispatch for js, css, img, view cache
 *
 * @param string $url Requested URL
 * @access public
 */
/*
	function cached($url) {
		if (strpos($url, 'css/') !== false || strpos($url, 'js/') !== false || strpos($url, 'img/') !== false) {
			if (strpos($url, 'ccss/') === 0) {
				include WWW_ROOT . DS . Configure::read('Asset.filter.css');
				$this->_stop();
			} elseif (strpos($url, 'cjs/') === 0) {
				include WWW_ROOT . DS . Configure::read('Asset.filter.js');
				$this->_stop();
			}
			$isAsset = false;
			$assets = array('js' => 'text/javascript', 'css' => 'text/css', 'gif' => 'image/gif', 'jpg' => 'image/jpeg', 'png' => 'image/png');
			$ext = array_pop(explode('.', $url));

			foreach ($assets as $type => $contentType) {
				if ($type === $ext) {
					if ($type === 'css' || $type === 'js') {
						$pos = strpos($url, $type . '/');
					} else {
						$pos = strpos($url, 'img/');
					}
					$isAsset = true;
					break;
				}
			}

			if ($isAsset === true) {
				$ob = @ini_get("zlib.output_compression") !== true && extension_loaded("zlib") && (strpos(env('HTTP_ACCEPT_ENCODING'), 'gzip') !== false);

				if ($ob && Configure::read('Asset.compress')) {
					ob_start();
					ob_start('ob_gzhandler');
				}
				$assetFile = null;
				$paths = array();

				if ($pos > 0) {
					$plugin = substr($url, 0, $pos - 1);
					$url = str_replace($plugin . '/', '', $url);
					$pluginPaths = Configure::read('pluginPaths');
					$count = count($pluginPaths);
					for ($i = 0; $i < $count; $i++) {
						$paths[] = $pluginPaths[$i] . $plugin . DS . 'vendors' . DS;
					}
				}
				$paths = array_merge($paths, Configure::read('vendorPaths'));

				foreach ($paths as $path) {
					if (is_file($path . $url) && file_exists($path . $url)) {
						$assetFile = $path . $url;
						break;
					}
				}

				if ($assetFile !== null) {
					$fileModified = filemtime($assetFile);
					header("Date: " . date("D, j M Y G:i:s ", $fileModified) . 'GMT');
					header('Content-type: ' . $assets[$type]);
					header("Expires: " . gmdate("D, j M Y H:i:s", time() + DAY) . " GMT");
					header("Cache-Control: cache");
					header("Pragma: cache");
					if ($type === 'css' || $type === 'js') {
						include($assetFile);
					} else {
						readfile($assetFile);
					}

					if(Configure::read('Asset.compress')) {
						ob_end_flush();
					}
					return true;
				}
			}
		}

		if (Configure::read('Cache.check') === true) {
			$path = $this->pageself;
			if ($this->pageself == '/') {
				$path = 'home';
			}
			$path = Inflector::slug($path);

			$filename = CACHE . 'views' . DS . $path . '.php';

			if (!file_exists($filename)) {
				$filename = CACHE . 'views' . DS . $path . '_index.php';
			}

			if (file_exists($filename)) {
				if (!class_exists('View')) {
					App::import('Core', 'View');
				}
				$element = null;
				$view =& new View($element, false);
				return $view->renderCache($filename, getMicrotime());
			}
		}
		return false;
	}
*/
}
?>