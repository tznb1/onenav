<?php

//获取Base64的文件大小
function GetFileSize($Base64){
    $img_len = strlen($Base64);
    $file_size = $img_len - ($img_len/8)*2;
    $file_size = number_format(($file_size/1024),2);
    return $file_size;
}

//检查目录是否存在,不存在则创建!失败返回假
function Check_Path($Path){
    if(!is_dir($Path)){
        return mkdir($Path,0755,true);
    }else{
        return true;
    }
}


//循环目录下的所有文件
function delFileUnderDir( $Path ){
    if ( $handle = opendir( "$Path" ) ) {
        while ( false !== ( $item = readdir( $handle ) ) ) {
            if ( $item != "." && $item != ".." ) {
                if(is_dir( "$Path/$item")) {
                    delFileUnderDir( "$Path/$item");
                }else{
                    unlink("$Path/$item");
                }
            }
        }
    closedir( $handle );
    }
}

//插入分类(导入db3)
function insert_categorys($name,$data,$all){
    global $db;
    $id = $db->get('on_categorys','id',[ 'name' =>  $name ]);
    if(!empty($id)){ return $id; } //存在则直接返回
    if(!empty($db->get('on_categorys','id',[ 'id' =>  $data['id'] ]))){
        unset($data['id']); //id冲突是导入分类使用新id
    }
    
    //不保留属性
    if(!$all){  
        $data['add_time'] = time();
        $data['up_time'] = null;
        $data['weight'] = 0;
    }
    //图标处理
    if(!empty($data['Icon'])){
        //扩展版
    }elseif(!empty($data['font_icon'])){ //小z新版
        $data['Icon'] = str_replace("fa ","",$data['font_icon']); 
    }elseif(preg_match('/<i class="fa (.+)"><\/i>/i',$data['name'],$matches) != 0){
        $data['Icon'] = $matches[1]; //古老的..
    }
    
    //防XSS处理
    $data['name'] = htmlspecialchars($data['name'],ENT_QUOTES);
    $data['description'] = htmlspecialchars($data['description'],ENT_QUOTES);
    $data['Icon'] = htmlspecialchars($data['Icon'],ENT_QUOTES);
    
    //父分类处理(兼容没有二级分类前的版本)
    $data['fid'] = empty($data['fid']) ? 0 : intval($data['fid']);
    
    //插入数据库
    $db->insert("on_categorys",$data);
    $id = $db->id();
    if(!empty($id)){ return $id; }else{ return 0;}
}

//获取URL状态码
function get_http_code($url) { 
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url); //设置URL 
        curl_setopt($curl, CURLOPT_HEADER, 1); //获取Header 
        curl_setopt($curl, CURLOPT_NOBODY, true); //Body就不要了吧，我们只是需要Head 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //数据存到成字符串吧，别给我直接输出到屏幕了 
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($curl); //开始执行啦～ 
        $return = curl_getinfo($curl, CURLINFO_HTTP_CODE); //我知道HTTPSTAT码哦～ 

        curl_close($curl); //用完记得关掉他 
        return $return; 
    }

//取主表配置
function UGet($Name){ global $udb; return $udb->get("config","Value",["Name"=>$Name]); }
//订阅验证
function is_subscribe($Bool = false){
    global $udb;$msg = '';
    $subscribe = unserialize($udb->get("config","Value",["Name"=>'s_subscribe']));
    $data['host'] = $_SERVER['HTTP_HOST']; //当前域名
    if ( preg_match('/(.+)\.(.+\..+)/i',$_SERVER["HTTP_HOST"],$HOST) ){$data['host'] = $HOST[2];} //取根域名
    if ( empty( $subscribe['order_id']) ){ //单号为空
        $msg ='您未订阅,请先订阅在使用';
    }elseif(!strstr( $subscribe['domain'] , $data['host'] ) && !strstr( $subscribe['host'] , $data['host'] ) ){
        $msg = "您的订阅不支持当前域名 >> ".$_SERVER['HTTP_HOST'];
    }elseif(time() > intval($subscribe['end_time'])){
        $msg ='您的订阅已过期'.$subscribe['end_time'];
    }
    if ($Bool){
        return empty($msg);
    }else{
        if(empty($msg)){
            return true;
        }else{
            msg(-1255,$msg);
        }
    }
}

function ccurl($url,$overtime = 3){
    try {
        $curl  =  curl_init ( $url ) ; //初始化
        curl_setopt($curl, CURLOPT_TIMEOUT, $overtime ); //超时
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $Res["content"] = curl_exec   ( $curl ) ;
        $Res["code"] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close  ( $curl ) ;
        
    } catch (\Throwable $th) {
        return false; 
    }
    return $Res;
}
//删除指定目录下N分钟前的文件!
function delfile($dir,$minute){
    
    //如果目录为空则返回!
    if(!is_dir($dir) || empty($minute) ){return;}
    //查找扩展名为html和db3的文件!
    $files = glob($dir.'/'.'*.{html,db3}',GLOB_BRACE);
    $time  = time();
    //var_dump($files);
    
    foreach ($files as $file){
        if (is_file($file) && basename($file) != 'index.html'){
          if ($time - filemtime($file) >= $minute * 60) {
            unlink($file);
          }
        }
    }
}
//验证邮箱
function checkEmail($email){
    $pregEmail = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
    return preg_match($pregEmail, $email);
}
//写入登陆日志
function WriteloginLog($name,$pass,$ip,$date,$dbname,$value){
$db = new Medoo\Medoo([
    'database_type' => 'sqlite',
    'database_file' => './data/login.log.db3'
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
    'database_file' => './data/login.log.db3'
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
//获取URL
function geturl($link){
    global $u;
if (getconfig('urlz')  == 'on' && empty($link['url_standby'])){
    return $link['url'];
}else{
    return "./index.php?c=click&id={$link['id']}&u={$u}";
}}

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
function geticourl($icon,$link){
    if(!empty( $link['iconurl'])){
        if(substr ($link['iconurl'], 0,4) == '<svg'){
            return('data:image/svg+xml;base64,'.base64_encode($link['iconurl']));
        } 
        return($link['iconurl']);
    }elseif (getconfig('LoadIcon') != 'on'){
        global $libs;
        return($libs.'/Other/default.ico');
    }elseif ($icon ==1){
        return('./favicon/index2.php?url='.$link['url']);
    }elseif($icon ==2){
        return('//favicon.rss.ink/v1/'.base64($link['url']));
    }elseif($icon ==4){
        return('//api.15777.cn/get.php?url='.$link['url']);
    }elseif($icon ==5){
        return('//favicon.cccyun.cc/'.$link['url']);
    }elseif($icon ==6){
        return('//api.iowen.cn/favicon/'.parse_url($link['url'])['host'].'.png');
    }elseif($icon ==0){
        return('./index.php?c=ico&text='.$link['title']);
    }else{
        return('./favicon/index2.php?url='.$link['url']);
    }//如果参数错误则使用本地服务器
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

//为了兼容原版主题..
function is_login(){
    return is_login2();
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
function de($t1,$version){
    $t2 = microtime(true);
    echo '<!--Powered by 落幕,QQ:271152681 执行耗时：'.round(($t2-$t1)*1000,3).'ms  '.$version.'-->';
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
//取文本右边
function getSubstrLeft($str, $leftStr){
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