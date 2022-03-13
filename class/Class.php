<?php
//验证邮箱
function checkEmail($email){
    $pregEmail = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
    return preg_match($pregEmail, $email);
}
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
//获取首页地址
function getindexurl(){
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' :'http://';
    $HOST = $http_type.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    return($HOST);
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
}}

//写入配置(如果不存在则创建)
function Writeconfigd($db,$table,$name,$value){
$count = $db->count($table,[ 'Name' => $name]);
if ($count){
    $db->update($table,['Value' => $value],['Name' => $name]);
}else{
    $db->insert($table,['Name'=> $name ,'Value'=> $value]);
}}
//获取图标URL
function geticourl($icon,$url){
if ($icon ==1){return('./favicon/?url='.$url);}
elseif($icon ==2){return('//favicon.rss.ink/v1/'.base64($url));}
elseif($icon ==3){return('//ico.hnysnet.com/get.php?url='.$url);}
elseif($icon ==4){return('//api.15777.cn/get.php?url='.$url);}
elseif($icon ==5){return('//favicon.cccyun.cc/'.$url);}
elseif($icon ==6){return('//api.iowen.cn/favicon/'.parse_url($url)['host'].'.png');}
else{return('./favicon/?url='.$url);}//如果参数错误则使用本地服务器
}
//读取配置 (22/3/2新增一个默认值参数,没找到记录时使用默认值写入并返回默认值)
function getconfig($name,$default =''){
global $db;
$value = $db->get("on_config","value",["name"=>$name]);
if($value =='' && $default !=''){
    $count = $db->count("on_config","value",["name"=>$name]);
    if ($count == 0){
        $value =$default;
        $db->insert('on_config',['name'=> $name ,'value'=> $value]);
    }
}
return ($value);
}
//生成用户登陆接口(22/2/27改为随机生成,不可逆)
function getloginC($user){
return (substr(md5($user.'6Uc2vFoU'.time().rand(5, 15)),0, 6).'_login');
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

//生成图标代码3 刘桐序专用
function geticon3($name){
if (substr($name,0, 3) =='lay')
{return('<i class="layui-icon '.$name.' icon-fw icon-lg mr-2"></i>');}
elseif (substr($name,0, 3) =='fa-')
{return('<i class="fa '.$name.' icon-fw icon-lg mr-2"></i>');}
else{return($name);}
}
//访问控制
function Visit(){
    global $Visit,$udb,$u;
    if($Visit==0 ){
        if($udb->get("user","Level",["User"=>$u]) == 999 && is_login2()){
            //允许管理员账号在登录的情况下正常使用!
            return;
        }
        $msg = "<h3>网站正在进行维护,请稍后再试!</h3>";
        require('./templates/admin/403.php' );
        exit;}
}

//用户名限制
function check_user($value){
if(!preg_match("/^[a-zA-Z0-9]+$/",$value)){
    return false;
}
    return true;
}
//取Get参数并过滤
function Get($str){
return inject_check (strip_tags(@$_GET[$str]));;}
//关键字过滤
function inject_check($str){
$tmp=preg_match('/select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|dump/i', $str); // 进行过滤

if($tmp){msg(-1000,'您的请求带有不合法参数，已被拦截！');}else{return $str;}}
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

//计算Key2
function Getkey2($user,$pass,$Expire,$Skey,$time){
    $txt = $user.'-'.$pass.'|'.$Expire;
    if($Skey == 1){
        $txt = $txt.'|'.$_SERVER['HTTP_USER_AGENT'];
    }elseif($Skey == 2){
        $txt = $txt.'|'.$_SERVER['HTTP_USER_AGENT'];
        $txt = $txt.'|'.getIP();
    }
    $txt = $txt.'|'.'9VKT9Kwh'.$time;
    $key = md5($txt);
    return $key;
}


//获取到期时间戳,默认30天
function GetExpire2($day =30){
    return time()+($day * 24 * 60 * 60);
}

//判断用户是否已经登录2
function is_login2(){
    global $username,$password,$Skey;
    $Ckey= $_COOKIE[$username.'_key2'];
    preg_match('/(.{32})\.(\d+)\.(\d+)/i',$Ckey,$matches);
    $Expire = $matches[2];
    $Ckey = $matches[1];
    $time = $matches[3];
    $keyOK = Getkey2($username,$password,$Expire,$Skey,$time);
    //如果已经成功登录
    if($keyOK === $Ckey ) {
        //Key验证成功,验证到期时间,如果为0说明会话级,直接返回真,否则判断是否到期
        if($Expire !='0'){
            if($Expire>time()){
                return true;
            }else{
                return false;
            }
        }
        return true;
    }else{
        return false;
    }
}

//验证二级密码
function check_Pass2(){
    global $username,$password,$Skey;
    $Ckey= $_COOKIE[$username.'_P2'];
    preg_match('/(.{32})\.(\d+)\.(\d+)/i',$Ckey,$matches);
    $Expire = $matches[2];
    $Ckey = $matches[1];
    $time = $matches[3];
    $keyOK = Getkey2($username,getconfig('Pass2'),$Expire,2,$time);

    //如果已经成功登录
    if($keyOK === $Ckey ) {
        //Key验证成功,验证到期时间,如果为0说明会话级,直接返回真,否则判断是否到期
        if($Expire !='0'){
            if($Expire>time()){
                return true;
            }else{
                return false;
            }
        }
        return true;
    }else{
        return false;
    }
}
//返回JSON信息(常规信息统一规范)
function msg($code,$msg){
    $data = ['code'=>$code,'msg'=>$msg];
    header('Content-Type:application/json; charset=utf-8');
    exit(json_encode($data));
}
//返回JSON信息(自定义信息)
function msgA($data){
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

function initial($User,$PassMD5,$RegTime,$Email,$Elogin,$RegIP){
    Writeconfig('User',$User);
    Writeconfig('Pass',$PassMD5);
    Writeconfig('SQLite3',$User.'.db3');
    Writeconfig('RegIP',$RegIP);
    Writeconfig('RegTime',$RegTime);
    Writeconfig('LoginFailed',0);
    Writeconfig('Style','0');
    Writeconfig("urlz", 'on');
    Writeconfig('gotop','on');
    Writeconfig('quickAdd','on');
    Writeconfig('GoAdmin','on');
    Writeconfig('LoadIcon','on');
    Writeconfig('Email',$Email);
    Writeconfig('Login',$Elogin);
    Writeconfig('session','360');
    Writeconfig('Skey','1');
    Writeconfig('HttpOnly','1');
}