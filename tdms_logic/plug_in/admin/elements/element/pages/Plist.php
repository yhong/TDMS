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
		alert('Element��� ���̺���� �ʼ��Դϴ�');
		return false;
	}else{
		return true;
	}
}
</script>


<fieldset>
<legend>���� �׸�</legend>


<form name="elementCreateForm" method="POST" action="/<?=$PLUGIN?>/<?=$ELEMENT?>/createElement" onSubmit="return checkSubmit()">

���� Element �� : <input type="text" name="element_name" size="15"> |
���̺� �� : <input type="text" name="table_name" size="15">

<br><br>
<table border="0" cellspacing="1" cellpadding="2" width="100%" bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" align="center">
<tr height="27" bgcolor="<?=$CONFIG->tbColor['tableColor']?>">
	<td align="center">
		<b class="tabletitle">��Ī</b>
	</td>
	<td align="center">
		<b class="tabletitle">db�ʵ��</b>
	</td>
	<td align="center">
		<b class="tabletitle">����/����</b>
	</td>
	<td align="center">
		<b class="tabletitle">������Ʈ Ÿ��</b>
	</td>
	<td align="center">
		<b class="tabletitle">�˻��׸�</b>
	</td>
	<td align="center">
		<b class="tabletitle">����Ʈ�׸�</b>
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
			<option value="true">����</option>
			<option value="false">����</option>
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
		<input type="button" value="�׸��߰�" onclick="addRow();" accesskey=I>
		

	</td>
	
</tr>
</table>
<br>
<div align="right">
<input type="submit" value="�����ϱ�" accesskey=I>
</div>
</fieldset>




</center>