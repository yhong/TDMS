<?
//if(USE_SESSION != "true" || USE_SESSION != "TRUE"){
//	session_destroy ();
//}
/*
*     - �ܺ� ���̺귯�� ����(ext_lib, ext_lib/pear) -    
*	Connect			:�����ͺ��̽� ����
*	Manage			: �����ͺ��̽� ����
*/
//$arrExtlib = explode(PATH_SEPARATOR, ini_get('include_path'));
//$arrExtlib = array_flip(array_flip((array_merge(array(CORE_EXT_LIB_ROOT, CORE_EXT_LIB_ROOT.D_S."pear".D_S."DB"), $arrExtlib))));
//$arrExtlib = array_flip(array_flip((array_merge(array(CORE_EXT_LIB_ROOT, CORE_EXT_LIB_ROOT.D_S."pear"), $arrExtlib))));
ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT); 
ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT.D_S."pear"); 
ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT.D_S."pear".D_S."DB"); 
ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT.D_S."Xml"); 
//ini_set('include_path', ini_get('include_path').";".CORE_EXT_LIB_ROOT.D_S."pear".D_S."Archive"); 

// pear.DB����ϱ� ����
require_once("db.php");
require_once("XmlParser.php");
require_once("XmlWriter1.php");

//require_once("Zip.php");

/*
*       - �����ͺ��̽� Ŭ����(PEAR���� ���) -    
*	Connect			:�����ͺ��̽� ����
*	Manage			: �����ͺ��̽� ����
*/
LOAD_LIBRARY("Database/Connect",
			 "Database/Manage"
			);

/*
*       - DBTable Ŭ���� -    
*	TableForm		: db����� ���̺�(Manage ��� & table���� �߰�)
*	PageTableManage	: TableForm ���
*	TableDataList	: ���̺� ����κ� �߰�(DBTableManage ���)
*/
LOAD_LIBRARY("DBTable/TableForm",
			 "DBTable/PageTableForm",
			 "DBTable/TableDataList",
			 "DBTable/TableMultiList"
			);
/*
*      - Board Ŭ���� -    
*	impComponentBlock	: ������Ʈ ���� �������̽�
*	TableMultiList		: �˻� ����� ����(���հ˻��� DBTableMultiList Ŭ������ ����)
*	AccessForm			: board�� ����ϴ� �� ��ɿ� ���õ� ���Ŭ����
*	impAccessFormView	: ���� �����ִ� �κ��� ������ �������̽�
*	AccessInsertForm	: ���� �Է�
*	AccessSelectForm	: ������ �����ֱ�
*	AccessUpdateForm	: ������ ����
*	AccessDeleteForm	: ������ �����
*	AccessConductForm	: ȸ�Ű��
*	AccessSearch	: ����Ʈ �˻���� insert���� ���
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
*      - ������Ʈ �� Ŭ����(�� ����Ŭ������ �ڵ� ��Ŭ��� �ȴ�) -    
*	impComponentBlock	: ������Ʈ ���� �������̽�
*	impComponentBlock	: ������Ʈ ���� ���� Ŭ����
*/
LOAD_LIBRARY(COMPONENT_BLOCK.D_S."impComponentBlock",
			 COMPONENT_BLOCK.D_S.COMPONENT_BLOCK);

/*
*      - Pager Ŭ���� -    
*/
LOAD_LIBRARY("Pager/Pager");

/*
*      - Auth Ŭ���� -    
*/
LOAD_LIBRARY("Auth/Login");



  ///////////////////////////////////
 //      * ���հ˻� Ŭ���� *      //
///////////////////////////////////
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_DBTableMultiList.php"); // ���� ���̺��� �˻���� �����ֱ�(DBTableDataList ���)
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_DBTableMultiSearch.php"); //�ϰ��˻� ���(�������� �������� �˻�)

  /////////////////////////////////////
 //* db���̺� ��������� �߰� Ŭ���� *//
/////////////////////////////////////
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_DBTablePageList.php"); //DBTableSingleList ���(���հ˻����� ���)



  ///////////////////////////////////
 //      * ���籺������ Ŭ���� *     //
////////////////////////////////////
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_Simpage.php");

  ///////////////////////////////////
 //     * ī�װ� ���� Ŭ���� *     //
////////////////////////////////////
require_once(CORE_LIB_ROOT.D_S."TotalSearch/class_CategoryManage.php");


?>