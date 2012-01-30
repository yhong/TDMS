<?php
//인터페이스 (필드처리구문)
interface impFieldDefine{
	function tableEtcFieldShow($val, $row);
}

class DBTable_PageTableForm extends DBTable_TableForm{
	
	private $indexNumber;
	protected $input_query_result;

	function DBTable_PageTableForm($dsn, $tbname, $arrFieldVal, $arrItem){

		$this->Database_Manage($dsn);
		$this->Database_TableForm($arrItem);

		$this->setTbName($tbname); 
		$this->setField($arrFieldVal); 
		$this->setQuery();
	}

	// 총 페이지수 얻어오기
	public function getTotalPageNum(){
		return $this->countTotalRecordRow();
	}


	/**
	 *  
	 * searchFieldName()
	 * 배열의 값(이차원 배열)에서 첫번째 값이 맞으면 그 배열의 인덱스를 리턴함
	 * protected
	 * 
	 * @param $field	: 필드 값(배열)
	 *
	 * @return $key : 리턴할 값
	*/
	// 필드의 이름을 검색합니다.
	public function searchFieldName($field){
		while(list($key, $value) = each($this->arr_field)){
			if(trim($value[0]) == trim($field)){
				return $key;
			}
		}
	}

	/**
	 *  
	 * setDataLikeis()
	 * 쿼리 조건 설정
	 * private
	 * 
	 * @param $field : 쿼리에 사용되는 필드 명(필드의 alias이므로 field값으로 바꿔주어야 한다)
	 * @param $fieldvalue : 해당 필드의 값
	 *
	 * @return nothing
	*/
	public function setDataLikeis_nottext($field, $fieldvalue, $board_id, $likeis){
		if(!$likeis){
			if($field && $fieldvalue){
				$this->likeis .= $this->arr_field[$field][0]."='".$fieldvalue."'";
			}
		}else{
			if($field && $fieldvalue){
				$this->likeis .= "or ".$this->arr_field[$field][0]."='".$fieldvalue."'";
			}
		}
		return $this->likeis;
	}

	/*
	 *  
	 * setField()
	 * 필드명은 ("별명"=>"dbfieldname")으로 구성된 배열을 입력받는다.
	 * @access public
	 * 
	 * @param $arrFieldValue : 필드값(배열)
	 *
	 * @return nothing
	*/
	public function setField($arrFieldValue){
		// 배열 그대로를 저장한다.
		$this->arr_field = $arrFieldValue;

		// 각 필드를 ,로 연결해서 저장한다

		while (list (, $val) = each ($arrFieldValue)) {
				 $field_kind.=$val[0].",";
		}
		$field_kind = substr($field_kind, 0, -1);
		$this->field_kind = $field_kind;
	}


	/**
	 *  
	 * setQuery()
	 * 쿼리 조건 설정
	 * private
	 * 
	 * @param $tb_name : 쿼리에 사용되는 테이블 명
	 *
	 * @return nothing
	*/
	public function setQuery(){
			if($this->likeis){
				$this->query = "select ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis." order by ID desc";
			}else{
				$this->query = "select ".$this->field_kind." from ".$this->tb_name." order by ID desc";
			}
	}	// END FUNCTION


	// 페이지를 나눈 쿼리를 실행
	public function executeLimitQuery($from, $count){ 
	/*
	if($this->likeis){
	$query = "select ".$this->field_kind." from (select rownum as linenum, ".$this->field_kind." from ".$this->tb_name." where rownum <=".($from+$count)." and ".$this->likeis.") where linenum >".$from;
	}else{
		$query = "select ".$this->field_kind." from (select rownum as linenum, ".$this->field_kind." from ".$this->tb_name." where rownum <=".($from+$count).") where linenum > ".$from;
	}
	*/
	//오라클 페이지기능(limitQuery 에러가 자주 남)

		$this->input_query_result = $this->db_conn->limitQuery($this->query, $from, $count);
		//$this->input_query_result = $this->db_conn->Query($query);
		if(DB::isError($this->input_query_result)){
			echo "에러!!<br>";
			echo $this->input_query_result->getMessage();
		}
		
	}

	//일반쿼리 실행
	public function executeQuery(){ 
		$this->input_query_result = $this->db_conn->Query($this->query);
		if(DB::isError($this->input_query_result)){
			echo "에러!!<br>";
			echo $this->input_query_result->getMessage();
		}
	}
}
?>
