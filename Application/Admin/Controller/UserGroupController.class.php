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
	 * 后台用户组页面
	 * */
	public function admincard($id=0){
		if($id){
			$UserGroup = D('UserGroup');
			$menu = M('Menu');
			$admin = $UserGroup->where('group_id = %d ',$id)->find();
			$adminmenu = $menu->where('group_id = 1 and status = 1')->select();
			$this->assign('admin',$admin);
			$this->assign('adminmenu',$adminmenu);
			$this->display();
		}
	}
}