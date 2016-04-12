<?php
namespace Boss\Controller;
use Think\Controller;
class SetPeopleController extends BaseController {
    // --------------------考勤设置---------------------
    public function register(){
    	$register = M('RegisterSelect');
        $registerselect = $register->select();
        $this->assign('registerselect',$registerselect);
        $this->display();
    }
	public function registeradd() {
		$Register = M('RegisterSelect');
        $name = I('post.name');
		$id = I('post.id');
        
        $Register->create();
        if($id==''){ //添加
            if($Register->add()){
                $this->ajaxReturn(true);
            }else{
                $msg = '添加失败';
                $this->ajaxReturn($msg);
            }
        }else{  //修改
            if($Register->save()){
                $this->ajaxReturn(true);
            }else{
                $msg = '修改失败';
                $this->ajaxReturn($msg);
            }
        }
     
    }	
	// --------------------绩效设置---------------------
    public function evaluation(){
        $this->display();
    }
	// --------------------员工设置---------------------
    public function staff(){
        $this->display();
    }
    public function staffadd() {
        $User = D('Admin/User', 'Logic');
        if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
        $data = array();
        $data['ip'] = get_client_ip();
        $data['uname'] = I('post.uname');
        $data['password'] = I('post.password');
        $data['number'] = I('post.number');
        $data['phone'] = I('post.phone');
        $data['address'] = I('post.address');
        if ($User -> staffadd($data)) {
            $this -> ajaxReturn(TRUE);
        } else {
            $msg = "用户名已存在或认证失败";
            $this -> ajaxReturn($msg);
        }
    }
    public function staffedit() {
        $User = D('Admin/User', 'Logic');
        if(I('post.repassword') != I('post.password')) $this -> ajaxReturn("确认密码失败");
        $data = array();
        $data['id'] = I('post.id');
//	    $data['img'] = '/Images/User/default.png';
        $data['uname'] = I('post.uname');
        $data['password'] = I('post.password');
        $data['number'] = I('post.number');
        $data['address'] = I('post.address');
        $data['phone'] = I('post.phone');
        if ($User -> staffedit($data)) {
            $msg = "修改成功！";
            $this -> ajaxReturn(TRUE);
        } else {
            $msg = "修改失败！";
            $this -> ajaxReturn(FALSE);
        }
    }
    public function stafftable() {
        $staffUser = D('Admin/StaffUserView');
        //获取Datatables发送的参数 必要
        $draw = I('get.draw');//这个值作者会直接返回给前台

        //排序
        $order_column = I('get.order')['0']['column'];//那一列排序，从0开始
        $order_dir = I('get.order')['0']['dir'];//ase desc 升序或者降序
        //拼接排序sql
        $orderSql = "";
        if (isset($order_column)) {
            $i = intval($order_column);
            switch($i) {
                case 0 :$orderSql = " id " . $order_dir;break;
                case 1 :$orderSql = " uname " . $order_dir;break;
                case 2 :$orderSql = " number " . $order_dir;break;
                case 3 :$orderSql = " address " . $order_dir;break;
                case 4 :$orderSql = " phone " . $order_dir;break;
                case 5 :$orderSql = " begintime " . $order_dir;break;
                default :$orderSql = '';
            }
        }

        //搜索
        $search = $_GET['search']['value'];//获取前台传过来的过滤条件
        //分页
        $start = $_GET['start'];//从多少开始
        $length = $_GET['length'];//数据长度
        //表的总记录数 必要
        $recordsTotal = $staffUser->count();

        $map['id|uname|number|address|phone|begintime']=array('like',"%".$search."%");
        if(strlen($search)>0){
            $recordsFiltered = count($staffUser->where($map)->select());
            $table = $staffUser->where($map)->order($orderSql)->limit($start.','.$length)->select();
        }else{
            $recordsFiltered = $recordsTotal;
            $table = $staffUser->order($orderSql)->limit($start.','.$length)->select();
        }

        $infos = array();
        foreach($table as $row){
            $obj = array($row['id'],$row['uname'],$row['number'],$row['address'],$row['phone'],$row['begintime']);
            array_push($infos,$obj);
        }

        $this->ajaxReturn(array(
            "draw" => intval($draw),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $infos
        ));
    }

	// --------------------排班设置---------------------
    public function schedule(){
        $schStaff = M('ScheduleStaff');
        $removestaff = $schStaff->where('status =1')->select();
        $staffUser = D('Admin/StaffUserView');
        $staff =$staffUser->where('status = 1')->field('uname')->select();
        $index = 0;$addstaff=[];
        foreach($staff as $user => $value){
            $state = 0;
            for($i=0;$i<count($removestaff);$i++){
                if($staff[$user]['uname'] == $removestaff[$i]['uname']){
                    $state = 1;break;
                }
            }
            if($state == 0){
                $addstaff[$index++] = $staff[$user];
            }
        }
        $this->assign('addstaff',$addstaff);
        $this->assign('removestaff',$removestaff);
        $this->display();
    }
    public function scheduleuser(){
        $type = I('post.type');
        $uname = I('post.uname');
        if($type == ''||$uname==''){
            $this->ajaxReturn('数据为空');
        }
        if($type == 'add'){
            $schStaff = M('ScheduleStaff');
            if(count($schStaff->where('uname = "%s"',$uname)->find())>0){
                $schStaff->status = 1;
                if($schStaff->save()){
                    $this->ajaxReturn(true);
                }else{
                    $this->ajaxReturn('添加失败');
                }
            }else{
                $schStaff->create();
                $schStaff->status = 1;
                if($schStaff->add()){
                    $this->ajaxReturn(true);
                }else{
                    $this->ajaxReturn('添加失败');
                }
            }
        }elseif($type == 'remove'){
            $schStaff = M('ScheduleStaff');
            $data = $schStaff->where('uname = "%s"',$uname)->find();
            if(count($data)==0){
                $this->ajaxReturn('移除失败');
            }else{
                $data['status'] = 0;
                if($schStaff->save($data)){
                    $this->ajaxReturn(true);
                }else{
                    $this->ajaxReturn('移除失败');
                }
            }
        }
        $this->ajaxReturn(false);
    }
    public function schedulechange(){
        if(I('post.uname')==''||I('post.type')==''){
            $this->ajaxReturn('数据为空');
        }
        $schStaff = M('ScheduleStaff');
        $staff = $schStaff ->where('uname = "%s"',I('post.uname'))->find();
        $str='';
        switch(I('post.type')){
            case 1: $str = 'mon';break;
            case 2: $str = 'tues';break;
            case 3: $str = 'wed';break;
            case 4: $str = 'thurs';break;
            case 5: $str = 'fri';break;
            case 6: $str = 'sat';break;
        }
        $staff[$str] = $staff[$str]==1?0:1;
        if($schStaff->save($staff)){
            $this->ajaxReturn(true);
        }else{
            $this->ajaxReturn('更改失败');
        }
    }
	// --------------------空操作---------------------
	public function _empty($name){
		echo "Not Found!";
    }
}