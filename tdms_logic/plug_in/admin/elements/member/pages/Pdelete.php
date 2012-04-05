<table width="<?=$CONFIG->tableWidth?>" border="0" cellspacing="1" cellpadding="2" bgcolor="<?=$CONFIG->tbColor['tableColor']?>" align="center">
<tr>
	<td bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" height="100">
		<p align=center>정말로 지우시겠습니까?</p>
	</td>
</tr>
<tr>
	<td colspan="2">
		<p align="center"><input type="button" value="삭제" OnClick="location.href='/<?=$PLUGIN?>/<?=$ELEMENT?>/Pdelete_Action?<?=$strLink?>'";>&nbsp;&nbsp;
		<input type="button" value="취소" OnClick="history.back(-1)";></p>
	</td>
</tr>
</table>