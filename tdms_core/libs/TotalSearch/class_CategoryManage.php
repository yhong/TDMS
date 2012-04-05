<?
class CategoryManage{

	var $arrBigCategory;
	var $arrMediumCategory;
	var $arrGroupField;


	function CategoryManage( $arrMediumCategory,$arrGroupField){
		$this->arrMediumCategory	=	$arrMediumCategory;
		$this->arrGroupField		=	$arrGroupField;

	}

	// Ű���� �Է¹޾� �����ϴ��� ���θ� �����Ѵ�.
	// ������ false�� �����Ѵ�.
	//(Ű���� �Է¹���)

	function getBCSearchKeyToBool($key){
		return array_key_exists( $key, $this->arrGroupField);
	}

	// ��з��� Ű���� �Է¹޾� �� Ű���� ���ϴ� 
	// �ߺз� ���̺��� �迭������ �����Ѵ�.(Ű���� �Է¹���)
	function getBCKeyToMCArrField($key){
		return $this->arrGroupField[$key][1];
	}

	//Ű���� �Է¹޾� �ش� Ű�� �̸��� �����Ѵ�.
	// ������ false�� �����Ѵ�.
	//(Ű���� �Է¹���)
	function getBCSearchName($key){
		return $this->arrGroupField[$key][0];
	}



	//�ߺз��� �ڵ�(���̺��)�� �Է¹޾� �� �̸��� �����Ѵ�.
	// ������ false�� �����Ѵ�.
	//(Ű���� �Է¹���)
	function getMCKeyToMCName($key){
		$arrTbInfo = $this->arrMediumCategory[$key];
		return $arrTbInfo[0];
	}


	//Ű���� �Է¹޾� �ش� Ű�� �̸��� �����Ѵ�.
	// ������ false�� �����Ѵ�.
	//(Ű���� �Է¹���)
	function getMCSearchName($key){
		return $this->arrMediumCategory[$key][0];
	}
}
?>