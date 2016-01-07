<?php
namespace Boss\Controller;
use Think\Controller;
class SetPeopleController extends Controller {
    // --------------------考勤设置---------------------
    public function register(){
        $this->display();
    }
	// --------------------绩效设置---------------------
    public function evaluation(){
        $this->display();
    }
	// --------------------员工设置---------------------
    public function staff(){
        $this->display();
    }
	// --------------------排班设置---------------------
    public function schedule(){
        $this->display();
    }
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}