<?
//if(USE_SESSION != "true" || USE_SESSION != "TRUE"){
//	session_destroy ();
//}
/*
*     - 외부 라이브러리 포함(ext_lib, ext_lib/pear) -    
*	Connect			:데이터베이스 접속
*	Manage			: 데이터베이스 관리
*/
//$arrExtlib = explode(PATH_SEPARATOR, ini_get('include_path'));
//$arrExtlib = array_flip(array_flip((array_merge(array(CORE_EXT_LIB_ROOT, CORE_EXT_LIB_ROOT.D_S."pear".D_S."DB"), $arrExtlib))));
//$arrExtlib = array_flip(array_flip((array_merge(array(CORE_EXT_LIB_ROOT, CORE_EXT_LIB_ROOT.D_S."pear"), $arrExtlib))));
ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT); 
ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT.D_S."pear"); 
ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT.D_S."pear".D_S."DB"); 
ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT.D_S."Xml"); 
//ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT.D_S."pear".D_S."Archive"); 

// pear.DB사용하기 위함
require_once("db.php");
require_once("XmlParser.php");
require_once("XmlWriter1.php");

//require_once("Zip.php");

/*
*       - 데이터베이스 클래스(PEAR에서 상속) -    
*	Connect			:데이터베이스 접속
*	Manage			: 데이터베이스 관리
*/
LOAD_LIBRARY("Database/Connect",
			 "Database/Manage"
			);

/*
*       - DBTable 클래스 -    
*	TableForm		: db연결된 테이블(Manage 상속 & table관리 추가)
*	PageTableManage	: TableForm 상속
*	TableDataList	: 테이블 결과부분 추가(DBTableManage 상속)
*/
LOAD_LIBRARY("DBTable/TableForm",
			 "DBTable/PageTableForm",
			 "DBTable/TableDataList",
			 "DBTable/TableMultiList"
			);
/*
*      - Board 클래스 -    
*	impComponentBlock	: 컴포넌트 블럭의 인터페이스
*	TableMultiList		: 검색 결과값 보기(통합검색에 DBTableMultiList 클래스를 수정)
*	AccessForm			: board에 사용하는 각 기능에 관련된 기반클래스
*	impAccessFormView	: 폼을 보여주는 부분을 선언한 인터페이스
*	AccessInsertForm	: 새로 입력
*	AccessSelectForm	: 데이터 보여주기
*	AccessUpdateForm	: 데이터 수정
*	AccessDeleteForm	: 데이터 지우기
*	AccessConductForm	: 회신결과
*	AccessSearch	: 리스트 검색기능 insert에서 상속
*/
LOAD_LIBRARY(COMPONENT_BLOCK.D_S."impComponentBlock",
			 "Board/Form",
			 "Board/FormList",
			 "Board/impForm",
			 "Board/FormInsert",
			 "Board/FormSelect",
			 "Board/FormUpdate",
			 "Board/FormDelete"
			);

/*
*      - 컴포넌트 블럭 클래스(각 서브클래스는 자동 인클루드 된다) -    
*	impComponentBlock	: 컴포넌트 블럭의 인터페이스
*	impComponentBlock	: 컴포넌트 블럭의 메인 클래스
*/
LOAD_LIBRARY(COMPONENT_BLOCK.D_S."impComponentBlock",
			 COMPONENT_BLOCK.D_S.COMPONENT_BLOCK);

/*
*      - Pager 클래스 -    
*/
LOAD_LIBRARY("Pager/Pager");

/*
*      - Auth 클래스 -    
*/
LOAD_LIBRARY("Auth/Login");



  ///////////////////////////////////
 //      * 통합검색 클래스 *      //
///////////////////////////////////
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_DBTableMultiList.php"); // 여러 테이블의 검색결과 보여주기(DBTableDataList 상속)
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_DBTableMultiSearch.php"); //일괄검색 기능(여러명을 다중으로 검색)

  /////////////////////////////////////
 //* db테이블에 페이지기능 추가 클래스 *//
/////////////////////////////////////
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_DBTablePageList.php"); //DBTableSingleList 상속(통합검색에서 사용)



  ///////////////////////////////////
 //      * 유사군번관련 클래스 *     //
////////////////////////////////////
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_Simpage.php");

  ///////////////////////////////////
 //     * 카테고리 관리 클래스 *     //
////////////////////////////////////
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_CategoryManage.php");


?>