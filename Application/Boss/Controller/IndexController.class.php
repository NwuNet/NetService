<?php
namespace Boss\Controller;
use Think\Controller;
class IndexController extends BaseController{

    public function index(){
        $this->display();
    }

    public function _empty($name){
        echo "Not Found!";
    }
}