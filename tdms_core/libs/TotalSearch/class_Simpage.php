<?
set_time_limit(1000);

// +----------------------------------------------------------------------+
// | ���հ˻����α׷�                                                     |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 TDMS									          |
// +----------------------------------------------------------------------+
// | �� �ҽ������� ���۱��� ȫ����(03-76101273)���� �ֽ��ϴ�.             |
// | ������ ������ ������� �������� �����ϰų� ������ �� �����ϴ�.       |
// +----------------------------------------------------------------------+
// | ������: ȫ���� <eric.hong81@gmail.com>                               |
// +----------------------------------------------------------------------+

/**
* ���籺�� ���Ŭ����
*
* ���̺��������� ���
*
* @autor ȫ���� <eric.hong81@gmail.com>;
* @version 0.1
* @access public
* @package sbh
*/

class Simpage extends DBTable_TableForm{
	private $lensolnum; // ������ ����
	private $ArrSimnum = array(array()); // ���������� ������ �迭(2�����迭 => ����,�̸�)
	private $SimPercent; //���缺(�ۼ�Ʈ ��� ����)
	public $simNumPeople; // ��µ� �����Ͱ� ��

	private $small;
	private $serialfield, $namefield;

	/**
	 *  
	 * simPage Ŭ���� ������(�ʱ�ȭ)
	 * 
	 * @param $solnum  : ����
	 * @param $db_conn : dbĿ�ؼ�(DB::PEAR)
	 * @param $tb_name : ���̺� ��
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

		$this->serialfield = "SERIAL";		//������ �ش��ϴ� db�� �ʵ��(�⺻��) 
		$this->namefield = "NAME";				//�̸��� �ش��ϴ� db�� �ʵ��(�⺻��)
		$this->strLink = "index.php?index=search_result&";
		$this->small = true;
		if(!$this->m_solname)
			$this->SimPercent = 'false';
		else
			$this->SimPercent = 'true';
	}
	
	public function setFieldname($arr_tb_field){
		$this->serialfield = $arr_tb_field[����];
		$this->namefield = $arr_tb_field[�̸�];
	}

	/**
	 *  
	 * setSimPercentValue()
	 * ������ �̸��� �Է� �޾ƾ� �۵��Ѵ�.
	 * 
	 * @param $value  : ���缺�ʵ� �������(bool��)
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
	 * @return $SimPercent : ���缺�ʵ� �������(bool��)
	*/
	public function getSimPercentValue(){
		return $this->SimPercent;
	}

	/**
	 *
	 * setNameFieldLabel()
	 * private
	 *
	 * �������� ����� �̸��� �ش��ϴ� �ʵ������ ��ģ��. �⺻��(NAME)
	 * 
	 * @param $value : ��ĥ �ʵ��
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
	 * �������� ����� �̸��� �ش��ϴ� �ʵ������ ��ģ��. �⺻��(SERIAL)
	 * 
	 * @param $value : ��ĥ �ʵ��
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
	 * �� �ڸ�������(0-9)���� �����Ͽ� ���� ����� �迭�� �����Ѵ�.
	 * where like 'nnn_nnn' ���� ����
	 * 
	 * @param nothing: �ٲ� ��ġ(0���� ����)
	 *
	 * @return nothing
	*/
	private function retArrSimnum(){
		$sameNumCheck=false;
		$rowid=0;
		for( $line=0; $line<($this->lensolnum); $line++ ){
			for( $i=0; $i<10; $i++ ){
				$ulsolnum = substr_replace( $this->m_solnum, $i, $line, 1 );
				
				//���� ������ �ѹ��� ������ ��
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
					die('��������');
				}

				if( $row = $queryresult->fetchRow()){
					//�����ι��� ����
					if( (trim($row[$this->serialfield]) == $this->m_solnum) && (trim($row[$this->namefield]) == $this->m_solname) ){
						continue;
					}
					//������ �Է��� ��쿡 ���� ���� �ι� ����
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
						$this->ArrSimnum[$rowid][0] = $row[0]; //����
						$this->ArrSimnum[$rowid][1] = $row[1]; //�̸�
						$this->ArrSimnum[$rowid][2] = $line; //��ȣ�� �ٲ� ��ġ
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
	 * �Է¹��� �� �ΰ��� ���� ���Ͽ� ���缺�� ������� 
	 * ����� �ۼ�Ʈ�� �����Ѵ�. 
	 * 
	 * @param $value1 : ��1
	 * @param $value2 : ��2
	 *
	 * @return $per : ���缺(�ۼ�Ʈ) ��
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
	 * ���̺��� ��带 ��� (viewSimpageTable()���� �θ� <tr>������ ���)
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
					<p align=center><font class=tabletitle><b>����</b></font></p>
				</td>
				<td bgcolor=#7e8ece >
					<p align=center><font class=tabletitle><b>�̸�</b></font></p>
				</td>
			 ";

		if( $this->SimPercent == 'true' ){
			echo "
				<td bgcolor=#7e8ece >
					<p align=center><font class=tabletitle><b>���缺</b></font></p>
				</td>
			";
		}	
			echo "
				<td bgcolor=#7e8ece >
					<p align=center><font class=tabletitle><b>��ġ����</b></font></p>
				</td>

				<td bgcolor=#7e8ece>
					<p align=center><font class=tabletitle><b>�󼼰˻�</b></font></p>
				</td>
			 ";
		echo "</tr>";
	}


	/**
	 *
	 * viewSimpageTable_Content()
	 * private
	 *
	 * ���̺��� ������ ��� (viewSimpageTable()���� �θ� <tr>������ ���)
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
			//�̸� ���缺 ���
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
							echo "�����ι�";
						}
						else if( $sim_solnum == $this->m_solnum ){
							echo "������ġ";
						}
						else if( $sim_solname == $this->m_solname ){
							echo "�̸���ġ";
						}
						
			echo "		</font></p>
					</td>
				";
					$simCondition = $sim_solnum."`".$sim_solname."`";
				echo "
						<td>
							<p style=\"color: ".$fncolor.";font-size: 10pt;\" align=center><font >";
				//echo "<a href=".$this->strLink."mode=-1&search_index=".$this->tb_name."&simple=false&cgPage=MSearch&condition=".$simCondition.">�˻�</a>";
				if($this->small == true){
					echo "<a href=small_search_result_index.php?condition=".$simCondition.">�˻�</a>";
				}else{
					echo "<a href=index.php?index=search_result&search_index=TOTAL&condition=".$simCondition.">�˻�</a>";
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
		$this->retArrSimnum(); //�� �ڸ�������(0-9)���� �����Ͽ� ���� ����� �迭�� �����Ѵ�.
	
		//����˻��� ����� ���� ���
		if($this->simNumPeople <= 0){

			echo "<table align=center>";
			echo "<tr height=30><td align=center>";
			echo "<font style=\"color: red; font-size: 10pt;\"><b>�˻��Ϸ��� ���� ������ �����ϴ�.</b></font>";	
			echo "</td></tr>";
			echo "</table>";

		}// ����� ���� ���
		else {

			echo "<table align=center>";
			echo "<tr height=30><td align=center>";
			echo "<font style=\"font-size: 10pt;\"><b>�˻� ������ ���� ������ ".$this->simNumPeople."�� ã�ҽ��ϴ�.</b></font>";
			echo "</td></tr>";
			echo "</table>";
		
			echo "<table width=".$this->m_table_width." align=center border=0 bgcolor=#f4fcff cellspacing=\"0\" cellpadding=\"0\"  style=\"border-style: solid; border-width: 1; padding-top: 0\">";
			$this->viewSimpageTable_Head(); //�ʵ�row ���

			echo $count_value;
			for( $i=0; $i<$this->simNumPeople; $i++ ){
				$sim_solnum  = $this->ArrSimnum[$i][0];  //���� ����
				$sim_solname = $this->ArrSimnum[$i][1]; // ���� �̸�
				$sim_num = $this->ArrSimnum[$i][2]; //�ٲ� ��ġ
			
				 $this->viewSimpageTable_Content($sim_solnum, $sim_solname, $sim_num, $fncolor);
			} // end for
			echo "</table>";
		}
	} 
}
?>