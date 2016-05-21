<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
	public function index() {
		if(D('Login','Service')->islogin()){
			$this->redirect('Home/User/index');
		}
		$this -> display();
	}
	/*
	 * 验证身份
	 * */
	public function verify() {
		$uname = I('post.uname');
		$password = I('post.password');
		$phone = I('post.phone');
		$verify = I('post.verify');
		if($uname==''|| $password==''|| $verify==''||$phone==''){
			$this->ajaxReturn('数据为空');
		}
		$HomeUser = D('HomeUser');
		$user = $HomeUser ->where('uname = "%s" and phone = "%s" and status =1',$uname,$phone)->find();
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
		$Verify->length = 4;
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
