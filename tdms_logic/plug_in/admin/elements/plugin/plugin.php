<?
Class plugin_plugin extends Element{
	private $Page;
	private $PageG;
	private $intStartNum;
	
	private $plist_link;

	private $how;
	private $how2;
	private $order;
	private $workYear;

	private $board_id;
	private $index;

	private $arrfldname;

	function plugin_plugin(){
		$this->layout = "main";

		if(!$_GET["Page"]){
			$this->Page = 1;  // ������ ��ȣ
		}else{
			$this->Page = $_GET["Page"];
		}
		if(!$_GET["PageG"]){ // ������ ����Ʈ ��ġ
			$this->PageG = 1;
		}else{
			$this->PageG = $_GET["PageG"];
		}
		if(!$_GET["intStartNum"]){
			$this->intStartNum = 0;
		}else{
			$_GET["intStartNum"] = $this->intStartNum;
		}

		if($_GET[how] == "desc"){
			$this->how = "asc";
			$this->how2 = "desc";
		}else{
			$this->how = "desc";
			$this->how2 = "asc";
		}

		if (!$_GET[order]){
			$this->order = "id";
			$this->how = "desc";
		}else{
			$this->order = $_GET[order];

		}	

		if (!$_GET["workYear"]){
			$this->workYear = date('Y');
		}else{
			$this->workYear = $_GET["workYear"];
		}

		$this->index = $_GET["index"];
		$this->board_id = $_GET["board_id"];

	}

	public function plugin_upload(){

	}


	public function plugin_extract(){

		$uploads_dir = WWW_ROOT.D_S.TEMP.D_S.'plugin_install';

		if($_FILES["plugin_file"]["error"] == UPLOAD_ERR_OK) {

				$tmp_name = $_FILES["plugin_file"]["tmp_name"];
				$name = $_FILES["plugin_file"]["name"];
				move_uploaded_file($tmp_name, $uploads_dir.D_S.$name);
				
		}

		LOAD_LIBRARY("Archive/Zip");


		$arrFilename = explode(".",$_FILES["plugin_file"]["name"]);
		
		if(file_exists(LOGIC_ROOT.D_S.PLUGIN.D_S.$arrFilename[0])){
			die('[ERROR]�̹� ��ġ�� �Ǿ����ϴ�');
		}else{
			if (file_exists($uploads_dir.D_S.$name)) {
				$objZip = new Archive_Zip($uploads_dir.D_S.$name, LOGIC_ROOT.D_S.PLUGIN.D_S.$arrFilename[0]);
				if($objZip->unzip()){
					$this->goLink("/".$this->plugin."/".$this->element."/Plist");
				}else{
					echo '��ġ�ÿ� ������ �߻��Ͽ����ϴ�.';
				}

			} else {
				die('������ �������� �ʽ��ϴ�.');
			}
		}

	}


	public function Plist(){
		$dir = LOGIC_ROOT.D_S.PLUGIN;
		
	
		$outputdata["header"][0]["key"] = "�Ϸù�ȣ";
		$outputdata["header"][1]["key"] = "�÷����� ��";
		$outputdata["header"][2]["key"] = "��ġ";
		$outputdata["header"][3]["key"] = "��ũ";
		//$outputdata["header"][4]["key"] = "����";

		$index = 0;
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($plugin_name = readdir($dh)) !== false) {
					if(filetype($dir .D_S. $plugin_name) == "dir" and $plugin_name != '.' and $plugin_name != '..'){

						$outputdata["rowdata"][$index][0] = $index+1;
						$outputdata["rowdata"][$index][1] = strtoupper($plugin_name);
						$outputdata["rowdata"][$index][2]= "[<a href='/".$this->plugin.D_S.$this->element.D_S.plugin_extract."?plugin=".$plugin_name."' target='_blank'>���̺� ����</a>]";
						$outputdata["rowdata"][$index][3]= "[<a href='".D_S.strtoupper($plugin_name)."' target='_blank'>��ũ</a>]";
						//$outputdata["rowdata"][$index][4]= "[<a href='".D_S.strtoupper($plugin_name).D_S."readLicense' target='_blank'>���̼���</a>|<a href='".D_S.strtoupper($plugin_name).D_S."readManual' target='_blank'>����</a>]";
						$index++;
					}
					
				}
				closedir($dh);
			}
		}else{
			echo "[ERROR]�÷����� ���丮�� Ȯ�����ּ���!";
		}


		

		$this->display(array("searchOutputData"=>$searchOutputData,
							 "listHeader"=>$outputdata["header"],
							 "outputData"=>$outputdata["rowdata"],
							 "outputSearchBoxData"=>$outputdata["searchbox"],
							 "pagelist"=>$outputdata["page"]));
	}
}

	?>