<form method="get" name="search_stat" action="/<?=$element->pageurl?>">
<input type="hidden" name="search_mode" value="true">
<table width="400" border="1">
<tr>
	<td><b>날짜</b></td>

	<?
	if($_GET["start_date"]){
		$start_date = $_GET["start_date"];
	}else{
		$start_date = date("Y-m-d");
	}

	if($_GET["end_date"]){
		$end_date = $_GET["end_date"];
	}else{
		$end_date = date("Y-m-d");
	}
	?>
	<td colspan="3"><input type="inputbox" name="start_date" size="15" value="<?=$start_date?>"> ~ <input type="inputbox" name="end_date" size="15" value="<?=$end_date?>"></td>
</tr>

<tr>
	<td><b>지출내역</b></td>
	<td><input type="inputbox" name="outname" size="15"  value="<?=$_GET["outname"]?>"></td>
	<td><b>옵션</b></td>
	<td>
		<?
			if($_GET["option"] == "totalprice"){
				$select1 = "selected";
				$select2 = "";
			}else if($_GET["option"] == "money"){
				$select2 = "selected";
				$select1 = "";
			}
		?>
		<select name="option">
			<option value="totalprice" <?=$select1?>>지출비 내림차순</option>
			<option value="money" <?=$select2?>>지출내역 내림차순</option>
		</select>
	</td>
</tr>
<tr>
	<td colspan="4" align="center"><input type="submit" value="검색"></td>
</tr>
</table>
</form>


<?

if($_GET["search_mode"] == "true"){
	
	$liekis = null;
	$is_search = false;
	
	
	if($_GET["start_date"] && $_GET["end_date"]){
		$is_search = true;
		$likeis="date between '".$_GET["start_date"]."' AND '".$_GET["end_date"]."'";
	}
	 
	 if($_GET["outname"]){
		
		$arrSite = explode(",", $_GET["outname"]);
		if(count($arrSite) > 0){
			$is_search = true;
			$index = 0;
			foreach($arrSite as $value){
				if($index == 0){
					$and = " ";
				}else{
					$and = " or ";
				}
				$likeis .= $and." outname like '%".$value."%' ";
				$index++;
			}
		}

	 }

    if($is_search == true){
		$likeis = " and  (".$likeis.")";
	}


	if($_GET["option"] == "totalprice"){
		$order = " order by totalprice desc";
	}else if($_GET["option"] == "money"){
		$order = " order by outname desc";
	}


$search_query = "select outname, count(ID) as cnt_item, sum(outprice) as totalprice from tdms_money_out where 1=1 ".$likeis." group by outname ".$order;
$result = $dbconn->query($search_query);

if($_GET["start_date"] or $_GET["end_date"]){
	echo $_GET["start_date"]."에서 ".$_GET["end_date"]."까지의 결과입니다.";
}

?>



<table width="300" border="1">
<tr>
	<th>
		일련번호
	</th>
	<th>
		지출내역
	</th>
	<th>
		지출개수
	</th>
	<th>
		지출비
	</th>
</tr>


<?
$index = 1;
while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
	echo "<tr>
	<td>
		".$index++."
	</td>
	<td>
		".$row[outname]."
	</td>
	<td>
		".$row[cnt_item]."
	</td>
	<td>
		".($row[totalprice])."
	</td>
</tr>";
	
}

?>

</table>


<?

}

?>

