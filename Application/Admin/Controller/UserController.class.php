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
			$this->assign('img',$table[0]['img']);
			$this->assign('uname',$table[0]['uname']);
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

	/*
	 * 添加后台用户
	 * */
	public function adminadd() {
		$User = D('User', 'Logic');
		if($repassword != I('post.password')) $this -> ajaxReturn("确认密码失败");
		$data = array();
		$data['ip'] = get_client_ip();
		$data['img'] = '/Images/User/default.png';
		$data['uname'] = I('post.uname');
		$data['password'] = I('post.password');
		$repassword = I('post.repassword');
		if(empty($data['ip'])||empty($data['img'])||$data['uname']||$data['password']||$data['repassword']){
			$this -> ajaxReturn("数据为空");
		}
		if ($User -> adminadd($data)) {
			$this -> ajaxReturn(TRUE);
		} else {
			$msg = "用户名已存在或认证失败";
			$this -> ajaxReturn($msg);
		}
	}

}
