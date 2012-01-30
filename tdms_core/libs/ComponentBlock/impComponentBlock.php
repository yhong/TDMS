<?

// 폼을 보여주는 부분
interface impComponentBlock{

	public function blockList();

	public function blockSelect();

	public function blockUpdate($id, $fieldName, $script, $opt);

	public function blockInsert($id, $fieldName, $script, $opt);

	public function blockSearch($id, $fieldName, $script, $opt);

	public function blockSearchCondition();
}

?>