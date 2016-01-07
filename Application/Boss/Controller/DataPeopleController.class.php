<?php
namespace Boss\Controller;
use Think\Controller;
class DataPeopleController extends Controller {
    public function index(){
        $this->display();
    }
	public function _empty($name){
		echo "Not Found!";
    }
}