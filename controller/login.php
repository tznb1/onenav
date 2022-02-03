<?php //登录入口
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
    require('templates/admin/login.php');exit;}
elseif($_SERVER['REQUEST_METHOD'] === 'POST'){}
else{msg(-1004,'不支持的请求方式');}
$user = $_POST['user'];//用户名
$pass = $_POST['pass'];//密码
if(!isset($user)){msg(-1131,'账号不能为空!');}
elseif(strlen($pass)!=32){msg(-1131,'密码错误!');}//密码不是32位一定是错的.
$userdb   = $udb->get("user","*",["User"=>$user]);
if(!isset($userdb['ID'])) {msg(-1002,'未找到账号数据.');}
if($Visit==0 && $userdb['Level'] != 999){msg(-1002,'网站正在进行维护,请稍后再试!');}
$SQLite3  = './data/'.$userdb['SQLite3'];//数据库路径
$RegTime  = $userdb['RegTime'];//注册时间
$pass = md5($pass.$RegTime);
$ip = getIP();
$db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$SQLite3]);

Writeconfig('LoginRequestDate',time());
//写请求时间
$LoginFailed = getconfig('LoginFailed');
//读登陆失败次数
$LoginFailedDate = getconfig('LoginFailedDate');
//读登陆失败时间
//根据登陆失败次数计算限制时间
if ($LoginFailed < 3 ) {
} elseif ($LoginFailed == 3  ) {
	$limitDate=$LoginFailedDate+10;
} elseif($LoginFailed > 3 && $LoginFailed <= 10  ) {
	$limitDate=$LoginFailedDate+60;
} elseif($LoginFailed > 10 && $LoginFailed <= 20 ) {
	$limitDate=$LoginFailedDate+120;
} else {
	$limitDate=$LoginFailedDate+86400;
}
//限制24小时
//如果当前时间小于限制到期时间则限制登陆
if (time() < $limitDate ) {
	$msg='您已被限制登陆,剩余限制时间:'.secondChanage(($limitDate-time()));
	WriteloginLog($user,$pass,$ip,time(),$user,$msg);
	msg(-1002,$msg);
}
//判断用户名和账号是否正确
if($pass == $userdb['Pass']){
	Writeconfig('LoginFailed',0);
	Writeconfig('LoginFailedDate',0);
	$Expire=GetExpire();
	$key =Getkey($user,$pass,$Expire);
	setcookie($user.'_key', $key, $Expire,"/");
	setcookie($user.'_Expire', $Expire, $Expire,"/");
	WriteloginLog($user,$pass,$ip,time(),$userdb['SQLite3'],'登陆成功');
	msgA(['code'=>0,'msg'=>'successful','u'=>$user]);
} else {
	Writeconfig('LoginFailed',$LoginFailed+1);
	Writeconfig('LoginFailedDate',time());
	$msg='用户名或密码错误:'.+getconfig('LoginFailed').'次!';
	if ($LoginFailed+1>=3) {
		$msg=$msg.',您将被限制登陆!';
	}
	WriteloginLog($user,$pass,$ip,time(),$userdb['SQLite3'],$msg);
	msg(-1004,$msg);
}

