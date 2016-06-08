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
	 	if($this->where('id = %d ',$data['id'])->find()) return FALSE;
//		 $this -> uname = $data['uname'];
		 if(strlen(md5($data['password'])) == strlen($data['password'])){
			 $this -> password = $data['password'];
		 }else{
			 $this -> password = md5($data['password']);
		 }
		 $data['begintime'] = date("Y-m-d H:i:s",NOW_TIME);
		 $data['status'] = 1;
//	 	$this -> user_id = $data['user_id'];
//	 	$this -> number = $data['number'];
//		$this -> phone = $data['phone'];
//	 	$this -> address = $data['address'];
//        $this -> img = $data['img'];
//		$this -> area= $data['area'];
		if($this -> add($data)) return TRUE;
		return FALSE;
	 }
	 /*
	  * 修改
	  * */
	 public function edit($data){
	     if(count($this->where('id = %d ',$data['id'] )->find())>0) {
			 if(strlen(md5($data['password'])) == strlen($data['password'])){
				 $this -> password = $data['password'];
			 }else{
				 $this -> password = md5($data['password']);
			 }
//			 $this -> uname = $data['uname'];
//			 $data['password'] = md5($data['password']);
//			 $this -> status = $data['status'];
//	         $this -> user_id = $data['user_id'];
//	         $this -> number = $data['number'];
//			 $this -> phone = $data['phone'];
//	         $this -> address = $data['address'];
//			 $this -> img = $data['img'];
//			 $this -> area= $data['area'];
	   
	         if($this -> save($data)) return TRUE;
	         return FALSE;
	     }
	 }
}
