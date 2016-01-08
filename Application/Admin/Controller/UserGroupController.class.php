<?php
namespace Admin\Controller;
use Think\Controller;
class UserGroupController extends BaseController {
	/*
	 * 权限页面加载
	 * */
    public function index(){
    	$UserGroup = D('UserGroup');
		$admin = $UserGroup->where("level = 1")->select();
		$boss = $UserGroup->where("level = 2")->select();
		$home = $UserGroup->where("level = 3")->select();
		$staff = $UserGroup->where("level = 4")->select();
		$this->assign('admin',$admin);
		$this->assign('boss',$boss);
		$this->assign('home',$home);
		$this->assign('staff',$staff);
        $this->display();
    }
	/*
	 * 添加用户组
	 * */
	public function add(){
		$UserGroup = D('UserGroup');
		$data = array();
		$data['gname'] = I('post.gname');
		$level = I('post.level');
		switch($level){
			case 'Admin':$data['level'] = 1;break;
			case 'Boss':$data['level'] = 2;break;
			case 'Home':$data['level'] = 3;break;
			case 'Staff':$data['level'] = 4;break;
		}
		if(empty($data['gname']) || empty($data['level'])) {
			$this -> ajaxReturn("数据为空");
		}
		if($UserGroup->plus($data)) {
			$this -> ajaxReturn(TRUE);
		}else{
			$msg = "添加失败";
			$this -> ajaxReturn($msg);
		}
	}
	/*
	 * 修改用户组
	 * */
	public function edit(){
		$UserGroup = D('UserGroup');
		$data = array();
		$data['id'] = I('post.id');
		$data['gname'] = I('post.gname');
		$data['status'] = I('post.status');
		$level = I('post.level');
		switch($level){
			case 'Admin':$data['level'] = 1;break;
			case 'Boss':$data['level'] = 2;break;
			case 'Home':$data['level'] = 3;break;
			case 'Staff':$data['level'] = 4;break;
		}
		trace(I('post.status'));
		if(empty($data['gname']) || empty($data['level']) ||empty($data['id'])) {
			$this -> ajaxReturn("数据为空");
		}
		if($UserGroup->edit($data)) {
			$this -> ajaxReturn(TRUE);
		}else{
			$msg = "修改失败";
			$this -> ajaxReturn($msg);
		}
	}
	/*
	 * admin后台用户组页面
	 * */
	public function admincard($id=0){
		if($id){
			$UserGroup = D('UserGroup');
			$menu = M('Menu');
			$admin = $UserGroup->where('group_id = %d and level =1',$id)->find();
			$adminmenu = $menu->where('group_id = 1 and status = 1')->select();
			$this->assign('admin',$admin);
			$this->assign('adminmenu',$adminmenu);
			if(empty($admin)||empty($adminmenu)){
				$this->_empty();
			}else{
				$this->display();
			}
		}
	}
	/*
	 * boss管理用户组页面
	 * */
	public function bosscard($id=0){
		if($id){
			$UserGroup = D('UserGroup');
			$menu = M('Menu');
			$boss = $UserGroup->where('group_id = %d and level = 2',$id)->find();
			$bossmenu = $menu->where('group_id = 2 and status = 1')->select();
			$this->assign('boss',$boss);
			$this->assign('bossmenu',$bossmenu);
			if(empty($boss)||empty($bossmenu)){
				$this->_empty();
			}else{
				$this->display();
			}
		}
	}
	/*
	 * staff员工用户组页面
	 * */
	public function staffcard($id=0){
		if($id){
			$UserGroup = D('UserGroup');
			$menu = M('Menu');
			$staff = $UserGroup->where('group_id = %d and level = 4',$id)->find();
			$staffmenu = $menu->where('group_id = 4 and status = 1')->select();
			$this->assign('staff',$staff);
			$this->assign('staffmenu',$staffmenu);
			if(empty($staff)||empty($staffmenu)){
				$this->_empty();
			}else{
				$this->display();
			}
		}
	}
	/*
	 * 空页面
	 * */
	public function _empty($name){
		echo "Not Found!";
    }
}