<?php

//写入登陆日志
function WriteloginLog($name,$pass,$ip,$date,$dbname,$value){
$db = new Medoo\Medoo([
    'database_type' => 'sqlite',
    'database_file' => './data/login.log.db'
]);
$data = $db->query("select * from sqlite_master where name = 'loginlog'")->fetchAll();
    if (count($data)==0){ 
    $db->query('CREATE TABLE "main"."loginlog" ("name" TEXT,"pass" TEXT,"ip" TEXT,"date" TEXT,"db" TEXT,"value" TEXT);');
    }
$db->insert('loginlog',['name'=> $name ,'pass'=> $pass,'ip'=> $ip,'date'=> $date,'db'=> $dbname.'.db3','value'=>$value]);
}

//写入登陆限制
function Writeloginlimit($name,$value){
$db = new Medoo\Medoo([
    'database_type' => 'sqlite',
    'database_file' => './data/login.log.db'
]);
$data = $db->query("select * from sqlite_master where name = 'loginlimit'")->fetchAll();
if (count($data)==0){ $db->query('CREATE TABLE "main"."loginlog" ("name" TEXT,"value" TEXT);');}

$count = $db->count('loginlimit',[ 'name' => $name]);
if ($count){
    $db->update('loginlimit',['value' => $value],['name' => $name]);
}else{
    $db->insert('loginlimit',['name'=> $name ,'value'=> $value]);
}
}
//登陆限制...暂时没用
function getloginlimit($name){
$db = new Medoo\Medoo([
    'database_type' => 'sqlite',
    'database_file' => './data/login.log.db'
]);
return ($db->get("loginlimit","value",["name"=>$name]));
}
//写入配置(如果不存在则创建)
function Writeconfig($name,$value){
global $db;
$count = $db->count('on_config',[ 'name' => $name]);
if ($count){
    $db->update('on_config',['value' => $value],['name' => $name]);
}else{
    $db->insert('on_config',['name'=> $name ,'value'=> $value]);
}
}
//获取图标URL
function geticourl($icon,$url){
if ($icon ==1){return('./favicon/?url='.$url);}
elseif($icon ==2){return('//favicon.rss.ink/v1/'.base64($url));}
elseif($icon ==3){return('//ico.hnysnet.com/get.php?url='.$url);}
elseif($icon ==4){return('//api.15777.cn/get.php?url='.$url);}
elseif($icon ==5){return('////favicon.cccyun.cc/'.$url);}
else{return('./favicon/?url='.$url);}//如果参数错误则使用本地服务器
}
//读取配置
function getconfig($name){
global $db;
return ($db->get("on_config","value",["name"=>$name]));
}
//生成用户登陆接口
function getloginC($udb){
return (substr(md5($udb.'6Uc2vFoU'),0, 6).'_login');
}
//生成图标代码
function geticon($name){
if (substr($name,0, 3) =='lay')
{return('<i class="layui-icon '.$name.'"></i>');}
elseif (substr($name,0, 3) =='fa-')
{return('<i class="fa '.$name.'"></i>');}
else{return($name);}
}
//生成图标代码2
function geticon2($name){
if (substr($name,0, 3) =='lay')
{return('class="layui-icon '.$name.'"');}
elseif (substr($name,0, 3) =='fa-')
{return('class="fa '.$name.'"');}
else{return($name);}
}

//用户名限制
function check_user($value){
if(!preg_match("/^[a-zA-Z0-9]+$/",$value)){
    return false;
}
    return true;
}
//获取访客IP
function getIP() { 
    if (getenv('HTTP_CLIENT_IP')) { 
    $ip = getenv('HTTP_CLIENT_IP'); 
  } 
  elseif (getenv('HTTP_X_FORWARDED_FOR')) { 
      $ip = getenv('HTTP_X_FORWARDED_FOR'); 
  } 
      elseif (getenv('HTTP_X_FORWARDED')) { 
      $ip = getenv('HTTP_X_FORWARDED'); 
  } 
    elseif (getenv('HTTP_FORWARDED_FOR')) { 
    $ip = getenv('HTTP_FORWARDED_FOR'); 
  } 
    elseif (getenv('HTTP_FORWARDED')) { 
    $ip = getenv('HTTP_FORWARDED'); 
  } 
  else { 
      $ip = $_SERVER['REMOTE_ADDR']; 
  } 
      return $ip; 
  }
//获取版本号
function get_version(){
    if( file_exists('./initial/version.txt') ) {
        $version = @file_get_contents('./initial/version.txt');
        return $version;
    }
    else{
        $version = 'null';
        return $version;
    }
}
//计算Key
function Getkey($user,$pass,$Expire){
    $key = md5($user.$pass.$Expire.$_SERVER['HTTP_USER_AGENT'].'9VKT9Kwh');
    return $key;
}
//获取到期时间戳,默认30天
function GetExpire(){
    return time()+30 * 24 * 60 * 60;
}
//判断用户是否已经登录
function is_login(){
    global $u,$username,$password;
    $key = Getkey($username,$password,$_COOKIE[$u.'_Expire']);
    //获取session
    $session = $_COOKIE[$u.'_key'];
    //如果已经成功登录
    if($session == $key) {
        return true;
    }
    else{
        return false;
    }
}
//返回JSON错误信息
function err_msg($code,$err_msg){
        $data = [
            'code'      =>  $code,
            'err_msg'   =>  $err_msg
        ];
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
}
//返回成功信息
function success_msg($code,$msg,$dbname){
        $data = [
            'code'      =>  $code,
            'msg'   =>  $msg,
            'u'   =>  $dbname
        ];
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($data));
}
//取文本左边 getSubstrRight('请用php取出这段字符串中左边文本','中');
function getSubstrRight($str, $rightStr)
{
    $right = strpos($str, $rightStr);
    return substr($str, 0, $right);
}
//将URL转换为base64编码
function base64($url){
    $urls = parse_url($url);

    //获取请求协议
    $scheme = empty( $urls['scheme'] ) ? 'http://' : $urls['scheme'].'://';
    //获取主机名
    $host = $urls['host'];
    //获取端口
    $port = empty( $urls['port'] ) ? '' : ':'.$urls['port'];

    $new_url = $scheme.$host.$port;
    return base64_encode($new_url);
}
//取文本右边  getSubstrLeft('请用php取出小超越工作室右边文本吧','小超越工作室');
function getSubstrLeft($str, $leftStr)
{
 $left = strpos($str, $leftStr);
 return substr($str, $left + strlen($leftStr));
}

//处理时间 秒转天时分秒
function secondChanage($second = 0)
{
    $newtime = '';
    $d = floor($second / (3600*24));
    $h = floor(($second % (3600*24)) / 3600);
    $m = floor((($second % (3600*24)) % 3600) / 60);
    $s = $second - ($d*24*3600) - ($h*3600) - ($m*60);

    empty($d) ?  
    $newtime = (
            empty($h) ? (
                empty($m) ? $s . '秒' : ( 
                    empty($s) ? $m.'分' :  $m.'分'.$s.'秒'
                    )
                ) : (
                empty($m) && empty($s) ? $h . '时' : (
                    empty($m) ? $h . '时' . $s . '秒' : (
                        empty($s) ? $h . '时' . $m . '分' : $h . '时' . $m . '分' . $s . '秒'
                        )
                )
            )
    ) : $newtime = (
        empty($h) && empty($m) && empty($s) ? $d . '天' : (
            empty($h) && empty($m) ? $d . '天' . $s .'秒' : (
                empty($h) && empty($s) ? $d . '天' . $m .'分' : (
                    empty($m) && empty($s) ? $d . '天' .$h . '时' : (
                        empty($h) ? $d . '天' .$m . '分' . $s .'秒' : (
                            empty($m) ? $d . '天' .$h . '时' . $s .'秒' : (
                                empty($s) ? $d . '天' .$h . '时' . $m .'分' : $d . '天' .$h . '时' . $m .'分' . $s . '秒'
                            )
                        )
                    )
                )
            )
        )
    );
 
    return $newtime;
  
}