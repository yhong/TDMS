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

// pear/DB ��Ű���� ��ӹ޾�
// �⺻������ ������ ���̽��� �ٷ�µ� �ʿ��� ��� ����


class Database_Connect extends DB{
	
	/**
    * database���� ����
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
    * �����ͺ��̽��� �����Ѵ�.
    *
    * �����ͺ��̽��� �����Ѵ�.
    *
    * @param String ���� ������ ������ �ִ� dsn��
    * @return nothing
    * @access public
    * @see $dsn
    */
	 //������ db�� �����Ѵ�.
	function Database_Connect($dsn){
		$this->dsn	   = $dsn;
		$this->db_conn = $this->Connect($dsn, true);
		
		if($this->isError($this->db_conn)){
			echo "�����ͺ��̽� ���ӿ���!!";
			die ($this->db_conn->getMessage());
		}
	}

	public function newConnection($dsn, $tbname){
		$this->db_conn = $this->Connect($dsn, true);
		if($this->isError($this->db_conn)){
			echo "�����ͺ��̽� ���ӿ���!!";
			die ($this->db_conn->getMessage());
		}
		$this->tb_name = $tbname;
	}

	/**
	 *  
	 * getConResource()
	 * ���� ���õ� ���̺���� �����Ѵ�.
	 * @access public
	 * 
	 * @param nothing 
	 *
	 * @return $db_conn : ���� ������Ʈ
	*/
	public function getConResource(){
		return $this->db_conn;
	}
	
	/**
	 *  
	 * setDSN()
	 * ������ dsn���� ���Ѵ�.
	 * @access public
	 * 
	 * @param $dsn : dsn��
	 *
	 * @return nothing
	*/
	public function setDSN($dsn){
		$this->dsn = $dsn;

	}
	
	/**
	 *  
	 * getDSN()
	 * ���� ���õ� dsn���� �����Ѵ�.
	 * @access public
	 * 
	 * @param nothing 
	 *
	 * @return $dsn : dsn ��
	*/
	// 
	public function getDSN(){
		return $this->dsn;

	}

	/**
	 *  
	 * setTbName()
	 * ������ ���̺���� ���Ѵ�.
	 * @access public
	 * 
	 * @param $tbname : ���̺� ��
	 *
	 * @return nothing
	*/
	public function setTbName($tbname){
		$this->tb_name = $tbname;

	}
	
	/**
	 *  
	 * getTbName()
	 * ���� ���õ� ���̺���� �����Ѵ�.
	 * @access public
	 * 
	 * @param nothing 
	 *
	 * @return $tb_name : ���̺� ��
	*/
	// 
	public function getTbName(){
		return $this->tb_name;

	}

	/**
	 *  
	 * setQuery()
	 * ������ ���� �Է� ���� 
	 * @access public
	 * 
	 * @param $value : �Է��� ������
	 *
	 * @return nothing
	*/
	public function setQuery($value){
		$this->query = $value;
	}

	/**
	 *  
	 * getQuery()
	 * ���� �� ����
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $query : ������
	*/
	public function getQuery(){
		return $this->query;
	}


	/**
	 *  
	 * TotalRecordRow()
	 * ������ ��� �� ������ ������
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $total_num : ������ ���ڵ��
	*/
	public function TotalRecordRow(){

		$cntsql = "select count(*) from ".$this->tb_name;
		$total_num = $this->db_conn->getOne($cntsql);
		return $total_num;
	}

	/**
	 *  
	 * countConditionRecordRow()
	 * ������ ���� ������ ��� �� ������ ������
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $total_num : ������ ���ڵ��
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
	 * ������ ���� �ش� �ʵ��� �ִ밪�� �����Ѵ�.
	 * @access public
	 * 
	 * @param $fieldname	: �ʵ��
	 * @param $likeis		: ����
	 *
	 * @return $total_num : �ִ밪
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
	 * ������ ���� �ش� �ʵ��� ���� �����Ѵ�.
	 * @access public
	 * 
	 * @param $fieldname	: �ʵ��
	 * @param $likeis		: ����
	 *
	 * @return $total_num : �ִ밪
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
	 * ������ ���� �ش� �ʵ��� ���� �����Ѵ�.
	 * @access public
	 * 
	 * @param $fieldname : �ʵ��
	 * @param $likeis	 : ����
	 *
	 * @return $one_value : ���� ��
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
	 * ������ ����
	 * @access public
	 * @param nothing
	 * @return nothing
	*/
	public function executeQuery(){ 
		$this->input_query_result = $this->db_conn->query($this->query);

		if(DB::isError($this->input_query_result)){
			echo "����!!<br>";
			echo $this->input_query_result->getMessage();
			return false;
		}else{
			return true;
		}
	}

	/**
	 *  
	 * getQueryResult()
	 * ������ ����� ����
	 * @access public
	 * @param nothing
	 * @return $rec_result_num : ������ ���ڵ��
	*/
	public function getQueryResult(){ 
		return $this->input_query_result;
	}

	/**
	 *  
	 * DBClose()
	 * ������ ��� �� ������ ������
	 * @access public
	 * @param nothing
	 * @return nothing
	*/
	public function DBClose(){
		$this->db_conn->disconnect();
	}


}
?>