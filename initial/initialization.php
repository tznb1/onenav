<?php
if(!file_exists('./data/lm.user.db3')){
    if (!copy('./initial/User.db3','./data/lm.user.db3')){
        exit('<h3>创建数据库失败.</h3>');}
    else{
        require ('./class/Medoo.php');//数据库框架
        require ('./class/Class.php');//载入函数库
        $udb = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>'./data/lm.user.db3']);
        $RegTime = time();
        $User = 'admin';//初始管理员账号!不建议修改!除非你知道怎么进入后台!
        $Pass = 'admin';//初始管理员密码!
        $Email = 'admin@qq.com';
        $PassMD5 = md5(md5($Pass).$RegTime);//密码算法:MD5(原密码MD5+注册时间),所以不要修改注册时间,否则会出现密码错误!
        $Re = $udb->update('user',['RegIP' => getIP() ,'RegTime'=>$RegTime,'Pass'=>$PassMD5,'User'=>$User,'SQLite3'=>$User.'.db3','Level'=>999,'Email'=>$Email],['User' => $User]);
        if($Re->rowCount() == 1){
            $SQLite3 = './data/'.$User.'.db3';
            if(!file_exists($SQLite3) && !copy('./initial/onenav.simple.db3',$SQLite3)){msg(-1003,'创建用户数据库失败.');}
            $db = new Medoo\Medoo(['database_type'=>'sqlite','database_file'=>$SQLite3]);
            Writeconfig('user',$User);
            Writeconfig('password',$PassMD5);
            Writeconfig('db',$User.'.db3');
            Writeconfig('RegIP',getIP());
            Writeconfig('RegDate',$RegTime);
            Writeconfig('LoginFailed',0);
            Writeconfig('Style','0');
            Writeconfig("urlz", 'on');
            Writeconfig('gotop','on');
            Writeconfig('quickAdd','on');
            Writeconfig('GoAdmin','on');
            Writeconfig('LoadIcon','on');
            Writeconfig('Email',$Email);
            $Expire=GetExpire();
            $key =Getkey($User,$PassMD5,$Expire);
            setcookie($User.'_key', $key, $Expire,"/");
            setcookie($User.'_Expire', $Expire, $Expire,"/");
            exit('<h3>安装成功:已初始化用户数据<br />管理员账号:'.$User.'<br />管理员密码:'.$Pass.'<br />请及时修改密码!刷新进入主页!</h3>');
        }else{
            exit('<h3>安装失败:数据库已创建成功,但在写入初始信息时失败了.</h3>');
        }
    }
}