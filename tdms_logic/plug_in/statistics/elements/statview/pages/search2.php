<center>

<form method="get" name="search_stat" action="/<?=$element->pageurl?>">
<input type="hidden" name="search_mode" value="true">
<table width="400" border="1">


<tr>
	<td><b>상품명</b></td><td colspan="2"><input type="inputbox" name="name" size="15" value="<?=$_GET["name"]?>"></td>
</tr>


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
	<td ><input type="inputbox" name="start_date" size="15" value="<?=$start_date?>"> ~ <input type="inputbox" name="end_date" size="15" value="<?=$end_date?>"></td>
	<td><input type="submit" value="검색"></td>
</tr>

</table>
</form>


<?

if($_GET["search_mode"] == "true"){
	
	$liekis = null;
	$is_search = false;
	
	
	if($_GET["start_date"] && $_GET["end_date"]){
		$is_search = true;
		$likeis="sell.takendate between '".$_GET["start_date"]."' AND '".$_GET["end_date"]."'";
	}
	 
	

	 if($_GET["name"]){
		 if($is_search == true){
			$likeis .= " and ";
		}
		$arrSite = explode(",", $_GET["name"]);
		if(count($arrSite) > 0){
			$is_search = true;
			$index = 0;
			foreach($arrSite as $value){
				if($index == 0){
					$and = " ";
				}else{
					$and = " or ";
				}
				$likeis .= $and." item.name like '%".$value."%' ";
				$index++;
			}
		}

	 }


    if($is_search == true){
		$likeis = " and  (".$likeis.")";
	}

$search_query = "select 
							sell.sizeinfo as sell_sizeinfo, item.name as item_name, count(sell.ID) as cnt, item.code as item_code
						from tdms_sell_manage sell, tdms_item_manage item where sell.itemid = item.id ".$likeis."  group by sell.sizeinfo  order by item_code asc ";
$result = $dbconn->query($search_query);


if($_GET["start_date"] or $_GET["end_date"]){
	echo $_GET["start_date"]."에서 ".$_GET["end_date"]."까지의 결과입니다.";
}

?>



<table width="100%" border="1">
<tr>
	<th>
		사이즈
	</th>
	<th>
		아이템 명
	</th>
	<th>
		거래처
	</th>
	<th>
		갯수
	</th>
</tr>


<?

while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
	if($row[sell_isreturn] == 'Y'){
		$rowcolor ="bgcolor='red'";	
	}else{
		$rowcolor = "";
	}
	echo "<tr>
	<td ".$rowcolor.">
		".$row[sell_sizeinfo]."
	</td>
	<td ".$rowcolor.">
		".$row[item_name]."
	</td>
	<td ".$rowcolor.">
		".$row[item_code]."
	</td>
	<td ".$rowcolor.">
		".$row[cnt]."
	</td>
</tr>";
	
}

?>

</table>

<?
}
?>
</center>