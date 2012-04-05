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
			// 파서 오브젝트를 셋팅(파일명)
			$parser = new XMLParser($xml);

			// 해석 시작
			$parser->Parse();

			$xmlSetting = $parser->document->settings[0];
			$dbSetting = $parser->document->database[0];
			

			$this->title		= $this->convertStr($xmlSetting->title[0]->tagData);

			// 데이터베이스의 설명
			$this->content		= $this->convertStr($xmlSetting->content[0]->tagData);

			// tablecolor		: 테이블의 색상
			// sub_tablecolor	: 테이블의 셀의 색상
			// line_tablecolor	: 테이블의 선의 색상
			$this->tbColor		= array("tableColor"		=> $this->convertStr($xmlSetting->tbcolor[0]->base[0]->tagData),
										"subTableColor"		=> $this->convertStr($xmlSetting->tbcolor[0]->sub[0]->tagData),
										"lineTableColor"	=> $this->convertStr($xmlSetting->tbcolor[0]->line[0]->tagData));
			// 테이블의 넓이 설정
			$this->tableWidth	= $this->convertStr($xmlSetting->tbwidth[0]->tagData);
			$this->plugin	 =$this->convertStr($xmlSetting->plugin[0]->tagData);




			// 데이터베이스 접속연결구문
			// 데이터베이스타입://아이디:비밀번호@도메인/데이터베이스 명
			$this->dsn			= $this->convertStr($dbSetting->dsn[0]->tagData);

			// 테이블 명칭
			$this->tbName		= $this->convertStr($dbSetting->table[0]->tagData);

			// 조인 타입
			// 1: 일대일 연결 
			// 2: 일대다 연결
			$this->joinType		= $this->convertStr($dbSetting->join[0]->type[0]->tagData); 

			// 조인할 대상 테이블
			$this->joinTarget   = $this->convertStr($dbSetting->join[0]->target[0]->tagData); 
			
			// 조인 할 조건 ('where' 이하 구문)
			// 예) "TDMS_SELL_MANAGE.itemid = TDMS_ITEM_MANAGE.id"
			$this->joinCondition= $this->convertStr($dbSetting->join[0]->condition[0]->tagData); 

			// 조인 시 정렬조건('order by' 이하 구문)
			$this->joinOrder	= $this->convertStr($dbSetting->join[0]->order[0]->tagData); 


			// 테이블 필드 설정 
			//  array("별칭"	=>	array(필드명, true/false(스트링/숫자형), 필드 컴포넌트 타입명, 초기값 혹은 설정값),...)
			// 예) "원가"		=>	array("oprice",		false, "TBOX/16/25", "")
			// 주의 사항 : ID, SUB_ID, CODE, CODENUMBER는 반드시 포함해야 하며, 데이터베이스에도 작성이 되어있어야 한다.
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