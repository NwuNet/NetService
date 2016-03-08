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
        $select = M('DeviceSelect');
        $device = $select->select();//获取数量
        $deviceselect = $select->order('name')->limit(count($device))->select();
        $this->assign('deviceselect',$deviceselect);
		$state = M('DeviceState');
        $devicestate = $state->select();
        $this->assign('devicestate',$devicestate);
        $this->display();
    }
	// --------------------设备select添加和修改---------------------
    public function deviceadd(){
        $name = I('post.name');//名称
        $brand = I('post.brand');//品牌
        $model = I('post.model');//型号
        $series = I('post.series');//系列
        if($name==''||$brand==''||$model==''||$series==''){
            $this->ajaxReturn("每项数据不能为空！");
        }
        $id = I('post.id');
        $select = M('DeviceSelect');
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
    // --------------------设备状态添加和修改---------------------
    public function devicestate(){
        if(I('post.name')==''||I('post.label')==''||I('post.icon')==''||I('post.status')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('DeviceState');
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
    // --------------------设备状态添加和修改---------------------
    public function devicestateflip(){
        if(I('post.id')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('DeviceState');
        $data = $state->where('id=%d',$id)->find();
        $data['status'] = $data['status']==1?0:1;
        if($state->save($data)){
            $this->ajaxReturn(true);
        }else{
            $msg = '修改失败';
            $this->ajaxReturn($msg);
        }
    }
	// --------------------证照---------------------
	public function paper(){
        $select = M('PaperSelect');
        $paper = $select->select();//获取数量
        $paperselect = $select->order('class')->limit(count($paper))->select();
        $this->assign('paperselect',$paperselect);
        $state = M('PaperState');
        $paperstate = $state->select();
        $this->assign('paperstate',$paperstate);
        $this->display();
    }
    // --------------------证照select添加和修改---------------------
    public function paperadd(){
        $class = I('post.class');//类别
        $name = I('post.name');//名称
        if($class==''||$name==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $select = M('PaperSelect');
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
    // --------------------证照状态添加和修改---------------------
    public function paperstate(){
        if(I('post.name')==''||I('post.label')==''||I('post.icon')==''||I('post.status')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('PaperState');
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
    // --------------------证照状态添加和修改---------------------
    public function paperstateflip(){
        if(I('post.id')==''){
            $this->ajaxReturn("数据为空");
        }
        $id = I('post.id');
        $state = M('PaperState');
        $data = $state->where('id=%d',$id)->find();
        $data['status'] = $data['status']==1?0:1;
        if($state->save($data)){
            $this->ajaxReturn(true);
        }else{
            $msg = '修改失败';
            $this->ajaxReturn($msg);
        }
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
	// --------------------其他状态添加和修改---------------------
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