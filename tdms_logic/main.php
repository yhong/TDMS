<?

Class main extends Element{
	protected $dbSessionObj;

	public function main(){
		$this->dbSessionObj = LOAD_MODULE("Auth.Login");
	}

	// 메인 페이지
	public function index(){
		$this->layout = "index";

	}

	public function Login(){
		if($this->dbSessionObj->login($_POST["user_id"], $_POST["user_password"]) == true){
			$this->goLink("/");
		}else{
			echo "<script>alert('로그인 에러');</script>";
		}

	}

	public function Logout(){
		if($this->dbSessionObj->logout() == true){
			$this->goLink("/");
		}else{
			echo "<script>alert('로그아웃 에러');</script>";
		}

	}
}
?>