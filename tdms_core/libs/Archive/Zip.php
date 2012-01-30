<?
// zip 압축관련 유틸(현재는 해제 기능만 가진다)
class Archive_Zip{
	private $sourceFile;
	private $targetDir;

	public function Archive_Zip($sourceFile, $targetDir){
		// zip인지 체크한다
		if(!eregi("\.(zip)$", $sourceFile)) {
			echo "[ERROR]zip파일이 아닙니다.";
			exit;
		}
		
		$this->sourceFile = $sourceFile;
		$this->targetDir = $targetDir;

		

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

	//$zipfile을 $unzip_path에 푼다. 하위 디렉토리까지 디렉토리 구조를 유지하면서 푼다.
	public function unzip()
	{
		$this->makeAndCheckPath($this->targetDir);
		$zipfile = $this->sourceFile;
		$unzip_path = $this->targetDir;

		$zip = zip_open($zipfile);
		if (!$zip)
			return false;

		while ($zip_entry = zip_read($zip)) 
		{
			if(!zip_entry_open($zip, $zip_entry, "r"))
				continue;

			$entry_name = zip_entry_name($zip_entry);
			$pos_last_slash = strrpos($entry_name, "/");

			if($pos_last_slash !== false)
			{
				$dir = substr($entry_name, 0, $pos_last_slash+1);
				$base = "$unzip_path/";
				foreach(explode("/", $dir) as $k)
				{
					$base .= "$k/";
					if(!file_exists($base))
						mkdir($base);
				}
			}

			$name = $unzip_path."/".zip_entry_name($zip_entry);

			if (!file_exists($name))
			{
				$fopen = fopen($name, "w");
				fwrite($fopen, zip_entry_read($zip_entry, zip_entry_filesize($zip_entry)), zip_entry_filesize($zip_entry));
			}
			zip_entry_close($zip_entry);
		}
		zip_close($zip);
		return true;
	} 
}
?>