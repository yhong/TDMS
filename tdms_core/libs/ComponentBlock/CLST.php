<?
class CLST extends ComponentBlock_ComponentBlock implements impComponentBlock{

	// 초기화
	public function CLST($inputdata, $isString, $fieldName, $formExtraValue){
		$this->inputdata		= $inputdata;
		$this->fieldName		= $fieldName;
		$this->formExtraValue	= $formExtraValue;
		$this->isString			= $isString;
	}

	
	public function blockList(){
		$result_form = "";

		$arrSplitData = explode("|", $this->inputdata);
		$ctn_complete = count($arrSplitData);

		if($ctn_complete == 1 && !$arrSplitData[0]){
			$result_form .= "<b><font style=color:brown>회신결과 입력X</font></b>";
		}else{

			for($i=0; $i < $ctn_complete; $i++){
				if($i != 0){ $result_form .= "&nbsp;&nbsp;"; }
				$resultData = substr($arrSplitData[$i],0,6);
				$dataKind = array_search($resultData, $this->formExtraValue);
				$num = substr($arrSplitData[$i],7);
				
				if(trim($num) == '-1'){
					$datactn = "<font style='color:red;font-size:9pt'>미보관</font>";
				}else if(trim($num) == '-2'){
					$datactn = "<font style='color:red;font-size:9pt'>기회신</font>";
				}
				else{
					$datactn = "<font style='color:blue;font-size:9pt'>".$num."개</font>";
				}
				
				$result_form .=  $dataKind." ".$datactn."<br>";
			}
		}
		return $result_form;
	}

	public function blockSelect(){
		$arrSplitData = explode("|",$this->inputdata);
		$ctn_complete = count($arrSplitData);

		if($ctn_complete == 1 && !$arrSplitData[0]){
			$result_form .= "<b><font style=color:brown>회신결과 입력X</font></b>";
		}else{

			for($i=0; $i<$ctn_complete; $i++){

				if($i!=0){	$result_form .= "&nbsp;&nbsp;"; }

				$resultData = substr($arrSplitData[$i],0,4);
				$dataKind = array_search($resultData, $this->formExtraValue);
				$num = substr($arrSplitData[$i],5);
				
				
				if($dataKind == "기타"){
					$datactn = "<font style='color:blue;font-size:9pt'>".$num."</font>";
					$result_form .=  $datactn."<br>";
				}else{
					if(trim($num) == '-2'){
						$datactn = "<font style='color:red;font-size:9pt'>이미 회신된 자료</font>";
					}
					else if(trim($num) == '-1'){
						$datactn = "<font style='color:red;font-size:9pt'>미보관</font>";
					}else{
						$datactn = "<font style='color:blue;font-size:9pt'>".$num."개</font>";
					}
					$result_form .=  $dataKind." ".$datactn."<br>";
				}
			}
		}

		return $result_form;
	}
	public function blockUpdate($id, $fieldName, $script, $opt){
		$i=0;
		$arrInputData=explode("|", $this->inputdata);
		$result_form = "<table width=100% border=0>";
		while(list($selkey, $selvalue) = each($this->formExtraValue)){
			$result_form .= "<tr><td>";

					$result_form .= "&nbsp;&nbsp;";
					$countnum='';
					for($j=0; $j<count($arrInputData); $j++){
						if($selvalue == substr($arrInputData[$j],0,4)){
							$countnum = substr($arrInputData[$j],5);
							break;
						}
					}
					$result_form .= $selkey;

				$result_form .= "</td><td>";
				$result_form .= "&nbsp;";

				if($selvalue == "기타"){
					$result_form .= "<input type=text ID=".$id." size=25 name=".$fieldName.$i." value=".$countnum." ".$script.">";
				}else{

					$result_form .= "<select ID=".$id." name=".$fieldName.$i." ".$script.">";
					$result_form .= "<option></option>";
					
						for($cntitem=-2; $cntitem<100; $cntitem++){
							if($cntitem==0){continue;}
							if(trim($countnum) == trim($cntitem)){
								$sel="selected";
							}else{
								$sel="";
							}
							if($cntitem == -2){$cntname="기회신";}
							else if($cntitem == -1){$cntname="미보관";}
							else{$cntname=$cntitem."개";}
							$result_form .= "<option ".$sel." value=".$cntitem.">".$cntname."</option>";
						}
						
					$result_form .= "</select>";
				}
				

				$result_form .= "</td></tr>";
			$i++;
		}
		$result_form .= "</table>";

		return $result_form;
	}

	public function blockInsert($id, $fieldName, $script, $opt){
		$i=0;
		$result_form = "<table width=100% border=0>";
		while(list($selkey, $selvalue) = each($this->formExtraValue)){
			$result_form .= "<tr><td>";

				$result_form .= "&nbsp;&nbsp;";
				$result_form .= $selkey;
				$result_form .= "</td><td>";

				$result_form .= "&nbsp;";
				$result_form .= "<select ID=".$id." name=".$fieldName.$i." ".$script.">";
				$result_form .= "<option></option>";
				
					for($cntitem=-1; $cntitem<100; $cntitem++){
						if($cntitem==0){continue;}
						if($cntitem == -2){$cntname="기회신";}
						else if($cntitem == -1){$cntname="미보관";}
						else{$cntname=$cntitem."개";}
						$result_form .= "<option ".$sel." value=".$cntitem.">".$cntname."</option>";
					}
					
				$result_form .= "</select>";
				$result_form .= "</td></tr>";
			$i++;
		}
		$result_form .= "</table>";

		return $result_form;
	}
	public function blockSearch($id, $fieldName, $script, $opt){
		$i=0;
		$result_form = "<table width=100% border=0>";
		while(list($selkey, $selvalue) = each($this->formExtraValue)){
			$result_form .= "<tr><td>";

					$result_form .= "&nbsp;&nbsp;";
					$result_form .= $selkey;
				$result_form .= "</td><td>";

				$result_form .= "&nbsp;";
				$result_form .= "<select ID=".$id." name=".$fieldName.$i." ".$script.">";
				$result_form .= "<option></option>";
				
					for($cntitem=-1; $cntitem<10; $cntitem++){
						if($cntitem==0){continue;}
						if($cntitem == -1){$cntname="미보관";}
						else{$cntname=$cntitem."개";}
						$result_form .= "<option ".$sel." value=".$cntitem.">".$cntname."</option>";
					}
					
				$result_form .= "</select>";
				$result_form .= "</td></tr>";
			$i++;
		}
		$result_form .= "</table>";

		return $result_form;
	}

	public function blockSearchCondition(){
		if($_GET[$this->fieldName] ){
			return $this->fieldName." = '".$_GET[$this->fieldName]."'";
		}else{
			return null;
		}
		
	}
}
?>