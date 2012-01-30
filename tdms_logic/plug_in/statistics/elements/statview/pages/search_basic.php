
<center>

<form method="get" name="search_stat" action="/<?=$element->pageurl?>">
<input type="hidden" name="search_mode" value="true">
<table width="100%" border="1">


<tr>
	<td><b>구매처</b></td><td><input type="inputbox" name="code" size="15" value="<?=$_GET["code"]?>"></td>
	<td><b>사이트</b></td><td><input type="inputbox" name="site" size="15" value="<?=$_GET["site"]?>"></td>
	<td><b>상품명</b></td><td><input type="inputbox" name="name" size="15" value="<?=$_GET["name"]?>"></td>
	<td><b>성별</b></td><td><input type="inputbox" name="sex" size="15" value="<?=$_GET["sex"]?>"></td>
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
	<td colspan="4"><input type="inputbox" name="start_date" size="15" value="<?=$start_date?>"> ~ <input type="inputbox" name="end_date" size="15" value="<?=$end_date?>"></td>
	<td colspan="4"><input type="submit" value="검색"></td>
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
	 

	 if($_GET["code"]){
        if($is_search == true){
			$likeis .= " and ";
		}
		$arrSite = explode(",", $_GET["code"]);
		if(count($arrSite) > 0){
			$is_search = true;
			$index = 0;
			foreach($arrSite as $value){
				if($index == 0){
					$and = " ";
				}else{
					$and = " or ";
				}
				$likeis .= $and." item.CODE like '%".$value."%' ";
				$index++;
			}
		}
	 }

	 if($_GET["site"]){
		 if($is_search == true){
			$likeis .= " and ";
		}
		$arrSite = explode(",", $_GET["site"]);
		if(count($arrSite) > 0){
			$is_search = true;
			$index = 0;
			foreach($arrSite as $value){
				if($index == 0){
					$and = " ";
				}else{
					$and = " or ";
				}
				$likeis .= $and." sell.CODE like '%".$value."%' ";
				$index++;
			}
		}

	 }

	  if($_GET["sex"]){
		 if($is_search == true){
			$likeis .= " and ";
		}
		$arrSite = explode(",", $_GET["sex"]);
		if(count($arrSite) > 0){
			$is_search = true;
			$index = 0;
			foreach($arrSite as $value){
				if($index == 0){
					$and = " ";
				}else{
					$and = " or ";
				}
				$likeis .= $and." sell.sex like '%".$value."%' ";
				$index++;
			}
		}

	 }


	if($_GET["name"]){
		
		if($is_search == true){
					$and = " and ";
		}else{
					$and = "";
		}

		$is_search = true;
		$likeis .= $and." item.name like '%".$_GET[name]."%' ";
	}

    if($is_search == true){
		$likeis = " and  (".$likeis.")";
	}






$search_query = "select 
							sell.ID as sell_ID, sell.SUBID as sell_SUBID, sell.CODE as sell_CODE, sell.CODENUMBER as sell_CODENUMBER, 
							item.name as item_name, sell.profit as sell_profit, sell.buyname as sell_buyname, sell.buynumber as sell_buynumber, 
							sell.address as sell_address, sell.takendate as sell_takendate, sell.note as sell_note, sell.isreturn as sell_isreturn
						from tdms_sell_manage sell, tdms_item_manage item where sell.itemid = item.id and sell.isreturn='N' ".$likeis." order by sell.takendate desc, sell.getname asc";
$result = $dbconn->query($search_query);


$profit_sum_query = "select  sum(sell.profit)
						from tdms_sell_manage sell, tdms_item_manage item where sell.itemid = item.id and sell.isreturn='N' ".$likeis;

$profit_sum = $dbconn->getOne($profit_sum_query);


$count_sell_query = "select  count(sell.ID)
						from tdms_sell_manage sell, tdms_item_manage item where sell.itemid = item.id and sell.isreturn='N' ".$likeis;

$count_sell = $dbconn->getOne($count_sell_query);

$count_return_query = "select  count(sell.ID)
						from tdms_sell_manage sell, tdms_item_manage item where sell.itemid = item.id and sell.isreturn='Y' ".$likeis;

$count_return = $dbconn->getOne($count_return_query);


$mo_likeis="";
if($_GET["start_date"] && $_GET["end_date"]){
		$mo_likeis="where date between '".$_GET["start_date"]."' AND '".$_GET["end_date"]."'";
}
$mo_query = "select  sum(outprice) from tdms_money_out ".$mo_likeis;
$mo_sum = $dbconn->getOne($mo_query);


$item_price_sum_query = "select  sum(item.price)
						from tdms_sell_manage sell, tdms_item_manage item where sell.itemid = item.id and sell.isreturn='N' ".$likeis;

$item_price_sum = $dbconn->getOne($item_price_sum_query);

if($_GET["start_date"] or $_GET["end_date"]){
	echo $_GET["start_date"]."에서 ".$_GET["end_date"]."까지의 결과입니다.";
}

?>


<p><b>순이익</b> <?=$profit_sum?>원  / <b>지출</b> <?=$mo_sum?>원  / <b>지출포함 순이익</b> <?=($profit_sum-$mo_sum)?>원 / <b>매출</b> <?=$item_price_sum?>원 / <b>판매개수</b> <?=$count_sell?>개 / <b>반품개수</b> <?=$count_return?>개</p>


<table width="100%" border="1">
<tr>
	<td>
		일련번호
	</td>
	<td>
		유형별번호
	</td>
	<td>
		주문물품
	</td>
	<td>
		이익금
	</td>
	<td>
		수령자
	</td>
	<td>
		송장번호
	</td>
	<td>
		유형
	</td>
	<td>
		주소
	</td>
	<td>
		접수일자
	</td>
	<td>
		비고
	</td>
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
		".$row[sell_ID]."
	</td>
	<td ".$rowcolor.">
		".$row[sell_CODE]."-".$row[sell_CODENUMBER]."
	</td>
	<td ".$rowcolor.">
		".$row[item_name]."
	</td>
	<td ".$rowcolor.">
		".$row[sell_profit]."
	</td>
	<td ".$rowcolor.">
		".$row[sell_buyname]."
	</td>
	<td ".$rowcolor.">
		".$row[sell_buynumber]."
	</td>
	<td ".$rowcolor.">
		".$row[sell_CODE]."
	</td>
	<td ".$rowcolor.">
		".$row[sell_address]."
	</td>
	<td ".$rowcolor.">
		".$row[sell_takendate]."
	</td>
	<td ".$rowcolor.">
		".$row[sell_note]."
	</td>
</tr>";
	
}

?>

</table>

<?
}
?>
</center>