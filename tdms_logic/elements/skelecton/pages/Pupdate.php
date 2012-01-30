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
				 if(name !='판매가' && name !='접수일자'){
					if(f.elements[i].value==''){
						alert(name+'을 입력해주세요');
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



<form name="Updateform" method="POST" action="/<?=$ELEMENT?>/Pupdate_Action?<?=$strLink?>">
<table width="<?=$CONFIG->tableWidth?>" bgcolor="<?=$CONFIG->tbColor['tableColor']?>" align="center">
<?

foreach($outputData as $key=>$value){
?>
	<tr bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" height="30">
		<td width="30%">
				<p align="left">&nbsp;&nbsp;<b><?=$value["title"]?></b></p>
		</td>
		<td bgcolor="white" width="70%">
			&nbsp;&nbsp;
			<?=$value["value"]?>
			
		</td>
	</tr>

<?
}
?>

<tr>
<td colspan="2">
	<p align="center">
		<input type="button" value="입력(P)" onClick="CheckNSubmit(this.form);" accesskey="P">
		&nbsp;&nbsp;
		<input type="button" value="목록보기(L)" onclick="location.href='/<?=$ELEMENT?>/Plist?<?=$strLink?>';" accesskey="L">
	</p>
</td>
</tr>
</table>
</form>

<script language="javascript">Insertform.<?=GET_FIELD_NAME("유형")?>.focus();</script>