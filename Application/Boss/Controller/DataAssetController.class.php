<?php
namespace Boss\Controller;
use Think\Controller;
class DataAssetController extends BaseController {
	public function index() {
		$Area = M('UserArea');
		$userarea = $Area -> select();
		$this -> assign('userarea', $userarea);
		$this -> display();
	}

	public function report() {
		if (I('post.begin_time') == '' || I('post.end_time') == '') {
			echo "<h1>" . I('post.begin_time') . I('post.end_time') . "数据不正确</h1>";
		} else {
			$begin_time = I('post.begin_time');
			$end_time = I('post.end_time');
			$this -> begin_time = I('post.begin_time');
			$this -> end_time = I('post.end_time');
			//--------------------------area-----------------------
			$Area = M('UserArea');
			$userarea = $Area -> select();
			$this -> assign('userarea', $userarea);

			$Service = M('ServiceCard');
			$Tool = M('AssetTool');
			$Model = new \Think\Model();
			
			$time_begin = date('Y-m-d 00:00:00', strtotime($begin_time));
			$time_end = date('Y-m-d 23:59:59', strtotime($end_time));

			//-------------------------graph bar line--------------------
			$map = array();
			$map['start'] = array('between', array(I('post.begin_time'), I('post.end_time')));
			$tmp = $Tool -> where($map) -> field('start') -> select();
			$timex = array();
			foreach ($tmp as $key => $item) {
				$str = date('Y-m-d', strtotime($item['start']));
				array_push($timex, $str);
			}
			//            $timey = array_count_values($timex);
			$timex = array_unique($timex);
			$this -> areax = $timex;
			//            $this->areay = $timey;
			//-------------------------graph area--------------------
			$areacount = array();
			$areadata = array();
			foreach ($userarea as $key => $item) {
				$map['start'] = array('between', array(I('post.begin_time'), I('post.end_time')));
				$map['area'] = $item['area'];
				$tmp = $Tool -> where($map) -> select();
				$areacount[$key] = count($tmp);

				foreach ($timex as $x) {
					$map['start'] = array('like', $x . '%');
					$tmp = $Tool -> where($map) -> select();
					$areadata[$key][$x] = count($tmp);
				}
			}
			$this -> areacount = $areacount;
			$this -> areadata = $areadata;

			//-----------------------------工具 ---------------------------
			$tooldata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_tool.`names`,
                COUNT(net_asset_tool.`names`)as count
                FROM
                net_asset_tool
                where net_asset_tool.area='%s' 
                and net_asset_tool.start>='%s' and net_asset_tool.start<='%s'                  
                GROUP BY net_asset_tool.`names`,net_asset_tool.area
                ", $item['area'], $time_begin, $end_time);

				$tooldata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}

			$this -> tooldata = $tooldata;
			//   $this -> ajaxReturn($tooldata);
            //-----------------------------耗材 ---------------------------
			$exhaustdata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_exhaust.`names`,
                sum(net_asset_exhaust.renumber)as count
                FROM
                net_asset_exhaust
                where net_asset_exhaust.area='%s' 
                and net_asset_exhaust.start>='%s' and net_asset_exhaust.start<='%s'                  
                GROUP BY net_asset_exhaust.area,net_asset_exhaust.`names`
                ", $item['area'], $time_begin, $end_time);

				$exhaustdata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$this -> exhaustdata = $exhaustdata;
			
			//-----------------------------设备 ---------------------------
			$devicedata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_device.`names`,
                count(net_asset_device.`names`)as count
                FROM
                net_asset_device
                where net_asset_device.area='%s' 
                and net_asset_device.start>='%s' and net_asset_device.start<='%s'                  
                GROUP BY net_asset_device.area,net_asset_device.`names`
                ", $item['area'], $time_begin, $end_time);

				$devicedata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$this -> devicedata = $devicedata;
			
			//-----------------------------证照 ---------------------------
			$paperdata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_paper.`name`,
                count(net_asset_paper.`name`)as count
                FROM
                net_asset_paper
                where net_asset_paper.area='%s' 
                and net_asset_paper.time>='%s' and net_asset_paper.time<='%s'                  
                GROUP BY net_asset_paper.area,net_asset_paper.`name`
                ", $item['area'], $time_begin, $end_time);

				$paperdata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$this -> paperdata = $paperdata;
			
			//-----------------------------其他 ---------------------------
			$otherdata = array();
			foreach ($userarea as $key => $item) {
				$tmp = $Model -> query("SELECT 
                net_asset_other.`names`,
                count(net_asset_other.`names`)as count
                FROM
                net_asset_other
                where net_asset_other.campus='%s' 
                and net_asset_other.start>='%s' and net_asset_other.start<='%s'                  
                GROUP BY net_asset_other.campus,net_asset_other.`names`
                ", $item['area'], $time_begin, $end_time);

				$otherdata[$key] = $tmp;
				//  $tooldata[$key] = $tmp;
			}
			$this -> otherdata = $otherdata;
						
			$this -> display();
		}
	}

	public function areareport() {
		if (I('post.begin_time') == '' || I('post.end_time') == '' || I('post.area') == '') {
			echo "<h1>" . I('post.begin_time') . I('post.end_time') . "数据不正确</h1>";
		} else {
			$begin_time = I('post.begin_time');
			$end_time = I('post.end_time');
			$this -> begin_time = $begin_time;
			$this -> end_time = $end_time;
			$area = I('post.area');
			$map['start'] = array('between', array($begin_time, $end_time));
			$map['area'] = $area;

			
			$Model = new \Think\Model();

			
			$time_begin = date('Y-m-d 00:00:00', strtotime($begin_time));
			$time_end = date('Y-m-d 23:59:59', strtotime($end_time));
				
		//	$this -> ajaxReturn($time_end);

			
			
			$this -> area = $area;
            			
			//-----------------------------Tool ---------------------------
			//-----------------------------工具 ---------------------------
			$Toolstate = M('ToolState');
			$toolstate = $Toolstate->where('status=1')->field('name')->select();
			$toolname = M('AssetTool')->field('names')->group('names')->select();          
			//------------------Test--------------
			$tmp = array();
			$tmp = $Model -> query(
			"SELECT 
				net_asset_content.id,
                net_asset_content.asset_id,
                net_asset_tool.`names`,
                net_asset_content.state,                
                net_asset_content.time,                
                net_asset_tool.`start`
                FROM
                net_asset_content ,
                net_asset_tool
                WHERE net_asset_tool.id=net_asset_content.asset_id AND net_asset_content.class=1  
                AND net_asset_tool.area='%s' 
                and net_asset_tool.start>='%s' and net_asset_tool.start<='%s'                          
                ", $area, $time_begin, $end_time);
			//-----------获得工具的最新状态----------	
			$data = array();
			$data = $tmp;
			foreach ($tmp as $key => $item) {
				foreach ($data as $key2 => $item2) {
				//	$k = array_search($item['asset_id'], $item2);
                    if($item2['asset_id']==$item['asset_id']){
                    	if($item2['time']<$item['time']){
                    		array_splice($data, $key2, 1);
                    	}                  	
                    }                 				
			    }				
			}
			              //-------------获取AssetContent中没有记录的还在库的工具---------------
			$zaikudata = array();			
			$map['start'] = array('between', array($time_begin, $end_time));
			$map['area'] = $area;
			$zaiku = M('AssetTool')->field('id,names,start') -> where($map) -> select();				
			foreach ($data as $key => $item){
				$notzaiku[$key] = $item['asset_id'];
			}
			foreach ($notzaiku as $key => $item){
				foreach ($zaiku as $key2 => $item2){
					if($item2['id'] == $item['asset_id']){
                    		array_splice($zaiku, $key2, 1);
                    	}
				}				
			}			
			//	$this -> ajaxReturn($data);
			              //-------------将工具按状态分组------------
			foreach ($toolstate as $key => $item) {
				foreach ($data as $key2 => $item2) {
				    if($item2['state']==$item['name'])
				    	$tm[$key][] = $item2;
					
				    }
				
			}
			             //-------------将新获得的在库工具，加到在库组中------------
			$lentool=count($tm[0]);$lentool2=count($zaiku);
		//	for($i=$lentool;$i<$lentool+$lentool2;$i++){
				for($j=0;$j<$lentool2;$j++){
					$tm[0][$lentool] = $zaiku[$j];
					$lentool = $lentool + 1;
				}
				
		//	}								
		//	$this -> ajaxReturn($tm[0]);
			           //-------------将数据转化为可在图表中显示的数据------------
			foreach ($tm as $key => $item) {
				$k=$item;
				foreach ($toolname as $key2 => $item2){
					$i = 0;
					foreach ($k as $key3 => $item3){
						if($item2['names']==$item3['names']){
						    $d[$key][$key2]['count']=$i+1;
							$i=$i+1;
					    }
					}
					if($i==0){
						$d[$key][$key2]['count']=0;
					}					
				}								
			}
			$this -> toolname = $toolname;
			$this -> toolstate = $toolstate;
			$this -> tooldata = $d;
			
		//	$this -> ajaxReturn($d);
		//-----------------------------耗材 ---------------------------
			$Exhauststate = M('ExhaustState');
			$exhauststate = $Exhauststate->where('status=1')->field('name')->select();
			$exhaustname = M('AssetExhaust')->field('names')->group('names')->select();  
			$this -> exhaustname = $exhaustname;
			$this -> exhauststate = $exhauststate;
			//------------------Test--------------
			$tmpexhaust = array();
			$tmpexhaust = $Model -> query(
			"SELECT 			
                net_asset_content.id,
                net_asset_exhaust.`names`,
                net_asset_content.num as renumber,
                net_asset_content.state
                FROM
                net_asset_exhaust ,
                net_asset_content
                WHERE net_asset_exhaust.id=net_asset_content.asset_id and net_asset_content.class=2
                AND net_asset_exhaust.area='%s' 
                and net_asset_content.time>='%s' and net_asset_content.time<='%s'                          
                ", $area, $time_begin, $end_time);
			
			//$map['start'] = array('between', array($begin_time, $end_time));
			$mapexhaust['area'] = $area;
			$tmpexhaust2 = M('AssetExhaust')->where($mapexhaust) -> select();	
			
			$dataexhaust[0]=$tmpexhaust2;
			$dataexhaust[1]=$tmpexhaust;
			foreach ($dataexhaust as $key => $item) {
				$data=$item;
				foreach ($exhaustname as $key2 => $item2) {
					$sum = 0;
					foreach ($data as $key3 => $item3){
						if($item2['names']==$item3['names']){
							$sum = $sum + $item3['renumber'];            	
                    		$dataexhaust[$key][$key2]['count']=$sum;                   	                 	
                        }    
					}				                                 				
			    }				
			}
			$this -> exhaustdata = $dataexhaust;
		//	$this -> ajaxReturn($tmpexhaust2);
			
			
			
			
			//----------------------------- 设备---------------------------
			$Devicestate = M('DeviceState');
			$devicestate = $Devicestate->where('status=1')->field('name')->select();
			$devicename = M('AssetDevice')->field('names')->group('names')->select();  
			$this -> devicename = $devicename;
			$this -> devicestate = $devicestate;
			//------------------Test--------------
			$tmpdevice = array();
			$tmpdevice = $Model -> query(
			"SELECT 			
                net_asset_content.id,
                net_asset_content.asset_id,
                net_asset_content.state,
                net_asset_content.time,                
                net_asset_device.`names`
                FROM
                net_asset_device ,
                net_asset_content
                WHERE net_asset_device.id=net_asset_content.asset_id AND net_asset_content.class=2
                AND net_asset_device.area='%s' 
                and net_asset_content.time>='%s' and net_asset_content.time<='%s'                          
                ", $area, $time_begin, $end_time);
			//-----------获得设备的最新状态----------	
			$data = array();
			$data = $tmpdevice;
			foreach ($tmpdevice as $key => $item) {
				foreach ($data as $key2 => $item2) {
				//	$k = array_search($item['asset_id'], $item2);
                    if($item2['asset_id']==$item['asset_id']){
                    	if($item2['time']<$item['time']){
                    		array_splice($data, $key2, 1);
                    	}                  	
                    }                 				
			    }				
			}
		//	$this -> ajaxReturn($data);
			//-------------获取AssetContent中没有记录的还在库的设备---------------
			$zaikudevice = array();			
			$map['start'] = array('between', array($time_begin, $end_time));
			$map['area'] = $area;
			$zaikudevice = M('AssetDevice')->field('id,names,start') -> where($map) -> select();				
			foreach ($data as $key => $item){
				$notzaikudevice[$key] = $item['asset_id'];
			}
			foreach ($notzaikudevice as $key => $item){
				foreach ($zaikudevice as $key2 => $item2){
					if($item2['id'] == $item['asset_id']){
                    		array_splice($zaikudevice, $key2, 1);
                    	}
				}				
			}			
			//	$this -> ajaxReturn($zaikudevice[1]);
			              //-------------将设备按状态分组------------
			foreach ($devicestate as $key => $item) {
				foreach ($data as $key2 => $item2) {
				    if($item2['state']==$item['name'])
				    	$tmdevice[$key][] = $item2;
					
				    }
				
			}
			             //-------------将新获得的在库设备，加到在库组中------------
			$lendevice=count($tmdevice[0]);$lendevice2=count($zaikudevice);
		//	for($i=$lendevice;$i<$lendevice+$lendevice2;$i++){
				for($j=0;$j<$lendevice2;$j++){
					$tmdevice[0][$lendevice] = $zaikudevice[$j];
					$lendevice = $lendevice + 1;
				}
				
		//	}	
			 //-------------将数据转化为可在图表中显示的数据------------
			foreach ($tmdevice as $key => $item) {
				$k=$item;
				foreach ($devicename as $key2 => $item2){
					$i = 0;
					foreach ($k as $key3 => $item3){
						if($item2['names']==$item3['names']){
						    $ddevice[$key][$key2]['count']=$i+1;
							$i=$i+1;
					    }
					}
					if($i==0){
						$ddevice[$key][$key2]['count']=0;
					}					
				}								
			}	
			$this -> devicedata = $ddevice;						
	//		$this -> ajaxReturn($tmdevice[0]);
			
			
			
			
			
			//----------------------------- 证照---------------------------
			$Paperstate = M('PaperState');
			$paperstate = $Paperstate->where('status=1')->field('name')->select();
			$papername = M('AssetPaper')->field('name')->group('name')->select();  
			$this -> papername = $papername;
			$this -> paperstate = $paperstate;
			//------------------Test--------------
			$tmppaper = array();
			$tmppaper = $Model -> query(
			"SELECT 			
                net_asset_content.id,
                net_asset_content.asset_id,
                net_asset_content.state,
                net_asset_content.time,                
                net_asset_paper.`name`
                FROM
                net_asset_paper ,
                net_asset_content
                WHERE net_asset_paper.id=net_asset_content.asset_id AND net_asset_content.class=4
                AND net_asset_paper.area='%s' 
                and net_asset_content.time>='%s' and net_asset_content.time<='%s'                          
                ", $area, $time_begin, $end_time);
			//-----------获得证照的最新状态----------	
			$data = array();
			$data = $tmppaper;
			foreach ($tmppaper as $key => $item) {
				foreach ($data as $key2 => $item2) {
				//	$k = array_search($item['asset_id'], $item2);
                    if($item2['asset_id']==$item['asset_id']){
                    	if($item2['time']<$item['time']){
                    		array_splice($data, $key2, 1);
                    	}                  	
                    }                 				
			    }				
			}
		//	$this -> ajaxReturn($data);
			//-------------获取AssetContent中没有记录的还在库的证照---------------
			$zaikupaper = array();			
			$map['time'] = array('between', array($time_begin, $end_time));
			$map['campus'] = $area;
			$zaikupaper = M('AssetPaper')->field('id,name,time') -> where($map) -> select();				
			foreach ($data as $key => $item){
				$notzaikupaper[$key] = $item['asset_id'];
			}
			foreach ($notzaikupaper as $key => $item){
				foreach ($zaikupaper as $key2 => $item2){
					if($item2['id'] == $item['asset_id']){
                    		array_splice($zaikupaper, $key2, 1);
                    	}
				}				
			}			
			//	$this -> ajaxReturn($zaikupaper[1]);
			              //-------------将证照按状态分组------------
			foreach ($paperstate as $key => $item) {
				foreach ($data as $key2 => $item2) {
				    if($item2['state']==$item['name'])
				    	$tmpaper[$key][] = $item2;
					
				    }
				
			}
			             //-------------将新获得的在库证照，加到在库组中------------
			$lenpaper=count($tmpaper[0]);$lenpaper2=count($zaikupaper);
		//	for($i=$lenpaper;$i<$lenpaper+$lenpaper2;$i++){
				for($j=0;$j<$lenpaper2;$j++){
					$tmpaper[0][$lenpaper] = $zaikupaper[$j];
					$lenpaper = $lenpaper + 1;
				}
				
		//	}	
			 //-------------将数据转化为可在图表中显示的数据------------
			foreach ($tmpaper as $key => $item) {
				$k=$item;
				foreach ($papername as $key2 => $item2){
					$i = 0;
					foreach ($k as $key3 => $item3){
						if($item2['name']==$item3['name']){
						    $dpaper[$key][$key2]['count']=$i+1;
							$i=$i+1;
					    }
					}
					if($i==0){
						$dpaper[$key][$key2]['count']=0;
					}					
				}								
			}	
			$this -> paperdata = $dpaper;						
		//	$this -> ajaxReturn($tmpaper[0][1]);
			
			
			
			
			
			
			
			//----------------------------- 其他---------------------------
			$Otherstate = M('OtherState');
			$otherstate = $Otherstate->where('status=1')->field('name')->select();
			$othername = M('AssetOther')->field('names')->group('names')->select();  
			$this -> othername = $othername;
			$this -> otherstate = $otherstate;
			//------------------Test--------------
			$tmpother = array();
			$tmpother = $Model -> query(
			"SELECT 			
                net_asset_content.id,
                net_asset_content.asset_id,
                net_asset_content.state,
                net_asset_content.time,                
                net_asset_other.`names`
                FROM
                net_asset_other ,
                net_asset_content
                WHERE net_asset_other.id=net_asset_content.asset_id AND net_asset_content.class=5
                AND net_asset_other.campus='%s' 
                and net_asset_content.time>='%s' and net_asset_content.time<='%s'                          
                ", $area, $time_begin, $end_time);
			//-----------获得其他的最新状态----------	
			$data = array();
			$data = $tmpother;
			foreach ($tmpother as $key => $item) {
				foreach ($data as $key2 => $item2) {
				//	$k = array_search($item['asset_id'], $item2);
                    if($item2['asset_id']==$item['asset_id']){
                    	if($item2['time']<$item['time']){
                    		array_splice($data, $key2, 1);
                    	}                  	
                    }                 				
			    }				
			}
		//	$this -> ajaxReturn($data);
			//-------------获取AssetContent中没有记录的还在库的其他---------------
			$zaikuother = array();			
			$map['start'] = array('between', array($time_begin, $end_time));
			$map['campus'] = $area;
			$zaikuother = M('AssetOther')->field('id,names,start') -> where($map) -> select();				
			foreach ($data as $key => $item){
				$notzaikuother[$key] = $item['asset_id'];
			}
			foreach ($notzaikuother as $key => $item){
				foreach ($zaikuother as $key2 => $item2){
					if($item2['id'] == $item['asset_id']){
                    		array_splice($zaikuother, $key2, 1);
                    	}
				}				
			}			
			//	$this -> ajaxReturn($zaikuother[1]);
			              //-------------将其他按状态分组------------
			foreach ($otherstate as $key => $item) {
				foreach ($data as $key2 => $item2) {
				    if($item2['state']==$item['name'])
				    	$tmother[$key][] = $item2;
					
				    }
				
			}
			             //-------------将新获得的在库其他，加到在库组中------------
			$lenother=count($tmother[0]);$lenother2=count($zaikuother);
		//	for($i=$lenother;$i<$lenother+$lenother2;$i++){
				for($j=0;$j<$lenother2;$j++){
					$tmother[0][$lenother] = $zaikuother[$j];
					$lenother = $lenother + 1;
				}
				
		//	}	
			 //-------------将数据转化为可在图表中显示的数据------------
			foreach ($tmother as $key => $item) {
				$k=$item;
				foreach ($othername as $key2 => $item2){
					$i = 0;
					foreach ($k as $key3 => $item3){
						if($item2['names']==$item3['names']){
						    $dother[$key][$key2]['count']=$i+1;
							$i=$i+1;
					    }
					}
					if($i==0){
						$dother[$key][$key2]['count']=0;
					}					
				}								
			}	
			$this -> otherdata = $dother;						
	//		$this -> ajaxReturn($tmother[0]);
						
			$this -> display();
		}
	}

	public function topdf() {

	}

	public function _empty($name) {
		echo "Not Found!";
	}

}
