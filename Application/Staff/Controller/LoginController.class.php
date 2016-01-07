<?php
namespace Staff\Controller;
use Think\Controller;
class LoginController extends Controller {
	public function index() {
		$this -> display();
	}

	public function verify_c() {
		ob_clean();
		$Verify = new \Think\Verify();
		$Verify -> entry();
	}

}
