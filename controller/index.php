<?php //首页模板入口
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
Visit();//访问控制

//如果已经登录，获取所有分类和链接
$is_login=is_login2();

if($is_login){
    //查询所有分类目录
    $categorys = $db->select('on_categorys','*',[
        "ORDER" =>  ["weight" => "DESC"]
    ]);
    //查询一级分类目录，分类fid为0的都是一级分类
    $category_parent = $db->select('on_categorys','*',[
        "fid"   =>  0,
        "ORDER" =>  ["weight" => "DESC"]
    ]);
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
        global $db;
        $fid = intval($fid);
        $links = $db->select('on_links','*',[ 
                'fid'   =>  $fid,
                'ORDER' =>  ["weight" => "DESC"]
            ]);
        return $links;
    }
    //右键菜单标识
    $onenav['right_menu'] = 'admin_menu();';
}
//如果没有登录，只获取公有链接
else{
    //查询分类目录
    $categorys = $db->select('on_categorys','*',[
        "property"  =>  0,
        "ORDER" =>  ["weight" => "DESC"]
    ]);
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
        global $db;
        $fid = intval($fid);
        $links = $db->select('on_links','*',[ 
            'fid' =>  $fid,
            'property'  =>  0,
            'ORDER' =>  ["weight" => "DESC"]
        ]);
        return $links;
    }
    //右键菜单标识
    $onenav['right_menu'] = 'user_menu();';
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
if ($Diy==='1' || $userdb['Level']==='999'){
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