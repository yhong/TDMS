<br>




	<script language='javascript'>
		function CheckNSubmit(f){
			var num_element;
			var name;
			var flag;

			num_element = f.length;
			flag = true;

			for(i=0; i<num_element; i++){
				name = f.elements[i].id;
				 if(name !='�ǸŰ�' && name !='��������'){
					if(f.elements[i].value==''){
						alert(name+'�� �Է����ּ���');
						flag = false;
						break;
					}
				 }
			}
			if(flag == true){
				f.submit();
			}
		}


		
	</script>

<form name="Insertform" method="POST" action="/<?=$ELEMENT?>/Pinsert_Action?<?=$strLink?>">
<table width="<?=$CONFIG->tableWidth?>" bgcolor="<?=$CONFIG->tbColor['tablecolor']?>" align="center">
<?
foreach($outputData as $key=>$value){
?>
	<tr bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" height="30">
		<td width="30%">
				<p align="left">&nbsp;&nbsp;<b><?=$value["title"]?></b></p>
		</td>
		<td bgcolor="white" width="70%">
			&nbsp;&nbsp;
			<?
			if(	$value["title"] == "����������" || $value["title"] == "Ư�̻���"){
				echo $value["value"];
			}else{
			
			echo $value["value"];
			}?>
		</td>
	</tr>

<?
}
?>

<tr>
<td colspan="2">
	<p align="center">
		<input type="button" value="�Է�(P)" onClick="CheckNSubmit(this.form)"; accesskey="P">
		&nbsp;&nbsp;
		<input type="button" value="��Ϻ���(L)" onclick="location.href='/<?=$ELEMENT?>/Plist?<?=$strLink?>';" accesskey="L">
	</p>
</td>
</tr>
</table>
</form>

<script language="javascript">

Insertform.<?=GET_FIELD_NAME("����")?>.focus();
//Insertform.<?=GET_FIELD_NAME("����������")?>.value='.';
//Insertform.<?=GET_FIELD_NAME("Ư�̻���")?>.value='.';

</script>