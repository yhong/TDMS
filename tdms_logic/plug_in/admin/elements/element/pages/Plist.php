<center>
<br>
<script>
var totalRowCount=5;

function addRow(){
	totalRowCount++;
	document.getElementById("trRow["+totalRowCount+"]").style.display='block';
	
}

function checkSubmit(){
	var f = document.elementCreateForm;

	if(f.element_name.value == "" || f.table_name.value == ""){
		alert('Element명과 테이블명은 필수입니다');
		return false;
	}else{
		return true;
	}
}
</script>


<fieldset>
<legend>서비스 항목</legend>


<form name="elementCreateForm" method="POST" action="/<?=$PLUGIN?>/<?=$ELEMENT?>/createElement" onSubmit="return checkSubmit()">

서비스 Element 명 : <input type="text" name="element_name" size="15"> |
테이블 명 : <input type="text" name="table_name" size="15">

<br><br>
<table border="0" cellspacing="1" cellpadding="2" width="100%" bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" align="center">
<tr height="27" bgcolor="<?=$CONFIG->tbColor['tableColor']?>">
	<td align="center">
		<b class="tabletitle">별칭</b>
	</td>
	<td align="center">
		<b class="tabletitle">db필드명</b>
	</td>
	<td align="center">
		<b class="tabletitle">문자/숫자</b>
	</td>
	<td align="center">
		<b class="tabletitle">컴포넌트 타입</b>
	</td>
	<td align="center">
		<b class="tabletitle">검색항목</b>
	</td>
	<td align="center">
		<b class="tabletitle">리스트항목</b>
	</td>
</tr>




<?
$component_option_list = "";

foreach($COMPONENT_LISTS as $k=>$v){
	$component_option_list .= "<option value='".$v."'>".$v."</option>\n";
}

	
for($i=0;$i<300;$i++){
	if($i<=5){
		$style = "display:block";
	}else{
		$style = "display:none";
	}
?>
<tr id="trRow[<?=$i?>]" style="<?=$style?>">
	<td align="center">
		<input type="text" name="alias_name[<?=$i?>]" size="15">
	</td>
	<td align="center">
		<input type="text" name="field_name[<?=$i?>]" size="15">
	</td>
	<td align="center">
		<select name="string_type[<?=$i?>]">
			<option value="true">문자</option>
			<option value="false">숫자</option>
		</select>
	</td>
	<td align="center">
		<select name="component_name[<?=$i?>]">
			<option value=''></option>
			<?=$component_option_list?>
		</select>
	</td>
	<td align="center">
		<input type="checkbox" name="search[<?=$i?>]">
	</td>
	<td align="center">
		<input type="checkbox" name="list[<?=$i?>]">
	</td>
</tr>
<?}?>
<tr>
	<td colspan="6" align="right">
		<input type="button" value="항목추가" onclick="addRow();" accesskey=I>
		

	</td>
	
</tr>
</table>
<br>
<div align="right">
<input type="submit" value="생성하기" accesskey=I>
</div>
</fieldset>




</center>