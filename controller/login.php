<?php //登录入口
if($libs==''){
    exit('<h3>非法请求</h3>');
}elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
    //Get请求,载入登陆页面!
    $Themeo = getconfig('Themeo','defaulto');//读取模板名
    $dir = "./templates/$Themeo"; //目录路径
    $path = "./templates/{$Themeo}/login.php"; //模板路径
    //如果不存在则使用默认模板
    if(empty($Themeo) || $Themeo == 'defaulto' ||!file_exists($path) ){
        $path = './templates/admin/login.php';
        if($Themeo != 'defaulto') Writeconfig('Themeo','defaulto');//写默认
    }
    
    
    require($path);
    exit;
}elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
    //POST请求
}else{
    //未知的请求方式
    msg(-1004,'不支持的请求方式');
}

$user = $_POST['user'];//用户名
$pass = $_POST['pass'];//密码
if(!isset($user)){
    msg(-1131,'账号不能为空!');
}elseif(strlen($pass)!==32){
    //密码不是32位一定是错的
    msg(-1131,'密码错误!');
}
$userdb = $udb->get("user","*",["User"=>$user]);
if(!isset($userdb['ID'])){
    msg(-1002,'未找到账号数据.');
}elseif($Visit==='0' && $userdb['Level'] != '999'){
    msg(-1002,'网站正在进行维护,请稍后再试!');
}
$SQLite3  = './data/'.$userdb['SQLite3'];//数据库路径
$RegTime  = $userdb['RegTime'];//注册时间
$Elogin   = $userdb['Login'];//专属登陆入口
$pass = md5($pass.$RegTime);//请求密码
$ip = getIP();
if(!file_exists($SQLite3)){
    msg(-1002,'没有找到用户数据库!');
}
// if($c !='login' && $c !=$Elogin && $c != $login) msg(-1002,'登陆入口异常!');

$db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$SQLite3]);
Writeconfig('LoginRequestDate',time());//写请求时间
$LoginFailed = getconfig('LoginFailed');//读登陆失败次数
$LoginFailedDate = getconfig('LoginFailedDate');//读登陆失败时间

//根据登陆失败次数计算限制时间
if ($LoginFailed < 3 ) {
} elseif ($LoginFailed == 3  ) {
	$limitDate=$LoginFailedDate+10;  //限制10秒
} elseif($LoginFailed > 3 && $LoginFailed <= 10  ) {
	$limitDate=$LoginFailedDate+60;  //限制1分钟
} elseif($LoginFailed > 10 && $LoginFailed <= 20 ) {
	$limitDate=$LoginFailedDate+120; //限制2分钟
} else {
	$limitDate=$LoginFailedDate+86400;//限制24小时
}

//如果当前时间小于限制到期时间则限制登陆
if (time() < $limitDate ) {
	$msg='您已被限制登陆,剩余限制时间:'.secondChanage(($limitDate-time()));
	WriteloginLog($user,$pass,$ip,time(),$user,$msg);
	msg(-1002,$msg);
}
//判断用户名和账号是否正确
if($pass === $userdb['Pass']){
	Writeconfig('LoginFailed',0);
	Writeconfig('LoginFailedDate',0);
	$HttpOnly = getconfig('HttpOnly','1'); //第二个参数是数据库没记录是写入的默认值!
	$session = getconfig('session','360');
    $Expire = $session == 0 ? time()+86400 : GetExpire2($session);
    $time = time();
    $key = Getkey2($user,$pass,$Expire,$Skey,$time);
    setcookie($username.'_key2', $key.'.'.$Expire.'.'.$time, $session == 0 ? 0 : $Expire,"/",'',false,$HttpOnly==1);
	WriteloginLog($user,$pass,$ip,time(),$userdb['SQLite3'],'登陆成功');
	msgA(['code'=>0,'msg'=>'successful','u'=>$user]);
} else {
	Writeconfig('LoginFailed',$LoginFailed+1);//写登陆失败次数
	Writeconfig('LoginFailedDate',time()); //写登陆失败时间
	$msg='用户名或密码错误:'.+getconfig('LoginFailed').'次!';
	if ($LoginFailed+1>=3) {
		$msg=$msg.',您将被限制登陆!';
	}
	WriteloginLog($user,$pass,$ip,time(),$userdb['SQLite3'],$msg);
	msg(-1004,$msg);
}

