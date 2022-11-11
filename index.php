<?php  //入口(落幕修改:2022/06/03)
error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
$t1 = microtime(true);
if(!file_exists('./data/lm.user.db3')){
    require ('./initial/initial.php');//初始数据!
}

require ('./class/Medoo.php');//数据库框架
require ('./class/Class.php');//载入函数库
$udb = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/lm.user.db3']);
$c = Get('c');$u = Get('u');
$CookieU  = $_COOKIE['DefaultDB'];
$Duser    = UGet('DUser');//默认用户
$reg      = UGet('Reg');//0.禁止注册 1.允许注册 2.邀请注册
$Register = UGet('Register');//注册入口 
$login    = UGet('Login'); //登陆入口名
$libs     = UGet('Libs'); //静态库路径
$IconAPI  = UGet('IconAPI'); //图标API编号
$Visit    = UGet('Visit'); //访问控制
$Diy      = UGet('Diy'); //自定义代码
$XSS      = UGet('XSS');  //防XSS脚本
$SQL      = UGet('SQL');  //防SQL注入
$offline  = UGet('offline') == 1? true:false;  //离线模式
$Pandomain = UGet('Pandomain') == 1? true:false; //泛域名
if($Pandomain && is_subscribe(true)){
    if (preg_match('/(.+)\.(.+\..+)/i',$_SERVER["HTTP_HOST"],$HOST) ){
        $twou = $udb->get("user","User",["User"=>$HOST[1]]);
        if (!empty($twou)) {
            $CookieU = $HOST[1];
        }
    }
}
$u = !empty($u)?$u:(!empty($CookieU)?$CookieU:(!empty($Duser)?$Duser:'admin'));//优先级:Get>Cookie/Host>默认用户>admin
$version  = get_version();//全局版本号
if($c !== $Register){
    $userdb   = $udb->get("user","*",["User"=>$u]);
    $SQLite3  = './data/'.$userdb['SQLite3'];//数据库路径
    $RegTime  = $userdb['RegTime'];//注册时间
    $username = $userdb['User'];//用户名
    $password = $userdb['Pass'];//密码
    $ApiToken = $userdb['Token'];//令牌
    $Elogin   = $userdb['Login'];//专属登陆入口
    
    if(!isset($userdb['ID'])){
        //ID为空
        msg(-1002,'未找到账号数据.');
    }elseif(!file_exists($SQLite3)){
        //文件不存在,复制数据库..
        if(copy('./initial/onenav.simple.db3',$SQLite3)){
            //复制数据库成功,待初始化!
            $initialization = true;
        }else{
            //复制数据库失败
            msg(-1003,'创建用户数据库失败.');
        }
    }
    $db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$SQLite3]);
    if($initialization){
        //当表存在用户,但库不见了,创建数据库后从表记录的关键数据写到用户库
        initial($username,$password,$RegTime,$userdb['Email'],$Elogin,$userdb['RegIP']);
    }
    $Skey = getconfig('Skey','1');//Key算法
}

//根据不同的请求载入不同的控制器
if((!isset($c)) or ($c == '') or ($c == 'index')){
    include_once("./controller/index.php");}//主页
elseif($c == 'admin'){
	include_once("./controller/admin.php");}//后台
elseif($c == 'click'){
	include_once("./controller/click.php");}//跳转
elseif($c == $login  || $c == $Elogin){
	include_once("./controller/login.php");}//登陆
elseif($c == $Register){
	include_once("./controller/Register.php");}//注册
elseif($c == 'api'){
	include_once("./controller/api.php");}//API
elseif($c == 'ico'){
	include_once("./controller/ico.php");}//图标
elseif($c == 'apply'){
	include_once("./controller/apply.php");}//图标
else{msg(-1001,'请求的接口不存在!');}




