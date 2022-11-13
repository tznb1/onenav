<?php //注册
Visit();//访问控制
if($reg === "0"){     
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found");
    exit; 
}
if($libs == ''){ exit('<h3>非法请求</h3>') ;}//禁止直接访问此接口!
elseif($_SERVER['REQUEST_METHOD'] === 'GET'){require('./templates/admin/Register.php');exit;}
elseif($_SERVER['REQUEST_METHOD'] === 'POST'){}
else{msg(-1004,'不支持的请求方式');}

$user = $_POST['user'];
$pass = $_POST['pass'];
$Email = $_POST['Email'];
$dbPath = './data/'.$user.'.db3';
$IP = getIP();
//检查注册配置
if ($reg === '0'){
    msg(-1131,'管理员已禁止注册,请联系管理员!');
}elseif ($reg === '1'){
    //允许注册
}elseif ($reg === '2'){
    //邀请注册
    
}else{
    msg(-1131,'注册配置错误,请检查配置!');
}
//检查账号和密码是否符合注册要求
if(!preg_match('/^[A-Za-z0-9]{4,13}$/', $user)){
    msg(-1131,'账号只能是4到13位的数字和字母!');
}elseif(strlen($pass)!=32){
    msg(-1131,'POST提交的密码异常≠32!');
}elseif($udb->count("user",["User"=>$user]) != 0 ){
    msg(-1131,'账号已存在!');
}elseif(file_exists($dbPath)){
    msg(-1131,'数据库:'.$user.'已存在,请联系管理员!');
}elseif($udb->count("user",["Email"=>$Email]) != 0 ){
    msg(-1131,'邮箱已存在!');
}elseif(preg_match("/(class|controller|data|favicon|initial|static|templates|index|root|cache|upload)/i",$user) ) {
    msg(-1132,'禁止注册保留用户名!');
}

if ($reg === '2'){
    //邀请注册处理
    YQReg();
    $log[uniqid()]['time'] = time();
    $log[uniqid()]['value'] = '用户:'.$_POST['Inviter'].'邀请注册,注册码:'.$_POST['key'];
    $log = serialize($log);
}

//插入用户表和创建初始数据库
$RegTime = time();
$PassMD5 = md5($pass.$RegTime);
$Elogin = getloginC($user);
$data = $udb-> insert ('user',[
    'RegIP' => getIP() ,
    'RegTime'=>$RegTime,
    'Pass'=>$PassMD5,
    'User'=>$user,
    'SQLite3'=>$user.'.db3',
    'Level'=>0,
    'Email'=>$Email,
    'Token'=>'',
    'Log'=>empty($log)?'':$log,
    'Login'=>$Elogin]);
//检测是否是否插入成功,成功就复制初始数据库
if($data->rowCount() == 0){
    msg(-1131,'注册失败,请联系管理员!');
}elseif(!copy('./initial/onenav.simple.db3',$dbPath)){
    //$udb->delete('user',['User'=>$user]);//复制失败时删除用户..
    msg(-1131,'创建数据库失败,请联系管理员!');
}

//注册成功,初始化数据库并写初始配置!
$db = new Medoo\Medoo(['database_type' => 'sqlite','database_file' => $dbPath]);
initial($user,$PassMD5,$RegTime,$Email,$Elogin,getIP());
//生成Cookie
$time = time();
$session = getconfig('session');
$Skey  = getconfig('Skey');
$HttpOnly = getconfig('HttpOnly');
$Expire = $session == 0 ? time()+86400 : GetExpire2($session);
$key = Getkey2($user,$PassMD5,$Expire,$Skey,$time);
setcookie($user.'_key2', $key.'.'.$Expire.'.'.$time, $session == 0 ? 0 : $Expire,"/",'',false,$HttpOnly==1);
if (getconfig('User') === $user && getconfig('Pass') === $PassMD5 ){
    WriteloginLog($user,$PassMD5,getIP(),$time,$user.'.db3','注册成功');
    msgA(['code'=> 0,'msg' =>'注册成功','user' =>$user]);
}else{
    //unlink($dbPath);//写入失败时删除数据库..
    msg(-1131,'写入数据库失败!请联系管理员!');
}

//邀请注册检测有效性和更新列表
function YQReg() {
    global $udb,$user;
    $Inviter = $_POST['Inviter'];
    $key = $_POST['key'];
    if(empty($Inviter) || empty($key)){
        msgA(['code'=> -1,'msg' =>'管理员已禁止注册']);
    }
    if(empty( $udb->count("user",["User"=>$Inviter]) )  ){
        msgA(['code'=> -1,'msg' =>'注册码无效']);
    }
    
    $Inviter_db = new Medoo\Medoo(['database_type' => 'sqlite','database_file' => './data/'.$Inviter.'.db3']);
    $list = $Inviter_db->get("on_config","value",["name"=>"invitation_list"]);
    $list = unserialize($list);
    if($list[$key]['code'] == '2' ){
        msgA(['code'=> -3,'msg' =>'注册码已被使用']);
    }elseif($list[$key]['code'] === '1' ){
        $list[$key]['use_time'] = time();
        $list[$key]['code'] = '2';
        $list[$key]['desc'] = '已被('.$user.')注册';
        $Inviter_db->update('on_config',['Value' => serialize($list)],['Name' => 'invitation_list']);
    }else{
        msgA(['code'=> -2,'msg' =>'注册码无效']);
    }
}
