<?php
namespace Staff\Controller;
use Think\Controller;
class BaseController extends Controller {
	/**
	 * 控制器初始化
	 * */
    public function _initialize(){
    	define("AUTHOR", D('Login','Service')->islogin()); //认证登录
		if(empty(AUTHOR)){
			$this->redirect('Login/index');
		}
		if( !AUTHOR && MODULE_NAME <> 'Staff' ){
			$this->redirect('Login/index');
		}
		$staffUser = D('Admin/StaffUserView');
		$this -> assign('user', $staffUser->where('id = %d',AUTHOR)->find());
    }
}