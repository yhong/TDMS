<?
/**
* DBConnect class for my project.
*
* DBConnect class set DB Connection
*
* @autor Hong Young Hoon <eric.hong81@gmail.com>;
* @version 0.2
* @access public
* @package Database
*/

// pear/DB 패키지를 상속받아
// 기본적으로 데이터 베이스를 다루는데 필요한 기능 모음


class Database_Connect extends DB{
	
	/**
    * database관련 변수
    * @var string
    * @see DBConnect()
    */
	protected $dsn;
	protected $db_conn;

	protected $tb_name;
	protected $query;
	public $likeis;  
	
	protected $input_query_result;  // DB::result()


	/**
    * 데이터베이스에 접속한다.
    *
    * 데이터베이스를 접속한다.
    *
    * @param String 접속 정보를 가지고 있는 dsn값
    * @return nothing
    * @access public
    * @see $dsn
    */
	 //생성자 db에 접속한다.
	function Database_Connect($dsn){
		$this->dsn	   = $dsn;
		$this->db_conn = $this->Connect($dsn, true);
		
		if($this->isError($this->db_conn)){
			echo "데이터베이스 접속에러!!";
			die ($this->db_conn->getMessage());
		}
	}

	public function newConnection($dsn, $tbname){
		$this->db_conn = $this->Connect($dsn, true);
		if($this->isError($this->db_conn)){
			echo "데이터베이스 접속에러!!";
			die ($this->db_conn->getMessage());
		}
		$this->tb_name = $tbname;
	}

	/**
	 *  
	 * getConResource()
	 * 현재 셋팅된 테이블명을 리턴한다.
	 * @access public
	 * 
	 * @param nothing 
	 *
	 * @return $db_conn : 접속 오브젝트
	*/
	public function getConResource(){
		return $this->db_conn;
	}
	
	/**
	 *  
	 * setDSN()
	 * 접속할 dsn명을 정한다.
	 * @access public
	 * 
	 * @param $dsn : dsn명
	 *
	 * @return nothing
	*/
	public function setDSN($dsn){
		$this->dsn = $dsn;

	}
	
	/**
	 *  
	 * getDSN()
	 * 현재 셋팅된 dsn명을 리턴한다.
	 * @access public
	 * 
	 * @param nothing 
	 *
	 * @return $dsn : dsn 명
	*/
	// 
	public function getDSN(){
		return $this->dsn;

	}

	/**
	 *  
	 * setTbName()
	 * 접속할 테이블명을 정한다.
	 * @access public
	 * 
	 * @param $tbname : 테이블 명
	 *
	 * @return nothing
	*/
	public function setTbName($tbname){
		$this->tb_name = $tbname;

	}
	
	/**
	 *  
	 * getTbName()
	 * 현재 셋팅된 테이블명을 리턴한다.
	 * @access public
	 * 
	 * @param nothing 
	 *
	 * @return $tb_name : 테이블 명
	*/
	// 
	public function getTbName(){
		return $this->tb_name;

	}

	/**
	 *  
	 * setQuery()
	 * 쿼리를 직접 입력 설정 
	 * @access public
	 * 
	 * @param $value : 입력할 쿼리값
	 *
	 * @return nothing
	*/
	public function setQuery($value){
		$this->query = $value;
	}

	/**
	 *  
	 * getQuery()
	 * 쿼리 값 리턴
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $query : 쿼리값
	*/
	public function getQuery(){
		return $this->query;
	}


	/**
	 *  
	 * TotalRecordRow()
	 * 쿼리의 결과 그 열수를 리턴함
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $total_num : 쿼리의 레코드수
	*/
	public function TotalRecordRow(){

		$cntsql = "select count(*) from ".$this->tb_name;
		$total_num = $this->db_conn->getOne($cntsql);
		return $total_num;
	}

	/**
	 *  
	 * countConditionRecordRow()
	 * 조건을 통해 쿼리의 결과 그 열수를 리턴함
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $total_num : 쿼리의 레코드수
	*/
	public function countConditionRecordRow($likeis){
		if($likeis){
			$cntsql = "select count(*) from ".$this->tb_name." where ".$likeis;
		}else{
			$cntsql = "select count(*) from ".$this->tb_name;
		}
		$total_num = $this->db_conn->getOne($cntsql);

		return $total_num;
	}

	/**
	 *  
	 * countMaxNum()
	 * 조건을 통해 해당 필드의 최대값을 리턴한다.
	 * @access public
	 * 
	 * @param $fieldname	: 필드명
	 * @param $likeis		: 조건
	 *
	 * @return $total_num : 최대값
	*/
	public function countMaxNum($fieldname, $likeis){
		if($likeis){
			$cntsql = "select max($fieldname) from ".$this->tb_name." where ".$likeis;
		}else{
			$cntsql = "select max($fieldname) from ".$this->tb_name;
		}
		$total_num = $this->db_conn->getOne($cntsql);
		if(!$total_num){$total_num = 0;}

		return $total_num;
	}

	/**
	 *  
	 * countMaxNum()
	 * 조건을 통해 해당 필드의 합을 리턴한다.
	 * @access public
	 * 
	 * @param $fieldname	: 필드명
	 * @param $likeis		: 조건
	 *
	 * @return $total_num : 최대값
	*/
	public function countSumNum($fieldname, $likeis){
		if($likeis){
			$cntsql = "select sum($fieldname) from ".$this->tb_name." where ".$likeis;
		}else{
			$cntsql = "select sum($fieldname) from ".$this->tb_name;
		}
		//echo $cntsql."<br>";
		$total_num = $this->db_conn->getOne($cntsql);
		if(!$total_num){$total_num = 0;}

		return $total_num;
	}

	/**
	 *  
	 * getFieldValue()
	 * 조건을 통해 해당 필드의 값을 리턴한다.
	 * @access public
	 * 
	 * @param $fieldname : 필드명
	 * @param $likeis	 : 조건
	 *
	 * @return $one_value : 리턴 값
	*/
	public function getFieldValue($fieldname, $likeis){
		if($likeis){
			$sql = "select ".$fieldname." from ".$this->tb_name." where ".$likeis;
		}else{
			$sql = "select ".$fieldname." from ".$this->tb_name;
		}
		$one_value = $this->db_conn->getOne($sql);

		return $one_value;
	}

	/**
	 *  
	 * executeQuery()
	 * 쿼리를 실행
	 * @access public
	 * @param nothing
	 * @return nothing
	*/
	public function executeQuery(){ 
		$this->input_query_result = $this->db_conn->query($this->query);

		if(DB::isError($this->input_query_result)){
			echo "에러!!<br>";
			echo $this->input_query_result->getMessage();
			return false;
		}else{
			return true;
		}
	}

	/**
	 *  
	 * getQueryResult()
	 * 쿼리의 결과를 리턴
	 * @access public
	 * @param nothing
	 * @return $rec_result_num : 쿼리의 레코드수
	*/
	public function getQueryResult(){ 
		return $this->input_query_result;
	}

	/**
	 *  
	 * DBClose()
	 * 쿼리의 결과 그 열수를 리턴함
	 * @access public
	 * @param nothing
	 * @return nothing
	*/
	public function DBClose(){
		$this->db_conn->disconnect();
	}


}
?>