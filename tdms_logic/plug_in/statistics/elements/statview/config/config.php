<?

class config_plugin_statview extends Config {
	public function config_plugin_statview(){
		$this->dsn			= "mysqli://root@localhost/tdms";
		$this->tbName		= "TDMS_SELL_MANAGE A";
		$this->plugin		= "STATISTICS";

		$this->joinType		= "1"; // 1: �ϴ��� ���� 2: �ϴ�� ����
		$this->joinTarget   = "TDMS_ITEM_MANAGE B"; // ������ ��� ���̺�
		$this->joinCondition= "A.itemid = B.id";
		$this->joinOrder	= "a.id desc";

		$this->content		= "�Ǹ� ���� ���̺�";
		$this->title		= "shop_item ����";
		$this->tbColor		= array("tableColor" => "#8E1002",
								 "subTableColor" => "#E2E2E2",
								 "lineTableColor" => "#AFAFAF");
		$this->tableWidth	= "710";
		$this->arrTbField = array(
								"�Ϸù�ȣ"	=>	array("A.ID", false, "", ""),
								"������ȣ"	=>	array("A.SUBID",false,"STAT/5/15",""),
								"�ϷῩ��"	=>	array("A.DONE",true,"TSEL", array("�Ϸ�"=>"Y","�̿Ϸ�"=>"N")),
								"����Ʈ"		=>	array("A.CODE", true, "STAT/20/4", 
													array("����"=>"����",
														  "G����"=>"G����",
														  "���̶�Ŭ"=>"���̶�Ŭ",
														  "GS�̽����"=>"GS�̽����",
														  "������ũ"=>"������ũ", 
														  "����"=>"����",
														  "���ŷ�"=>"���ŷ�",
														  "11����"=>"11����"
														  )
													),
								"�ڵ��ȣ"	=>	array("A.CODENUMBER",true, "STAT/5/15", ""),
								"������"	=>	array("A.getname", true, "STAT/16/25", ""),
								"�ּ�"		=>	array("A.address",true, "STAT/35/255", ""),
								"�Ǹž��̵�"=>	array("A.userid", true, "STAT/16/25", ""),
								"��ȭ��ȣ"	=>	array("A.tel", true, "STAT/16/15", ""),
								"�ڵ���"	=>	array("A.mobile", true, "STAT/16/15", ""),
								"�������"	=>	array("A.status",true, "TSEL", array( "����"=>"����", "����"=>"����" )),
								"�䱸����"	=>	array("A.request",true, "TEXT/40/4",""),
								"�����ȣ"	=>	array("A.sn",true, "STAT/14/25", ""),
								"������"	=>	array("A.buyname", true, "STAT/16/25", ""),
								"����"		=>	array("A.sex",true, "STAT/20/4", array( "��"=>"��", "��"=>"��", "��"=>"" )),
								"�̸���"	=>	array("A.email", true, "STAT/20/50", ""),
								
								"�ֹ���ǰ"	=>	array("A.itemid",true, "OCMB", 
														array( 
															"TBNAME"=>"TDMS_ITEM_MANAGE B", 
															"TITLE"=>"B.name", 
															"VALUE"=>"B.id",
															"VALUETO"=>"���ͱ�"
														)
													),
								"���ͱ�"	=>	array("A.profit",false, "STAT/10/25", ""),
								"������ȣ"	=>	array("A.buynumber",true, "STAT/10/25", ""),
								"��������"	=>	array("A.takendate", true, "DATE/10/20", ""),
								"ȸ������"	=>	array("A.conducteddate", true, "DATE/10/20", ""),
								"ȸ�Ű��"	=>	array("A.conductingresult", true, "ELST", 
												array(
														"��ǰ"=>"��ǰ", 
														"��ǰ"=>"��ǰ",
														"��Ÿ"=>"��Ÿ")
												),
								"��ǰ����"		=>	array("A.isreturn", true, "TSEL", array("N"=>"N","Y"=>"Y")),
								"���"		=>	array("A.note", true, "TEXT/40/4", ""),
								"��ǰ�Ϸù�ȣ"	=>	array("B.ID", true, "", ""),
								"��ǰ��"	=>	array("B.name", true, "STAT/20/4", ""),
								"����ó"	=>	array("B.CODE", true, "STAT/20/4", ""),
								"��ǰ�ڵ�"	=>	array("B.CODENUMBER", true, "STAT/20/4", ""),
								"����"	=>	array("B.oprice", true, "STAT/20/4", "")
								
							);
				
		$this->arrTbItem = " ";
	}
}
?>