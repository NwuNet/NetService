<?php
namespace Admin\Model;
use Think\Model;
/**
 * 后台用户
 */

class StaffUserModel extends Model{
	
	protected $_validate = array( 
		array('ip', array(7,15), 'ip格式错误', 3, 'length') //验证ip地址长度
	);
	
	protected $_auto = array(
		array('ip', 'get_client_ip',3,'function')
	);
	/*
	 * 添加  key 9
	 * */
	 public function plus($data){
	 	if($this->where('user_id = %d ',$data['user_id'])->find()) return FALSE;
	 	$this -> user_id = $data['user_id'];//-----------------1
		 $this -> cname = $data['cname'];//-----------------2
	 	$this -> number = $data['number'];//-----------------3
	 	$this -> address = $data['address'];//-----------------4
	 	$this -> phone = $data['phone'];//-----------------5
		$this -> img = $data['img'];//-----------------6
		 $this -> area = $data['area'];//-----------------7
		 $this -> yuanxi = $data['yuanxi'];//-----------------8
		 $this -> zhuanye = $data['zhuanye'];//-----------------9
		
		if($this -> add()) return TRUE;
		return FALSE;
	 }
	 /*
	  * 修改
	  * */
	 public function edit($data){
	     if($this->where('user_id = %d ',$data['user_id'])->find()) {
			 $this -> id = $data['id'];
			 $this -> user_id = $data['user_id'];
			 $this -> cname = $data['cname'];
			 $this -> number = $data['number'];
			 $this -> address = $data['address'];
			 $this -> phone = $data['phone'];
			 $this -> img = $data['img'];
			 $this -> area = $data['area'];
			 $this -> yuanxi = $data['yuanxi'];
			 $this -> zhuanye = $data['zhuanye'];
	         if($this -> save()) return TRUE;
	         return FALSE;
	     }
	 }
}
