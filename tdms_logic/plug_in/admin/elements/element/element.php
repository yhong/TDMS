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

	// ������Ʈ�� �ϳ� �����Ѵ�.
	public function createElement(){
		if(file_exists($elementConfigPath = LOGIC_ROOT.D_S.ELEMENTS.D_S.$_POST["element_name"])){
			//die($_POST["element_name"]."�� �̹� ������ ������Ʈ�Դϴ�!");
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
							$xml->element('alias',		"�Ϸù�ȣ");
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
							$xml->element('alias',		"����");
							$xml->element('fieldname',  "CODE");
							$xml->element('string',		"true");
							$xml->element('component',  "TSEL");
							$xml->push('settings');
								$xml->push('setting');
									$xml->element('title', "����1");
									$xml->element('value', "����1");
								$xml->pop();
								$xml->push('setting');
									$xml->element('title', "����2");
									$xml->element('value', "����2");
								$xml->pop();
							$xml->pop();
						$xml->pop();

						$xml->push('field');
							$xml->element('alias',		"�ڵ��ȣ");
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
					echo $_POST["element_name"].D_S.CONFIG_DIR_NAME." ȯ�漳�� ���� �����Ϸ�<br>";
				}
				fclose($fp);
			}else{
				die("[ERROR]ȯ�漳�� ���丮 ���� ����!");
			}

			/*
				// ȯ�漳�� �ۼ� ����
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


				$config_script .= $tabTwo.$tabTwo.$tabOne."\"�Ϸù�ȣ\" => ";
				$config_script .= "array(\"ID\", false, \"\", \"\"),".$newLine;
				$config_script .= $tabTwo.$tabTwo.$tabOne."\"����\" => ";
				$config_script .= "array(\"CODE\",	true, \"TSEL\",array(\"CODE1\"=>\"1\",\"CODE2\"=>\"2\")),".$newLine;
				$config_script .= $tabTwo.$tabTwo.$tabOne."\"�ڵ��ȣ\" => ";
				$config_script .= "array(\"CODENUMBER\",true, \"TBOX/5/15\", \"\"),".$newLine;

				foreach($_POST["alias_name"] as $key=>$value){
					if($_POST["alias_name"] && $_POST["field_name"][$key] && $_POST["string_type"][$key] && $_POST["component_name"][$key]){
						$config_script .= $tabTwo.$tabTwo.$tabOne."\"".$_POST["alias_name"][$key]."\" => ";
						$config_script .= "array(\"".$_POST["field_name"][$key]."\",".$_POST["string_type"][$key].",\"".$_POST["component_name"][$key]."/10/10\",array()),".$newLine;
					}
				}
			}else{
				die("[ERROR] ������Ʈ��� ���̺���� �ʼ��Դϴ�.");
			}
			
			$config_script .= $tabTwo.$tabTwo.$tabOne.");".$newLine;
			$config_script .= $tabTwo."\$this->arrTbItem".$tabOne."= \" \";".$newLine;
			$config_script .= $tabTwo."}".$newLine;
			$config_script .= $tabOne."}".$newLine;

			$config_script .= "?>".$newLine;

			*/

			
			// ��Ű�� ���� ���� 
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
					echo CONFIG_DIR_NAME.D_S.SQL_DIR_NAME." ��Ű�� ���� �����Ϸ�<br>";
				}
				fclose($fp);
			}else{
				die("[ERROR]ȯ�漳�� ���丮 ���� ����!");
			}

			// �����ͺ��̽� ����
			$objDB = new Database_Connect("mysqli://root@localhost/tdms");
			$objDB->setQuery($element_scheme);
			if($objDB->executeQuery()){
				echo "�����ͺ��̽� ����<br>";
			}else{
				echo "�����ͺ��̽� ���� ����<br>";
			}

			// �⺻ ���������� ����
			$elementPagesPath = LOGIC_ROOT.D_S.ELEMENTS.D_S.$_POST["element_name"].D_S.PAGES_DIR_NAME;
			$this->makeAndCheckPath($elementPagesPath);
			if(file_exists($elementPagesPath)){
				$sourcePageDir = CORE_ROOT.D_S."element".D_S.PAGES_DIR_NAME;
				$targetPageDir = $elementPagesPath;
				$arrSkelFile = array("Plist.php","Pinsert.php","Pview.php","Pupdate.php","Pdelete.php");
				foreach($arrSkelFile as $value){
					if (!copy($sourcePageDir.D_S.$value, $targetPageDir.D_S.$value)) {
						die($value." ���� ���� ����!\n");
					}else{
						echo PAGES_DIR_NAME.D_S.$value." ���� �����Ϸ�<br>";
					}
				}
			}else{
				die("[ERROR]������ ���丮 ���� ����!");
			}


			// ��Ʈ�ѷ� ����
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
					echo "element".D_S.$_POST["element_name"].".php"." ��Ʈ�ѷ� ���� �����Ϸ�<br>";
				}
				fclose($fp);
				fclose($fp1);


			}else{
				die("[ERROR]ȯ�漳�� ���丮 ���� ����!");
			}

		}
		echo "<a href='/ADMIN/element/Plist'>����Ʈ�� ���ư���</a>";
	
	}
	
	// ��θ� �Է��ϸ� Ȯ���ϰ� ������ ������ش�.
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
					echo '���丮�� �������� �ʾҽ��ϴ�';
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
			echo "[ERROR]�÷����� ���丮�� Ȯ�����ּ���!";
		}

		$this->display(array("COMPONENT_LISTS"=>$arrComponentList));
	}
}

	?>