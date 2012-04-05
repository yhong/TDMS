<br>

<b>일괄 입력결과</b>

<table border="1">
<tr>
	<td><b>번호</b></td>
	<td><b>수령자/구매자</b></td>
	<td><b>주소</b></td>
	<td><b>전화번호</b></td>
	<td><b>구매 상품명</b></td>
	<td><b>입력결과</b></td>
</tr>
<?
foreach($arrOutput as $arr_table_value){
	echo "<tr>";
		echo "<td>".$arr_table_value[0]."</td>";
		echo "<td>".$arr_table_value[1]."</td>";
		echo "<td>".$arr_table_value[2]."</td>";
		echo "<td>".$arr_table_value[3]."</td>";
		echo "<td>".$arr_table_value[4]."</td>";
		echo "<td>".$arr_table_value[5]."</td>";
	echo "</tr>";
}
?>
</table>