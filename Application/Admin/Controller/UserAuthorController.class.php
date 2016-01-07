<?php
namespace Admin\Controller;
use Think\Controller;
class UserAuthorController extends BaseController {
    public function home(){
        $this->display();
    }
	public function staff(){
        $this->display();
    }
	public function boss(){
        $this->display();
    }
	public function admin(){
        $this->display();
    }
}