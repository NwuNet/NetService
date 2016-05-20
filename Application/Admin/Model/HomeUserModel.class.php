<?php
namespace Admin\Model;
use Think\Model;
/**
 * 后台用户
 */

class HomeUserModel extends Model{
	
	protected $_validate = array( 
		array('ip', array(7,15), 'ip格式错误', 3, 'length') //验证ip地址长度
	);
	
	protected $_auto = array(
		array('ip', 'get_client_ip',3,'function')
	);
	/*
	 * 添加
	 * */
	 public function plus($data){
	 	if($this->where('user_id = %d ',$data['user_id'])->find()) return FALSE;
	 	$this -> user_id = $data['user_id'];
	 	$this -> number = $data['number'];
		$this -> phone = $data['phone'];
	 	$this -> address = $data['address'];
        $this -> img = $data['img'];
		$this -> area= $data['area'];
		if($this -> add()) return TRUE;
		return FALSE;
	 }
	 /*
	  * 修改
	  * */
	 public function edit($data){
	     if($this->where('user_id = %d ',$data['user_id'])->find()) {
	         $this -> user_id = $data['user_id'];
	         $this -> number = $data['number'];
			 $this -> phone = $data['phone'];
	         $this -> address = $data['address'];			 
	   
	         if($this -> save()) return TRUE;
	         return FALSE;
	     }
	     
	     
	 }
}
