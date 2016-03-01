<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 后台用户查询视图
 */

class ToolViewModel extends ViewModel {

	public $viewFields = array(
	'Tool' => array('asset_id', 'name','brand','model','price','unit','ifborrow'),
		    
	);

}
