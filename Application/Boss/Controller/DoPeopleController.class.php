<?php
namespace Boss\Controller;
use Think\Controller;
class DoPeopleController extends Controller {
	// --------------------考勤信息---------------------
    public function register(){
        $this->display();
    }
	// --------------------招聘信息---------------------
    public function employ(){
        $this->display();
    }
	// --------------------绩效评分---------------------
    public function evaluate(){
        $this->display();
    }
	// --------------------绩效查询---------------------
    public function query(){
        $this->display();
    }
	// --------------------员工管理---------------------
    public function staff(){
        $this->display();
    }
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}