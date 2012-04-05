<?
class CategoryManage{

	var $arrBigCategory;
	var $arrMediumCategory;
	var $arrGroupField;


	function CategoryManage( $arrMediumCategory,$arrGroupField){
		$this->arrMediumCategory	=	$arrMediumCategory;
		$this->arrGroupField		=	$arrGroupField;

	}

	// 키값을 입력받아 존재하는지 여부를 리턴한다.
	// 없으면 false를 리턴한다.
	//(키값을 입력받음)

	function getBCSearchKeyToBool($key){
		return array_key_exists( $key, $this->arrGroupField);
	}

	// 대분류의 키값을 입력받아 그 키값에 속하는 
	// 중분류 테이블을 배열값으로 리턴한다.(키값을 입력받음)
	function getBCKeyToMCArrField($key){
		return $this->arrGroupField[$key][1];
	}

	//키값을 입력받아 해당 키에 이름을 리턴한다.
	// 없으면 false를 리턴한다.
	//(키값을 입력받음)
	function getBCSearchName($key){
		return $this->arrGroupField[$key][0];
	}



	//중분류의 코드(테이블명)을 입력받아 그 이름을 리턴한다.
	// 없으면 false를 리턴한다.
	//(키값을 입력받음)
	function getMCKeyToMCName($key){
		$arrTbInfo = $this->arrMediumCategory[$key];
		return $arrTbInfo[0];
	}


	//키값을 입력받아 해당 키에 이름을 리턴한다.
	// 없으면 false를 리턴한다.
	//(키값을 입력받음)
	function getMCSearchName($key){
		return $this->arrMediumCategory[$key][0];
	}
}
?>