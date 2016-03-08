<?php
namespace Boss\Controller;
use Think\Controller;
class SetAssetController extends BaseController {
    // --------------------工具---------------------
    public function tool(){
        $select = M('ToolSelect');
        $tool = $select->select();//获取数量
        $toolselect = $select->order('name')->limit(count($tool))->select();
        $this->assign('toolselect',$toolselect);
        $state = M('ToolState');
        $toolstate = $state->select();
        $this->assign('toolstate',$toolstate);
        $this->display();
    }
    // --------------------工具select添加和修改---------------------
    public function tooladd(){
        $name = I('post.name');//名称
        $brand = I('post.brand');//品牌
        $model = I('post.model');//型号
        if($name==''||$brand==''||$model==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $select = M('ToolSelect');
        $select->create();
        if($id==''){ //添加
            if($select->add()){
                $this->ajaxReturn(true);
            }else{
                $msg = '添加失败';
                $this->ajaxReturn($msg);
            }
        }else{  //修改
            if($select->save()){
                $this->ajaxReturn(true);
            }else{
                $msg = '修改失败';
                $this->ajaxReturn($msg);
            }
        }
    }
    // --------------------工具状态添加和修改---------------------
    public function toolstate(){
        if(I('post.name')==''||I('post.label')==''||I('post.icon')==''||I('post.status')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('ToolState');
        $state->create();
        if($id==''){ //添加
            if($state->add()){
                $this->ajaxReturn(true);
            }else{
                $msg = '添加失败';
                $this->ajaxReturn($msg);
            }
        }else{  //修改
            if($state->save()){
                $this->ajaxReturn(true);
            }else{
                $msg = '修改失败';
                $this->ajaxReturn($msg);
            }
        }
    }
    // --------------------工具状态添加和修改---------------------
    public function toolstateflip(){
        if(I('post.id')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('ToolState');
        $data = $state->where('id=%d',$id)->find();
        $data['status'] = $data['status']==1?0:1;
        if($state->save($data)){
            $this->ajaxReturn(true);
        }else{
            $msg = '修改失败';
            $this->ajaxReturn($msg);
        }
    }
	// --------------------耗材---------------------
	public function exhaust(){
        $select = M('ExhaustSelect');
        $other = $select->select();//获取数量
        $exhaustselect = $select->order('name')->limit(count($exhaust))->select();
        $this->assign('exhaustselect',$exhaustselect);
		$state = M('ExhaustState');
        $exhauststate = $state->select();
        $this->assign('exhauststate',$exhauststate);
        $this->display();
    }
	// --------------------耗材select添加和修改---------------------
    public function exhaustadd(){
        $name = I('post.name');//名称
        $brand = I('post.brand');//品牌
        $model = I('post.model');//型号
        if($name==''||$brand==''||$model==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $select = M('ExhaustSelect');
        $select->create();
        if($id==''){ //添加
            if($select->add()){
                $this->ajaxReturn(true);
            }else{
                $msg = '添加失败';
                $this->ajaxReturn($msg);
            }
        }else{  //修改
            if($select->save()){
                $this->ajaxReturn(true);
            }else{
                $msg = '修改失败';
                $this->ajaxReturn($msg);
            }
        }
    }
    // --------------------耗材状态添加和修改---------------------
    public function exhauststate(){
        if(I('post.name')==''||I('post.label')==''||I('post.icon')==''||I('post.status')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('ExhaustState');
        $state->create();
        if($id==''){ //添加
            if($state->add()){
                $this->ajaxReturn(true);
            }else{
                $msg = '添加失败';
                $this->ajaxReturn($msg);
            }
        }else{  //修改
            if($state->save()){
                $this->ajaxReturn(true);
            }else{
                $msg = '修改失败';
                $this->ajaxReturn($msg);
            }
        }
    }
    // --------------------耗材状态添加和修改---------------------
    public function exhauststateflip(){
        if(I('post.id')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('ExhaustState');
        $data = $state->where('id=%d',$id)->find();
        $data['status'] = $data['status']==1?0:1;
        if($state->save($data)){
            $this->ajaxReturn(true);
        }else{
            $msg = '修改失败';
            $this->ajaxReturn($msg);
        }
    }
	// --------------------设备---------------------
	public function device(){
        $this->display();
    }
	// --------------------证照---------------------
	public function paper(){
        $this->display();
    }
	// --------------------其他---------------------
	public function other(){
		$select = M('OtherSelect');
        $other = $select->select();//获取数量
        $otherselect = $select->order('name')->limit(count($other))->select();
        $this->assign('otherselect',$otherselect);
		$state = M('OtherState');
        $otherstate = $state->select();
        $this->assign('otherstate',$otherstate);
        $this->display();
        
    }
	// --------------------其他select添加和修改---------------------
    public function otheradd(){
        $name = I('post.name');//名称
        $campus = I('post.campus');//校区
        $room = I('post.room');//房间
        if($name==''||$campus==''||$room==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $select = M('OtherSelect');
        $select->create();
        if($id==''){ //添加
            if($select->add()){
                $this->ajaxReturn(true);
            }else{
                $msg = '添加失败';
                $this->ajaxReturn($msg);
            }
        }else{  //修改
            if($select->save()){
                $this->ajaxReturn(true);
            }else{
                $msg = '修改失败';
                $this->ajaxReturn($msg);
            }
        }

    }
	// --------------------工具状态添加和修改---------------------
    public function otherstate(){
        if(I('post.name')==''||I('post.label')==''||I('post.icon')==''||I('post.status')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('OtherState');
        $state->create();
        if($id==''){ //添加
            if($state->add()){
                $this->ajaxReturn(true);
            }else{
                $msg = '添加失败';
                $this->ajaxReturn($msg);
            }
        }else{  //修改
            if($state->save()){
                $this->ajaxReturn(true);
            }else{
                $msg = '修改失败';
                $this->ajaxReturn($msg);
            }
        }
    }
    // --------------------其他状态添加和修改---------------------
    public function otherstateflip(){
        if(I('post.id')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('OtherState');
        $data = $state->where('id=%d',$id)->find();
        $data['status'] = $data['status']==1?0:1;
        if($state->save($data)){
            $this->ajaxReturn(true);
        }else{
            $msg = '修改失败';
            $this->ajaxReturn($msg);
        }
    }
	// --------------------全局字段---------------------
	public function overall(){
        $this->display();
    }

	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}