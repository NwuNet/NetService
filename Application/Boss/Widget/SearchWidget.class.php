<?php
namespace Admin\Widget;
use Think\Controller;
class SearchWidget extends Controller {
    public function index(){
        $this->display('Search:index');
    }
}