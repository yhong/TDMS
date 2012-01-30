<?
class DBTableMultiList extends DBTable_TableDataList{

	private $TableType;
	private $arrMediumCategory;
	private $solnum, $solname, $jumin;
	protected $condition;
	protected $element;

	public $arrnoSearchCheck = array();

	function DBTableMultiList($tbNameValue, $arrMediumCategory, $mode, $condition, $element, $user_id){

		$this->arrMediumCategory = $arrMediumCategory;
		$this->mode = $mode; // 여러테이블 검색시 각 아이템별 번호(-1 전체검색)
		$this->condition = $condition;
		$this->element = $element;
		$this->user_id = $user_id;

		// $tbNameValue에 $arr_tblist나 $search_index를 넣어 값의 범위를 정한다.
		// $tableType 은 테이블의 타입(여러테이블을 동시에 검색하는가 아니면 하나만 검색하는가)
		// true  : 여러테이블 검색
		// false : 한개의 테이블 검색
		
		if(is_array($tbNameValue)){
			$this->TableType = true;  //전체 및 부분 검색
			
			//if($mode == -1 || $mode == $i){  //모드를 보고 전체보기를 하거나 자신의 mode가 아니면 보여주지 않는다.
				$this->FirstDataSetting($tbNameValue);
				$this->viewData();
			//}
			
		}else{

			$this->TableType = false; // 한개 검색
			$this->FirstDataSetting($tbNameValue);
			$this->viewData();
		}
			
	}
	// dsn과 테이블 이름, 아이템, 필드명등 print_table_list 클래스에 필요한 데이터를 설정한다.
	// 단 싱글과 멀티를 구분해야 함
	private function FirstDataSetting($tbNameValue){
		if($this->TableType == true){ //여러개일 때
			for($i=0;$i<count($tbNameValue);$i++){
				$this->FirstData[$i] = $this->arrMediumCategory[$tbNameValue[$i]]; //테이블의 상세정보알기
				array_push ($this->FirstData[$i], $tbNameValue[$i]); //마지막에 테이블명 추가

			} //end for
		}else{ //데이터가 하나일 때
			$this->FirstData[0] = $this->arrMediumCategory[$tbNameValue][0];	// 테이블의 제목명($tb_title)
			
			$elmexp = explode("`", $this->element);
			if($tbNameValue == "pa01mt"){
				if($elmexp[0]){
					for($i=0;$i<($elmexp[0]+1);$i++){
						list(,$value) = each($this->arrMediumCategory[$tbNameValue][1]);	// 테이블의 연결명DSN($Dsn)
						$this->FirstData[1] = $value;	
					}
					//echo "//". $this->FirstData[1]."//";
				}else{
					$this->FirstData[1] = $this->arrMediumCategory[$tbNameValue][1];	// 테이블의 연결명DSN($Dsn)
				}
			}else{
				$this->FirstData[1] = $this->arrMediumCategory[$tbNameValue][1];	// 테이블의 연결명DSN($Dsn)
			}
			//print_r($this->FirstData[1]);


			$this->FirstData[2] = $this->arrMediumCategory[$tbNameValue][2];	// 테이블의 필드  제목명(배열)($arrTbItem)
			$this->FirstData[3] = $this->arrMediumCategory[$tbNameValue][3];	// db테이블의 필드명(배열)($arrTbField)
			$this->FirstData[6] = $tbNameValue; //테이블명 추가
		}
	}

	public function viewData(){
		if($this->TableType == true){ //여러개일 때

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
			//$key는 각 dsn의 설명
			while(list($key, $dsnvalue) = each($Dsn)){
				//echo $dsnvalue;
				$this->funcListViewTable($dsnvalue, $tbname, $key, true);
				
			}
		}else{
			
				$this->funcListViewTable($Dsn, $tbname,  "", false);
		} // end if
	}

	// 결과 테이블을 보여주는 일련 명령들	
	public function funcListViewTable($dsnval, $tbname,  $subtitle, $isarr){
		
		$tb_title			=	$this->arrMediumCategory[$tbname][0];// 테이블의 제목
		$arr_tb_item		=	$this->arrMediumCategory[$tbname][2];// 테이블의  필드
		$arr_tb_field		=	$this->arrMediumCategory[$tbname][3];// 테이블의 데이터 명(배열)

		if($subtitle){
			$title = $tb_title."(".$subtitle.")"; //서브타이틀이 있을때..
		}else{
			$title = $tb_title;
		}
		
		view_collection($tbname);
		$this->DBTableDataList($title, $arr_tb_item, $this->user_id);

		 $this->DBManage($dsnval);
		
		 $this->setTbName($tbname);
		 $this->setField($arr_tb_field);
		 $this->setCondition($this->condition);
		 $this->setElement($this->element);

		//$this->getItemList();
		//echo $this->getQuery()."<br>";
		$likeisval=$this->setBasicInputQuery();  //쿼리를 구성
		
		
		// 추가기능
		$eleval = explode('`', $this->element);
		if($tbname=="pa01mt"){
			if (array_key_exists("임관일자", $arr_tb_field)) {
				$likeisval = $this->addSearchBETWEENDate2Field($arr_tb_field['임관일자'], $eleval[1],	$arr_tb_field['임관일자'], $eleval[2], $likeisval, '-');
			}
			if (array_key_exists("전역일자", $arr_tb_field)) {
				$likeisval = $this->addSearchBETWEENDate2Field($arr_tb_field['전역일자'], $eleval[3],	$arr_tb_field['전역일자'], $eleval[4], $likeisval, '-');
			}
		}
		if($tbname=="H_TOTAL_JARY"){
			if (array_key_exists("자료형태", $arr_tb_field)) {
				$likeisval = $this->searchLikeorEqu($arr_tb_field['자료형태'], $eleval[0], $likeisval, true);
			}
		}
		
		$this->setQuery("select ".$this->field_kind." from ".$this->tb_name." where ".$likeisval);
		//echo "select ".$this->field_kind." from ".$this->tb_name." where ".$likeisval;

		if($this->likeis){  // (특수한 자료만 있는 경우에 검색하지 않음) => 주민번호가 없는 자료는 검색하지 않는다.
			$this->executeQuery();
			$rowvalue = $this->countTotalRecordRow();
			//$this->totalView();

			if($rowvalue <= 0){
				array_push($this->arrnoSearchCheck, $this->title);
			}
			else if(($isarr==true && $rowvalue > 0) || $isarr == false){ // 배열 dsn이고 값이 없는경우, 출력하지 않는다.
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
					  $smpage->setSmallFlag(false);
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