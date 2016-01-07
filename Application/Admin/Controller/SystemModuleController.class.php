<?php
namespace Admin\Controller;
use Think\Controller;
class SystemModuleController extends BaseController {
    public function index(){
    	$menu = M('Menu');
		$admin = $menu->where('group_id = 1')->select();
		$boss = $menu->where('group_id = 2')->select();
		$home = $menu->where('group_id = 3')->select();
		$staff = $menu->where('group_id = 4')->select();
		$this->assign('admin',$admin);
		$this->assign('boss',$boss);
		$this->assign('home',$home);
		$this->assign('staff',$staff);
        $this->display();
    }
}