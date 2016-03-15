<?php
namespace Boss\Model;
use Think\Model\ViewModel;
/**
 * 后台用户查询视图
 */

class AssetToolViewModel extends ViewModel {

	public $viewFields = array(
	'Assettool' => array('id', 'seq','names','brand','model','unit','start')
	);

}
