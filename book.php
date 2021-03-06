<?php
	require_once 'util.php';
	require_once 'db_helper.php';

	$id = decode_and_int($_REQUEST['id']);
	$db = getDBInstance();
	
	$book = getRowById($db, 'books', $id);
//	$db->debug();
	$smarty = getSmartyInstance();
	$smarty->assign('book',$book);
	$sql = "select c.* from categories c, book_category bc where bc.book_id = $id and bc.category_id = c.id;";
	$categories = $db->get_results($sql);
//	$db->debug();
	$smarty->assign('categories',$categories);
	
	
	$type = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'VIEW';
	
	if($type == 'VIEW'){
		$smarty->display('book.html');
	}elseif($type == 'EDIT'){
		$all_categories = getAllRows($db, 'categories');
		if($book->pages){
			$aids = str_replace('|',',',$book->pages);
			$pages = getRowsByCondition($db, 'attachments', array("id in ($aids)"));
			
			$attachments = array();
			foreach (explode('|',$book->pages) as $aid){
				$attachments[$aid] = null;
			}
			
			foreach ($pages as $p){
				if(key_exists($p->id, $attachments)){
					$attachments[$p->id] = $p;
				}
			}
					
			unset($_SESSION['attachments']);
			$_SESSION['attachments'] = array();

			foreach ($attachments as $k=>$v){
				$_SESSION['attachments'][$k] = array('url'=>$v->file_url,'path'=>$v->file_path);
			}
		}else{
			$attachments = array();
		}
		
		$select_ids = array();
		if($categories){
			foreach ($categories as $c){
				$select_ids[] = $c->id;
			}
		}
			
		$smarty->assign('all_categories',$all_categories);
		$smarty->assign('select_ids',$select_ids);
		$smarty->assign('attachments',$attachments);
		$smarty->display('book_edit.html');
	}else{
		message('No such action!');
		redirect('index.php');
	}

?>