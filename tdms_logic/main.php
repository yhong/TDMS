<?

Class main extends Element{
	protected $dbSessionObj;

	public function main(){
		$this->dbSessionObj = LOAD_MODULE("Auth.Login");
	}

	// ���� ������
	public function index(){
		$this->layout = "index";

	}

	public function Login(){
		if($this->dbSessionObj->login($_POST["user_id"], $_POST["user_password"]) == true){
			$this->goLink("/");
		}else{
			echo "<script>alert('�α��� ����');</script>";
		}

	}

	public function Logout(){
		if($this->dbSessionObj->logout() == true){
			$this->goLink("/");
		}else{
			echo "<script>alert('�α׾ƿ� ����');</script>";
		}

	}
}
?>