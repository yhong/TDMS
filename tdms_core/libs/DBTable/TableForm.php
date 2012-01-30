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

		$this->m_table_width	= 720;		//����� ��� ���̺��� ����(html���̺�)
		$this->arr_item = $arr_tb_item;		// ���̺��� ���� ��(�迭)

		$this->tablecolor	= "#7e8ece";
		$this->sub_tablecolor	= "#cfdfef";
		$this->line_tablecolor	= "#7fafdf";
	}
	

	/**
	 *  
	 * setTableColor()
	 * ���̺��� �ʵ������� ����
	 * public
	 * 
	 * @param $arr_tb_item : ���ڿ��� �迭��
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
	 * ���̺��� �ʵ������� ����
	 * public
	 * 
	 * @param $arr_tb_item : ���ڿ��� �迭��
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
	 * ���̺��� �ʵ������� ����
	 * public
	 * 
	 * @param $arr_tb_item : ���ڿ��� �迭��
	 *
	 * @return nothing
	*/
	public function setItem($arr_tb_item){
		$this->arr_item = $arr_tb_item;
	}

	/**
	 *  
	 * setTableWidth()
	 * ���̺��� ���α��� ����
	 * public
	 * 
	 * @param $value : ���̺��� ���α���
	 *
	 * @return nothing
	*/
	public function setTableWidth($value){
		$this->m_table_width = $value;
	}

	/**
	 *  
	 * getTableWidth()
	 * ���̺��� ���α��� �˾ƿ�
	 * public
	 * 
	 * @param nothing
	 *
	 * @return $m_table_width : ���̺��� ���α���
	*/
	public function getTableWidth(){
		return $this->m_table_width;
	}

	/**
	 *  
	 * setTableHeight()
	 * ���̺��� ���α��� ����
	 * public
	 * 
	 * @param $value : ���̺��� ���α���
	 *
	 * @return nothing
	*/
	public function setTableHeight($value){
		$this->m_table_height = $value;
	}

	/**
	 *  
	 * getTableHeight()
	 * ���̺��� ���α��� �˾ƿ�
	 * public
	 * 
	 * @param nothing
	 *
	 * @return $m_table_height : ���̺��� ���α���
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