<?
class DBTableMultiSearch extends DBTable_TableForm{
	private $arrMCategory;
	private $state_flag;
	private $arrTbList;
	protected $user_id;
	private $arrDsn;
	private $bcheck;

	public function DBTableMultiSearch($dsn, $arrMediumCategory, $arrTbList, $user_id, $bcheck){
		$this->bcheck = $bcheck;
		$this->arrDsn = $dsn;
		$this->DBManage($dsn[0]);
		$this->arrMCategory = $arrMediumCategory;
		//$arrTbList = array_keys($arrMediumCategory);
		$this->arrTbList = $arrTbList;
		$this->state_flag = false; //����Ʈ(�⺻��)
		$this->user_id = $user_id;
	}

	// false : ����Ʈ�� ���� �Է¹���
	// true  : ���Ϸ� ���� �Է¹���
	public function setState($value){
		$this->state_flag = $value; 
	}

	public function retarrTbName(){
		$arrName=array();

		for($i=0;$i<count($this->arrTbList);$i++){
			$arrName[$i]= $this->arrMCategory[$this->arrTbList[$i]][0];
		}
		return $arrName;
	}
	public function PrintMultiList_column($tablename, $solnum){

		$this->setTbName($tablename);
		$record_num = $this->countConditionRecordRow($this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
		
		if($record_num > 0){
			
				if($tablename == "pa01mt"){
					if($this->bcheck == '1'){
						$this->setTbName($tablename);
						while(list($key, $value) = each($this->arrDsn[5])){
							$this->newConnection($value, $tablename);
							$datacnt = $this->countConditionRecordRow($this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
							
							if($datacnt>0){
								$bname= $this->getFieldValue($this->arrMCategory[$tablename][3][�̸�], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
								$benddata= $this->getFieldValue($this->arrMCategory[$tablename][3][��������], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
								
								
								$bbyunga_code= $this->getFieldValue($this->arrMCategory[$tablename][3][����], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
								$this->setTbName("ps01mt");
								$bbyunga= $this->getFieldValue("code_nm", "major_cd='19' and minor_cd='".trim($bbyunga_code)."'");


								echo "<p align=left>".$key."/".trim($bname)."/".$benddata."/".$bbyunga."</p>";
							}else{
								continue;	
							}
						}
						reset($this->arrDsn[5]);

						$this->newConnection($this->arrDsn[0], $tablename);
						return;
					}else{
						echo "[�˻�����]";
					}
					return;
				}
			

			echo "<font color=blue>O(".$record_num."��)</font>".$honum;
			
			if($tablename == "H_TOTAL_SANGE"){
				$ret_date_val = $this->getFieldValue($this->arrMCategory[$tablename][3][��������], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
				$ret_ho_val = $this->getFieldValue($this->arrMCategory[$tablename][3][���ȣ��], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
				echo "<br>".substr($ret_date_val,0,4)."��/".$ret_ho_val."ȣ";
			}
			if($tablename == "H_TOTAL_BYUNGSANG"){
				$ret_isserver_val = $this->getFieldValue($this->arrMCategory[$tablename][3][����ȭ����], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
				if($ret_isserver_val == 'Y'){
					echo "<br><font style='color:blue'>����O</font>";
				}else{
					echo "<br><font style='color:red'>����X</font>";
				}
			}
			if($tablename == "H_TOTAL_JARY"){
				?>
					<script language="javascript">
					<!--
					function viewjaryuck1(c_doc_id, c_doc_count, user_id) //�ڷ�ǥ ��������
					{	
						var open_url = "http://archive.army.mil/home/girokbojon/sangse/h_view/H_jaryuck_viewer.asp?c_doc_id="+c_doc_id + "&c_doc_count="+c_doc_count+"&system_id=1&user_id="+user_id;
						open_window('jaryuck',open_url, 0, 0, 675, 680, 0, 0, 0, 1, 1);
					}
					function viewjaryuck2(c_docnumber,user_id)
					{
					   
						var open_url = "http://archive.army.mil/home/girokbojon/sangse/h_view/h_viewer.asp?c_docnumber="+c_docnumber+"&value=3&user_id="+user_id;
						open_window('jaryuck', open_url, 0, 0, 675, 680, 0, 0, 0, 1, 1);
					}
					//-->
					</script>
				<?
				$ret_sol_val = $this->getFieldValue($this->arrMCategory[$tablename][3][�ڷ�����], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
				$ret_docnum_val = $this->getFieldValue($this->arrMCategory[$tablename][3][������ȣ], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
				

				if($ret_sol_val == 0){
					$ret_page_val = $this->getFieldValue($this->arrMCategory[$tablename][3][������], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
					echo "<a href=\"javascript:viewjaryuck1('".$ret_docnum_val."','".$ret_page_val."','".$this->user_id."')\"><img src='img/btn_09.gif' width='14' height='14' border=0)></a>";
				}else if($ret_sol_val == 1){
					echo "<a href=\"javascript:viewjaryuck2('".$ret_docnum_val."','".$this->user_id."')\"><img src='img/btn_09.gif' width='14' height='14' border=0)></a>";
				}
			}
		}else{
			if($tablename == "H_TOTAL_BYUNGSANG"){
				echo "<font color=gray><a href=javascript:SearchWindow('search','small_search_result_index.php?&search_index=TOTAL&mode1=2&condition=".$solnum."``&solnum=&solname=&cgPage=BSearch')><b>X</b></font>";
			}else{
				echo "<font color=gray>X</font>";
			}
		}
							
	}

	private function compare_three_value($first, $two, $three){
	$value[0]=$first;
	$value[1]=$two;
	$value[2]=$three;
	$flag=true;

		for($i=0;$i<3;$i++){
			if($value[$i]=='')
				break;
					for($j=0;$j<3;$j++){
						if($value[$j]=='')
							break;
						if($value[$i] != $value[$j]){
							$flag=false;
						}
					}
				}
		if($flag){
			if($value[0])
				return $value[0];
			else if($value[1])
				return $value[1];
			else if($value[2])
				return $value[2];
			else 
				return false;
		}else{
			return "<font style='color:red;font-size=9pt'>�󼼰˻����</font>";
		}
	}

	// sol_multi_list.php���� ���
	public function PrintMultiList($solnum, $index_no){
		
	//	if(ereg ("([0-9]{1})", $solnum)){ //�����϶�

			$this->PrintMultiList_is_sn($solnum, $index_no);
/*
			}else{  // �̸��� ��
				$this->PrintMultiList_is_name($solnum, $index_no);	
			}		
*/
	}


	//�����϶�
	private function PrintMultiList_is_sn($solnum, $index_no){
		echo "<tr bgcolor=".$this->tablecolor.">";
			echo "<td align=center bgcolor=".$this->sub_tablecolor.">".$index_no."</td>";
				echo "<td bgcolor=white>&nbsp;$solnum</td>";

				// �̸� ��� compare_three_value => 3���̺��� �̸� ���� ���Ͽ� ������ ������ �˻�
/*
				for($i=0; $i<count($this->arrTbList); $i++){
					$name[$i] = $this->PrintMultiList_name_return($this->arrTbList[$i], $solnum);
				}

				if($cmp_result = $this->compare_three_value($name[0], $name[1], $name[2]))
					echo "<td bgcolor=white><p align=center>".$cmp_result."</p></td>";
				else
					echo "<td bgcolor=white><p align=center>X</p></td>";
*/

				for($i=0; $i<count($this->arrTbList); $i++){
					echo "<td  bgcolor=white align=center>";
						$this->PrintMultiList_column($this->arrTbList[$i], $solnum); //column ���� ���
					echo " </td>";
				}

				/*
				echo "<td bgcolor=white align=center>
					<a href=javascript:SearchWindow('search','small_search_result_index.php?search_index=HOSPITAL&condition=".$solnum."``')>Ȯ��</a>
				</td>";
				*/
				
				echo "<td bgcolor=white align=center>
					<a href=javascript:SearchWindow('search','small_search_result_index.php?condition=".$solnum."``')>Ȯ��</a>";
				/*
				echo "
					<input type=button onClick=window.open('http://archive.army.mil/home/girokbojon/sangse/h_view/h_jaryuck_result.asp?user_id=".$this->user_id."&sn=".$solnum."&kind=1','','channelmode,outerHeight,outerWidth'); value='(�屳)�����ڷ�ǥ'>";
				echo "<input type=button onClick=window.open('http://archive.army.mil/home/girokbojon/sangse/h_view/h_jaryuck_result.asp?user_id=".$this->user_id."&sn=".$solnum."&kind=2','','channelmode,outerHeight,outerWidth'); value='(�λ��)�����ڷ�ǥ'>";

				echo "</td>";
				*/

				echo "</tr>";
				return $index_no;
	}

	//�̸��϶�
	private function PrintMultiList_is_name($solname,$index_no,$arr_table){
		for($i=0;$i<count($arr_table);$i++){
			$count_value=$this->countConditionRecordRow($this->arrMCategory[$tablename][3][�̸�]."='".trim($solname)."'");	
				if($count_value > 0){
					$ret_name_val = getFieldValue($this->arrMCategory[$tablename][3][����], $this->arrMCategory[$tablename][3][�̸�]."='".trim($solname)."'");
					
					while($num = mysql_fetch_array($result)){
						$index_no = $this->PrintMultiList_is_sn($ret_name_val, ($index_no+1),$arr_table);
					}
				}else{
					break;
				}
		}
	}


		// }}}
		// {{{ PrintMultiList_name_return()

		/**
		 * PrintMultiList���� �����ϴ� �Լ�
		 * db���̺��� ������ �������� �̸��� �˻��Ͽ� �����ϴ� �Լ�
		 *
		 * @param $tablename db���� ������ ���̺��
		 * @param $solnum �� ������ ����
		 * @param $dbconn dbĿ�ؼ�
		 *
		 * @return ���� ������ �� ��(�̸�)�� �����ϰ� ������ ������ �����Ѵ�.
		*/

		public function PrintMultiList_name_return($tablename, $solnum){
			//$this->setTbName($tablename);
			$retnamevalue = $this->getFieldValue($this->arrMCategory[$tablename][3][�̸�], $this->arrMCategory[$tablename][3][����]."='".trim($solnum)."'");
			
			if($retnamevalue){
				return $retnamevalue;
			}else{
				return '';
			}
		}

}
?>