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

// DBConnect는 단순 접속가능 클래스(테이블명, DSN)
// DBManage는 condition을 받아서 where 까지 쿼리 구성
class Database_Manage extends Database_Connect{   

	// 하위클래스에서 접근해야 함
	protected $m_solname;
	protected $m_solnum;
	protected $condition;
	protected $element;

	public $rec_result_num;
	
	protected $arr_field = array();		// 테이블 필드 정보(배열)
	public $field_kind;

    //생성자 db에 접속한다.
	function Database_Manage($dsn){
		parent::Database_Connect($dsn);

	}

	/**
	 *  
	 * setField()
	 * 필드명은 ("별명"=>"dbfieldname")으로 구성된 배열을 입력받는다.
	 * @access public
	 * 
	 * @param $arrFieldValue : 필드값(배열)
	 *
	 * @return nothing
	*/
	// 
	public function setField($arrFieldValue){
		// 배열 그대로를 저장한다.
		$this->arr_field = $arrFieldValue;

		// 각 필드를 ,로 연결해서 저장한다
		while (list (, $val) = each ($arrFieldValue)) {
				 $field_kind.=$val.",";
		}
		$field_kind = substr($field_kind, 0, -1);
		$this->field_kind = $field_kind;
	}

	/**
	 *  
	 * setField()
	 * 필드명은 ("dbfieldname","dbfieldname1","dbfieldname2")으로 구성된 배열을 입력받는다.
	 * @access public
	 * 
	 * @param $arrFieldValue : 필드값(배열)
	 *
	 * @return nothing
	*/
	// 
	public function setField1($arrFieldValue){
		// 배열 그대로를 저장한다.
		$this->arr_field = $arrFieldValue;

		// 각 필드를 ,로 연결해서 저장한다
		for($i=0;$i<count($arrFieldValue);$i++){
			$field_kind.=$arrFieldValue[$i].",";
		}
		$field_kind = substr($field_kind, 0, -1);
		$this->field_kind = $field_kind;
	}

	public function setFieldByArray($arrFieldValue){
		// 배열 그대로를 저장한다.
		$this->arr_field = $arrFieldValue;

		// 각 필드를 ,로 연결해서 저장한다
		for($i=0;$i<count($arrFieldValue);$i++){
			$field_kind.=$arrFieldValue[$i].",";
		}
		$field_kind = substr($field_kind, 0, -1);
		$this->field_kind = $field_kind;
	}

	/**
	 *  
	 * getField()
	 * 리턴은 각 필드명을 ,(comma)로 연결해서 리턴한다.
	 * @access public
	 * 
	 * @param nothing
	 *
	 * @return $field_kind : 필드명 값 리턴
	*/
	public function getField(){
		return $this->field_kind;
	}

	/**
	 *  
	 * setCondition()
	 * 검색조건 값 설정
	 * public
	 * 
	 * @param $condition	: 조건을 `문자로 연결해서 입력을 받는다.
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
	 * 검색조건 값 설정
	 * public
	 * 
	 * @param $condition	: 추가조건을 `문자로 연결해서 입력을 받는다.
	 *
	 * @return nothing	
	*/
	public function setElement($element){
		$this->element = $element;
	}

	/**
	 *  
	 * setCondition()
	 * 조건 설정 (where 뒤에 값)
	 * @access public
	 * 
	 * @param $inputValue : 입력값(where 뒤에 들어갈 값)
	 *
	 * @return $query : 쿼리값
	*/
	public function setInputCondition($inputValue){
		$this->condition = $inputValue;
	}

	/**
	 *  
	 * getCondition()
	 * 검색조건 값 리턴
	 * private
	 * 
	 * @param nothing
	 *
	 * @return $this->condition	: 조건을 `문자로 연결한 값을 리턴한다.
	*/
	public function getCondition(){
		return $this->condition;
	}

	/**
	 *  
	 * getItemList()
	 * 테이블명, 조건, 필드명 세가지 아이템을 차례대로 보기 좋게 출력한다.
	 * private
	 * 
	 * @param nothing
	 *
	 * @return $this->condition	: 조건을 `문자로 연결한 값을 리턴한다.
	*/
	public function getItemList(){
		if(isset($this->tb_name)){
			echo "테이블 명은 <b>[".$this->tb_name."]</b> 입니다<br>";
		}
		if(isset($this->condition)){
			echo "조건값은 <b>[".$this->condition."]</b> 입니다<br>";
		}
		if(isset($this->field_kind)){
			echo "필드값은 <b>[".$this->field_kind."]</b> 입니다"; 
		}
	}

	/**
	 *  
	 * checkData()
	 * 쿼리를 만드는데 필요한 자료들이 다 있는지 체크
	 * 테이블명, 필드명(배열), 조건명(배열) 세가지를 체크해서 결과를 리턴한다. 
	 * private
	 * 
	 * @param nothing
	 *
	 * @return $isok : bool로 리턴(true:다 있음, false :없음)
	*/
	// 조건을 리턴한다.
	protected function checkData(){
		// 값이 있는지 체크
		$one = isset ($this->tb_name);
		$two = (isset($this->condition) && $this->condition != "````");
		$three = isset($this->field_kind);

		if($one && $two && $three){
			return true;
		}
		
		return false; // 값이 없을경우
	}

	/**
	 *  
	 * setEQUCondition()
	 * 쿼리 조건값 설정
	 * private
	 * 
	 * @param $fieldname	: 필드명(db의 필드명)
	 * @param $fieldvalue	: 필드의 값(입력값)
	 * @param $likeis		: 전체 필드 항목(a, b, c...)
	 * @param $isstring		: 문자열인지 아닌지 설정(true==문자열 false=숫자형)
	 *
	 * @return $likeis		: 리턴된 필드항목($fieldvalue가 없으면 그대로 리턴)
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
	 * 쿼리 조건값 설정
	 * private
	 * 
	 * @param $fieldname	: 필드명(db의 필드명)
	 * @param $fieldvalue	: 필드의 값(입력값)
	 * @param $likeis		: 전체 필드 항목(a, b, c...)
	 * @param $isstring		: 문자열인지 아닌지 설정(true==문자열 false=숫자형)
	 *
	 * @return $likeis		: 리턴된 필드항목($fieldvalue가 없으면 그대로 리턴)
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
	 * 쿼리값 설정(문자열만 가능)
	 * private
	 * 
	 * @param $fieldname	: 필드명(db의 필드명)
	 * @param $fieldvalue	: 필드의 값(입력값)
	 * @param $likeis		: 전체 필드 항목(a, b, c...)
	 *
	 * @return $likeis		: 리턴된 필드항목($fieldvalue가 없으면 그대로 리턴)
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
	 * 쿼리값 설정(문자열만 가능)
	 * private
	 * 
	 * @param $fieldname	: 필드명(db의 필드명)
	 * @param $fieldvalue	: 필드의 값(입력값)
	 * @param $likeis		: 전체 필드 항목(a, b, c...)
	 *
	 * @return $likeis		: 리턴된 필드항목($fieldvalue가 없으면 그대로 리턴)
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
	 * 쿼리값 설정(문자열만 가능)
	 * private
	 * 
	 * @param $fieldname	: 필드명(db의 필드명)
	 * @param $fieldvalue	: 필드의 값(입력값)
	 * @param $likeis		: 전체 필드 항목(a, b, c...)
	 *
	 * @return $likeis		: 리턴된 필드항목($fieldvalue가 없으면 그대로 리턴)
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
	 * 쿼리값 설정(문자열만 가능)
	 * private
	 * 
	 * @param $fieldname	: 필드명(db의 필드명)
	 * @param $fieldvalue	: 필드의 값(입력값)
	 * @param $likeis		: 전체 필드 항목(a, b, c...)
	 * @param $seperator	: 기간 구분자 (예: 2004-10-10 => -)
	 *
	 * @return $likeis		: 리턴된 필드항목($fieldvalue가 없으면 그대로 리턴)
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
	 * 쿼리값 설정(문자열만 가능)
	 * private
	 * 
	 * @param $fieldname	: 필드명(db의 필드명)
	 * @param $fieldvalue	: 필드의 값(입력값)
	 * @param $likeis		: 전체 필드 항목(a, b, c...)
	 * @param $seperator	: 기간 구분자 (예: 2004-10-10 => -)
	 *
	 * @return $likeis		: 리턴된 필드항목($fieldvalue가 없으면 그대로 리턴)
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
	 * 부분입력인지 전체 입력인지 확인
	 * private
	 * 
	 * @param $checkValue	: 입력값이 like인지 equal인지 체크
	 *
	 * @return 		: bool값
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
	 * 쿼리의 결과 그 열수를 리턴함
	 * public
	 * 
	 * @param nothing
	 *
	 * @return $rec_result_num : 쿼리의 레코드수
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
	 * 필드의 값의 합계를 구함
	 * public
	 * 
	 * @param $field : 필드명
	 *
	 * @return $rec_result_num : 쿼리의 합계
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
	 * 쿼리 실행(페이지인지 일반리스트인지 구분)
	 *
	 * @access private
	 * 
	 * @param $from, $count : 시작, 갯수
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