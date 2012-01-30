<?
Class plugin_element extends Element{
	private $Page;
	private $PageG;

	private $how;
	private $how2;
	private $order;

	function plugin_element(){
		$this->layout = "main";

	}

	// 엘레먼트를 하나 생성한다.
	public function createElement(){
		if(file_exists($elementConfigPath = LOGIC_ROOT.D_S.ELEMENTS.D_S.$_POST["element_name"])){
			//die($_POST["element_name"]."는 이미 생성한 엘레먼트입니다!");
		}

		if($_POST["element_name"] && $_POST["table_name"]){
 
			$xml = new XmlWriter1();
			
			$xml->push('config');
				$xml->push('settings');
					$xml->element('title', "");
					$xml->element('content', "");
					
					$xml->push('tbcolor');
						$xml->element('base', "#7e8ece");
						$xml->element('sub',  "#cfdfef");
						$xml->element('line', "#7fafdf");
					$xml->pop();

					$xml->element('tbwidth', "700");
					$xml->element('plugin', "");
				$xml->pop();


				$xml->push('database');
					$xml->element('dsn', "mysqli://root@localhost/tdms");
					$xml->element('table', $_POST["table_name"]);
					$xml->push('join');
						$xml->element('type', "");
						$xml->element('target', "");
						$xml->element('condition', "");
						$xml->element('order', "");
					$xml->pop();

					$xml->push('fields');


						$xml->push('field');
							$xml->element('alias',		"일련번호");
							$xml->element('fieldname',  "ID");
							$xml->element('string',		"false");
							$xml->element('component',  "");
							$xml->push('settings');
								$xml->push('setting');
									$xml->element('title', "");
									$xml->element('value', "");
								$xml->pop();
							$xml->pop();
						$xml->pop();

						$xml->push('field');
							$xml->element('alias',		"유형");
							$xml->element('fieldname',  "CODE");
							$xml->element('string',		"true");
							$xml->element('component',  "TSEL");
							$xml->push('settings');
								$xml->push('setting');
									$xml->element('title', "유형1");
									$xml->element('value', "유형1");
								$xml->pop();
								$xml->push('setting');
									$xml->element('title', "유형2");
									$xml->element('value', "유형2");
								$xml->pop();
							$xml->pop();
						$xml->pop();

						$xml->push('field');
							$xml->element('alias',		"코드번호");
							$xml->element('fieldname',  "CODENUMBER");
							$xml->element('string',		"true");
							$xml->element('component',  "TBOX/5/15");
							$xml->push('settings');
								$xml->push('setting');
									$xml->element('title', "");
									$xml->element('value', "");
								$xml->pop();
							$xml->pop();
						$xml->pop();

					  foreach($_POST["alias_name"] as $key=>$value){
						if($_POST["alias_name"] && $_POST["field_name"][$key] && $_POST["string_type"][$key] && $_POST["component_name"][$key]){
							$xml->push('field');
							
								$xml->element('alias',		$_POST["alias_name"][$key]);
								$xml->element('fieldname',  $_POST["field_name"][$key]);
								$xml->element('string',		$_POST["string_type"][$key]);
								$xml->element('component',  $_POST["component_name"][$key]."//");
								$xml->push('settings');
									$xml->push('setting');
										$xml->element('title', "");
										$xml->element('value', "");
									$xml->pop();
								$xml->pop();
							$xml->pop();
						}
					  }
					$xml->pop();

					$xml->element('tbitem', " ");

				$xml->pop();
			$xml->pop();

			$elementConfigPath = LOGIC_ROOT.D_S.ELEMENTS.D_S.$_POST["element_name"].D_S.CONFIG_DIR_NAME;
			$this->makeAndCheckPath($elementConfigPath);
			if(file_exists($elementConfigPath)){
				$fp = fopen($elementConfigPath.D_S.CONFIG_FILE_NAME.".xml", "w");
				if(fwrite($fp, $xml->getXml())){
					echo $_POST["element_name"].D_S.CONFIG_DIR_NAME." 환경설정 파일 생성완료<br>";
				}
				fclose($fp);
			}else{
				die("[ERROR]환경설정 디렉토리 생성 실패!");
			}

			/*
				// 환경설정 작성 시작
				$config_script = "";

				$tabOne = "\t";
				$tabTwo = "\t\t";
				$newLine = "\n";
				$config_script .= "<?\n";
				$config_script .= "class config_".$_POST["element_name"]." extends Config {".$newLine;
				$config_script .= $tabOne."public function config_".$_POST["element_name"]."(){".$newLine;
				

				$config_script .= $tabTwo."\$this->dsn".$tabOne."= \"mysqli://root@localhost/tdms\";".$newLine;
				$config_script .= $tabTwo."\$this->tbName".$tabOne."= \"".$_POST["table_name"]."\";".$newLine;
				$config_script .= $tabTwo."\$this->content".$tabOne."= \"\";".$newLine;
				$config_script .= $tabTwo."\$this->title".$tabOne."= \"\";".$newLine;
				$config_script .= $tabTwo."\$this->tbColor".$tabOne."= array(\"tableColor\" => \"#7e8ece\",".$newLine;
				$config_script .= $tabTwo.$tabTwo.$tabTwo.$tabTwo."\"subTableColor\" => \"#cfdfef\",".$newLine;
				$config_script .= $tabTwo.$tabTwo.$tabTwo.$tabTwo."\"lineTableColor\" => \"#7fafdf\");".$newLine;
				$config_script .= $tabTwo."\$this->tableWidth".$tabOne."= \"700\";".$newLine;
				$config_script .= $tabTwo."\$this->arrTbField".$tabOne."= array(".$newLine;


				$config_script .= $tabTwo.$tabTwo.$tabOne."\"일련번호\" => ";
				$config_script .= "array(\"ID\", false, \"\", \"\"),".$newLine;
				$config_script .= $tabTwo.$tabTwo.$tabOne."\"유형\" => ";
				$config_script .= "array(\"CODE\",	true, \"TSEL\",array(\"CODE1\"=>\"1\",\"CODE2\"=>\"2\")),".$newLine;
				$config_script .= $tabTwo.$tabTwo.$tabOne."\"코드번호\" => ";
				$config_script .= "array(\"CODENUMBER\",true, \"TBOX/5/15\", \"\"),".$newLine;

				foreach($_POST["alias_name"] as $key=>$value){
					if($_POST["alias_name"] && $_POST["field_name"][$key] && $_POST["string_type"][$key] && $_POST["component_name"][$key]){
						$config_script .= $tabTwo.$tabTwo.$tabOne."\"".$_POST["alias_name"][$key]."\" => ";
						$config_script .= "array(\"".$_POST["field_name"][$key]."\",".$_POST["string_type"][$key].",\"".$_POST["component_name"][$key]."/10/10\",array()),".$newLine;
					}
				}
			}else{
				die("[ERROR] 엘레먼트명과 테이블명은 필수입니다.");
			}
			
			$config_script .= $tabTwo.$tabTwo.$tabOne.");".$newLine;
			$config_script .= $tabTwo."\$this->arrTbItem".$tabOne."= \" \";".$newLine;
			$config_script .= $tabTwo."}".$newLine;
			$config_script .= $tabOne."}".$newLine;

			$config_script .= "?>".$newLine;

			*/

			
			// 스키마 파일 생성 
			$element_scheme = "";
			
			$element_scheme .= "create table ".$_POST["table_name"]."(\n";
			$element_scheme .= "\t ID	int(10) auto_increment not null,\n";
			$element_scheme .= "\t CODE varchar(10),\n";
			$element_scheme .= "\t CODENUMBER varchar(15),\n";
							
			foreach($_POST["field_name"] as $key=>$value){
				if($_POST["alias_name"] && $_POST["field_name"][$key] && $_POST["string_type"][$key] && $_POST["component_name"][$key]){
					 if($_POST["string_type"][$key] == "true"){
						 $element_scheme .= "\t".$_POST["field_name"][$key]." varchar(20),\n";
					 }else{
						$element_scheme .= "\t".$_POST["field_name"][$key]." int(10),\n";
					 }				 
				}
			}
			$element_scheme .= "    \t primary key(id)\n";
			$element_scheme .= ");";		

			$elementConfigPath = LOGIC_ROOT.D_S.ELEMENTS.D_S.$_POST["element_name"].D_S.CONFIG_DIR_NAME.D_S.SQL_DIR_NAME;
			$this->makeAndCheckPath($elementConfigPath);
			if(file_exists($elementConfigPath)){
				$fp = fopen($elementConfigPath.D_S.sql.".php", "w");
				if(fwrite($fp, $element_scheme)){
					echo CONFIG_DIR_NAME.D_S.SQL_DIR_NAME." 스키마 파일 생성완료<br>";
				}
				fclose($fp);
			}else{
				die("[ERROR]환경설정 디렉토리 생성 실패!");
			}

			// 데이터베이스 생성
			$objDB = new Database_Connect("mysqli://root@localhost/tdms");
			$objDB->setQuery($element_scheme);
			if($objDB->executeQuery()){
				echo "데이터베이스 생성<br>";
			}else{
				echo "데이터베이스 생성 에러<br>";
			}

			// 기본 페이지파일 복사
			$elementPagesPath = LOGIC_ROOT.D_S.ELEMENTS.D_S.$_POST["element_name"].D_S.PAGES_DIR_NAME;
			$this->makeAndCheckPath($elementPagesPath);
			if(file_exists($elementPagesPath)){
				$sourcePageDir = CORE_ROOT.D_S."element".D_S.PAGES_DIR_NAME;
				$targetPageDir = $elementPagesPath;
				$arrSkelFile = array("Plist.php","Pinsert.php","Pview.php","Pupdate.php","Pdelete.php");
				foreach($arrSkelFile as $value){
					if (!copy($sourcePageDir.D_S.$value, $targetPageDir.D_S.$value)) {
						die($value." 파일 복사 실패!\n");
					}else{
						echo PAGES_DIR_NAME.D_S.$value." 파일 생성완료<br>";
					}
				}
			}else{
				die("[ERROR]페이지 디렉토리 생성 실패!");
			}


			// 컨트롤러 설정
			$skelectonConrtollerFile = CORE_ROOT.D_S."element".D_S.SKELECTON_FILE_NAME.".php";
			$targetConrtollerFile = LOGIC_ROOT.D_S.ELEMENTS.D_S.$_POST["element_name"].D_S.$_POST["element_name"].".php";
			if(file_exists($skelectonConrtollerFile)){
				$fp = fopen($skelectonConrtollerFile, "rb");
				$contents = stream_get_contents($fp);

				$pattern = "|%(.*)%|U";
				$match = array();
				preg_match_all($pattern, $contents, $match);

				for($i=0;$i<count($match[0]);$i++){
						$contents = str_ireplace($match[0][$i], $_POST[($match[1][$i])], $contents);
				}

				$fp1 = fopen($targetConrtollerFile, "w");
				if(fwrite($fp1, $contents)){
					echo "element".D_S.$_POST["element_name"].".php"." 컨트롤러 파일 생성완료<br>";
				}
				fclose($fp);
				fclose($fp1);


			}else{
				die("[ERROR]환경설정 디렉토리 생성 실패!");
			}

		}
		echo "<a href='/ADMIN/element/Plist'>리스트로 돌아가기</a>";
	
	}
	
	// 경로를 입력하면 확인하고 없으면 만들어준다.
	private function mkpath($path){
			$dirs=array();
			$path=preg_replace('/(\/){2,}|(\\\){1,}/','/',$path);
			$dirs=explode("/",$path);
			$path="";
			foreach ($dirs as $element){
				$path.=$element."/";
				if(!is_dir($path)){ 
					if(!mkdir($path)){ 
						return false; 
					}
				}   
	 		}
		}
		
	private function makeAndCheckPath($file_path){
			if(!is_dir($file_path)){
				$this->mkpath($file_path);
				if(!is_dir($file_path)){
					echo '디렉토리가 생성되지 않았습니다';
					exit;
				}
			}
		}

	public function Plist(){

		$dir = CORE_LIB_ROOT.D_S.COMPONENT_BLOCK;
		
		$index = 0;
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
			
				while (($component_name = readdir($dh)) !== false) {
						
					if(filetype($dir .D_S. $component_name) == "file" and $component_name != '.' and $component_name != '..'){
						$arrtmpfilename = explode(".", $component_name);
						if(trim(COMPONENT_BLOCK) == trim($arrtmpfilename[0]) || trim("imp".COMPONENT_BLOCK) == trim($arrtmpfilename[0])){
							continue;
						}
						$arrComponentList[$index] = $arrtmpfilename[0]; 
						$index++;
					}
					
				}
				closedir($dh);
			}
		}else{
			echo "[ERROR]플러그인 디렉토리를 확인해주세요!";
		}

		$this->display(array("COMPONENT_LISTS"=>$arrComponentList));
	}
}

	?>