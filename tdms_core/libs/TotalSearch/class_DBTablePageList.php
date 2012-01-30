<?
class DBTablePageList extends DBTable_TableDataList{

	private $limit_query_result;

	function DBTablePageList($title, $arr_tb_item, $user_id){
		$this->DBTableDataList($title, $arr_tb_item, $user_id);
		$this->user_id = $user_id;
	}


	// ������ ����
	public function executeLimitQuery(){ 
		// �����ۼ�
		//$this->query = "select ".$this->field_kind." from (select rownum as linenum, ".$this->field_kind." from ".$this->tb_name." where rownum <=".($startnum+$pageperrow)." and ".$this->likeis." ) where linenum >= ".$startnum; //����Ŭ

		//$this->query = "select FIRST 10 ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis; //�����ͽ�
		// �����ͽ��� db��ü�� ���������� ����� �����Ƿ� ���� �����ؾ� �Ѵ�

		$this->query = "select ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis;
		$this->limit_query_result = $this->db_conn->query($this->query);

		if(DB::isError($this->limit_query_result)){
			echo "����!!<br>";
			echo $this->limit_query_result->getMessage();
		}
	}

	/**
	 *  
	 * tableContentView()
	 * ���̺��� ������ �����ش�(���̺�� ����) 
	 * public
	 * 
	 * @param $table_width : ���̺��� ����
	 *
	 * @return nothing
	*/
	public function tableContentView($startnum, $rowdatanum){
		
		echo "
		<table width=".$this->m_table_width." height=85 align=center border=0 cellspacing=\"0\" cellpadding=\"0\"  style=\"border-style: solid; border-width: 1; padding-top: 0\">
		";

		// �� �ʵ��� Ÿ��Ʋ �κ�
		echo "<tr height=43%>";
			$numfield = count($this->arr_item); // �ʵ��� ����
		echo "<td bgcolor=#7e8ece>
				<p align=center><font class=tabletitle><b>�Ϸù�ȣ</b></font></p>
			</td>
			";
			for($i=0; $i<$numfield; $i++){	
				echo "
					<td bgcolor=#7e8ece>
						<p align=center><font class=tabletitle><b>".$this->arr_item[$i]."</b></font></p>
					</td>
				";
			} // END FOR
			
		echo "</tr>";

			$from = $startnum;
		// ����
			$skDataIndex = 0;
			$data_count = 0;
			$startnum += 1;
			
			while($row=($this->limit_query_result->fetchRow(DB_FETCHMODE_ASSOC))){
				
				if($skDataIndex < $from){
					$skDataIndex++;
					continue;
				}else{
					$data_count++;
				}

				echo "<tr height=57%>";	

					echo "<td>
							<p align=center>"; //�ʵ��� ����
							
						echo $startnum++;
						echo "</p></td>"; //�ʵ� ��
					while (list (, $val) = each ($this->arr_item)) {
					
						echo "<td>
							<p align=center>"; //�ʵ��� ����
							
						$this->tableEtcFieldShow($val, $row); //�ʵ� �����ֱ�

						echo "</p></td>"; //�ʵ� ��

					} // END WHILE
					reset($this->arr_item);

				echo "</tr>";
				if($data_count >= $rowdatanum){
					break;
				}
			} // END WHILE
			//���� ��

		echo "</table>";	
	}

	/**
	 *  
	 * tableEtcFieldShow()
	 * Ư�� �ʵ带 ó���ؼ� �����ش�
	 * protect
	 * 
	 * @param $val : �ʵ��� �̸�($this->arr_field�� ������ �������� ���� �����ش�)
	 * @param $row : DB ResultSet ��� �迭 ��
	 *
	 * @return nothing
	*/
	protected function tableEtcFieldShow($val, $row){

		$loginmessage = "�������⿡ ������ �����ϴ�.";

		switch($this->tb_name){
			case "pa01mt": //����DB
				$odbcid = explode("@", $this->dsn);
				switch($odbcid[1]){
					case "byungjuk":
						$kind2=1;
						break;
					case "byungjuk1":
						$kind2=2;
						break;
					case "byungjuk2":
						$kind2=3;
						break;
					case "byungjuk3":
						$kind2=4;
						break;
					case "byungjuk4":
						$kind2=5;
						break;
				}

				$cntsql = "select code_nm from ps01mt where ";
				if($val == "����"){
					$cntsql .= "major_cd='19' and minor_cd='".$row[$this->arr_field['����']]."'";
					echo $this->db_conn->getOne($cntsql);
					return;
				}
				if($val == "���"){
					$cntsql .= "major_cd='21' and minor_cd='".$row[$this->arr_field['��������']]."'";
					echo $this->db_conn->getOne($cntsql);
					return;
				}
				if($val == "����"){
					$cntsql .= "major_cd='37' and minor_cd='".$row[$this->arr_field['����']]."'";
					echo $this->db_conn->getOne($cntsql);
					return;
				}
				if($val == "����"){
					$condition=trim($row[$this->arr_field['����']]).'`'.'`';
				echo "<a href=../index.php?index=search_result&mode=-1&search_index=TOTAL&simple=false&condition=".$condition.">".trim($row[$this->arr_field['����']])."</a>";	
					return;
				}
				if($val == "�ֹι�ȣ"){
					if($this->user_id != "GUEST"){
						echo trim($row[$this->arr_field['�ֹι�ȣ']]);
					}else{
						echo  substr(trim($row[$this->arr_field['�ֹι�ȣ']]),0,7);
					}
					return;
				}
				if($val == "�⺻��������"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:byungjukview('".trim($row[$this->arr_field['����']])."','".$kind2."','1','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "���������"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:byungjukview('".trim($row[$this->arr_field['����']])."','".$kind2."','2','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "���������(��)"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:byungjukview('".trim($row[$this->arr_field['����']])."','".$kind2."','3','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "�����ڷ�ǥ"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:byungjukview('".trim($row[$this->arr_field['����']])."','".$kind2."','4','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case "H_TOTAL_JARY":  //�ڷ�ǥ
				if($val == "���"){
					if($row[$this->arr_field['���']]=='0'){
						echo "�屺";
					}else if($row[$this->arr_field['���']]=='1'){
						echo "�屳";
					}else if($row[$this->arr_field['���']]=='2'){
						echo "�λ��";
					}else if($row[$this->arr_field['���']]=='3'){
						echo "��";
					}else if($row[$this->arr_field['���']]=='4'){
						echo "������";
					}else{
						echo "��޹̻�";
					}
					return;
				}
				if($val == "�⵵"){
					echo $row[$this->arr_field['���۳�']]."-".$row[$this->arr_field['����']];
					return;
				}

				if($val == "��������"){
					if($this->user_id!="GUEST"){
						if($row[$this->arr_field[�ڷ�����]] == 0){
							echo "<a href=\"javascript:viewjaryuck1('".$row[$this->arr_field[������ȣ]]."','".$row[$this->arr_field[������]]."','".$this->user_id."')\"><img src='../img/btn_09.gif' width='14' height='14' border=0)></a>";
						}else if($row[$this->arr_field[�ڷ�����]] == 1){
							echo "<a href=\"javascript:viewjaryuck2('".$row[$this->arr_field[������ȣ]]."','".$this->user_id."')\"><img src='../img/btn_09.gif' width='14' height='14' border=0)></a>";
						}
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}

				break;
			case "H_TOTAL_SANGE":  //���̱���
				if($val == "��ɱٰ�"){
					echo $row[$this->arr_field['��ɱٰ�']]."�� ���Ƹ��";
					if($row[$this->arr_field['���ȣ��']]){
						echo "&nbsp;".$row[$this->arr_field['���ȣ��']]."ȣ";
					}
					return;
				}
				
				if($val == "����"){
					echo "<img src=\"../img/btn_09.gif\" width=14 height=14 border=0 onclick=\"SANG_Btn_clk('".$row[$this->arr_field['����']]."','".$row[$this->arr_field['�̸�']]."')\">";
					return;
				}
				if($val == "��������"){
					if($this->user_id!="GUEST"){
						echo "<img src=\"../img/btn_09.gif\" width=14 height=14 border=0 onclick=\"SANG_B_img_clk('".$row[$this->arr_field['��ɱٰ�']]."','".$row[$this->arr_field['���ȣ��']]."','".$row[$this->arr_field['��������']]."')\">";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case "H_TOTAL_MYUNGLIST":  //�����ڸ��
				if($val == "��������"){
					if($this->user_id!="GUEST"){
						echo "<img src='../img/btn_09.gif' width='14' height='14' border=0 onclick=B_img_clk('".$row[$this->arr_field['������']].'-'.$row[$this->arr_field['��������']]."')>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "������"){
					echo($row[$this->arr_field['������']].'-'.$row[$this->arr_field['��������']]);
					return;
				}
				break;
			case "H_TOTAL_BYUNGSANG":  // ��������
				if($val == "����ȭ����"){
						if($row[$this->arr_field['����ȭ����']] == 'Y'){
							echo "<font style='color=blue'>O</font>";
						}else{
							echo "<font style='color=red'>X</font>";
						}
						return;
				}
				
				if($val == "����"){
					if($this->user_id!="GUEST"){
						if($row[$this->arr_field['����ȭ����']]=='Y'){
						echo "<a href=\"javascript:byungsangview('".$row[$this->arr_field['����']]."',".$row[$this->arr_field['��������ȣ']].",'".$this->user_id."')\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0></a>";
						}
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case 'H_TOTAL_GUBYU':  // �޿�����
				if($val == "��������"){
					echo substr($row[$this->arr_field['��������']],0,10);
					return;
				}
				if($val == "����"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:gubyuview('".$row[$this->arr_field['������ȣ']]."','".$row[$this->arr_field['����']]."','".$this->user_id."')\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0></a>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case 'H_TOTAL_SAGUN':  // ��ǰ���
				if($val == "��������"){
					echo substr($row[$this->arr_field['��������']],0,10);
					return;
				}
				if($val == "�߼���"){
					if($row[$this->arr_field['�߼���']] == 'Y'){
						echo "O";
					}else{
						echo "X";
					}
					return;
				}
				if($val == "����"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:sagunview('".$row[$this->arr_field['������ȣ']]."','".$this->user_id."')\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case "C_WOLNAM":	//�����ĺ������
				if($val == "��������"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:view('".$row[$this->arr_field['SYSTEM_ID']]."','".$this->user_id."')\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "�����Ŀ�"){
					//echo ($row[$this->arr_field['�����Ŀ�']] == 'True') ? "O" : "X");
					
					if($row[$this->arr_field['�����Ŀ�']] == 'True'){
						echo "O";
					}else{
						echo "X";
					}
					return;
					
				}
				break;
			case "ja01mt":	// ������ڷ�(����)
				if($val == "����"){
					if($this->user_id!="GUEST"){
						echo "<a href=javascript:junsa_view('".trim($row[$this->arr_field['����']])."','".trim($row[$this->arr_field['�̸�']])."','".trim($row[$this->arr_field['�ֹι�ȣ']])."','1','".$this->user_id."')><img src=\"../img/btn_09.gif\" width=14 height=14 border=0\">";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "��������"){
					if($row[$this->arr_field['��������1']]){
						echo $row[$this->arr_field['��������']]."(".$row[$this->arr_field['��������1']].")";
					}else{
						echo $row[$this->arr_field['��������']];
					}
					return;
				}
				break;
			case "bja01mt":	//������ڷ�(����)
				if($val == "����"){
					if($this->user_id!="GUEST"){
						echo "<a href=javascript:junsa_view('".trim($row[$this->arr_field['����']])."','".trim($row[$this->arr_field['�̸�']])."','".trim($row[$this->arr_field['�ֹι�ȣ']])."','2','".$this->user_id."')><img src=\"../img/btn_09.gif\" width=14 height=14 border=0\">";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "��������"){
					if($row[$this->arr_field['��������1']]){
						echo $row[$this->arr_field['��������']]."(".$row[$this->arr_field['��������1']].")";
					}else{
						echo $row[$this->arr_field['��������']];
					}
					return;
				}
				break;
			case "pa30ht":	//�����ڷ�
				if($val == "�ź�"){
					switch(trim($row[$this->arr_field['�ź�']])){
						case 'O':
							echo "�屳";
							break;
						case 'N':
							echo "�λ��";
							break;
						case 'E':
							echo "��";
							break;
						case 'F':
							echo "��";
							break;
						case 'G':
							echo "������";
							break;
					}
					return;
				}
				if($val == "���Ʊ��"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:sanghunview2('".trim($row[$this->arr_field['����']])."','".trim($row[$this->arr_field['�̸�']])."','".trim($row[$this->arr_field['�ֹι�ȣ']])."','','','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "�����ڷ�"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:sanghunview1('".trim($row[$this->arr_field['����']])."','".trim($row[$this->arr_field['�̸�']])."','".trim($row[$this->arr_field['�ֹι�ȣ']])."','".trim($row[$this->arr_field['�Ʊ��ȣ']])."','".trim($row[$this->arr_field['��������']])."','".$this->user_id."');\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0></a>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				
				break;
		}
		

		//���̺� ���������� ���� ����
		switch($val){
			case "�̸�":

				if(($this->countTotalRecordRow()) == 1){
					array_push($this->arrnamecheck, $row[$this->arr_field['�̸�']]);
				}
				break;
		}

		// ó������ �ʰ� �״�� ȭ�鿡 ����� �͵� ���
		if(array_key_exists($val, $this->arr_field)) {
			echo $row[$this->arr_field[$val]];
		}
	} // end function

}

?>