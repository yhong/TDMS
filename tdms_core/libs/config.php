<?
class Config {	
		public $dsn				= null;
		public $tbName			= null;
		public $joinType		= null; 
		public $joinTarget		= null; 
		public $joinCondition	= null;
		public $joinOrder		= null;
		public $content			= null;
		public $title			= null;
		public $tbColor			= array();
		public $tableWidth		= null;
		public $arrTbField		= array();
		public $arrTbItem		= null;
		public $plugin			= null;

		public function Config($xml_file){
			
			$xml = file_get_contents($xml_file);
			// �ļ� ������Ʈ�� ����(���ϸ�)
			$parser = new XMLParser($xml);

			// �ؼ� ����
			$parser->Parse();

			$xmlSetting = $parser->document->settings[0];
			$dbSetting = $parser->document->database[0];
			

			$this->title		= $this->convertStr($xmlSetting->title[0]->tagData);

			// �����ͺ��̽��� ����
			$this->content		= $this->convertStr($xmlSetting->content[0]->tagData);

			// tablecolor		: ���̺��� ����
			// sub_tablecolor	: ���̺��� ���� ����
			// line_tablecolor	: ���̺��� ���� ����
			$this->tbColor		= array("tableColor"		=> $this->convertStr($xmlSetting->tbcolor[0]->base[0]->tagData),
										"subTableColor"		=> $this->convertStr($xmlSetting->tbcolor[0]->sub[0]->tagData),
										"lineTableColor"	=> $this->convertStr($xmlSetting->tbcolor[0]->line[0]->tagData));
			// ���̺��� ���� ����
			$this->tableWidth	= $this->convertStr($xmlSetting->tbwidth[0]->tagData);
			$this->plugin	 =$this->convertStr($xmlSetting->plugin[0]->tagData);




			// �����ͺ��̽� ���ӿ��ᱸ��
			// �����ͺ��̽�Ÿ��://���̵�:��й�ȣ@������/�����ͺ��̽� ��
			$this->dsn			= $this->convertStr($dbSetting->dsn[0]->tagData);

			// ���̺� ��Ī
			$this->tbName		= $this->convertStr($dbSetting->table[0]->tagData);

			// ���� Ÿ��
			// 1: �ϴ��� ���� 
			// 2: �ϴ�� ����
			$this->joinType		= $this->convertStr($dbSetting->join[0]->type[0]->tagData); 

			// ������ ��� ���̺�
			$this->joinTarget   = $this->convertStr($dbSetting->join[0]->target[0]->tagData); 
			
			// ���� �� ���� ('where' ���� ����)
			// ��) "TDMS_SELL_MANAGE.itemid = TDMS_ITEM_MANAGE.id"
			$this->joinCondition= $this->convertStr($dbSetting->join[0]->condition[0]->tagData); 

			// ���� �� ��������('order by' ���� ����)
			$this->joinOrder	= $this->convertStr($dbSetting->join[0]->order[0]->tagData); 


			// ���̺� �ʵ� ���� 
			//  array("��Ī"	=>	array(�ʵ��, true/false(��Ʈ��/������), �ʵ� ������Ʈ Ÿ�Ը�, �ʱⰪ Ȥ�� ������),...)
			// ��) "����"		=>	array("oprice",		false, "TBOX/16/25", "")
			// ���� ���� : ID, SUB_ID, CODE, CODENUMBER�� �ݵ�� �����ؾ� �ϸ�, �����ͺ��̽����� �ۼ��� �Ǿ��־�� �Ѵ�.
			if(count($dbSetting->fields[0]->field) > 0){
				foreach($dbSetting->fields[0]->field as $field){
					$alias = $this->convertStr($field->alias[0]->tagData);
					$fieldname = $this->convertStr($field->fieldname[0]->tagData);
					$string = $this->convertStr($field->string[0]->tagData);
					$component = $this->convertStr($field->component[0]->tagData);
					
					foreach($field->settings[0]->setting as $setting){
						$option[$alias][$this->convertStr($setting->title[0]->tagData)] = $this->convertStr($setting->value[0]->tagData);
					}
					$this->arrTbField[$alias] = array($fieldname, $string, $component, $option[$alias]);
				}
			}


			//$this->arrTbField = array();
					
			$this->arrTbItem = $this->convertStr($dbSetting->tbitem[0]->tagData);
			

		}

		public function setDsn($value){
			$this->dsn = $value;
		}
		public function setTbName($value){
			$this->tbName = $value;
		}

		public function setJoinType($value){
			$this->joinType = $value;
		}

		public function setJoinTarget($value){
			$this->joinTarget = $value;
		}

		public function setJoinCondition($value){
			$this->joinCondition = $value;
		}
		public function setJoinOrder($value){
			$this->joinOrder = $value;
		}


		public function setContent($value){
			$this->content = $value;
		}


		public function setTitle($value){
			$this->title = $value;
		}


		public function setTbColor($value){
			$this->tbColor = $value;
		}


		public function setTableWidth($value){
			$this->tableWidth = $value;
		}


		public function setArrTbField($value){
			$this->arrTbField = $value;
		}

		public function setArrTbItem($value){
			$this->tbarrTbItem = $value;
		}

		public function setPlugin($value){
			$this->plugin = $value;
		}


		private function convertStr($str){
			return iconv("utf-8","euc-kr",$str);
		}
}

?>