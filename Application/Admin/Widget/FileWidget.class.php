<?php
namespace Admin\Widget;
use Think\Controller;
class FileWidget extends Controller {
    public function index(){
        $this->display('File:index');
    }
}