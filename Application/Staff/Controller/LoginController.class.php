<?php
namespace Staff\Controller;
use Think\Controller;
class LoginController extends Controller {
	public function index() {
		$this -> display();
	}

	/*
	 * 验证身份
	 * */
	public function verify() {
		$uname = I('post.uname');
		$password = I('post.password');
		$verify = I('post.verify');
		if(empty($uname) || empty($password) || empty($verify)){
			$this->ajaxReturn(FALSE);
		}
		$StaffUser = D('Admin/StaffUserView');
		$user = $StaffUser ->where('uname = "%s" and status =1',$uname)->find();
		if(empty($user)){
			$this->ajaxReturn(FALSE);
		}
		if($user['password']<>md5($password)){
			$this->ajaxReturn(FALSE);
		}
		if($this -> check_c($verify)){
			$setlogin = D('Login','Service')->setlogin($user['id']);
			$this->ajaxReturn($setlogin);
		}
		$this->ajaxReturn(FALSE);
	}

	//验证码生成
	public function verify_c() {
		ob_clean();
		$Verify = new \Think\Verify();
		$Verify -> entry();
	}

	//验证码认证
	public function check_c($code, $id = "") {
		$verify = new \Think\Verify();
		return $verify -> check($code, $id);
	}

	/**
	 * 退出
	 * */
	public function logout(){
		if(D('Login','Service')->logout()){
			$this->redirect('Home/Index/index');
		}
	}

	public function _empty($name) {
		echo "Not Found!";
	}

}
