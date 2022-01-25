<?php  //入口
error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
$c = inject_check ( strip_tags(@$_GET['c'])); //取Get参数并过滤
$u = inject_check ( strip_tags(@$_GET['u'])); //取Get参数并过滤
$reg= 1 ; //0.禁止注册  1.允许注册  2.禁止注册(admin除外) 
$login = 'login';//登陆入口名称!如果名为login则隐藏入口!反之未隐藏入口,隐藏时用户如果没保存专属登陆入口且不知道入口名将无法登陆!
$libs = "./static"; //使用本地服务器请填写./static 使用CDN请将static文件夹上传并修改地址,后缀不要带/
//图标获取接口: 1:本地服务器 2:favicon.rss.ink(原著用的接口) 3:ico.hnysnet.com 4:api.15777.cn 5:favicon.cccyun.cc (SSL证书过期..) 6:api.iowen.cn
//接口都是网上搜集来的,可靠性未知,如果图标获取异常请尝试更换!个人比较推荐6
$favicon = 1;
if((!isset($u)) or ($u == '')){ 
    $Default_DB=$_COOKIE['DefaultDB'];
    if ($Default_DB !=''){
        $u=$Default_DB;
    }else{
        $u="admin";
    }
} //默认首页

require ('./class/Medoo.php');//数据库框架
require ('./class/Class.php');//载入函数库
$version =get_version();//设置全局版本号
//如果数据文件存在则载入数据库
if(file_exists('./data/'.$u.'.db3')){
$db = new Medoo\Medoo([
    'database_type' => 'sqlite',
    'database_file' => './data/'.$u.'.db3'
]);
require ('./controller/updata.php');//检测数据库是否需要升级
$username =  $db->get("on_config","value",["name"=>'user']);
$password =  $db->get("on_config","value",["name"=>'password']);
$ApiToken =  $db->get("on_config","value",["name"=>'Token']);
}

//根据不同的请求载入不同的控制器
if((!isset($c)) or ($c == '') or ($c == 'index') ){ //如果没有请求控制器
    include_once("./controller/index.php"); //载入主页
}//这样写的目的是防止阿里云扫描此文件为后门文件,同时也提高安全性!
elseif($c == 'admin'){
	include_once("./controller/admin.php");}
elseif($c == 'click'){
	include_once("./controller/click.php");}
elseif($c == $login  || $c == getloginC($u)){
	include_once("./controller/login.php");}
elseif($c == 'api'){
	include_once("./controller/api.php");}
else{err_msg(-1001,'请求的接口不存在!');}

function inject_check($str){
$tmp=preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $str); // 进行过滤
if($tmp){err_msg(-1002,'请求内容非法!');}else{return $str;}}


