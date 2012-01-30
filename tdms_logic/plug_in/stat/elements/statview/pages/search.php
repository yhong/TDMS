<center>
	<table align=center>
		<tr>
			<td>
				<form name="searchbox" method="GET" action="/<?=$element->pageurl?>">
				<input type="hidden" name="searchmode" value="TRUE">
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


<?
if($SEARCH_MODE == 'TRUE'){

	echo "순이익 : ".$SUM_PROFIT."/ 매출 : ".$TOTAL_PROFIT." / 판매갯수 : ".$COUNT_ROW." / 반품갯수 : ".$COUNT_RETURN_ROW;
?>


<table border=0 cellspacing='1' cellpadding='2' width="<?=$CONFIG->tableWidth?>" bgcolor="<?=$CONFIG->tbColor['lineTableColor']?>" align="center">
<tr height='27' bgcolor='<?=$CONFIG->tbColor['tableColor']?>'>

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
					<font class='tabletitle'><b>".$listHeader[$i]["key"]."</b></font>
				</p>
			</td>
		";
	}

?>

</tr>

<?

for($i=0; $i<count($outputFormData); $i++){
	echo "<tr>";
	$return_val = $DB_OBJECT->getFieldValue(GET_FIELD_NAME("반품유무"), GET_FIELD_NAME("일련번호")."=".$outputData[$i][GET_FIELD_NAME("일련번호")]);

	foreach($outputFormData[$i] as $value){
		if($return_val == 'Y'){
			$bgcolor="#FFBBFF";
		}else{
			$bgcolor="white";
		}
		echo "<td bgcolor=".$bgcolor." align=center height=30>
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
		<td>
			<p align="center">
				<?=$pagelist?>
			</p>
		</td>
	</tr>
	</table>
</center>

<?}?>