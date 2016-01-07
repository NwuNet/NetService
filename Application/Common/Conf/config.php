<?php
//当前路径
$dir = dirname(__FILE__);
//数据库
$db = include $dir . '/db.php';

$config = array(
'URL_PATHINFO_DEPR' => '-', //URL分隔模式
'URL_MODEL' => 3, //URL模式，兼容模式
);
//联合配置并返回
$config = array_merge($db, $config);
return $config;
