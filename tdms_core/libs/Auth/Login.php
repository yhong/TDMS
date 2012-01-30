<?

class Auth_Login extends Database_Manage{
	private $user_id;
	private $username;
	private $password;
	private $ok;
	private $salt = SESSION_SALT;
	private $domain = ".iiooiioo.com";

	public function Auth_Login(){
		parent::Database_Manage(GET_CONFIG("dsn"));
		$this->setTbName(MEMBER_TABLE);
		
		$this->user_id = 0;
		$this->username = "Guest";
		$this->ok = false;
		
		return $this->ok;
	}
	
	public function check_session(){
		if(!empty($_SESSION['auth_username']) && !empty($_SESSION['auth_password']))
			return $this->check($_SESSION['auth_username'], $_SESSION['auth_password']);
		else
			return false;
	}


	
	public function login($user_id, $user_password){
		if($this->countConditionRecordRow("userid = '".$user_id."' AND password = '".$user_password."'") == 1)
		{
			$this->user_id = $this->getFieldValue("userid", "userid = '".$username."'");
			$this->username =$this->getFieldValue("fullname", "userid = '".$username."'");
			$this->ok = true;

			$_SESSION['auth_username'] = $user_id;
			$_SESSION['auth_password'] = md5($user_password . $this->salt);

			return true;
		}

		return false;
	}		

	public function check($user_id, $user_password){
		if($this->countConditionRecordRow("username = '".$user_id."'") == 1)
		{
			
			$db_password = $this->getFieldValue("password", "userid = '".$user_id."'");
			if(md5($db_password . $this->salt) == $password)
			{
				$this->user_id = $this->getFieldValue("userid", "userid = '".$user_id."'");
				$this->username = $this->getFieldValue("fullname", "userid = '".$username."'");
				$this->ok = true;
				return true;
			}
		}			
		return false;
	}
	
	public function logout(){
		$this->user_id = 0;
		$this->username = "Guest";
		$this->ok = false;
		
		$_SESSION['auth_username'] = "";
		$_SESSION['auth_password'] = "";
		$result = $this->check_session();
		return !$result;
	}
}
?>