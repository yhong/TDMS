<?
class DBTable_TableMultiList extends DBTable_TableDataList{

	private $TableType;
	private $arrMediumCategory;
	private $solnum, $solname, $jumin;
	protected $condition;
	private $PetiSearchResult;
	public $arrnoSearchCheck = array();
	private $mode;

	function DBTable_TableMultiList($tbNameValue, $arrMediumCategory, $mode, $condition,$element, $user_id){
		$this->arrMediumCategory = $arrMediumCategory;
		$this->mode = $mode; // �������̺� �˻��� �� �����ۺ� ��ȣ(-1 ��ü�˻�)
		$this->condition = $condition;
		$this->user_id = $user_id;

		// $tbNameValue�� $arr_tblist�� $search_index�� �־� ���� ������ ���Ѵ�.
		// $tableType �� ���̺��� Ÿ��(�������̺��� ���ÿ� �˻��ϴ°� �ƴϸ� �ϳ��� �˻��ϴ°�)
		// true  : �������̺� �˻�
		// false : �Ѱ��� ���̺� �˻�
		
		if(is_array($tbNameValue)){

			$this->TableType = true;  //��ü �� �κ� �˻�
			
			//if($mode == -1 || $mode == $i){  //��带 ���� ��ü���⸦ �ϰų� �ڽ��� mode�� �ƴϸ� �������� �ʴ´�.
				$this->FirstDataSetting($tbNameValue);
				$this->viewData();
			//}
			
		}else{

			$this->TableType = false; // �Ѱ� �˻�
			$this->FirstDataSetting($tbNameValue);
			$this->viewData();
		}
			
	}
	// dsn�� ���̺� �̸�, ������, �ʵ��� print_table_list Ŭ������ �ʿ��� �����͸� �����Ѵ�.
	// �� �̱۰� ��Ƽ�� �����ؾ� ��
	private function FirstDataSetting($tbNameValue){
		if($this->TableType == true){ //�������� ��
			for($i=0;$i<count($tbNameValue);$i++){
				$this->FirstData[$i] = $this->arrMediumCategory[$tbNameValue[$i]]; //���̺��� �������˱�
				array_push ($this->FirstData[$i], $tbNameValue[$i]); //�������� ���̺�� �߰�

			} //end for
		}else{ //�����Ͱ� �ϳ��� ��
			$this->FirstData[0] = $this->arrMediumCategory[$tbNameValue][0];	// ���̺��� �����($tb_title)
			$this->FirstData[1] = $this->arrMediumCategory[$tbNameValue][1];	// ���̺��� �����DSN($Dsn)
			$this->FirstData[2] = $this->arrMediumCategory[$tbNameValue][2];	// ���̺��� �ʵ�  �����(�迭)($arr_tb_item)
			$this->FirstData[3] = $this->arrMediumCategory[$tbNameValue][3];	// db���̺��� �ʵ��(�迭)($arr_tb_field)
			$this->FirstData[6] = $tbNameValue; //���̺�� �߰�
		}
	}

	public function viewData(){
		if($this->TableType == true){ //�������� ��

			for($i=0;$i<count($this->FirstData);$i++){
				
				if( $this->mode == $this->FirstData[$i][5] || $this->mode == -1 ){
					$this->isArrDsnAndView( $this->FirstData[$i][1], $this->FirstData[$i][6] );
				}
			}
		}else{
			$this->isArrDsnAndView( $this->FirstData[1], $this->FirstData[6] );
		}
	}

	// private
	private function isArrDsnAndView($Dsn, $tbname){

		if(is_array($Dsn)){
			//$key�� �� dsn�� ����
			while(list($key, $dsnvalue) = each($Dsn)){
				//echo $dsnvalue;
				$this->funcListViewTable($dsnvalue, $tbname, $key, true);
			}
		}else{
				$this->funcListViewTable($Dsn, $tbname,  "", false);
		} // end if
	}

	public function getSearchResultValue(){
		return $this->PetiSearchResult;
	}

	// ��� ���̺��� �����ִ� �Ϸ� ��ɵ�	
	public function funcListViewTable($dsnval, $tbname,  $subtitle, $isarr){
		
		$tb_title			=	$this->arrMediumCategory[$tbname][0];// ���̺��� ����
		$arr_tb_item		=	$this->arrMediumCategory[$tbname][2];// ���̺���  �ʵ�
		$arr_tb_field		=	$this->arrMediumCategory[$tbname][3];// ���̺��� ������ ��(�迭)

		if($subtitle){
			$title = $tb_title."(".$subtitle.")"; //����Ÿ��Ʋ�� ������..
		}else{
			$title = $tb_title;
		}
		
		view_collection($tbname);
		$this->DBTableDataList($title, $arr_tb_item, $this->user_id);

		 $this->DBManage($dsnval);
		
		 $this->setTbName($tbname);
		 $this->setField($arr_tb_field);

		 $this->setCondition($this->condition);

		//$this->getItemList();
		//echo $this->getQuery()."<br>";
		//$this->setBasicInputQuery();  //������ ����
		$likeisval=$this->setBasicInputQuery();  //������ ����
		$this->setQuery("select ".$this->field_kind." from ".$this->tb_name." where ".$likeisval);

		if($this->likeis){  // (Ư���� �ڷḸ �ִ� ��쿡 �˻����� ����) => �ֹι�ȣ�� ���� �ڷ�� �˻����� �ʴ´�.
			$this->executeQuery();
			$rowvalue = $this->countTotalRecordRow();
			//$this->totalView();

			if($rowvalue <= 0){ // ���� ���°��, ������� �ʴ´�.
				array_push($this->arrnoSearchCheck, $this->title);
			}
			else if(($isarr==true && $rowvalue > 0) || $isarr == false){ // �迭 dsn�̰� ���� �ִ� ��� ���
			$this->PetiSearchResult[$tbname] = $rowvalue;
			echo "
				<tr>
					<td colspan=4 height=100>
				";
					$this->TitleView();
					$this->tableContentView();
				echo "
					</tr>
				</td>
				";
			}
			if( ($this->mode != -1 || !$this->mode)  && $this->m_solnum){
					 $smpage = new Simpage($dsnval, $tbname, $this->condition);
					 $smpage->setFieldname($arr_tb_field);
					 $smpage->DBTableManage($arr_tb_item);
					 //$smpage->setLink("small_search_result_index.php?");
					 $smpage->setTableWidth(450);
					 $smpage->viewSimpageTable();
					 echo "<br>";
				}
		}
		$this->DBClose();
	} // end function
} // end class
?>