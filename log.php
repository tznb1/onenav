<?php 
require ('./class/Medoo.php');//数据库框架
require ('./class/Class.php');//载入函数库
$log = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/login.log.db']);

$logs = $log->select('loginlog','*',[
        "ORDER" =>  ["weight" => "DESC"]
    ]);
    
    foreach ($logs as $logs) {
        echo($logs['name'].$logs['value'].'<br />');
	}
	