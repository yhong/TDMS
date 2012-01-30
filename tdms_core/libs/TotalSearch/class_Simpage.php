<?
set_time_limit(1000);

// +----------------------------------------------------------------------+
// | 통합검색프로그램                                                     |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 TDMS									          |
// +----------------------------------------------------------------------+
// | 이 소스파일의 저작권은 홍영훈(03-76101273)에게 있습니다.             |
// | 누구도 본인의 허락없이 무단으로 수정하거나 변조할 수 없습니다.       |
// +----------------------------------------------------------------------+
// | 만든이: 홍영훈 <eric.hong81@gmail.com>                               |
// +----------------------------------------------------------------------+

/**
* 유사군번 출력클래스
*
* 테이블형식으로 출력
*
* @autor 홍영훈 <eric.hong81@gmail.com>;
* @version 0.1
* @access public
* @package sbh
*/

class Simpage extends DBTable_TableForm{
	private $lensolnum; // 군번의 길이
	private $ArrSimnum = array(array()); // 유사정보을 저장할 배열(2차원배열 => 군번,이름)
	private $SimPercent; //유사성(퍼센트 출력 유무)
	public $simNumPeople; // 출력된 데이터갯 수

	private $small;
	private $serialfield, $namefield;

	/**
	 *  
	 * simPage 클래스 생성자(초기화)
	 * 
	 * @param $solnum  : 군번
	 * @param $db_conn : db커넥션(DB::PEAR)
	 * @param $tb_name : 테이블 명
	 *
	 * @return nothing
	*/
	function Simpage($dsnval, $tablename, $condition){
		// DBManage
		$this->DBManage($dsnval);

		$this->setTbName($tablename);
		$this->setCondition($condition);

		$this->lensolnum	= strlen(trim($this->m_solnum));
		$this->table_width	=	380;

		$this->serialfield = "SERIAL";		//군번에 해당하는 db의 필드명(기본값) 
		$this->namefield = "NAME";				//이름에 해당하는 db의 필드명(기본값)
		$this->strLink = "index.php?index=search_result&";
		$this->small = true;
		if(!$this->m_solname)
			$this->SimPercent = 'false';
		else
			$this->SimPercent = 'true';
	}
	
	public function setFieldname($arr_tb_field){
		$this->serialfield = $arr_tb_field[군번];
		$this->namefield = $arr_tb_field[이름];
	}

	/**
	 *  
	 * setSimPercentValue()
	 * 무조건 이름을 입력 받아야 작동한다.
	 * 
	 * @param $value  : 유사성필드 출력유무(bool형)
	 *
	 * @return nothing
	*/
	public function setSimPercentValue($value='false'){

		if(!$this->m_solname || $value=='false')
			$this->SimPercent = 'false';
		else
			$this->SimPercent = 'true';
	}

	/**
	 *  
	 * getSimPercentValue()
	 * 
	 * @param nothing
	 *
	 * @return $SimPercent : 유사성필드 출력유무(bool형)
	*/
	public function getSimPercentValue(){
		return $this->SimPercent;
	}

	/**
	 *
	 * setNameFieldLabel()
	 * private
	 *
	 * 쿼리에서 사용할 이름에 해당하는 필드명으로 고친다. 기본값(NAME)
	 * 
	 * @param $value : 고칠 필드명
	 *
	 * @return nothing
	*/
	public function setNameFieldLabel($value){
		$this->namefield = $value;
	}

	/**
	 *
	 * setSerialFieldLabel()
	 * private
	 *
	 * 쿼리에서 사용할 이름에 해당하는 필드명으로 고친다. 기본값(SERIAL)
	 * 
	 * @param $value : 고칠 필드명
	 *
	 * @return nothing
	*/
	public function setSerialFieldLabel($value){
		$this->serialfield = $value;
	}
	
	/**
	 *
	 * retArrSimnum()
	 * private
	 *
	 * 각 자리수마다(0-9)까지 대입하여 나온 결과를 배열로 저장한다.
	 * where like 'nnn_nnn' 보다 빠름
	 * 
	 * @param nothing: 바꿀 위치(0부터 시작)
	 *
	 * @return nothing
	*/
	private function retArrSimnum(){
		$sameNumCheck=false;
		$rowid=0;
		for( $line=0; $line<($this->lensolnum); $line++ ){
			for( $i=0; $i<10; $i++ ){
				$ulsolnum = substr_replace( $this->m_solnum, $i, $line, 1 );
				
				//같은 군번은 한번만 나오게 함
				if(substr($this->m_solnum, $line, 1) == $i){
					if($sameNumCheck==true){
						continue;
					}else{
						$sameNumCheck = true;
					}
				}

				$sql="select ".$this->serialfield.", ".$this->namefield." from ".$this->tb_name." where ".$this->serialfield."='$ulsolnum' ";	

				$queryresult = $this->db_conn->query($sql);
				if(DB::isError($queryresult)){
					echo $queryresult->getMessage();
					die('쿼리에러');
				}

				if( $row = $queryresult->fetchRow()){
					//동일인물은 배재
					if( (trim($row[$this->serialfield]) == $this->m_solnum) && (trim($row[$this->namefield]) == $this->m_solname) ){
						continue;
					}
					//군번만 입력한 경우에 군번 동일 인물 배재
					else if($this->m_solnum && !$this->m_solname){
						if( trim($row[$this->serialfield]) == $this->m_solnum ){
							continue;
						}
					}
					
					list($this->ArrSimnum[$rowid][0], $this->ArrSimnum[$rowid][1]) = $row;

				//	echo "<<  ".$this->ArrSimnum[$rowid][0]." ".$this->ArrSimnum[$rowid][1]."  >>";
					

					if($this->ArrSimnum[$rowid][1] ==''){
						continue;
					}else{
						$this->ArrSimnum[$rowid][0] = $row[0]; //군번
						$this->ArrSimnum[$rowid][1] = $row[1]; //이름
						$this->ArrSimnum[$rowid][2] = $line; //번호가 바뀐 위치
						$rowid++;
					}


				} //END IF
				

			} // END FOR
		} // END FOR
			$this->simNumPeople = $rowid;


	} // END FUNCTION


	/**
	 *
	 * perValue()
	 * public
	 *
	 * 입력받은 값 두개를 서로 비교하여 유사성을 계산한후 
	 * 결과를 퍼센트로 리턴한다. 
	 * 
	 * @param $value1 : 값1
	 * @param $value2 : 값2
	 *
	 * @return $per : 유사성(퍼센트) 값
	*/
	private function perValue($value1,$value2){
		$value1 = trim($value1);
		$value2 = trim($value2);

		$test=similar_text($value1, $value2, $test);
		$per=strlen($value1);
		$per=ceil(($test/$per)*100);
		if(strlen($value1)!= strlen($value2)){
			$per=$per*(0.7);
		}
		return $per;
	}

	/**
	 *
	 * viewSimpageTable_Head()
	 * public
	 *
	 * 테이블의 헤드를 출력 (viewSimpageTable()에서 부름 <tr>단위로 출력)
	 * private
	 * 
	 * @param nothing
	 *
	 * @return nothing
	*/
	private function viewSimpageTable_Head(){
		echo "<tr>";
		echo "
				<td bgcolor=#7e8ece >
					<p align=center><font class=tabletitle><b>군번</b></font></p>
				</td>
				<td bgcolor=#7e8ece >
					<p align=center><font class=tabletitle><b>이름</b></font></p>
				</td>
			 ";

		if( $this->SimPercent == 'true' ){
			echo "
				<td bgcolor=#7e8ece >
					<p align=center><font class=tabletitle><b>유사성</b></font></p>
				</td>
			";
		}	
			echo "
				<td bgcolor=#7e8ece >
					<p align=center><font class=tabletitle><b>일치유무</b></font></p>
				</td>

				<td bgcolor=#7e8ece>
					<p align=center><font class=tabletitle><b>상세검색</b></font></p>
				</td>
			 ";
		echo "</tr>";
	}


	/**
	 *
	 * viewSimpageTable_Content()
	 * private
	 *
	 * 테이블의 내용을 출력 (viewSimpageTable()에서 부름 <tr>단위로 출력)
	 * private
	 * 
	 * @param nothing
	 *
	 * @return nothing
	*/
	private function viewSimpageTable_Content($sim_solnum, $sim_solname, $sim_num){
			$sim_solnum = trim($sim_solnum);
			$sim_solname = trim($sim_solname);

			if( $this->SimPercent == 'true'){
			//이름 유사성 계산
			$per = $this->perValue($this->m_solname, $sim_solname);
			
			if($this->m_solname == $sim_solname || $this->m_solnum == $sim_solnum){
				$tbcolor="#f2f42f";
			}else{
				$tbcolor="";
			}
				if( $per >= 30 && $per < 50 ){
					$fncolor="blue";
				}else if( $per >= 50 && $per < 80 ){
					$fncolor="green";
				}else if( $per >= 80 ){
					$fncolor="red";
				}else{
					$fncolor="black";
				}
					
			}
			$temp_str=substr($sim_solnum, $sim_num, 1);
			$temp_str=substr_replace($sim_solnum, "<font style=\"color: red\">".$temp_str."</font>", $sim_num, 1);

			
		
		echo "
			<tr style=\"color: ".$tbcolor."\">
				<td >
					<p align=center><font style=\"color: ".$fncolor."\">$temp_str</font></p>
				</td>
				<td>
					<p align=center><font style=\"color: ".$fncolor."\">$sim_solname</font></p>
				</td>
			";

				if( $this->SimPercent == 'true' ){
					echo "<td><p align=center><font style=\"color: ".$fncolor."\">";
					echo ceil($per);
					echo"%</font></p></td>";
				}

			echo "
					<td>
						<p align=center><font style=\"color: ".$fncolor.";font-size: 10pt;\">";

						if( ( $sim_solnum == $this->m_solnum ) && ( $sim_solname == $this->m_solname )){
							echo "동일인물";
						}
						else if( $sim_solnum == $this->m_solnum ){
							echo "군번일치";
						}
						else if( $sim_solname == $this->m_solname ){
							echo "이름일치";
						}
						
			echo "		</font></p>
					</td>
				";
					$simCondition = $sim_solnum."`".$sim_solname."`";
				echo "
						<td>
							<p style=\"color: ".$fncolor.";font-size: 10pt;\" align=center><font >";
				//echo "<a href=".$this->strLink."mode=-1&search_index=".$this->tb_name."&simple=false&cgPage=MSearch&condition=".$simCondition.">검색</a>";
				if($this->small == true){
					echo "<a href=small_search_result_index.php?condition=".$simCondition.">검색</a>";
				}else{
					echo "<a href=index.php?index=search_result&search_index=TOTAL&condition=".$simCondition.">검색</a>";
				}
				echo "
							</font></p>
						</td>
					";
			echo "</tr>";
	}

	public function setSmallFlag($value){
		$this->small = $value;
	}

	
	/**
	 *
	 * viewSimpageTable()
	 * private
	 *
	 * viewSimpageTable_Head() + viewSimpageTable_Content()
	 * 
	 * @param nothing
	 *
	 * @return nothing
	*/
	public function viewSimpageTable(){
		$this->retArrSimnum(); //각 자리수마다(0-9)까지 대입하여 나온 결과를 배열로 저장한다.
	
		//유사검색에 결과가 없을 경우
		if($this->simNumPeople <= 0){

			echo "<table align=center>";
			echo "<tr height=30><td align=center>";
			echo "<font style=\"color: red; font-size: 10pt;\"><b>검색하려는 유사 군번이 없습니다.</b></font>";	
			echo "</td></tr>";
			echo "</table>";

		}// 결과가 있을 경우
		else {

			echo "<table align=center>";
			echo "<tr height=30><td align=center>";
			echo "<font style=\"font-size: 10pt;\"><b>검색 군번의 유사 군번을 ".$this->simNumPeople."명 찾았습니다.</b></font>";
			echo "</td></tr>";
			echo "</table>";
		
			echo "<table width=".$this->m_table_width." align=center border=0 bgcolor=#f4fcff cellspacing=\"0\" cellpadding=\"0\"  style=\"border-style: solid; border-width: 1; padding-top: 0\">";
			$this->viewSimpageTable_Head(); //필드row 출력

			echo $count_value;
			for( $i=0; $i<$this->simNumPeople; $i++ ){
				$sim_solnum  = $this->ArrSimnum[$i][0];  //계산된 군번
				$sim_solname = $this->ArrSimnum[$i][1]; // 계산된 이름
				$sim_num = $this->ArrSimnum[$i][2]; //바뀐 위치
			
				 $this->viewSimpageTable_Content($sim_solnum, $sim_solname, $sim_num, $fncolor);
			} // end for
			echo "</table>";
		}
	} 
}
?>