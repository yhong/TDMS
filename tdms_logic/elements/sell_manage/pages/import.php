<br>

<b>�ϰ� �Է°��</b>

<table border="1">
<tr>
	<td><b>��ȣ</b></td>
	<td><b>������/������</b></td>
	<td><b>�ּ�</b></td>
	<td><b>��ȭ��ȣ</b></td>
	<td><b>���� ��ǰ��</b></td>
	<td><b>�Է°��</b></td>
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