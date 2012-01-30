<?
class DBTable_TableForm extends Database_Manage{

	protected $m_table_width;
	protected $m_table_height;
	
	protected $tablecolor;
	protected $sub_tablecolor;
	protected $line_tablecolor;

	protected $arr_item;
	protected $strLink;

	
	function DBTable_TableForm($arr_tb_item){

		$this->m_table_width	= 720;		//출력할 결과 테이블의 넓이(html테이블)
		$this->arr_item = $arr_tb_item;		// 테이블의 제목 명(배열)

		$this->tablecolor	= "#7e8ece";
		$this->sub_tablecolor	= "#cfdfef";
		$this->line_tablecolor	= "#7fafdf";
	}
	

	/**
	 *  
	 * setTableColor()
	 * 테이블의 필드제목을 설정
	 * public
	 * 
	 * @param $arr_tb_item : 문자열의 배열값
	 *
	 * @return nothing
	*/
	public function setColor($tablecolor, $sub_tablecolor, $line_tablecolor){

		$this->tablecolor		= $tablecolor;
		$this->sub_tablecolor	= $sub_tablecolor;
		$this->line_tablecolor	= $line_tablecolor;
	}


	/**
	 *  
	 * setTableColor()
	 * 테이블의 필드제목을 설정
	 * public
	 * 
	 * @param $arr_tb_item : 문자열의 배열값
	 *
	 * @return nothing
	*/
	public function setTableColor($arr_tb_color){

		$this->tablecolor = $arr_tb_color[tablecolor];
		$this->sub_tablecolor = $arr_tb_color[sub_tablecolor];
		$this->line_tablecolor = $arr_tb_color[line_tablecolor];
	}

	/**
	 *  
	 * setItem()
	 * 테이블의 필드제목을 설정
	 * public
	 * 
	 * @param $arr_tb_item : 문자열의 배열값
	 *
	 * @return nothing
	*/
	public function setItem($arr_tb_item){
		$this->arr_item = $arr_tb_item;
	}

	/**
	 *  
	 * setTableWidth()
	 * 테이블의 가로길이 설정
	 * public
	 * 
	 * @param $value : 테이블의 가로길이
	 *
	 * @return nothing
	*/
	public function setTableWidth($value){
		$this->m_table_width = $value;
	}

	/**
	 *  
	 * getTableWidth()
	 * 테이블의 가로길이 알아옴
	 * public
	 * 
	 * @param nothing
	 *
	 * @return $m_table_width : 테이블의 가로길이
	*/
	public function getTableWidth(){
		return $this->m_table_width;
	}

	/**
	 *  
	 * setTableHeight()
	 * 테이블의 세로길이 설정
	 * public
	 * 
	 * @param $value : 테이블의 가로길이
	 *
	 * @return nothing
	*/
	public function setTableHeight($value){
		$this->m_table_height = $value;
	}

	/**
	 *  
	 * getTableHeight()
	 * 테이블의 가로길이 알아옴
	 * public
	 * 
	 * @param nothing
	 *
	 * @return $m_table_height : 테이블의 가로길이
	*/
	public function getTableHeight(){
		return $this->m_table_height;
	}


	public function setLink($arrValue){
		while(list($key, $value) = each($arrValue)){
			$this->strLink .= $key.'='.$value.'&';
		}
		$this->strLink = substr($this->strLink, 0, -1);
	}

	public function getLink(){
		return $this->strLink;
	}

} // end class

?>