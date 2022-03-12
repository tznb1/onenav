<?php
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $libs = './static';
    require('./templates/admin/initial.php');
    exit;
}elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (!is_dir('./data')) mkdir('./data',0777,true) or msg(-1004,'创建数据库目录失败,请检查权限!');
    //设置执行时长,防止数据较多时超时!
    set_time_limit(5*60);//设置执行最长时间，0为无限制。单位秒!
    ignore_user_abort(true);//关闭浏览器，服务器执行不中断。
}else{
    msg(-1004,'不支持的请求方式');
}



require ('./class/Class.php');//载入函数库
if( file_exists('./data/onenav.db3') && file_exists('./data/config.php') && !file_exists('./data/lm.user.db3')){
    require ('./data/config.php');//载入配置
    $USER = $site_setting['user'];
    $SQLite3 = './data/'.$USER.'.db3';
    if($USER == 'onenav'){msg(-1000,'用户名不能是onenav!请到/data/config.php修改后再试!');}
    unlink($SQLite3);
    if(!copy('./initial/onenav.simple.db3',$SQLite3)){msg(-1003,'错误:请检查data目录权限!');}
    //查找是否存在on_db_logs表,如果存在则说明是v0.9.16+,后续在根据里面的记录来确定版本!
    $data = $db->query("SELECT count(*) AS num FROM sqlite_master WHERE type='table' AND name='on_db_logs'")->fetchAll();
    $num = intval($data[0]['num']);
    if ( $num == 1 ){ 
        //有on_db_logs表,取ID最大且标记为成功的SQL文件名!并去除后缀名!
        $Ver = $db->query("SELECT sql_name FROM on_db_logs  WHERE status = 'TRUE'  ORDER BY id DESC LIMIT 1")->fetchAll();
        $Ver = str_replace(".sql","",$Ver[0]['sql_name']);
    }else{
        $Ver ='20220304'; //没有on_db_logs表,姑且认为是20220304之前的版本!
    }
    $Newdb = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$SQLite3]);
    if(!$Newdb-> query("DELETE FROM on_categorys;")){msg(-1003,'清空分类表失败.');}
    if(!$Newdb-> query("DELETE FROM on_links;")){msg(-1003,'清空连接表失败.');}
    //导入分类
    $categorys = $db->query('SELECT * FROM "on_categorys"')->fetchAll();
    foreach ($categorys as $categorys) {
        if ($Ver == '20220304' ){
            if(preg_match('/<i class="fa (.+)"><\/i>/i',htmlspecialchars_decode($categorys['name']),$matches) != 0){
                $ico=$matches[1];
            }else{
                 $ico='';
            }
        }else{
            $ico = str_replace("fa ","",$categorys['font_icon']); //去掉头才符合我的要求
        }
        //name 先解码(因为有的版本是编码过的,不解码无法去除标签),去掉HTML标签,因为没标签了,所以就不在编码了
        $data = [
                'id'            =>  $categorys['id'],
                'name'          =>  strip_tags(htmlspecialchars_decode($categorys['name'])),
                'add_time'      =>  $categorys['add_time'],
                'up_time'       =>  $categorys['up_time'],
                'weight'        =>  $categorys['weight'],
                'property'      =>  $categorys['property'],
                'description'   =>  $categorys['description'],
                'Icon'          =>  $ico,
                'font_icon'     =>  $categorys['font_icon']
                ];
        $Newdb->insert("on_categorys",$data); 
    }
    //导入链接
    $link = $db->query('SELECT * FROM "on_links"')->fetchAll();
    foreach ($link as $link) {
        $data = [
                'id'            =>  $link['id'],
                'fid'           =>  $link['fid'],
                'title'         =>  $link['title'],
                'url'           =>  $link['url'],
                'url_standby'   =>  $link['url_standby'],
                'description'   =>  $link['description'],
                'add_time'      =>  $link['add_time'],
                'up_time'       =>  $link['up_time'],
                'weight'        =>  $link['weight'],
                'property'      =>  $link['property'],
                'click'         =>  $link['click'],
                'topping'       =>  $link['topping']
                ];
        $Newdb->insert("on_links",$data);
    }
    //导入配置
    $USER = $site_setting['user'];
    $PASS = $site_setting['password']; 
    $site_setting['Email']      =   EMAIL;
    $site_setting['TOKEN']      =   TOKEN; //需要处理!
    $Email = $site_setting['Email'];
    $title = $site_setting['title'];
    $logo  = $site_setting['logo'];
    $keys  = $site_setting['keywords'];
    $ms    = $site_setting['description'];
    $Token = md5($USER.$site_setting['TOKEN']);
    $RegTime = time();
    $PassMD5 = md5(md5($PASS).$RegTime);
    $Elogin  = getloginC($USER);
    $RegIP      = getIP();
    Writeconfigd($Newdb,'on_config','User',$USER);
    Writeconfigd($Newdb,'on_config','Pass',$PassMD5);
    Writeconfigd($Newdb,'on_config','title',$title);
    Writeconfigd($Newdb,'on_config','logo',$logo);
    Writeconfigd($Newdb,'on_config','keywords',$keys);
    Writeconfigd($Newdb,'on_config','description',$ms);
    Writeconfigd($Newdb,'on_config','Token',$Token);
    Writeconfigd($Newdb,'on_config','SQLite3',$USER.'.db3');
    Writeconfigd($Newdb,'on_config','RegIP',$RegIP);
    Writeconfigd($Newdb,'on_config','RegTime',$RegTime);
    Writeconfigd($Newdb,'on_config','LoginFailed',0);
    Writeconfigd($Newdb,'on_config','Style','0');
    Writeconfigd($Newdb,'on_config',"urlz", '');
    Writeconfigd($Newdb,'on_config','gotop','on');
    Writeconfigd($Newdb,'on_config','quickAdd','on');
    Writeconfigd($Newdb,'on_config','GoAdmin','on');
    Writeconfigd($Newdb,'on_config','LoadIcon','on');
    Writeconfigd($Newdb,'on_config','Email',$Email);
    Writeconfigd($Newdb,'on_config','Login',$Elogin);
    Writeconfigd($Newdb,'on_config','session','360');
    Writeconfigd($Newdb,'on_config','Skey','1');
    Writeconfigd($Newdb,'on_config','HttpOnly','1');
    unlink('./data/onenav.db3');
    unlink('./data/config.php');
    //原版升级End
}else{
    //全新安装
    $USER = $_POST['user'];
    $PASS = $_POST['pass']; 
    $Email = $_POST['Email'];
    $SQLite3 = './data/'.$USER.'.db3';
    $retain = $_GET['retain'];
    if($USER ==''){
        msg(-1000,'用户名不能为空');
    }elseif($PASS ==''){
         msg(-1000,'密码不能为空');
    }elseif($Email ==''){
         msg(-1000,'邮箱不能为空');
    }
    
    if($retain == 'default'){
        //默认,如果存在文件则提示,不存在就复制
        if( file_exists($SQLite3) ){
            msg(-1002,'警告:存在'.$USER.'.db3,是否保留?');
        }elseif(!copy('./initial/onenav.simple.db3',$SQLite3)){
            msg(-1003,'错误:请检查data目录权限!');
        }
    }elseif($retain == 'no'){
        //不保留数据,如果文件存在就删除!
        if( file_exists($SQLite3) ){
            unlink($SQLite3);
        }
        if(!copy('./initial/onenav.simple.db3',$SQLite3)){
            msg(-1003,'错误:请检查data目录权限!');
        }
    }elseif($retain == 'yes'){
        //保留数据!
    }
    
    require ('./class/Medoo.php');//数据库框架
    $Newdb = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$SQLite3]);
    //检查是否存在on_config字段,如果没有就不支持保留!
    $sql = "SELECT count(*) AS num FROM sqlite_master WHERE type='table' AND name='on_config'";
    $Re = $Newdb->query($sql)->fetchAll();
    $num = intval($Re[0]['num']);
    if($num == 0) {
        msg(-1003,'不支持保留此数据库!');
    }
    $RegTime = time();
    $PassMD5 = md5(md5($PASS).$RegTime);
    $Elogin  = getloginC($USER);
    $RegIP      = getIP();

    Writeconfigd($Newdb,'on_config','User',$USER);
    Writeconfigd($Newdb,'on_config','Pass',$PassMD5);
    Writeconfigd($Newdb,'on_config','SQLite3',$USER.'.db3');
    Writeconfigd($Newdb,'on_config','RegIP',$RegIP);
    Writeconfigd($Newdb,'on_config','RegTime',$RegTime);
    Writeconfigd($Newdb,'on_config','LoginFailed',0);
    Writeconfigd($Newdb,'on_config','Style','0');
    Writeconfigd($Newdb,'on_config',"urlz", '');
    Writeconfigd($Newdb,'on_config','gotop','on');
    Writeconfigd($Newdb,'on_config','quickAdd','on');
    Writeconfigd($Newdb,'on_config','GoAdmin','on');
    Writeconfigd($Newdb,'on_config','LoadIcon','on');
    Writeconfigd($Newdb,'on_config','Email',$Email);
    Writeconfigd($Newdb,'on_config','Login',$Elogin);
    Writeconfigd($Newdb,'on_config','session','360');
    Writeconfigd($Newdb,'on_config','Skey','1');
    Writeconfigd($Newdb,'on_config','HttpOnly','1');
    Writeconfigd($Newdb,'on_config','Pass2','');
    //全新安装End
}



if(!file_exists('./data/lm.user.db3')){
    if (!copy('./initial/User.db3','./data/lm.user.db3')){msg(-1004,'复制主表数据库失败,请检查权限!');}
        $udb = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/lm.user.db3']);
        $Re = $udb->update('user',[
            'RegIP' => $RegIP ,
            'RegTime'=>$RegTime,
            'Pass'=>$PassMD5,
            'User'=>$USER,
            'SQLite3'=>$USER.'.db3',
            'Level'=>999,
            'Email'=>''.$Email,
            'Token'=>''.$Token,
            'Login'=>''.$Elogin
            ],['ID' => '1']);
        if($Re->rowCount() == 1){
            $Re = $udb->update('config',['Value' => $USER ,],['Name' => 'DUser']); //写默认用户!
            $time = time();
            $session = '360';
            $Skey  = '1';
            $HttpOnly = '1';
            $Expire = $session == 0 ? time()+86400 : GetExpire2($session);
            $key = Getkey2($USER,$PassMD5,$Expire,$Skey,$time);
            setcookie($USER.'_key2', $key.'.'.$Expire.'.'.$time, $session == 0 ? 0 : $Expire,"/",'',false,$HttpOnly==1);
            msgA(['code'=>0,'msg'=>'安装成功！','user'=>$USER,'pass'=>$PASS ]);
        }else{
            msg(-1004,'-1234:安装失败!');
        }

}

msg(-1004,'安装失败!');
