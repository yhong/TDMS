<table width="<?=$CONFIG->tableWidth?>" border="0" cellspacing="1" cellpadding="2" bgcolor="<?=$CONFIG->tbColor['tableColor']?>" align="center">
<tr>
	<td bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" height="100">
		<p align=center>������ ����ðڽ��ϱ�?</p>
	</td>
</tr>
<tr>
	<td colspan="2">
		<p align="center"><input type="button" value="����" OnClick="location.href='/<?=$PLUGIN?>/<?=$ELEMENT?>/Pdelete_Action?<?=$strLink?>'";>&nbsp;&nbsp;
		<input type="button" value="���" OnClick="history.back(-1)";></p>
	</td>
</tr>
</table>