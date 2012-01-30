<?php
//�������̽� (�ʵ�ó������)
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

	// �� �������� ������
	public function getTotalPageNum(){
		return $this->countTotalRecordRow();
	}


	/**
	 *  
	 * searchFieldName()
	 * �迭�� ��(������ �迭)���� ù��° ���� ������ �� �迭�� �ε����� ������
	 * protected
	 * 
	 * @param $field	: �ʵ� ��(�迭)
	 *
	 * @return $key : ������ ��
	*/
	// �ʵ��� �̸��� �˻��մϴ�.
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
	 * ���� ���� ����
	 * private
	 * 
	 * @param $field : ������ ���Ǵ� �ʵ� ��(�ʵ��� alias�̹Ƿ� field������ �ٲ��־�� �Ѵ�)
	 * @param $fieldvalue : �ش� �ʵ��� ��
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
	 * �ʵ���� ("����"=>"dbfieldname")���� ������ �迭�� �Է¹޴´�.
	 * @access public
	 * 
	 * @param $arrFieldValue : �ʵ尪(�迭)
	 *
	 * @return nothing
	*/
	public function setField($arrFieldValue){
		// �迭 �״�θ� �����Ѵ�.
		$this->arr_field = $arrFieldValue;

		// �� �ʵ带 ,�� �����ؼ� �����Ѵ�

		while (list (, $val) = each ($arrFieldValue)) {
				 $field_kind.=$val[0].",";
		}
		$field_kind = substr($field_kind, 0, -1);
		$this->field_kind = $field_kind;
	}


	/**
	 *  
	 * setQuery()
	 * ���� ���� ����
	 * private
	 * 
	 * @param $tb_name : ������ ���Ǵ� ���̺� ��
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


	// �������� ���� ������ ����
	public function executeLimitQuery($from, $count){ 
	/*
	if($this->likeis){
	$query = "select ".$this->field_kind." from (select rownum as linenum, ".$this->field_kind." from ".$this->tb_name." where rownum <=".($from+$count)." and ".$this->likeis.") where linenum >".$from;
	}else{
		$query = "select ".$this->field_kind." from (select rownum as linenum, ".$this->field_kind." from ".$this->tb_name." where rownum <=".($from+$count).") where linenum > ".$from;
	}
	*/
	//����Ŭ ���������(limitQuery ������ ���� ��)

		$this->input_query_result = $this->db_conn->limitQuery($this->query, $from, $count);
		//$this->input_query_result = $this->db_conn->Query($query);
		if(DB::isError($this->input_query_result)){
			echo "����!!<br>";
			echo $this->input_query_result->getMessage();
		}
		
	}

	//�Ϲ����� ����
	public function executeQuery(){ 
		$this->input_query_result = $this->db_conn->Query($this->query);
		if(DB::isError($this->input_query_result)){
			echo "����!!<br>";
			echo $this->input_query_result->getMessage();
		}
	}
}
?>
