<?php //注册
Visit();//访问控制
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
elseif($_SERVER['REQUEST_METHOD'] === 'GET'){require('./templates/admin/Register.php');exit;}
elseif($_SERVER['REQUEST_METHOD'] === 'POST'){}
else{msg(-1004,'不支持的请求方式');}

$user = $_POST['user'];
$pass = $_POST['pass'];
$Email = $_POST['Email'];
$dbPath = './data/'.$user.'.db3';
//检查注册配置
if ($reg == 0){msg(-1131,'管理员已禁止注册,请联系管理员!');}
elseif ($reg == 1){}
else{msg(-1131,'注册配置错误,请检查配置!');}
//检查注册条件
if(!preg_match('/^[A-Za-z0-9]{4,13}$/', $user)){msg(-1131,'账号只能是4到13位的数字和字母!');}
elseif(strlen($pass)!=32){msg(-1131,'POST提交的密异常!');}
elseif($udb->count("user",["User"=>$user]) != 0 ){msg(-1131,'账号已存在!');}
elseif(file_exists($dbPath)){msg(-1131,'数据库:'.$user.'已存在!');}
//插入用户表和创建初始数据库
$RegTime = time();
$PassMD5 = md5($pass.$RegTime);
$data = $udb-> insert ('user',[
    'RegIP' => getIP() ,
    'RegTime'=>$RegTime,
    'Pass'=>$PassMD5,
    'User'=>$user,
    'SQLite3'=>$user.'.db3',
    'Level'=>0,
    'Email'=>$Email,
    'Token'=>'',
    'Log'=>'',
    'Login'=>'']);
if($data->rowCount() == 0){msg(-1131,'注册失败!');}
elseif(!copy('./initial/onenav.simple.db3',$dbPath)){msg(-1131,'创建数据库失败!');}

//通过检查注册成功,初始化数据库并写初始配置!
$db = new Medoo\Medoo(['database_type' => 'sqlite','database_file' => $dbPath]);
Writeconfig('user',$user);
Writeconfig('password',$PassMD5);
Writeconfig('db',$user.'.db3');
Writeconfig('RegIP',getIP());
Writeconfig('RegDate',$RegTime);
Writeconfig('LoginFailed',0);
Writeconfig('Style','0');
Writeconfig("urlz", 'on');
Writeconfig('gotop','on');
Writeconfig('quickAdd','on');
Writeconfig('GoAdmin','on');
Writeconfig('LoadIcon','on');
$Expire=GetExpire();
$key =Getkey($user,$PassMD5,$Expire); 
setcookie($user.'_key', $key, $Expire,"/");
setcookie($user.'_Expire', $Expire, $Expire,"/");
if (getconfig('user') == $user && getconfig('password') == $PassMD5 ){msgA( ['code'=> 0,'msg' =>'注册成功','user' =>$user] );}
else{ unlink($dbPath); msg(-1131,'写入数据库失败!');}