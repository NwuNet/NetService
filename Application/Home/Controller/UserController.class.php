<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {
	public function index() {
		$homeuser = D('Login','Service')->getuserInfo();
		$Card = M('ServiceCard');
		$cardinfo = $Card->where('status=0 and name = "%s" ',$homeuser['uname'])->select();
		$this->assign("cardinfo",$cardinfo);

		$repair = M('ServiceRepair');
		$servicerepair = $repair->where('servicecard_id = %d',$cardinfo[0]['id'])->select();
		$this->assign("servicerepair",$servicerepair);
			
		$this -> display();
	}
    // --------------------维修单添加完成状态---------------------
	public function servicerepairadd(){
		$servicecard_id = I('post.servicecard_id');
		$evaluation = I('post.evaluation');
		if($servicecard_id==''){
			$this->ajaxReturn("数据为空");
		}
		$repair = M('ServiceRepair');
		$repair->create();
		$repair->state='完成';
		$repair->operator=$evaluation;
		$repair->time  = date("Y-m-d H:i:s",NOW_TIME);
		$repair->add();
		if($repair){						
				$Card = M('ServiceCard');
				$data = $Card->where('id = "%d"',$servicecard_id)->field("id")->find();
		        $Card->id = $data['id'];
		        $Card->status = '1' ;
		        $Card ->save();
				if($Card){
			        $this->ajaxReturn(true);
		        }else{
			        $this->ajaxReturn("提交失败");
				}
		
		}else if($repair){
			        $this->ajaxReturn(true);
		        } else{
			        $this->ajaxReturn("提交失败");
		        }
	}
	public function _empty($name) {
		echo "Not Found!";
	}

}
