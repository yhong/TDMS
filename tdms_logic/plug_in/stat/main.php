<?
Class plugin_main extends main{

	// 메인 페이지
	public function plugin_main(){
		parent::main();
		$this->layout = "subpage";
		// $this->dbSessionObj으로 세션공유가 가능하다.
		
	}

	// 플러그인 명(소문자)과 같은 이름의 메소드를 포함해야 한다.
	public function stat(){

	}
}
?>