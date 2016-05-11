<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends BaseController {
	/*
	 * 后台用户
	 * */
	public function admin() {
		$adminUser = D('AdminUserView');
		$this -> assign('table', $adminUser -> select());
		$this -> display();
	}
	/*
	 * 后台用户详细信息
	 * */
	public function admincard($id=''){
		if(!empty($id)){
			$adminUser = D('AdminUserView');
			$table = $adminUser->where('id=%d',$id)->select();
			$this->assign('id',$id);
			$this->assign('user_id',$table[0]['user_id']);
			$this->assign('img',$table[0]['img']);
			$this->assign('uname',$table[0]['uname']);
			$this->assign('begintime',$table[0]['begintime']);
			$this->assign('ip',$table[0]['ip']);
			$this->display();
		}
	}
	public function bosscard($id=''){
	    if(!empty($id)){
	        $bossUser = D('BossUserView');
	        $table = $bossUser->where('id=%d',$id)->select();
	        $this->assign('id',$id);
			$this->assign('user_id',$table[0]['user_id']);
	        $this->assign('img',$table[0]['img']);
	        $this->assign('uname',$table[0]['uname']);
	        $this->assign('begintime',$table[0]['begintime']);
	        $this->assign('ip',$table[0]['ip']);
	        $this->display();
	    }
	}
	public function homecard($id=''){
	    if(!empty($id)){
	        $homeUser = D('HomeUserView');
	        $table = $homeUser->where('id=%d',$id)->select();
	        $this->assign('id',$id);
			$this->assign('user_id',$table[0]['user_id']);
	        $this->assign('img',$table[0]['img']);
	        $this->assign('uname',$table[0]['uname']);
	        $this->assign('number',$table[0]['number']);
			$this->assign('phone',$table[0]['phone']);
	        $this->assign('address',$table[0]['address']);
	        $this->assign('begintime',$table[0]['begintime']);
	        $this->assign('ip',$table[0]['ip']);
	        $this->display();
	    }
	}
	public function staffcard($id=''){
	    if(!empty($id)){
	        $staffUser = D('StaffUserView');
	        $table = $staffUser->where('id=%d',$id)->select();
	        $this->assign('id',$id);
			$this->assign('user_id',$table[0]['user_id']);
	        $this->assign('img',$table[0]['img']);
	        $this->assign('uname',$table[0]['uname']);
	        $this->assign('number',$table[0]['number']);
	        $this->assign('address',$table[0]['address']);
	        $this->assign('phone',$table[0]['phone']);
	        $this->assign('begintime',$table[0]['begintime']);
	        $this->assign('ip',$table[0]['ip']);
	        $this->display();
	    }
	}

	/*
	 * 后台用户表服务器处理
	 * */
	public function admintable() {
		$adminUser = D('AdminUserView');
		//获取Datatables发送的参数 必要
		$draw = I('get.draw');//这个值作者会直接返回给前台

		//排序
		$order_column = I('get.order')['0']['column'];//那一列排序，从0开始
		$order_dir = I('get.order')['0']['dir'];//ase desc 升序或者降序
		//拼接排序sql
		$orderSql = "";
		if (isset($order_column)) {
			$i = intval($order_column);
			switch($i) {
				case 0 :$orderSql = " id " . $order_dir;break;
				case 1 :$orderSql = " uname " . $order_dir;break;
				case 2 :$orderSql = " begintime " . $order_dir;break;
				case 3 :$orderSql = " ip " . $order_dir;break;
				default :$orderSql = '';
			}
		}

		//搜索
		$search = $_GET['search']['value'];//获取前台传过来的过滤条件
		//分页
		$start = $_GET['start'];//从多少开始
		$length = $_GET['length'];//数据长度
		//表的总记录数 必要
		$recordsTotal = $adminUser->count();

		$map['id|uname|begintime|ip']=array('like',"%".$search."%");
		if(strlen($search)>0){
			$recordsFiltered = count($adminUser->where($map)->select());
			$table = $adminUser->where($map)->order($orderSql)->limit($start.','.$length)->select();
		}else{
			$recordsFiltered = $recordsTotal;
			$table = $adminUser->order($orderSql)->limit($start.','.$length)->select();
		}
		
		$infos = array();
		foreach($table as $row){
			$obj = array($row['id'],$row['uname'],$row['begintime'],$row['ip']);
			array_push($infos,$obj);
		}
		
		$this->ajaxReturn(array(
    		"draw" => intval($draw),
   			"recordsTotal" => intval($recordsTotal),
    		"recordsFiltered" => intval($recordsFiltered),
    		"data" => $infos
		));
	}

	public function bosstable() {
	    $bossUser = D('BossUserView');
	    //获取Datatables发送的参数 必要
	    $draw = I('get.draw');//这个值作者会直接返回给前台
	
	    //排序
	    $order_column = I('get.order')['0']['column'];//那一列排序，从0开始
	    $order_dir = I('get.order')['0']['dir'];//ase desc 升序或者降序
	    //拼接排序sql
	    $orderSql = "";
	    if (isset($order_column)) {
	        $i = intval($order_column);
	        switch($i) {
	            case 0 :$orderSql = " id " . $order_dir;break;
	            case 1 :$orderSql = " uname " . $order_dir;break;
	            case 2 :$orderSql = " begintime " . $order_dir;break;
	            case 3 :$orderSql = " ip " . $order_dir;break;
	            default :$orderSql = '';
	        }
	    }
	
	    //搜索
	    $search = $_GET['search']['value'];//获取前台传过来的过滤条件
	    //分页
	    $start = $_GET['start'];//从多少开始
	    $length = $_GET['length'];//数据长度
	    //表的总记录数 必要
	    $recordsTotal = $bossUser->count();
	
	    $map['id|uname|begintime|ip']=array('like',"%".$search."%");
	    if(strlen($search)>0){
	        $recordsFiltered = count($bossUser->where($map)->select());
	        $table = $bossUser->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $bossUser->order($orderSql)->limit($start.','.$length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['id'],$row['uname'],$row['begintime'],$row['ip']);
	        array_push($infos,$obj);
	    }
	
	    $this->ajaxReturn(array(
	        "draw" => intval($draw),
	        "recordsTotal" => intval($recordsTotal),
	        "recordsFiltered" => intval($recordsFiltered),
	        "data" => $infos
	    ));
	}
	public function hometable() {
	    $homeUser = D('HomeUserView');
	    //获取Datatables发送的参数 必要
	    $draw = I('get.draw');//这个值作者会直接返回给前台
	
	    //排序
	    $order_column = I('get.order')['0']['column'];//那一列排序，从0开始
	    $order_dir = I('get.order')['0']['dir'];//ase desc 升序或者降序
	    //拼接排序sql
	    $orderSql = "";
	    if (isset($order_column)) {
	        $i = intval($order_column);
	        switch($i) {
	            case 0 :$orderSql = " id " . $order_dir;break;
	            case 1 :$orderSql = " uname " . $order_dir;break;
	            case 2 :$orderSql = " number " . $order_dir;break;
				case 3 :$orderSql = " phone " . $order_dir;break;
				case 4 :$orderSql = " address " . $order_dir;break;
	            case 5 :$orderSql = " begintime " . $order_dir;break;	            
	            default :$orderSql = '';
	        }
	    }
	
	    //搜索
	    $search = $_GET['search']['value'];//获取前台传过来的过滤条件
	    //分页
	    $start = $_GET['start'];//从多少开始
	    $length = $_GET['length'];//数据长度
	    //表的总记录数 必要
	    $recordsTotal = $homeUser->count();
	
	    $map['id|uname|number|phone|address|begintime']=array('like',"%".$search."%");
	    if(strlen($search)>0){
	        $recordsFiltered = count($homeUser->where($map)->select());
	        $table = $homeUser->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $homeUser->order($orderSql)->limit($start.','.$length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['id'],$row['uname'],$row['number'],$row['phone'],$row['address'],$row['begintime']);
	        array_push($infos,$obj);
	    }
	
	    $this->ajaxReturn(array(
	        "draw" => intval($draw),
	        "recordsTotal" => intval($recordsTotal),
	        "recordsFiltered" => intval($recordsFiltered),
	        "data" => $infos
	    ));
	}
	public function stafftable() {
	    $staffUser = D('StaffUserView');
	    //获取Datatables发送的参数 必要
	    $draw = I('get.draw');//这个值作者会直接返回给前台
	
	    //排序
	    $order_column = I('get.order')['0']['column'];//那一列排序，从0开始
	    $order_dir = I('get.order')['0']['dir'];//ase desc 升序或者降序
	    //拼接排序sql
	    $orderSql = "";
	    if (isset($order_column)) {
	        $i = intval($order_column);
	        switch($i) {
	            case 0 :$orderSql = " id " . $order_dir;break;
	            case 1 :$orderSql = " uname " . $order_dir;break;
	            case 2 :$orderSql = " number " . $order_dir;break;
	            case 3 :$orderSql = " address " . $order_dir;break;
	            case 4 :$orderSql = " phone " . $order_dir;break;
	            case 5 :$orderSql = " begintime " . $order_dir;break;
	            default :$orderSql = '';
	        }
	    }
	
	    //搜索
	    $search = $_GET['search']['value'];//获取前台传过来的过滤条件
	    //分页
	    $start = $_GET['start'];//从多少开始
	    $length = $_GET['length'];//数据长度
	    //表的总记录数 必要
	    $recordsTotal = $staffUser->count();
	
	    $map['id|uname|number|address|phone|begintime']=array('like',"%".$search."%");
	    if(strlen($search)>0){
	        $recordsFiltered = count($staffUser->where($map)->select());
	        $table = $staffUser->where($map)->order($orderSql)->limit($start.','.$length)->select();
	    }else{
	        $recordsFiltered = $recordsTotal;
	        $table = $staffUser->order($orderSql)->limit($start.','.$length)->select();
	    }
	
	    $infos = array();
	    foreach($table as $row){
	        $obj = array($row['id'],$row['uname'],$row['number'],$row['address'],$row['phone'],$row['begintime']);
	        array_push($infos,$obj);
	    }
	
	    $this->ajaxReturn(array(
	        "draw" => intval($draw),
	        "recordsTotal" => intval($recordsTotal),
	        "recordsFiltered" => intval($recordsFiltered),
	        "data" => $infos
	    ));
	}
	
	/*
	 * 添加后台用户
	 * */
    public function adminadd() {
		$User = D('User', 'Logic');
		if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
		$data = array();
		$data['ip'] = get_client_ip();
		$data['img'] = '/Images/User/default.png';
		$data['uname'] = I('post.uname');
		$data['password'] = I('post.password');
		
		if(empty($data['ip'])||empty($data['img'])||empty($data['uname'])||empty($data['password'])){
			$this -> ajaxReturn("数据为空");
		}
		if ($User -> adminadd($data)) {
			$this -> ajaxReturn(TRUE);
		} else {
			$msg = "用户名已存在或认证失败";
			$this -> ajaxReturn($msg);
		}
	}
	public function bossadd() {
	    $User = D('User', 'Logic');
	    if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
	    $data = array();
	    $data['ip'] = get_client_ip();
	    $data['img'] = '/Images/User/default.png';
	    $data['uname'] = I('post.uname');
	    $data['password'] = I('post.password');
	    if ($User -> bossadd($data)) {
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "用户名已存在或认证失败";
	        $this -> ajaxReturn($msg);
	    }
	}
	public function homeadd() {
	    $User = D('User', 'Logic');
	    if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
	    $data = array();
	    $data['ip'] = get_client_ip();
		$data['img'] = '/Images/User/default.png';
	    $data['uname'] = I('post.uname');
	    $data['password'] = I('post.password');
	    $data['number'] = I('post.number');
		$data['phone'] = I('post.phone');
	    $data['address'] = I('post.address');
	    if ($User -> homeadd($data)) {
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "用户名已存在或认证失败";
	        $this -> ajaxReturn($msg);
	    }
	}
	public function staffadd() {
	    $User = D('User', 'Logic');
	    if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
	    $data = array();
	    $data['ip'] = get_client_ip();
		$data['img'] = '/Images/User/default.png';
	    $data['uname'] = I('post.uname');
	    $data['password'] = I('post.password');
	    $data['number'] = I('post.number');
	    $data['phone'] = I('post.phone');
	    $data['address'] = I('post.address');
	    if ($User -> staffadd($data)) {
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "用户名已存在或认证失败";
	        $this -> ajaxReturn($msg);
	    }
	}
	/*
	 * 修改Admin用户
	 * */
	public function adminedit() {
	    $User = D('User', 'Logic');
	    if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
	    $data = array();
	    $data['id'] = I('post.id');
		$data['user_id'] = I('post.user_id');
//	    $data['ip'] = get_client_ip();
//	    $data['img'] = '/Images/User/default.png';
	    $data['uname'] = I('post.uname');
	    $data['password'] = I('post.password');
	    if ($User -> adminedit($data)) {
	        $msg = "修改成功！";
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "修改失败！";
	        $this -> ajaxReturn(FALSE);
	    }
	}
	public function bossedit() {
	    $User = D('User', 'Logic');
	    if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
	    $data = array();
	    $data['id'] = I('post.id');
		$data['user_id'] = I('post.user_id');
//	    $data['img'] = '/Images/User/default.png';
	    $data['uname'] = I('post.uname');
	    $data['password'] = I('post.password');
	    if ($User -> bossedit($data)) {
	        $msg = "修改成功！";
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "修改失败！";
	        $this -> ajaxReturn(FALSE);
	    }
	}
	public function homeedit() {
	    $User = D('User', 'Logic');
	    if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
	    $data = array();
	    $data['id'] = I('post.id');	
		$data['user_id'] = I('post.user_id');   
//	    $data['img'] = '/Images/User/default.png';
	    $data['uname'] = I('post.uname');
	    $data['password'] = I('post.password');
	    $data['number'] = I('post.number');
		$data['phone'] = I('post.phone');
	    $data['address'] = I('post.address');
	    if ($User -> homeedit($data)) {
	        $msg = "修改成功！";
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "修改失败！";
	        $this -> ajaxReturn(FALSE);
	    }
	}
	public function staffedit() {
	    $User = D('User', 'Logic');
	    if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
	    $data = array();
	    $data['id'] = I('post.id');
		$data['user_id'] = I('post.user_id');
//	    $data['img'] = '/Images/User/default.png';
	    $data['uname'] = I('post.uname');
	    $data['password'] = I('post.password');
	    $data['number'] = I('post.number');
	    $data['address'] = I('post.address');
	    $data['phone'] = I('post.phone');
	    if ($User -> staffedit($data)) {
	        $msg = "修改成功！";
	        $this -> ajaxReturn(TRUE);
	    } else {
	        $msg = "修改失败！";
	        $this -> ajaxReturn(FALSE);
	    }
	}

}
