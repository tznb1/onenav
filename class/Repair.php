<?php 
set_time_limit(5*60);//设置执行最长时间，0为无限制。单位秒!
ignore_user_abort(true);//关闭浏览器，服务器执行不中断。
global $udb;
$msg ='';

$dir = dirname(dirname(__FILE__));//取网站运行目录

// //待更新的数据库文件目录
// $sql_dir = 'initial/sql/';
// //待更新的sql文件列表，默认为空
// $sql_files_all = [];
// //打开一个目录，读取里面的文件列表
// if (is_dir($sql_dir)){
//     if ($dh = opendir($sql_dir)){
//         while (($file = readdir($dh)) !== false){
//             //排除.和..
//             if ( ($file != ".") && ($file != "..") ) {
//                 array_push($sql_files_all,$file);
//                 $msg=$msg.$file.'<br />';
//             }
//         }
//         closedir($dh); //关闭句柄
//     }
// }

// class MyDB extends SQLite3{
//     function __construct(){
//         $this->open('data/lm.user.db3');
//     }
// }
// $db2 = new MyDB();
//   if(!$db2){
//       //打开数据库失败!
//   } else {
//       $msg=$msg.'打开数据库成功<br />';
//   }
// $db2->close();
//扫描文件,用于解决存在用户库,但用户表中没有该用户信息!基本兼容本项目发布的版本!
$dirdata = $dir.'/data';
if(is_dir($dirdata)) { 
    if($dh=opendir($dirdata)) {
        while (false !== ($file = readdir($dh))) {
            if($file!="." && $file!="..") {  
                $fullpath=$dirdata."/".$file;//完整路径
                if(!is_dir($fullpath) && preg_match('/(^[a-zA-Z0-9]+)\.db3$/i',$file,$matches) == 1) {
                    if($file != 'lm.user.db3' && $file !='admin.db3' && $udb->count('user','*',['SQLite3'=>$file]) == 0 ) {
                        $db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$fullpath]);
                        //通过一些特征来识别新老数据库User是新的,user是老的
                        if(getconfigdb($db,"User") !='' && getconfigdb($db,"Pass") ){
                            $User1 = getconfigdb($db,"User");
                            $Pass1 = getconfigdb($db,"Pass");
                            $Token = getconfigdb($db,"Token");
                            $RegTime = getconfigdb($db,"RegTime");
                            $RegIP = getconfigdb($db,"RegIP");
                            if($RegTime =='') {$RegTime = time();}
                            if($RegIP =='') {$RegIP = '0.0.0.0';}
                            $data = $udb-> insert ('user',[
                            'RegIP' => $RegIP ,
                            'RegTime'=>$RegTime,
                            'Pass'=>getconfigdb($db,"Pass"),
                            'User'=>getconfigdb($db,"User"),
                            'SQLite3'=>$file,
                            'Level'=>0,
                            'Email'=>''.getconfigdb($db,"Email"),
                            'Token'=>''.getconfigdb($db,"Token"),
                            'Log'=>time().':管理员修复时系统导入(2)',
                            'Login'=>substr(md5($User1.'6Uc2vFoU'),0, 6).'_login'
                            ]);
                            $msg=$msg.'主表缺失,数据库:'.$file.',User:'.$User1.' PassMD5:'.$Pass1.',导入用户名:'.$matches[1].($data->rowCount() == 0 ? '>>>导入数据失败!!!':'')."<br />";
                            
                            //$msg=$msg. ($udb ->last())."<br />"; //输出SQL语句,分析错误时用!
                        }elseif(getconfigdb($db,"user") !='' && getconfigdb($db,"password") ){
                            //老版本的数据库处理
                            $User1 = getconfigdb($db,"user");
                            $Pass1 = getconfigdb($db,"password");
                            $Token = getconfigdb($db,"Token");
                            $RegDate = getconfigdb($db,"RegDate");
                            $RegIP = getconfigdb($db,"RegIP");
                            if($RegDate =='') {$RegDate = time();}
                            if($RegIP =='') {$RegIP = '0.0.0.0';}
                            $data = $udb-> insert ('user',[
                            'RegIP' => $RegIP ,
                            'RegTime'=>$RegDate,
                            'Pass'=>md5(md5($Pass1).$RegDate),
                            'User'=>$matches[1],
                            'SQLite3'=>$file,
                            'Level'=>0,
                            'Email'=>''.getconfigdb($db,"Email"),
                            'Token'=>''.getconfigdb($db,"Token"),
                            'Log'=>time().':管理员修复时系统导入(1)',
                            'Login'=>substr(md5($User1.'6Uc2vFoU'),0, 6).'_login'
                            ]);
                            
                            $msg=$msg.'主表缺失,数据库:'.$file.',user:'.$User1.' password:'.$Pass1.',导入用户名:'.$matches[1].($data->rowCount() == 0 ? '>>>导入数据失败!!!':'')."<br />";
                        }else{
                            $msg=$msg.'主表缺失,数据库:'.$file.",未读取到账号密码,如果是原版数据请注册账号后登陆后台导入!<br />";
                        }
                        //var_dump( $udb->log() );
                    }
                }
            } 
        }
    }
    closedir($dh);   
}
//扫描文件结束


//检查主表和关联的库
$userl = $udb->select('user','*');//获取用户列表
foreach ($userl as $user) {
    $ID = $user['ID'];
    $name = $user['User'];
    $SQLite3 = $dir.'/data/'.$user['User'].'.db3';
    $initialization = false;
    //print_r($ID.'.'.$name."");
    
    //检测专属登陆入口
    if($user['Login'] ==''){
        $Login = getloginC($name);
        $msg=$msg.'ID:'.$ID.',用户名:'.$name.',入口缺失,设:'.$Login."<br />";
        $udb->update('user',['Login'=>$Login],['ID' => $ID]);
        $user['Login'] = $Login;//写到变量中
    }
    
    //下面检测用户数据库,不存在就复制,失败就跳过
    if(!file_exists($SQLite3)){
        if(copy($dir.'/initial/onenav.simple.db3',$SQLite3)){
            $initialization = true;//标记要写入初始数据!
        }else{
            $msg=$msg.'ID:'.$ID.',用户名:'.$name.',数据库缺失:创建失败'."<br />" ;
            continue;
        }
    }
    //载入数据库
    $db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$SQLite3]);
    
    //是否写初始值
    if($initialization){
        initial($username,$password,$RegTime,$userdb['Email'],$Elogin,$userdb['RegIP']);
        $msg=$msg.'ID:'.$ID.',用户名:'.$name.',数据库缺失:创建成功'."<br />";
    }
    
    //多余数据清理
    // $db->delete('on_config',[ 'name' => 'user']);
    // $db->delete('on_config',[ 'name' => 'password']);
    // $db->delete('on_config',[ 'name' => 'db']);
    // $db->delete('on_config',[ 'name' => 'RegDate']);
    // $db->delete('on_config',[ 'name' => 'ICP']);
    
    //同步检测
    if(
        getconfigdb($db,"User") != $user['User'] || 
        getconfigdb($db,"Pass") != $user['Pass'] || 
        getconfigdb($db,"RegTime") != $user['RegTime'] || 
        getconfigdb($db,"RegIP") != $user['RegIP']|| 
        getconfigdb($db,"SQLite3") != $user['SQLite3']  || 
        getconfigdb($db,"Email") != $user['Email'] || 
        getconfigdb($db,"Token") != $user['Token'] ||
        getconfigdb($db,"Login") != $user['Login']
        ){
        Writeconfigd($db,'on_config',"User",$user['User']);
        Writeconfigd($db,'on_config',"Pass",$user['Pass']);
        Writeconfigd($db,'on_config',"RegTime",$user['RegTime']);
        Writeconfigd($db,'on_config',"RegIP",$user['RegIP']);
        Writeconfigd($db,'on_config',"SQLite3",$user['SQLite3']);
        Writeconfigd($db,'on_config',"Email",$user['Email']);
        Writeconfigd($db,'on_config',"Token",$user['Token']);
        Writeconfigd($db,'on_config',"Login",$user['Login']);
        $msg=$msg.'ID:'.$ID.',用户名:'.$name.',信息不同步:已修复!'."<br />";
    }
    //同步检测END

    //参数缺失检测
    if(getconfigdb($db,"Skey") == '' ){
        Writeconfigd($db,'on_config',"Skey",'1');
        $msg=$msg.'ID:'.$ID.',用户名:'.$name.',Skey为空:设1级!'."<br />";
    }
    if(getconfigdb($db,"session") == '' ){
        Writeconfigd($db,'on_config',"session",'360');
        $msg=$msg.'ID:'.$ID.',用户名:'.$name.',登陆保持为空:设360天!'."<br />";
    }
    if(getconfigdb($db,"HttpOnly") == '' ){
        Writeconfigd($db,'on_config',"HttpOnly",'1');
        $msg=$msg.'ID:'.$ID.',用户名:'.$name.',HttpOnly为空:设开启!'."<br />";
    }   
    //参数缺失检测END
    
    //数据库升级检测
    //判断数据库日志表是否存在
    $sql = "SELECT count(*) AS num FROM sqlite_master WHERE type='table' AND name='on_db_logs'";
    //查询结果
    $q_result = $db->query($sql)->fetchAll();
    //如果数量为0，则说明on_db_logs这个表不存在，需要提前导入
    $num = intval($q_result[0]['num']);
    if ( $num === 0 ) {
        //$db2->open('data/lm.user.db3');

        $msg=$msg.'ID:'.$ID.',用户名:'.$name.',on_db_logs表不存在:请手动进入用户后台触发数据库升级!'."<br />";
    }else{
    
    }
   

}


if($msg ==''){
    msg(0,'暂无可修复项');
}else{
    msg(1,$msg.'注意:您可以在点击一次修复,直至提示暂无可修复项!正常情况不超过3次,如果一直有修复内容且内容相同,则说明存在异常或者需要手动处理!<br />点击搜索或者刷新页面才会刷新数据!');
}
//print_r($userl);

function getconfigdb($db,$name){
return ($db->get("on_config","value",["name"=>$name]));
}
