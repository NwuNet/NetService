<?php
namespace Home\Controller;
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
		if( !AUTHOR && MODULE_NAME <> 'Home' ){
			$this->redirect('Login/index');
		}
		$homeUser = M('HomeUser');
		$this -> assign('user', $homeUser->where('id = %d',AUTHOR)->find());
    }
}