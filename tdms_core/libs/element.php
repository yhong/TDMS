<?
// 최상위 객체
class Element {
	// page url 기본
	public $pageurl;

	// 파라메터
	public $params;

	// 레이아웃 파일명(확장자 생략)
	public $layout;

	// 전체 페이지 내용을 출력할 변수
	public $output;

	// element = 클래스명
	public $element;
	
	// element 루트(플러그인 고려)
	public $siteRoot;

	// page = 메소드명
	public $page;

	// 템플릿 변수(배열형태)
	public $tplvar = array();

	// plugin = 플러그인 명
	public $plugin;

	public function Element(){

	}

	public function goLink($link){
		echo "<meta http-equiv=\"refresh\" content=\"0;url=".$link."\">";
	}
	

	// 화면에 표현할 메소드
	public function display($arrShowParam){

		// 화면에 뿌려 줄 내용을 tplvar에 먼저 넣어야 한다.
		// 넣은 tplvar값은 switchPage에서 화면으로 출력되게 된다.

		$this->tplvar = array_merge($this->tplvar, $arrShowParam);  

	}

}
?>