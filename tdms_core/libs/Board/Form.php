<?

class Board_Form extends DBTable_TableForm{
	
	protected $data;
	protected $field;

	function Board_Form(){
		$CONFIG = GET_CONFIG(null);
		$this->Database_Connect($CONFIG->dsn);
		$this->tb_name = $CONFIG->tbName;
		$this->data = $CONFIG->arrTbItem;
		$this->field = $CONFIG->arrTbField;

		// DBTableManage 생성자에서 실행
		$this->tablecolor		= "#7e8ece";
		$this->sub_tablecolor	= "#cfdfef";
		$this->line_tablecolor	= "#7fafdf";
	}

}
?>