<form method="get" name="search_stat" action="/<?=$element->pageurl?>">
<input type="hidden" name="search_mode" value="true">
<table width="400" border="1">
<tr>
	<td><b>��¥</b></td>

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
	<td><b>����Ʈ</b></td>
	<td><input type="inputbox" name="site" size="15"  value="<?=$_GET["site"]?>"></td>
	<td><b>�ɼ�</b></td>
	<td>
	<?
			if($_GET["option"] == "profit"){
				$select1 = "selected";
				$select2 = "";
			}else if($_GET["option"] == "cnt_item"){
				$select2 = "selected";
				$select1 = "";
			}
		?>
		<select name="option">
			<option value="profit" <?=$select1?>>������ ��������</option>
			<option value="cnt_item" <?=$select2?>>�ǸŰ��� ��������</option>
		</select>
	</td>
</tr>
<tr>
	<td colspan="4" align="center"><input type="submit" value="�˻�"></td>
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
	 
	 if($_GET["site"]){
		
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

    if($is_search == true){
		$likeis = " and  (".$likeis.")";
	}


	if($_GET["option"] == "profit"){
		$order = " order by item_code asc, totalprice desc ";
	}else if($_GET["option"] == "cnt_item"){
		$order = " order by item_code asc, cnt_item desc ";
	}else{
		$order = " order by item_code asc";
	}


if($_GET["start_date"] or $_GET["end_date"]){
	echo $_GET["start_date"]."���� ".$_GET["end_date"]."������ ����Դϴ�.";
}


// �ŷ�ó�� �� ����
$search_code_query = "select item.code as item_code, sum(item.rprice)-sum(item.oprice)  as totalprice, count(sell.id) as cnt_item from tdms_sell_manage sell, tdms_item_manage item where sell.itemid = item.id and sell.isreturn='N' ".$likeis." group by item.code ".$order;
$resultpercode = $dbconn->query($search_code_query);


$search_query = "select item.name as name, count(sell.id) as cnt_item, sum(rprice)-sum(oprice)  as totalprice, item.code as item_code from tdms_sell_manage sell, tdms_item_manage item where sell.itemid = item.id and sell.isreturn='N' ".$likeis." group by item.name ".$order;
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

?>


<p><b>������</b> <?=$profit_sum?>��  / <b>����</b> <?=$mo_sum?>��  / <b>�������� ������</b> <?=($profit_sum-$mo_sum)?>�� / <b>����</b> <?=$item_price_sum?>�� / <b>�ǸŰ���</b> <?=$count_sell?>�� / <b>��ǰ����</b> <?=$count_return?>��</p>





<table width="400" border="1">
<tr>
	<th>
		�Ϸù�ȣ
	</th>
	<th>
		�ŷ�ó
	</th>
	
	<th>
		������
	</th>
	<th>
		�ǸŰ���
	</th>
</tr>


<?
$index = 1;
while($row = $resultpercode->fetchRow(DB_FETCHMODE_ASSOC)){
	echo "<tr>
	<td>
		".$index++."
	</td>
	
	<td>
		".$row[item_code]."
	</td>
	
	<td>
		".($row[totalprice])."
	</td>
	<td>
		".$row[cnt_item]."
	</td>
</tr>";
	
}

?>
</table>
<br>

<table width="400" border="1">
<tr>
	<th>
		�Ϸù�ȣ
	</th>
	<th>
		��ǰ��
	</th>
	<th>
		�ŷ�ó
	</th>
	<th>
		�ǸŰ���
	</th>
	<th>
		������
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
		".$row[name]."
	</td>
	<td>
		".$row[item_code]."
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