<?php
mb_internal_encoding('utf-8');
header("Content-Type: text/html; charset=utf-8");

// configuration
require_once($_SERVER['DOCUMENT_ROOT'] . '/../configs/master_config.php');

function __autoload($className) { 
	$templateRoot = $_SERVER['DOCUMENT_ROOT'] . '/templates/';
	$classRoot = $_SERVER['DOCUMENT_ROOT'] . '/common/';
	// classes
	$class['Basecamp']    	 	= $templateRoot . 'basecamp/Basecamp.class.php';
	$class['HipChat'] 	= $templateRoot . 'hipchat/Hipchat.class.php';
	$class['dbMysqli']    	 	= $classRoot 	. 'dbMysqli.class.php';


    if (file_exists($class[$className])) {
          require_once $class[$className]; 
          return true; 
    } 
      
    return false; 
}
$basecampSetting = new BasecampSetting();
$hipchatSetting = new HipchatSetting();
$mysql = new MysqlSetting();
$dbFacile = new dbMysqli();
$dbFacile->open($mysql->database, $mysql->username, $mysql->password, $mysql->host);
$dbFacile->execute("SET CHARACTER SET utf8");