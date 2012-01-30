<center>
	<table align="center">
		<tr>
			<td>
				<form name="searchbox" method="GET" action="/<?=$PAGEURL?>">
				<input type="hidden" name="searchmode" value="1">
					<table bgcolor="#FAFAFF" width="<?=$CONFIG->tableWidth?>" style=\"border-width:1px; border-color:rgb(0,0,153); border-style:solid;\" cellpadding="0" cellspacing="0" bordercolordark="white" bordercolorlight="black">
					<tr>
					<?
						$SearchFormRowCnt = ceil(count($outputSearchBoxData)/2);
						$i = 0;
						foreach($outputSearchBoxData as $key=>$value){
								if($SearchFormRowCnt == $i){
									echo "</tr><tr>";
								}
								echo "<td>";
									echo $value["title"];
								echo "</td>";
								echo "<td>";
									
									echo $value["value"];
								echo "</td>";
								$i++;
						}
					?>
						<td align="center" colspan="2">
							<input type="submit" name="ok" value="검색(S)" accesskey="S">
						</td>
					</tr>
					</table>
					</form>
				<?=$searchForm?>
			</td>
		</tr>
	</table>

<table border="0" cellspacing="1" cellpadding="2" width="<?=$CONFIG->tableWidth?>" bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" align="center">
<tr height="27" bgcolor="<?=$CONFIG->tbColor['tableColor']?>">
<?
/*
$listHeader[$recordIndex]["fieldname"]
$listHeader[$recordIndex]["how"]
$listHeader[$recordIndex]["key"]
*/
	for($i=0; $i<count($listHeader); $i++){
		echo "
			<td>
				<p align=center>
					<a href=/".$ELEMENT."/Plist?order=".$listHeader[$i]["fieldname"]."&how=".$listHeader[$i]["ordertype"].">
							<font class=tabletitle><b>".$listHeader[$i]["key"]."</b></font>
					</a>
				</p>
			</td>
		";
	}
?>

</tr>

<?
for($i=0; $i<count($outputFormData); $i++){
	echo "<tr>";
	foreach($outputFormData[$i] as $value){
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

		<td width="120" align="center" >
			<input type="button" value="새로등록(I)" onclick="location.href='/<?=$PLUGIN?>/<?=$ELEMENT?>/Pinsert';" accesskey=I>
		</td>
	</tr>
</table>
</center>