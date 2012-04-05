<br>
<br>
<p align="center"><b>엑셀 일괄등록</b></p>

<form name="excel_import" method="POST" action="/sell_manage/import"  enctype="multipart/form-data">
<table width="100"  border="0" cellspacing="1" cellpadding="2"  align="center">
<tr>
	<td colspan="2">
		 <p align="center">
			<b>등록날짜 입력</b> : <input type="inputbox" name="register_date" value="<?=date("Y-m-d")?>" size="10">
			<input type="file" name="excel_file"  size="30">&nbsp; <input type="submit" value="입력"> 
		</p>
	</td>
</tr>

</table>