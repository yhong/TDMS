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

// DBConnect�� �ܼ� ���Ӱ��� Ŭ����(���̺��, DSN)
// DBManage�� condition�� �޾Ƽ� where ���� ���� ����
class Database_Manage extends Database_Connect{   

	// ����Ŭ�������� �����ؾ� ��
	protected $m_solname;
	protected $m_solnum;
	protected $condition;
	protected $element;

	public $rec_result_num;
	
	protected $arr_field = array();		// ���̺� �ʵ� ����(�迭)
	public $field_kind;

    //������ db�� �����Ѵ�.
	function Database_Manage($dsn){
		parent::Database_Connect($dsn);

	}

	/**
	 *  
	 * setField()
	 * �ʵ���� ("����"=>"dbfieldname")���� ������ �迭�� �Է¹޴´�.
	 * @access public
	 * 
	 * @param $arrFieldValue : �ʵ尪(�迭)
	 *
	 * @return nothing
	*/
	// 
	public function setField($arrFieldValue){
		// �迭 �״�θ� �����Ѵ�.
		$this->arr_field = $arrFieldValue;

		// �� �ʵ带 ,�� �����ؼ� �����Ѵ�
		while (list (, $val) = each ($arrFieldValue)) {
				 $field_kind.=$val.",";
		}
		$field_kind = substr($field_kind, 0, -1);
		$this->field_kind = $field_kind;
	}

	/**
	 *  
	 * setField()
	 * �ʵ���� ("dbfieldname","dbfieldname1","dbfieldname2")���� ������ �迭�� �Է¹޴´�.
	 * @access public
	 * 
	 * @param $arrFieldValue : �ʵ尪(�迭)
	 *
	 * @return nothing
	*/
	// 
	public function setField1($arrFieldValue){
		// �迭 �״�θ� �����Ѵ�.
		$this->arr_field = $arrFieldValue;

		// �� �ʵ带 ,�� �����ؼ� �����Ѵ�
		for($i=0;$i<count($arrFieldValue);$i++){
			$field_kind.=$arrFieldValue[$i].",";
		}
		$field_kind = substr($field_kind, 0, -1);
		$this->field_kind = $field_kind;
	}

	public function setFieldByArray($arrFieldValue){
		// �迭 �״�θ� �����Ѵ�.
		$this->arr_field = $arrFieldValue;

		// �� �ʵ带 ,�� �����ؼ� �����Ѵ�
		for($i=0;$i<count($arrFieldValue);$i++){
			$field_kind.=$arrFieldValue[$i].",";
		}
		$field_kind = substr($field_kind, 0, -1);
		$this->field_kind = $field_kind;
	}

	/**
	 *  
	 * getField()
	 * ������ �� �ʵ���� ,(comma)�� �����ؼ� �����Ѵ�.
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $field_kind : �ʵ�� �� ����
	*/
	public function getField(){
		return $this->field_kind;
	}

	/**
	 *  
	 * setCondition()
	 * �˻����� �� ����
	 * public
	 * 
	 * @param $condition	: ������ `���ڷ� �����ؼ� �Է��� �޴´�.
	 *
	 * @return nothing	
	*/
	public function setCondition($condition){
		$this->condition = $condition;
		
		$item = explode('`', $condition);
		$this->m_solnum	 = $item[0];
		$this->m_solname = $item[1];
		$this->m_jumin	 = $item[2];
		$this->m_date1	 = $item[3];
		$this->m_date2	 = $item[4];
	}

	/**
	 *  
	 * setElement()
	 * �˻����� �� ����
	 * public
	 * 
	 * @param $condition	: �߰������� `���ڷ� �����ؼ� �Է��� �޴´�.
	 *
	 * @return nothing	
	*/
	public function setElement($element){
		$this->element = $element;
	}

	/**
	 *  
	 * setCondition()
	 * ���� ���� (where �ڿ� ��)
	 * @access public
	 * 
	 * @param $inputValue : �Է°�(where �ڿ� �� ��)
	 *
	 * @return $query : ������
	*/
	public function setInputCondition($inputValue){
		$this->condition = $inputValue;
	}

	/**
	 *  
	 * getCondition()
	 * �˻����� �� ����
	 * private
	 * 
	 * @param nothing
	 *
	 * @return $this->condition	: ������ `���ڷ� ������ ���� �����Ѵ�.
	*/
	public function getCondition(){
		return $this->condition;
	}

	/**
	 *  
	 * getItemList()
	 * ���̺��, ����, �ʵ�� ������ �������� ���ʴ�� ���� ���� ����Ѵ�.
	 * private
	 * 
	 * @param nothing
	 *
	 * @return $this->condition	: ������ `���ڷ� ������ ���� �����Ѵ�.
	*/
	public function getItemList(){
		if(isset($this->tb_name)){
			echo "���̺� ���� <b>[".$this->tb_name."]</b> �Դϴ�<br>";
		}
		if(isset($this->condition)){
			echo "���ǰ��� <b>[".$this->condition."]</b> �Դϴ�<br>";
		}
		if(isset($this->field_kind)){
			echo "�ʵ尪�� <b>[".$this->field_kind."]</b> �Դϴ�"; 
		}
	}

	/**
	 *  
	 * checkData()
	 * ������ ����µ� �ʿ��� �ڷ���� �� �ִ��� üũ
	 * ���̺��, �ʵ��(�迭), ���Ǹ�(�迭) �������� üũ�ؼ� ����� �����Ѵ�. 
	 * private
	 * 
	 * @param nothing
	 *
	 * @return $isok : bool�� ����(true:�� ����, false :����)
	*/
	// ������ �����Ѵ�.
	protected function checkData(){
		// ���� �ִ��� üũ
		$one = isset ($this->tb_name);
		$two = (isset($this->condition) && $this->condition != "````");
		$three = isset($this->field_kind);

		if($one && $two && $three){
			return true;
		}
		
		return false; // ���� �������
	}

	/**
	 *  
	 * setEQUCondition()
	 * ���� ���ǰ� ����
	 * private
	 * 
	 * @param $fieldname	: �ʵ��(db�� �ʵ��)
	 * @param $fieldvalue	: �ʵ��� ��(�Է°�)
	 * @param $likeis		: ��ü �ʵ� �׸�(a, b, c...)
	 * @param $isstring		: ���ڿ����� �ƴ��� ����(true==���ڿ� false=������)
	 *
	 * @return $likeis		: ���ϵ� �ʵ��׸�($fieldvalue�� ������ �״�� ����)
	*/
	public function setEQUCondition($fieldname, $fieldvalue, $isstring=true){
		if($fieldvalue){
		  if($this->likeis){
			$this->likeis .= " and ";
		  }
		  if($isstring == true){
			$this->likeis .= $fieldname."='".$fieldvalue."'";
		  }else{
			$this->likeis .= $fieldname."=".$fieldvalue;
		  }
		}
	}


	/**
	 *  
	 * addSearchEQUField()
	 * ���� ���ǰ� ����
	 * private
	 * 
	 * @param $fieldname	: �ʵ��(db�� �ʵ��)
	 * @param $fieldvalue	: �ʵ��� ��(�Է°�)
	 * @param $likeis		: ��ü �ʵ� �׸�(a, b, c...)
	 * @param $isstring		: ���ڿ����� �ƴ��� ����(true==���ڿ� false=������)
	 *
	 * @return $likeis		: ���ϵ� �ʵ��׸�($fieldvalue�� ������ �״�� ����)
	*/
	protected function addSearchEQUField($fieldname, $fieldvalue, $likeis, $isstring=true){
		if($fieldvalue){
		  if($likeis){
			$likeis .= " and ";
		  }
		  if($isstring == true){
			$likeis .= $fieldname."='".$fieldvalue."'";
		  }else{
			$likeis .= $fieldname."=".$fieldvalue;
		  }
		}
		return $likeis;
	}

	/**
	 *  
	 * addSearchALLLIKEField()
	 * ������ ����(���ڿ��� ����)
	 * private
	 * 
	 * @param $fieldname	: �ʵ��(db�� �ʵ��)
	 * @param $fieldvalue	: �ʵ��� ��(�Է°�)
	 * @param $likeis		: ��ü �ʵ� �׸�(a, b, c...)
	 *
	 * @return $likeis		: ���ϵ� �ʵ��׸�($fieldvalue�� ������ �״�� ����)
	*/
	protected function addSearchALLLIKEField($fieldname, $fieldvalue, $likeis){
		if($fieldvalue){
		  if($likeis){
			$likeis .= " and ";
		  }
		  $likeis .= $fieldname." like '%".$fieldvalue."%'";
		}
		return $likeis;
	}

	/**
	 *  
	 * addSearchLIKEField()
	 * ������ ����(���ڿ��� ����)
	 * private
	 * 
	 * @param $fieldname	: �ʵ��(db�� �ʵ��)
	 * @param $fieldvalue	: �ʵ��� ��(�Է°�)
	 * @param $likeis		: ��ü �ʵ� �׸�(a, b, c...)
	 *
	 * @return $likeis		: ���ϵ� �ʵ��׸�($fieldvalue�� ������ �״�� ����)
	*/
	protected function addSearchLIKEField($fieldname, $fieldvalue, $likeis){
		if($fieldvalue){
		  if($likeis){
			$likeis .= " and ";
		  }
		  $likeis .= $fieldname." like '".$fieldvalue."%'";
		}
		return $likeis;
	}

	/**
	 *  
	 * addSearchBETWEENField()
	 * ������ ����(���ڿ��� ����)
	 * private
	 * 
	 * @param $fieldname	: �ʵ��(db�� �ʵ��)
	 * @param $fieldvalue	: �ʵ��� ��(�Է°�)
	 * @param $likeis		: ��ü �ʵ� �׸�(a, b, c...)
	 *
	 * @return $likeis		: ���ϵ� �ʵ��׸�($fieldvalue�� ������ �״�� ����)
	*/
	protected function addSearchBETWEENField($fieldname, $fieldvalue1, $fieldvalue2, $likeis){
		if($fieldvalue1 || $fieldvalue2){
		  if($likeis){
			$likeis .= " and ";
		  }
		  if(!$fieldvalue1 && $fieldvalue2){
			   $likeis .= $fieldname." < '".$fieldvalue2."'";
		  }
		  else if($fieldvalue1 && !$fieldvalue2){
			   $likeis .= $fieldname." > '".$fieldvalue1."'";
		  }
		  else if($fieldvalue1 && $fieldvalue2){
			 $likeis .= $fieldname." BETWEEN '".$fieldvalue1."' AND '".$fieldvalue2."'";
		  } 
		}
		return $likeis;
	}
	/**
	 *  
	 * addSearchBETWEENField()
	 * ������ ����(���ڿ��� ����)
	 * private
	 * 
	 * @param $fieldname	: �ʵ��(db�� �ʵ��)
	 * @param $fieldvalue	: �ʵ��� ��(�Է°�)
	 * @param $likeis		: ��ü �ʵ� �׸�(a, b, c...)
	 * @param $seperator	: �Ⱓ ������ (��: 2004-10-10 => -)
	 *
	 * @return $likeis		: ���ϵ� �ʵ��׸�($fieldvalue�� ������ �״�� ����)
	*/
	protected function addSearchBETWEENDateField($fieldname, $fieldvalue1, $fieldvalue2, $likeis, $seperator){
		if($fieldvalue1 || $fieldvalue2){
		  if($likeis){
			$likeis .= " and ";
		  }
		  if(!$fieldvalue1 && $fieldvalue2){
			   $likeis .= $fieldname." < '".$fieldvalue2.$seperator."12".$seperator."31'";
		  }
		  else if($fieldvalue1 && !$fieldvalue2){
			   $likeis .= $fieldname." > '".$fieldvalue1.$seperator."01".$seperator."01'";
		  }
		  else if($fieldvalue1 && $fieldvalue2){
			 $likeis .= " (".$fieldname2." between '".$fieldvalue1.$seperator."01".$seperator."01' and '".$fieldvalue2.$seperator."12".$seperator."31')";
		  }
		}
		return $likeis;
	}

	/**
	 *  
	 * addSearchBETWEENField()
	 * ������ ����(���ڿ��� ����)
	 * private
	 * 
	 * @param $fieldname	: �ʵ��(db�� �ʵ��)
	 * @param $fieldvalue	: �ʵ��� ��(�Է°�)
	 * @param $likeis		: ��ü �ʵ� �׸�(a, b, c...)
	 * @param $seperator	: �Ⱓ ������ (��: 2004-10-10 => -)
	 *
	 * @return $likeis		: ���ϵ� �ʵ��׸�($fieldvalue�� ������ �״�� ����)
	*/
	protected function addSearchBETWEENDate2Field($fieldname1, $fieldvalue1, $fieldname2, $fieldvalue2, $likeis, $seperator){
		if($fieldvalue1 || $fieldvalue2){
		  if($likeis){
			$likeis .= " and ";
		  }
		  if(!$fieldvalue1 && $fieldvalue2){
			   $likeis .= $fieldname2." < '".$fieldvalue2.$seperator."12".$seperator."31'";
		  }
		  else if($fieldvalue1 && !$fieldvalue2){
			   $likeis .= $fieldname1." > '".$fieldvalue1.$seperator."01".$seperator."01'";
		  }
		else if($fieldvalue1 && $fieldvalue2){
			 $likeis .= "(".$fieldname2." between '".$fieldvalue1.$seperator."01".$seperator."01' and '".$fieldvalue2.$seperator."12".$seperator."31')";
		  }
		}
		return $likeis;
	}

	/**
	 *  
	 * searchTypeCheck()
	 * �κ��Է����� ��ü �Է����� Ȯ��
	 * private
	 * 
	 * @param $checkValue	: �Է°��� like���� equal���� üũ
	 *
	 * @return 		: bool��
	*/
	private function searchTypeCheck($checkValue){
		if(substr($checkValue,-1,1) == '%'){
			return true;
		}else{
			return false;
		}
	}

	protected function searchLikeorEqu($fieldValue, $value, $likeis, $isString){
		if($this->searchTypeCheck($value) == true){
			$value = substr($value, 0, -1);
			$likeis = $this->addSearchLIKEField($fieldValue,$value,  $likeis, $isString);
		}else{
			$likeis = $this->addSearchEQUField($fieldValue,	$value,  $likeis, $isString);
		}
		return $likeis;
	}

	public function setAddLikeis($value){
		if($this->likeis){
			$this->likeis .= " and ".$value;
		}else{
			$this->likeis = $value;
		}
	}


	/**
	 *  
	 * countRecordRow()
	 * ������ ��� �� ������ ������
	 * public
	 * 
	 * @param nothing
	 *
	 * @return $rec_result_num : ������ ���ڵ��
	*/
	public function countTotalRecordRow(){
		if($this->likeis){
			$cntsql = "select count(*) from ".$this->tb_name." where ".$this->likeis;
		}else{
			$cntsql = "select count(*) from ".$this->tb_name;
		}

		$this->rec_result_num = $this->db_conn->getOne($cntsql);

		return $this->rec_result_num;
	}

	/**
	 *  
	 * SumRecordRow()
	 * �ʵ��� ���� �հ踦 ����
	 * public
	 * 
	 * @param $field : �ʵ��
	 *
	 * @return $rec_result_num : ������ �հ�
	*/
	public function SumRecordRow($field){
		if($this->likeis){
			$cntsql = "select SUM(".$field.") from ".$this->tb_name." where ".$this->likeis;
		}else{
			$cntsql = "select SUM(".$field.") from ".$this->tb_name;
		}

		$this->rec_result_num = $this->db_conn->getOne($cntsql);

		return $this->rec_result_num;
	}

	/* 
	 * exe_query()
	 * ���� ����(���������� �Ϲݸ���Ʈ���� ����)
	 *
	 * @access private
	 * 
	 * @param $from, $count : ����, ����
	 *
	 * @return nothing
	*/
	public function exe_query($from, $count){
		if(!$count || $count == 0){
			$this->executeQuery();
		}else{
			$this->executeLimitQuery($from, $count);
		}
	}



	public function getRowValue(){
		if($this->likeis){
			$this->query = "select ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis;
		}else{
			$this->query = "select ".$this->field_kind." from ".$this->tb_name;
		}

		$values = $this->db_conn->getOne($this->query);

		return $values;
	}


	public function getRowValues(){
		if($this->likeis){
			$this->query = "select ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis;
		}else{
			$this->query = "select ".$this->field_kind." from ".$this->tb_name;
		}

		$values = $this->db_conn->getAll($this->query, array(), DB_FETCHMODE_ASSOC);

		return $values;
	}

} // END DBManage class
?>