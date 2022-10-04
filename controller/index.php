<?php //首页模板入口
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
Visit();//访问控制



//如果已经登录，获取所有分类和链接
$is_login=is_login2();

//全局强制私有
$Privacy = UGet('Privacy');
if($Privacy != 0 && !$is_login){ //不为零说明存在限制
    if( ($Privacy == 1) || ($Privacy == 2 && $userdb['Level']!='999') ){
        if($login === 'login'){ //未隐藏登陆入口时直接跳转到登录页面
            header('location:/?c=login&u='.$username);
        }else{ //隐藏入口时仅提示
            $msg = '<p>站长禁止了未登录时访问主页,请使用您的专属登录入口登录后在试!</p>';
            require('./templates/admin/403.php');
        }
        exit();
    }
}


$tag = Get('tag'); //获取标签信息
$taghome = getconfig('taghome');//值为on时已设标签的链接不在主页显示
if(!empty($tag)){
    if ( ! is_subscribe(true) ){
        $msg = '<p>订阅无效,请联系站长检查!</p>';require('./templates/admin/403.php');exit();
    }
    $s = unserialize($udb->get("config","Value",["Name"=>'s_subscribe']));
    $tagin = getconfig('tagin','id/mark');
    if($tagin == 'id'){
        $tag_info = $db->get('lm_tag','*',['id'=>$tag]); 
    }elseif($tagin == 'mark'){
        $tag_info = $db->get('lm_tag','*',['mark'=>$tag]); 
    }elseif($tagin == 'id/mark'){
        $tag_info = $db->get('lm_tag','*',["OR" => ['id'=>$tag , 'mark'=>$tag ] ]); 
    }
    
    if(empty($tag_info)){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            msg(-5555,"未找到标签组");
        }else{
            $msg = '<p>未找到标签组</p>';require('./templates/admin/403.php');exit();
        }
    }
    
    //检查访问密码 S
    $key = md5(getIP().$_SERVER['HTTP_USER_AGENT'].$tag_info['pass'].$tag_info['id']);
    if(!empty($_POST['check'])){ 
        if(empty($tag_info['pass'])){
            msg(-1111,"该标签组未设置访问密码");
        }elseif($_POST['check'] === $tag_info['pass']){
            setcookie('tag_'.$tag_info['id'], $key, 0 ,"/",'',false,true);
            msg(0,"验证通过");
        }else{
            msg(-5555,"验证失败");
        }
    }
    //检查访问密码 E
    
    //存在访问密码,尝试读取Cookie记录 (登录状态忽略访问密码)
    if(!empty($tag_info['pass']) && !$is_login){
        $PassC = $_COOKIE['tag_'.$tag_info['id']];
        if($PassC != $key){
            //载入访问密码验证页面
            $msg = '<p>请验证密码！</p>';require('./templates/admin/check_tag_pass.php');exit();
        }
    }
    
    //过期检测 (登录时忽略)
    if( intval($tag_info['expire']) != 0 && intval($tag_info['expire']) < time() && !$is_login){
        $msg = '<p>标签已过期！</p>';require('./templates/admin/403.php');exit();
    }
    
    //浏览次数+1
    $db->update('lm_tag',['views[+]'=>  1],['id'=>  $tag_info['id']]);
}


if($is_login && empty($tag)){
    //查询一级分类目录，分类fid为0的都是一级分类
    $category_parent = $db->select('on_categorys','*',[
        "fid"   =>  0,
        "ORDER" =>  ["weight" => "DESC"]
    ]);
    //var_dump($category_parent);
    //根据分类ID查询二级分类，分类fid大于0的都是二级分类
    function get_category_sub($id) {
        global $db;
        $id = intval($id);
        $category_sub = $db->select('on_categorys','*',[
            "fid"   =>  $id,
            "ORDER"     =>  ["weight" => "DESC"]
        ]);

        return $category_sub;
    }

    //根据category id查询链接
    function get_links($fid) {
        global $db,$taghome;
        $fid = intval($fid);
        if($taghome == 'on'){
            $links = $db->select('on_links','*',[ 'fid' => $fid,"tagid" => 0 ,'ORDER' => ["weight" => "DESC"]]);
        }else{
            $links = $db->select('on_links','*',[ 'fid' => $fid,'ORDER' => ["weight" => "DESC"]]);
        }
        return $links;
    }
    //右键菜单标识
    $onenav['right_menu'] = 'admin_menu();';
}elseif(!empty($tag)){  //访问标签组
    $category_parent[0] = ['name' => $tag_info['name'] ,"Icon" =>"fa-pencil-square-o" , "id" => $tag_info['id'] ,"description" => "标签组链接"];
    $onenav['right_menu'] = $is_login ? 'admin_menu();':'user_menu();'; 
    function get_category_sub($id) {return;} //空的,为了不改主题的前提下不报错

    function get_links($tag) {
        global $db,$is_login;
        if($is_login || getconfig('tag_private') == 'on'){
            $links = $db->select('on_links','*',[ 'tagid' => $tag,'ORDER' => ["weight" => "DESC"]]);
        }else{
            $links = $db->select('on_links','*',[ 'tagid' => $tag,'property'  =>  0,'ORDER' => ["weight" => "DESC"]]);
        }
        
        return $links;
    }
    
    
}else{ //如果没有登录，只获取公有链接
    //查询一级分类目录，分类fid为0的都是一级分类
    $category_parent = $db->select('on_categorys','*',[
        "fid"   =>  0,
        'property'  =>  0,
        "ORDER" =>  ["weight" => "DESC"]
    ]);
    //根据分类ID查询二级分类，分类fid大于0的都是二级分类
    function get_category_sub($id) {
        global $db;
        $id = intval($id);

        $category_sub = $db->select('on_categorys','*',[
            "fid"   =>  $id,
            'property'  =>  0,
            "ORDER"     =>  ["weight" => "DESC"]
        ]);

        return $category_sub;
    }
    //根据category id查询链接
    function get_links($fid) {
        global $db,$taghome;;
        $fid = intval($fid);
        if($taghome == 'on'){
            $links = $db->select('on_links','*',[ 'fid' => $fid,'property'  =>  0,"tagid" => 0 ,'ORDER' => ["weight" => "DESC"]]);
        }else{
            $links = $db->select('on_links','*',[ 'fid' => $fid,'property'  =>  0,'ORDER' => ["weight" => "DESC"]]);
        }
        return $links;
    }
    //右键菜单标识
    $onenav['right_menu'] = 'user_menu();';
}

// 重新整理分类顺序,让二级跟在父后面
$categorys = []; //清空数组,然后遍历父分类!
$i = 0;
foreach ($category_parent as $category) {
    array_push($categorys,$category);
    //查找父分类下的二级分类
    if($is_login){
        $category_subs = $db->select('on_categorys','*',["fid" => $category['id'],"ORDER" => ["weight" => "DESC"] ]);
    }else{ //未登录只查询公开分类
        $category_subs = $db->select('on_categorys','*', ["fid" => $category['id'],"ORDER" => ["weight" => "DESC"] ,'property' =>  0]);
    }
    
    //写父分类下面有几个子分类,可用于后面判断是否需要折叠分类!
    $category_parent[$i]['count'] = count($category_subs);
    //合并数组
    $categorys = array_merge ($categorys,$category_subs);
    $i++;
}


// ICP备案号和底部代码(全局)
$ICP    = $udb->get("config","Value",["Name"=>'ICP']);
$Ofooter = $udb->get("config","Value",["Name"=>'footer']);
$Ofooter = htmlspecialchars_decode(base64_decode($Ofooter));

$site['title']          =   getconfig("title");
$site['subtitle']       =   getconfig("subtitle");
$site['logo']           =   getconfig("logo");
$site['keywords']       =   getconfig("keywords");
$site['description']    =   getconfig("description");
$site['Title']          =   $site['title'].(empty($site['subtitle'])?'':' - '.$site['subtitle']);
$site['urlz']           =   getconfig('urlz');
$site['GoAdmin']        =   getconfig('GoAdmin')  == 'on'?true:false;
$site['LoadIcon']       =   getconfig('LoadIcon') == 'on'?true:false;
$site['quickAdd']       =   getconfig('quickAdd') == 'on'?true:false;
$site['gotop']          =   getconfig('gotop') == 'on'?true:false;
//$site['URLdesc']        =   '作者很懒，没有填写描述。';

//自定义头部/底部代码,符合条件则读取!(用户)
if ($Diy==='1' || $userdb['Level']=='999'){
    $site['custom_header'] = getconfig("head");
    if ($site['custom_header'] != ''){
        $site['custom_header'] = htmlspecialchars_decode(base64_decode($site['custom_header']));
    }
    $site['custom_footer'] = getconfig("footer");
    if ($site['custom_footer'] != ''){
        $site['custom_footer'] = htmlspecialchars_decode(base64_decode($site['custom_footer']));
    }
}



//使用参数载入指定主题(主题预览)
$Theme = $_GET['Theme'] ;
if ( !empty ($Theme) ){
    $Theme='./templates/'.$_GET['Theme'];
    $templates = $Theme.'/index.php';
    if(file_exists(dirname(dirname(__FILE__)).'/'.$Theme.'/index.php')){ 
        $config = 'Theme-'.$_GET['Theme'].'-'; 
        require($templates);
        de($t1,$version);
        exit;
    }
}

//匹配移动端浏览器UA关键字来识别终端类型,匹配不到就载入PC主题
if(preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i',$_SERVER['HTTP_USER_AGENT'])){
    // 载入前台首页模板,如果文件不存在则恢复默认主题!
    $config = 'Theme-'.getconfig('Theme2').'-'; 
    $Theme='./templates/'.getconfig('Theme2');
    $templates = $Theme.'/index.php';
    if(!file_exists($templates)){
        Writeconfig('Theme2','default');
        $Theme='./templates/default';
        $templates = './templates/default/index.php';
    }
}else{
    // 载入前台首页模板,如果文件不存在则恢复默认主题!
    $config = 'Theme-'.getconfig('Theme').'-'; 
    $Theme='./templates/'.getconfig('Theme');
    $templates = $Theme.'/index.php';
    if(!file_exists($templates)){
        Writeconfig('Theme','default');
        $Theme='./templates/default';
        $templates = './templates/default/index.php';
    }
}
require($templates);
de($t1,$version);
?>