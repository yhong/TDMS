<?
class DBTablePageList extends DBTable_TableDataList{

	private $limit_query_result;

	function DBTablePageList($title, $arr_tb_item, $user_id){
		$this->DBTableDataList($title, $arr_tb_item, $user_id);
		$this->user_id = $user_id;
	}


	// 쿼리를 실행
	public function executeLimitQuery(){ 
		// 쿼리작성
		//$this->query = "select ".$this->field_kind." from (select rownum as linenum, ".$this->field_kind." from ".$this->tb_name." where rownum <=".($startnum+$pageperrow)." and ".$this->likeis." ) where linenum >= ".$startnum; //오라클

		//$this->query = "select FIRST 10 ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis; //인포믹스
		// 인포믹스는 db자체에 페이지관련 기능이 없으므로 직접 구현해야 한다

		$this->query = "select ".$this->field_kind." from ".$this->tb_name." where ".$this->likeis;
		$this->limit_query_result = $this->db_conn->query($this->query);

		if(DB::isError($this->limit_query_result)){
			echo "에러!!<br>";
			echo $this->limit_query_result->getMessage();
		}
	}

	/**
	 *  
	 * tableContentView()
	 * 테이블의 내용을 보여준다(테이블로 구성) 
	 * public
	 * 
	 * @param $table_width : 테이블의 넓이
	 *
	 * @return nothing
	*/
	public function tableContentView($startnum, $rowdatanum){
		
		echo "
		<table width=".$this->m_table_width." height=85 align=center border=0 cellspacing=\"0\" cellpadding=\"0\"  style=\"border-style: solid; border-width: 1; padding-top: 0\">
		";

		// 각 필드의 타이틀 부분
		echo "<tr height=43%>";
			$numfield = count($this->arr_item); // 필드의 갯수
		echo "<td bgcolor=#7e8ece>
				<p align=center><font class=tabletitle><b>일련번호</b></font></p>
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
		// 내용
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
							<p align=center>"; //필드의 시작
							
						echo $startnum++;
						echo "</p></td>"; //필드 끝
					while (list (, $val) = each ($this->arr_item)) {
					
						echo "<td>
							<p align=center>"; //필드의 시작
							
						$this->tableEtcFieldShow($val, $row); //필드 보여주기

						echo "</p></td>"; //필드 끝

					} // END WHILE
					reset($this->arr_item);

				echo "</tr>";
				if($data_count >= $rowdatanum){
					break;
				}
			} // END WHILE
			//내용 끝

		echo "</table>";	
	}

	/**
	 *  
	 * tableEtcFieldShow()
	 * 특정 필드를 처리해서 보여준다
	 * protect
	 * 
	 * @param $val : 필드의 이름($this->arr_field의 내용을 바탕으로 값을 보여준다)
	 * @param $row : DB ResultSet 결과 배열 값
	 *
	 * @return nothing
	*/
	protected function tableEtcFieldShow($val, $row){

		$loginmessage = "원문보기에 권한이 없습니다.";

		switch($this->tb_name){
			case "pa01mt": //병적DB
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
				if($val == "병과"){
					$cntsql .= "major_cd='19' and minor_cd='".$row[$this->arr_field['병과']]."'";
					echo $this->db_conn->getOne($cntsql);
					return;
				}
				if($val == "계급"){
					$cntsql .= "major_cd='21' and minor_cd='".$row[$this->arr_field['군번관련']]."'";
					echo $this->db_conn->getOne($cntsql);
					return;
				}
				if($val == "본적"){
					$cntsql .= "major_cd='37' and minor_cd='".$row[$this->arr_field['본적']]."'";
					echo $this->db_conn->getOne($cntsql);
					return;
				}
				if($val == "군번"){
					$condition=trim($row[$this->arr_field['군번']]).'`'.'`';
				echo "<a href=../index.php?index=search_result&mode=-1&search_index=TOTAL&simple=false&condition=".$condition.">".trim($row[$this->arr_field['군번']])."</a>";	
					return;
				}
				if($val == "주민번호"){
					if($this->user_id != "GUEST"){
						echo trim($row[$this->arr_field['주민번호']]);
					}else{
						echo  substr(trim($row[$this->arr_field['주민번호']]),0,7);
					}
					return;
				}
				if($val == "기본병적사항"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:byungjukview('".trim($row[$this->arr_field['군번']])."','".$kind2."','1','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "군경력증명서"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:byungjukview('".trim($row[$this->arr_field['군번']])."','".$kind2."','2','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "군경력증명서(민)"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:byungjukview('".trim($row[$this->arr_field['군번']])."','".$kind2."','3','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "전산자력표"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:byungjukview('".trim($row[$this->arr_field['군번']])."','".$kind2."','4','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case "H_TOTAL_JARY":  //자력표
				if($val == "계급"){
					if($row[$this->arr_field['계급']]=='0'){
						echo "장군";
					}else if($row[$this->arr_field['계급']]=='1'){
						echo "장교";
					}else if($row[$this->arr_field['계급']]=='2'){
						echo "부사관";
					}else if($row[$this->arr_field['계급']]=='3'){
						echo "병";
					}else if($row[$this->arr_field['계급']]=='4'){
						echo "군무원";
					}else{
						echo "계급미상";
					}
					return;
				}
				if($val == "년도"){
					echo $row[$this->arr_field['시작년']]."-".$row[$this->arr_field['끝년']];
					return;
				}

				if($val == "원문보기"){
					if($this->user_id!="GUEST"){
						if($row[$this->arr_field[자료형태]] == 0){
							echo "<a href=\"javascript:viewjaryuck1('".$row[$this->arr_field[문서번호]]."','".$row[$this->arr_field[페이지]]."','".$this->user_id."')\"><img src='../img/btn_09.gif' width='14' height='14' border=0)></a>";
						}else if($row[$this->arr_field[자료형태]] == 1){
							echo "<a href=\"javascript:viewjaryuck2('".$row[$this->arr_field[문서번호]]."','".$this->user_id."')\"><img src='../img/btn_09.gif' width='14' height='14' border=0)></a>";
						}
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}

				break;
			case "H_TOTAL_SANGE":  //상이기장
				if($val == "명령근거"){
					echo $row[$this->arr_field['명령근거']]."본 상훈명령";
					if($row[$this->arr_field['명령호수']]){
						echo "&nbsp;".$row[$this->arr_field['명령호수']]."호";
					}
					return;
				}
				
				if($val == "내용"){
					echo "<img src=\"../img/btn_09.gif\" width=14 height=14 border=0 onclick=\"SANG_Btn_clk('".$row[$this->arr_field['군번']]."','".$row[$this->arr_field['이름']]."')\">";
					return;
				}
				if($val == "원문보기"){
					if($this->user_id!="GUEST"){
						echo "<img src=\"../img/btn_09.gif\" width=14 height=14 border=0 onclick=\"SANG_B_img_clk('".$row[$this->arr_field['명령근거']]."','".$row[$this->arr_field['명령호수']]."','".$row[$this->arr_field['수여일자']]."')\">";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case "H_TOTAL_MYUNGLIST":  //명제자명부
				if($val == "원문보기"){
					if($this->user_id!="GUEST"){
						echo "<img src='../img/btn_09.gif' width='14' height='14' border=0 onclick=B_img_clk('".$row[$this->arr_field['페이지']].'-'.$row[$this->arr_field['부페이지']]."')>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "페이지"){
					echo($row[$this->arr_field['페이지']].'-'.$row[$this->arr_field['부페이지']]);
					return;
				}
				break;
			case "H_TOTAL_BYUNGSANG":  // 병상일지
				if($val == "전산화유무"){
						if($row[$this->arr_field['전산화유무']] == 'Y'){
							echo "<font style='color=blue'>O</font>";
						}else{
							echo "<font style='color=red'>X</font>";
						}
						return;
				}
				
				if($val == "원문"){
					if($this->user_id!="GUEST"){
						if($row[$this->arr_field['전산화유무']]=='Y'){
						echo "<a href=\"javascript:byungsangview('".$row[$this->arr_field['군번']]."',".$row[$this->arr_field['페이지번호']].",'".$this->user_id."')\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0></a>";
						}
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case 'H_TOTAL_GUBYU':  // 급여관계
				if($val == "생산일자"){
					echo substr($row[$this->arr_field['생산일자']],0,10);
					return;
				}
				if($val == "원문"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:gubyuview('".$row[$this->arr_field['문서번호']]."','".$row[$this->arr_field['군번']]."','".$this->user_id."')\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0></a>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case 'H_TOTAL_SAGUN':  // 사건관계
				if($val == "생산일자"){
					echo substr($row[$this->arr_field['생산일자']],0,10);
					return;
				}
				if($val == "중수단"){
					if($row[$this->arr_field['중수단']] == 'Y'){
						echo "O";
					}else{
						echo "X";
					}
					return;
				}
				if($val == "원문"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:sagunview('".$row[$this->arr_field['문서번호']]."','".$this->user_id."')\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0></a>";
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				break;
			case "C_WOLNAM":	//월남파병연명부
				if($val == "원문보기"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:view('".$row[$this->arr_field['SYSTEM_ID']]."','".$this->user_id."')\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "이중파월"){
					//echo ($row[$this->arr_field['이중파월']] == 'True') ? "O" : "X");
					
					if($row[$this->arr_field['이중파월']] == 'True'){
						echo "O";
					}else{
						echo "X";
					}
					return;
					
				}
				break;
			case "ja01mt":	// 전사망자료(군인)
				if($val == "원문"){
					if($this->user_id!="GUEST"){
						echo "<a href=javascript:junsa_view('".trim($row[$this->arr_field['군번']])."','".trim($row[$this->arr_field['이름']])."','".trim($row[$this->arr_field['주민번호']])."','1','".$this->user_id."')><img src=\"../img/btn_09.gif\" width=14 height=14 border=0\">";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "유족관계"){
					if($row[$this->arr_field['유족관계1']]){
						echo $row[$this->arr_field['유족관계']]."(".$row[$this->arr_field['유족관계1']].")";
					}else{
						echo $row[$this->arr_field['유족관계']];
					}
					return;
				}
				break;
			case "bja01mt":	//전사망자료(비군인)
				if($val == "원문"){
					if($this->user_id!="GUEST"){
						echo "<a href=javascript:junsa_view('".trim($row[$this->arr_field['군번']])."','".trim($row[$this->arr_field['이름']])."','".trim($row[$this->arr_field['주민번호']])."','2','".$this->user_id."')><img src=\"../img/btn_09.gif\" width=14 height=14 border=0\">";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "유족관계"){
					if($row[$this->arr_field['유족관계1']]){
						echo $row[$this->arr_field['유족관계']]."(".$row[$this->arr_field['유족관계1']].")";
					}else{
						echo $row[$this->arr_field['유족관계']];
					}
					return;
				}
				break;
			case "pa30ht":	//상훈자료
				if($val == "신분"){
					switch(trim($row[$this->arr_field['신분']])){
						case 'O':
							echo "장교";
							break;
						case 'N':
							echo "부사관";
							break;
						case 'E':
							echo "병";
							break;
						case 'F':
							echo "병";
							break;
						case 'G':
							echo "방위병";
							break;
					}
					return;
				}
				if($val == "상훈기록"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:sanghunview2('".trim($row[$this->arr_field['군번']])."','".trim($row[$this->arr_field['이름']])."','".trim($row[$this->arr_field['주민번호']])."','','','".$this->user_id."')\"><img src=../img/btn_09.gif width=14 height=14 border=0></a>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				if($val == "상훈자료"){
					if($this->user_id!="GUEST"){
						echo "<a href=\"javascript:sanghunview1('".trim($row[$this->arr_field['군번']])."','".trim($row[$this->arr_field['이름']])."','".trim($row[$this->arr_field['주민번호']])."','".trim($row[$this->arr_field['훈기번호']])."','".trim($row[$this->arr_field['수여일자']])."','".$this->user_id."');\"><img src=\"../img/btn_09.gif\" width=14 height=14 border=0></a>";
						return;
					}else{
						echo "<a href=\"javascript:alert('".$loginmessage."');\"><img src=../img/btn_09.gif width=14 height=14 border=0></a></script>";
					}
				}
				
				break;
		}
		

		//테이블에 공통적으로 들어가는 내용
		switch($val){
			case "이름":

				if(($this->countTotalRecordRow()) == 1){
					array_push($this->arrnamecheck, $row[$this->arr_field['이름']]);
				}
				break;
		}

		// 처리하지 않고 그대로 화면에 출력할 것들 출력
		if(array_key_exists($val, $this->arr_field)) {
			echo $row[$this->arr_field[$val]];
		}
	} // end function

}

?>