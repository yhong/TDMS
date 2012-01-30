<br>

<table width="<?=$CONFIG->tableWidth?>"  border="0" cellspacing="1" cellpadding="2"  bgcolor="<?=$CONFIG->tbColor['tableColor']?>" align="center">

<?
//$this->OutputData["rowdata"][$fieldname][title] = $totalname;
//$this->OutputData["rowdata"][$fieldname][value] = $result_form1."-".$result_form2;

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
			<input type="button" value="목록보기(L)" onClick="location.href='/<?=$ELEMENT?>/Plist'" accesskey="L">&nbsp;&nbsp;
			<input type="button" value="수정(E)" onClick="location.href='/<?=$ELEMENT?>/Pupdate?id=<?=$_GET["id"]?>'" accesskey="E">&nbsp;&nbsp;
			<input type="button" value="삭제(D)" onClick="location.href='/<?=$ELEMENT?>/Pdelete?id=<?=$_GET["id"]?>'" accesskey="D">
		</p>
	</td>
</tr>

</table>