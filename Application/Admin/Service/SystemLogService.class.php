<?php
namespace Admin\Service;
use Think\Model;
/**
 * 系统日志服务
 */

class SystemLogService{
	
	/**
	 * 用户登录
	 * user,module,controller,info,status
	 * */
    public function add($data)
    {
		$data['ip'] = get_client_ip();
		$data['time'] = date("Y-m-d H:i:s",NOW_TIME);
		$log = M('SystemLog');
		if($log->add($data)){
			return true;
		}else{
			return false;
		}
    }
	public function count(){
		$log = M('SystemLog');
		return count($log->field('log_id')->select());
	}
	/**
	 * 访问量
	 * */
	public function addhome(){
		$s = M('LogHome');
		$day =  date("Y-m-d",NOW_TIME);
		$sum = $s->where('time= "%s"',$day)->find();
		if(count($sum)>0){
			$sum['sum'] ++;
			$s->save($sum);
		}else{
			$sum['sum'] = 1;
			$sum['time'] = $day;
			$s->add($sum);
		}
	}
	public function dayhome(){
		$s = M('LogHome');
		$day =  date("Y-m-d",NOW_TIME);
		$sum = $s->where('time= "%s"',$day)->find();
		return $sum['sum'];
	}
	public function allhome(){
		return M('LogHome')->sum('sum');
	}
}
