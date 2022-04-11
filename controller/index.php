<?php //首页模板入口
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
Visit();//访问控制

//如果已经登录，获取所有分类和链接
$is_login=is_login2();

//前台载入主题配置
if($_GET['fn'] == 'config'){
    $Theme='./templates/'.getconfig('Theme','default');
	include_once($Theme.'/config.php');
    exit;
}
    
if($is_login){
    //查询分类目录
    $categorys = $db->select('on_categorys','*',[
        "ORDER" =>  ["weight" => "DESC"]
    ]);
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
// ICP备案号和底部代码
$ICP    = $udb->get("config","Value",["Name"=>'ICP']);
$Ofooter = $udb->get("config","Value",["Name"=>'footer']);
$Ofooter = htmlspecialchars_decode(base64_decode($Ofooter));

//原版主题兼容(注意:原版为单用户设计,使用时用户账号为默认账号,不兼容多用户!仅兼容标题,logo,关键字,描述,其他设置对其无效!)
$compatible = false ; //true or false
if ($compatible) {
    define('TEMPLATE',getconfig('Theme'));
    $site_setting['title']          =   getconfig("title");
    $site_setting['logo']           =   getconfig("logo");
    $site_setting['keywords']       =   getconfig("keywords");
    $site_setting['description']    =   getconfig("description");
}

//允许使用参数载入指定主题,如需禁止屏蔽或删掉此段!
$Style= $_GET['Style']==''? '0':$_GET['Style'] ;
$Theme='./templates/'.$_GET['Theme'];
$templates = $Theme.'/index.php';
//如果主题文件存在则使用载入它!
if(file_exists(dirname(dirname(__FILE__)).'/'.$Theme.'/index.php')){require($templates);exit;}

//匹配移动端浏览器UA关键字来识别终端类型,匹配不到就载入PC主题
if(preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i',$_SERVER['HTTP_USER_AGENT'])){
    // 载入前台首页模板,如果文件不存在则恢复默认主题!
    $Style=getconfig('Style2');
    $Theme='./templates/'.getconfig('Theme2');
    $templates = $Theme.'/index.php';
    if(!file_exists($templates)){
        Writeconfig('Theme2','default');
        Writeconfig('Style2','0');
        $Theme='./templates/default';
        $Style='0';
        $templates = './templates/default/index.php';
    }
}else{
    // 载入前台首页模板,如果文件不存在则恢复默认主题!
    $Style=getconfig('Style');
    $Theme='./templates/'.getconfig('Theme');
    $templates = $Theme.'/index.php';
    if(!file_exists($templates)){
        Writeconfig('Theme','default');
        Writeconfig('Style','0');
        $Theme='./templates/default';
        $Style='0';
        $templates = './templates/default/index.php';
    }
}

require($templates);
$t2 = microtime(true);
echo '<!--Powered by 落幕,QQ:271152681 执行耗时：'.round(($t2-$t1)*1000,3).'ms. -->';
?>