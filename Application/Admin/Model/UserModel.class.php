<?php
namespace Admin\Model;
use Think\Model;
/**
 * 用户
 */

class UserModel extends Model {

	/*
	 * 自动验证
	 * */
	protected $_validate = array( 
		array('uname', '', '帐号名称已经存在！', 0, 'unique', 1), // 在新增的时候验证name字段是否唯一
		array('begintime', 10, '时间戳错误', 0, 'length'), // 验证时间戳长度是否正常
		array('status', array(0, 1), '状态错误', 0, 'in') // 验证状态
	);
	
	/*
	 * 自动完成
	 * */
	protected $_auto = array( 
		array('password', 'md5', 3, 'function'), // 对password字段在新增和编辑的时候使md5函数处理
		array('begintime', 'time', 1, 'function'), // 对begin_time字段在新增的时候写入当前时间戳
		array('status', 1, 1) // 新增的时候把status字段设置为1
	);
	
	/*
	 * @ prama {array{uname,password}}
	 * @ return true/false
	 * User添加
	 * */
	 public function plus($data){
	 	if($this->where('uname = "%s"',$data['uname'])->find()) return FALSE;
		$this -> uname = $data['uname'];
		 if(strlen(md5($data['password'])) == strlen($data['password'])){
			 $this -> password = $data['password'];
		 }else{
			 $this -> password = md5($data['password']);
		 }
		$this -> begintime = date("Y-m-d H:i:s",NOW_TIME);
		$this -> status = 1;
		 $this -> level = $data['level'];
		if( $this -> add()) return TRUE;
		return FALSE;
	 }
	/*
	 * User修改
	 * */
	public function edit($data){
		$old = $this->where('user_id = %d',$data['user_id'])->find();
		if($old['uname'] =='') return false;
		$this -> user_id = $data['user_id'];
		$this -> uname = $data['uname'];
		if($data['password'] == ''){
			$this->password = $old['password'];
		}elseif(strlen(md5($data['password'])) == strlen($data['password'])){
			$this -> password = $data['password'];
		}else{
			$this -> password = md5($data['password']);
		}
		$this -> level = $data['level'];
		$this -> status = $data['status'];
		if($this -> save()) return true;
		return false;
	}
}
