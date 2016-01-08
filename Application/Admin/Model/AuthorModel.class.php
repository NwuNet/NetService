<?php
namespace Admin\Model;
use Think\Model;
/**
 * 用户
 */

class AuthorModel extends Model {

	/*
	 * 自动验证
	 * */
	protected $_validate = array( 
		array('group_id', 'require','group必须！'),
		array('menu_id', 'require','menu必须！'), 
		array('status', 'require','status必须！')
	);
	
	/*
	 * 自动完成
	 * */
	protected $_auto = array( 
		array('status', 1, 1) 
	);
	
	/*
	 * @ prama {array{group_id,menu_id}}
	 * @ return true/false
	 * 添加
	 * */
	 public function plus($data){
	 	$author = $this->where('group_id = %d and menu_id = %d',$data['group_id'],$data['menu_id'])->find();
	 	/*如果存在则令status=1*/
	 	if($author){
	 		$author['status'] = 1;
			if( $this -> save($author)) return TRUE;
			return FALSE;
	 	}else{
	 		$this -> group_id = $data['group_id'];
			$this -> menu_id = $data['menu_id'];
			$this -> status = 1;
			if( $this -> add()) return TRUE;
			return FALSE;
	 	}
	 }
	 /*
	 * @ prama {array{group_id,menu_id}}
	 * @ return true/false
	 * 删除
	 * */
	 public function del($data){
	 	$author = $this->where('group_id = %d and menu_id = %d',$data['group_id'],$data['menu_id'])->find();
	 	/*如果存在则令status=1*/
	 	if($author){
	 		$author['status'] = 0;
			if( $this -> save($author)) return TRUE;
	 	}
		return FALSE;
	 }
}
