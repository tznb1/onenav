<?php
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
/**
 * 登录入口
 */
$ip = getIP();
//如果认证通过，直接跳转到后台管理
$key = Getkey($username,$password,$_COOKIE[$u.'_Expire']); //计算key
$cookie = $_COOKIE[$u.'_key'];//获取cookie中的key
//登录检查
if( $_GET['check'] == 'login' ) {
    header('Content-Type:application/json; charset=utf-8');
    $user = $_POST['user'];
    $pass = $_POST['password'];
    $u = $_POST['u'];
    $dbPath = './data/'.$u.'.db3';
    //用户不存在提示
    if (!file_exists($dbPath)){
        WriteloginLog($user,$pass,$ip,time(),$u,'数据库不存在！');
        exit(json_encode(['code'=>-1012,'err_msg'=>'数据库不存在！']));
    }

    Writeconfig('LoginRequestDate',time());//写请求时间
    $LoginFailed = getconfig('LoginFailed');//读登陆失败次数
    $LoginFailedDate = getconfig('LoginFailedDate');//读登陆失败时间
    //根据登陆失败次数计算限制时间
    if ($LoginFailed < 3 ){}
    elseif ($LoginFailed == 3  ){$limitDate=$LoginFailedDate+10;}
    elseif($LoginFailed > 3 && $LoginFailed <= 10  ){$limitDate=$LoginFailedDate+60;}
    elseif($LoginFailed > 10 && $LoginFailed <= 20 ){$limitDate=$LoginFailedDate+120;}
    else{$limitDate=$LoginFailedDate+86400;}//限制24小时
    //如果当前时间小于限制到期时间则限制登陆
    if (time() < $limitDate ){
        $msg='您已被限制登陆,剩余限制时间:'.secondChanage(($limitDate-time()));
        WriteloginLog($user,$pass,$ip,time(),$u,$msg);
        exit(json_encode(['code'=>-1012,'err_msg'=>$msg]));
    }
    //判断用户名和账号是否正确
    if( ($user == $username) && ($pass == $password) ) {
        Writeconfig('LoginFailed',0);
        Writeconfig('LoginFailedDate',0);
        $Expire=GetExpire();
        $key =Getkey($username,$password,$Expire); 
        setcookie($u.'_key', $key, $Expire,"/");
        setcookie($u.'_Expire', $Expire, $Expire,"/");
        WriteloginLog($user,$pass,$ip,time(),$u,'登陆成功');
        exit(json_encode(['code'=>0,'msg'=>'successful','u'=>$u]));
    }
    else{
        Writeconfig('LoginFailed',$LoginFailed+1);
        Writeconfig('LoginFailedDate',time());
        $msg='用户名或密码错误:'.+getconfig('LoginFailed').'次!';
        if ($LoginFailed+1>=3){$msg=$msg.',您将被限制登陆!';}
        WriteloginLog($user,$pass,$ip,time(),$u,$msg);
        exit(json_encode(['code'=>-1012,'err_msg'=>$msg]));}
}

//注册检查
if( $_GET['check'] == 'register' ) {
$user = $_POST['user'];
$pass = $_POST['password'];
$u = $_POST['u'];
$dbPath = './data/'.$u.'.db3';
//检查注册配置
if ($reg == 0){err_msg(-1131,'管理员已禁止注册,请联系管理员!');}
elseif ($reg == 1){}
elseif ($reg == 2 && $u != 'admin'){err_msg(-1131,'当前配置仅允许注册管理员账户!');}
elseif ($reg == 2 && $u == 'admin'){}
else{err_msg(-1131,'注册配置错误,请检查配置!');}
//检查注册条件
if (!check_user($u) || !check_user($user)){err_msg(-1131,'库名和账号只能使用英文和数字!');}
elseif(strlen($pass)<8){err_msg(-1131,'密码长度不能小于8个字符!');}
elseif (file_exists($dbPath)){err_msg(-1131,'数据库:'.$user.'已存在!');}
elseif (!copy('./initial/onenav.simple.db3',$dbPath)){err_msg(-1131,'创建数据库失败!路径:'.$dbPath);}
//通过检查注册成功,初始化数据库并写初始配置!
$db = new Medoo\Medoo(['database_type' => 'sqlite','database_file' => $dbPath]);
Writeconfig('user',$user);
Writeconfig('password',$pass);
Writeconfig('db',$u.'.db3');
Writeconfig('RegIP',$ip);
Writeconfig('RegDate',time());
Writeconfig('LoginFailed',0);
Writeconfig('Style','0');
Writeconfig("urlz", 'on');
Writeconfig('gotop','on');
Writeconfig('quickAdd','on');
Writeconfig('GoAdmin','on');
Writeconfig('LoadIcon','on');
$Expire=GetExpire();
$key =Getkey($user,$pass,$Expire); 
setcookie($u.'_key', $key, $Expire,"/");
setcookie($u.'_Expire', $Expire, $Expire,"/");
if (getconfig('user') == $user && getconfig('password') == $pass ){success_msg(0,'注册成功',$u);}
else{ unlink($dbPath); err_msg(-1131,'写入数据库失败!');}
//注册End
}

//如果已经登录，直接跳转
if( $cookie == $key ){header('location:index.php?c=admin&u='.$u);exit;}

// 载入后台登录模板
require('templates/admin/login.php');