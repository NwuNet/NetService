<?php
namespace Admin\Model;
use Think\Model;
/**
 * 用户组
 */

class UserGroupModel extends Model{
	
	protected $_validate = array(
		array('gname', array(7,15), 'ip格式错误', 3, 'length') //验证ip地址长度
	);
	
	protected $_auto = array(
		array('level', array(1,4), '模块错误', 3, 'length')
	);
	/*
	 * 添加
	 * */
	 public function plus($data){
	 	if($this->where('gname = "%s" ',$data['gname'])->find()) return FALSE;
	 	$this -> gname = $data['gname'];
		$this -> level = $data['level'];
		$this -> status = 1;
		if($this -> add()) return TRUE;
		return FALSE;
	 }
	 /*
	 * 修改
	 * */
	 public function edit($data){
	 	$map['gname'] = $data['gname'];//判断组名是否重复
		$map['group_id'] = array('neq',$data['id']); //不相等的id
	 	if($this->where($map)->find()) return FALSE;
		$this -> group_id = $data['id'];
	 	$this -> gname = $data['gname'];
		$this -> level = $data['level'];
		$this -> status = $data['status'];
		if($this -> save()) return TRUE;
		return FALSE;
	 }
}
