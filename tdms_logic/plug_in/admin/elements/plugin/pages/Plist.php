<center>
<br><br><br>
<form method="post" action="/<?=$PLUGIN?>/<?=$ELEMENT?>/plugin_extract" encType="multipart/form-data">

<fieldset>
<legend><b>플러그인 업로드</b></legend>

<br><input type="file" name="plugin_file">
<input type="submit" value="설치">
</fieldset>


</form>

<table border="0" cellspacing="1" cellpadding="2" width="<?=$CONFIG->tableWidth?>" bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" align="center">
<tr height="27" bgcolor="<?=$CONFIG->tbColor['tableColor']?>">

<?
	for($i=0; $i<count($listHeader); $i++){
		echo "
			<td>
				<p align=center>
							<font class=tabletitle><b>".$listHeader[$i]["key"]."</b></font>
				</p>
			</td>
		";
	}

?>
</tr>

<?

for($i=0; $i<count($outputData); $i++){
	echo "<tr>";
	foreach($outputData[$i] as $value){
		echo "<td bgcolor=white align=center height=30>
			".$value."
			</td>
		";
	}
	echo "</tr>";
}
?>

</table>

<table border="0" width="100%">
	<tr>
		<td width="120">
		</td>
		<td>
			<p align="center">
				<?=$pagelist?>
			</p>
		</td>

	</tr>
</table>
</center>