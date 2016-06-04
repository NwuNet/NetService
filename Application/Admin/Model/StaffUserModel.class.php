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
			 $this -> id = $data['id'];//-----------------1
			 $this -> user_id = $data['user_id'];//-----------------2
			 $this -> cname = $data['cname'];//-----------------3
			 $this -> number = $data['number'];//-----------------4
			 $this -> address = $data['address'];//-----------------5
			 $this -> phone = $data['phone'];//-----------------6
			 $this -> img = $data['img'];//-----------------7
			 $this -> area = $data['area'];//-----------------8
			 $this -> yuanxi = $data['yuanxi'];//-----------------9
			 $this -> zhuanye = $data['zhuanye'];//-----------------10
	         if($this -> save()) return TRUE;
	         return FALSE;
	     }
	 }
}
