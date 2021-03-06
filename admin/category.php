<?php
	require_once '../util.php';
	require_once '../db_helper.php';
	require_once 'check_login.php';

	$action = isset($_REQUEST['q']) ? $_REQUEST['q'] : 'LIST';
	
	if($action=='LIST'){
// 		$result = getAllRows(getDBInstance(), 'categories', 'name');
		$db = getDBInstance();
		$sql = "select * from categories order by seq;";
		$result = $db->get_results ( $sql );
		
		$smarty = getSmartyInstance();
		$smarty->assign('categories',$result);
		$smarty->assign('menu_current','TAB_CATEGORY');
		$smarty->display('admin/category.html');
	}elseif ($action == 'EDIT') {
		$smarty = getSmartyInstance();
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
			$db = getDBInstance();
			$c = getRowById($db, 'categories', $id);
			$smarty->assign('category',$c);
			$smarty->assign('type','UPDATE');
		}else{
			$smarty->assign('type','NEW');
		}
		$smarty->assign('menu_current','TAB_CATEGORY');
		$smarty->display('admin/category_edit.html');
	}elseif ($action == 'SAVE') {
		if(!isset($_REQUEST['type'])){
			message('No such action type!');
			redirect('category.php');
		}
		
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
		$type = $_REQUEST['type'];
		if (get_magic_quotes_gpc()) {
			$name = $_REQUEST['name'];		
			$desc = $_REQUEST['desc'];
		}else{
			$name = addslashes($_REQUEST['name']);		
			$desc = addslashes($_REQUEST['desc']);
		}
		$promote = intval($_REQUEST['promote']);
		$create_by = $_SESSION['user']->id;
		$db = getDBInstance();
		saveCateogry($db,$type, $id, $name, $desc, $create_by,$promote);
		$db->debug();
		$msg = $_REQUEST['type'] == 'UPDATE' ? 'Update the record successfully!' : 'Add the record successfully!';
		message($msg);
		redirect('category.php');
	}elseif ($action == 'DELETE') {
		$id = $_REQUEST['id'];
		$db = getDBInstance();
		saveCateogry($db, 'DELETE', $id, null , null, null);
		echo json_encode(array('flag' => 0));
	}elseif($action=='MOVE'){
		$id = $_REQUEST['id'];
		$type = $_REQUEST['type'];
		$db = getDBInstance();
		$current = getRowById($db, 'categories', $id);
		if($type=='UP'){
			$sql = "select * from categories where seq < '".$current->seq."' order by seq desc limit 1";
		}else{
			$sql = "select * from categories where seq >'".$current->seq."' order by seq limit 1;";
		}
		$object = $db->get_row($sql);
		
		if($object){
			$sql1 = "update categories set seq = ".$current->seq." where id=".$object->id.";";
			$db->query($sql1);
			$sql2 = "update categories set seq = ".$object->seq." where id=".$current->id.";";
			$db->query($sql2);
		}
		echo json_encode(array('flag'=> 0));
	}else{
		message('No such action!');
		redirect('category.php');
	}

?>