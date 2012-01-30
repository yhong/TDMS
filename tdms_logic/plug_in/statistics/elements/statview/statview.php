<?
Class plugin_statview extends main{
	private $Page;
	private $PageG;
	private $intStartNum;
	
	private $plist_link;

	private $how;
	private $how2;
	private $order;
	private $workYear;

	private $board_id;
	private $index;

	function plugin_statview(){
		$this->layout = "main";


		if(!$_GET["Page"]){
			$this->Page = 1;  // 페이지 번호
		}else{
			$this->Page = $_GET["Page"];
		}
		if(!$_GET["PageG"]){ // 페이지 리스트 위치
			$this->PageG = 1;
		}else{
			$this->PageG = $_GET["PageG"];
		}
		if(!$_GET["intStartNum"]){
			$this->intStartNum = 0;
		}else{
			$_GET["intStartNum"] = $this->intStartNum;
		}

		if($_GET[how] == "desc"){
			$this->how = "asc";
			$this->how2 = "desc";
		}else{
			$this->how = "desc";
			$this->how2 = "asc";
		}

		if (!$_GET[order]){
			$this->order = GET_FIELD_NAME("일련번호");
			$this->how = "desc";
		}else{
			$this->order = $_GET["order"];
			$this->how = $_GET["how"];
		}	

		if (!$_GET["workYear"]){
			$this->workYear = date('Y');
		}else{
			$this->workYear = $_GET["workYear"];
		}

		$this->index = $_GET["index"];
		$this->board_id = $_GET["board_id"];
		
	

	}

	public function search_basic(){
		$dbObj = LOAD_MODULE("Board.FormList");
		$dbconn = $dbObj->getConResource();
	    $this->display(array("dbconn"=>$dbconn));
	}

	public function search(){
		$dbObj = LOAD_MODULE("Board.FormList");
		$dbconn = $dbObj->getConResource();
	    $this->display(array("dbconn"=>$dbconn));
	}

	public function search1(){
		$dbObj = LOAD_MODULE("Board.FormList");
		$dbconn = $dbObj->getConResource();
	    $this->display(array("dbconn"=>$dbconn));
	}

	public function search2(){
		$dbObj = LOAD_MODULE("Board.FormList");
		$dbconn = $dbObj->getConResource();
	    $this->display(array("dbconn"=>$dbconn));
	}
}

	?>