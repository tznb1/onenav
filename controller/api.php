<?php
if($libs==''){exit('<h3>非法请求</h3>');}//禁止直接访问此接口!
/**
 * name:API入口文件
 * update:2020/12
 * author:xiaoz<xiaoz93@outlook.com>
 * blog:xiaoz.me
 */

//允许跨域访问
header("Access-Control-Allow-Origin: *");
require('./class/Api.php');

$api = new Api($db);

//获取请求方法
$method = $_GET['method'];
//对方法进行判断
switch ($method) {
    case 'add_category':
        add_category($api);
        break;
    case 'edit_category':
        edit_category($api);
        break;
    case 'del_category':
        del_category($api);
        break;
    case 'add_link':
        add_link($api);
        break;
    case 'edit_link':
        edit_link($api);
        break;
    case 'del_link':
        del_link($api);
        break;
    case 'edit_property':
        edit_property($api);
        break;    
    case 'category_list':
        category_list($api);
        break;
    case 'link_list':
        link_list($api);
        break;
    case 'get_link_info':
        get_link_info($api);
        break;
    case 'add_js':
        add_js($api);
        break;
    case 'upload':
        upload($api);
        break;
    case 'imp_link':
        imp_link($api);
        break;
    case 'edit_homepage':
        edit_homepage($api);
        break;
    case 'edit_user':
        edit_user($api);
        break;
    case 'edit_danyuan':
        edit_danyuan($api);
        break;
    case 'Mobile_class':
        Mobile_class($api);
        break;
    case 'edit_tiquan':
        edit_tiquan($api);
        break;        
    default:
        # code...
        break;
}

/**
 * 主页设置
 */
function edit_homepage($api){
    global $u;
    $token = $_POST['token'];//获取token
    $title =htmlspecialchars($_POST['title']);//站点标题
    $description = htmlspecialchars($_POST['description']);//站点描述
    $logo = htmlspecialchars($_POST['logo']);//站点logo
    $keywords = htmlspecialchars($_POST['keywords']);//keywords
    $urlz = $_POST['urlz'];
    $db = htmlspecialchars($_POST['db']);//db
    $TEMPLATE = htmlspecialchars($_POST['TEMPLATE']);//主题风格
    $ICP = htmlspecialchars($_POST['ICP']);//备案号,留空不显示
    $footer = htmlspecialchars($_POST['footer']);//底部代码
    $gotop = $_POST['gotop'];
    $quickAdd = $_POST['quickAdd'];
    $GoAdmin = $_POST['GoAdmin'];
    $DefaultDB = $_POST['DefaultDB'];
    $navwidth = $_POST['navwidth'];
    $head = $_POST['head'];
    $LoadIcon = $_POST['LoadIcon'];
    if ($DefaultDB =='on' &&  $_COOKIE['DefaultDB'] != $u){setcookie('DefaultDB',$u, 32472115200,"/");}
    $api->edit_homepage($token,$title,$logo,$keywords,$urlz,$TEMPLATE,$description,$ICP,$footer,$db,$gotop,$quickAdd,$GoAdmin,$navwidth,$head,$LoadIcon);
}
/**
 * 账号设置
 */
function edit_user($api){
    $token = $_POST['token'];//获取token
    $user = $_POST['user'];
    $pass = $_POST['password'];
    $newpassword = $_POST['newpassword'];
    $NewToken = $_POST['NewToken'];
    $Email = $_POST['Email'];
    $api->edit_user($token,$Email,$NewToken,$user,$pass,$newpassword);
}
/**
 * 编辑单元格
 */
function edit_danyuan($api){
    $token = $_POST['token'];//获取token
    $id = intval($_POST['id']);
    $field = $_POST['field'];
    $value = $_POST['value'];
    $form = $_POST['form'];
    $api->edit_danyuan($token,$id,$field,$value,$form);
}
/**
 * 修改连接分类
 */
function Mobile_class($api){
    $token = $_POST['token'];//获取token
    $lid = $_POST['lid'];
    $cid = $_POST['cid'];
    $api->Mobile_class($token,$lid,$cid);
}

/**
 * 提权和置顶
 */
function edit_tiquan($api){
    $token = $_POST['token'];//获取token
    $id = $_POST['id'];
    $value = $_POST['value'];
    $form = $_POST['form'];
    $api->edit_tiquan($token,$id,$value,$form);
}
/**
 * 添加分类目录入口
 */
function add_category($api){
    //获取token
    $token = $_POST['token'];
    //获取分类名称
    $name = $_POST['name'];
    //获取分类图标
    $Icon = $_POST['Icon'];
    //获取私有属性
    $property = empty($_POST['property']) ? 0 : 1;
    //获取权重
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);
    //获取描述
    $description = empty($_POST['description']) ? '' : $_POST['description'];
    //描述过滤
    $description = htmlspecialchars($description);
    $api->add_category($token,$name,$property,$weight,$description,$Icon);
}
/**
 * 修改分类目录入口
 */
function edit_category($api){
    //获取ID
    $id = intval($_POST['id']);
    
    //获取token
    $token = $_POST['token'];
    //获取分类名称
    $name = $_POST['name'];
    //获取分类图标
    $Icon = $_POST['Icon'];    
    //获取私有属性
    $property = empty($_POST['property']) ? 0 : 1;
    //获取权重
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);
    //获取描述
    $description = empty($_POST['description']) ? '' : $_POST['description'];
    //描述过滤
    $description = htmlspecialchars($description);
    $api->edit_category($token,$id,$name,$property,$weight,$description,$Icon);
}
/**
 * 删除分类目录
 */
function del_category($api){
    //获取ID
    $id = $_POST['id'];
    $batch = intval($_POST['batch']);
    $force = intval($_POST['force']);
    //获取token
    $token = $_POST['token'];
    $api->del_category($token,$id,$batch,$force);
}
/**
 * 插入链接
 */
function add_link($api){
    //获取token
    $token = $_POST['token'];
    //获取fid
    $fid = intval(@$_POST['fid']);
    $title = $_POST['title'];
    $url = $_POST['url'];
    $description = empty($_POST['description']) ? '' : $_POST['description'];
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);
    $property = empty($_POST['property']) ? 0 : 1;
    
    $api->add_link($token,$fid,$title,$url,$description,$weight,$property);
    
}
/**
 * 修改链接
 */
function edit_link($api){
    //获取token
    $token = $_POST['token'];
    $id = intval(@$_POST['id']);
    
    //获取fid
    $fid = intval(@$_POST['fid']);
    $title = $_POST['title'];
    $url = $_POST['url'];
    $description = empty($_POST['description']) ? '' : $_POST['description'];
    $weight = empty($_POST['weight']) ? 0 : intval($_POST['weight']);
    $property = empty($_POST['property']) ? 0 : 1;
    
    $api->edit_link($token,$id,$fid,$title,$url,$description,$weight,$property);
    
}

/**
 * 删除链接
 */
function del_link($api){
    $token = $_POST['token'];
    $id = $_POST['id'];
    $batch = intval($_POST['batch']);
    $api->del_link($token,$id,$batch);
}
/**
 * 修改连接私有属性
 */
function edit_property($api){
    $token = $_POST['token'];
    $id = intval(@$_POST['id']);
    $property = intval(@$_POST['property']);
    $source = @$_POST['source'];
    $api->edit_property($token,$id,$property,$source);
}
/**
 * 查询分类目录列表
 */
function category_list($api){
    $token = $_POST['token'];
    $_page = $_POST['page'];
    if ($_page !=''){
        $page = empty(intval($_page)) ? 1 : intval($_page);
    }else{
        $page = empty(intval($_GET['page'])) ? 1 : intval($_GET['page']);
    }
    $_limit = $_POST['limit'];
    if ($_limit !=''){
        $limit = empty(intval($_limit)) ? 10 : intval($_limit);
    }else{
        $limit = empty(intval($_GET['limit'])) ? 10: intval($_GET['limit']);
    }
    
    $query = $_POST['query'];
    $api->category_list($page,$limit,$token,$query);
}

/**
 * 查询链接列表
 */
function link_list($api){
    $token = $_POST['token'];
    $_page = $_POST['page'];
    if ($_page !=''){
        $page = empty(intval($_page)) ? 1 : intval($_page);
    }else{
        $page = empty(intval($_GET['page'])) ? 1 : intval($_GET['page']);
    }
    $_limit = $_POST['limit'];
    if ($_limit !=''){
        $limit = empty(intval($_limit)) ? 10 : intval($_limit);
    }else{
        $limit = empty(intval($_GET['limit'])) ? 10: intval($_GET['limit']);
    }
    $query = $_POST['query'];
    $fid  = intval($_POST['fid']);
    $api->link_list($page,$limit,$token,$query,$fid);
}

/**
 * 获取链接信息
 */
function get_link_info($api) {
    //获取token
    $token = $_POST['token'];
    //获取URL
    $url = @$_POST['url'];
    $api->get_link_info($token,$url);
}

/**
 * 添加自定义js
 */
function add_js($api) {
    //获取token
    $token = $_POST['token'];
    $content = @$_POST['content'];
    $u = $_GET['u'];
    $api->add_js($token,$content,$u);
}
// 上传书签
function upload($api){
    //获取token
    $token = $_POST['token'];
    //获取上传类型
    $type = $_GET['type'];
    $api->upload($token,$type);
}
//书签导入
function imp_link($api) {
    //获取token
    $token = $_POST['token'];
    //获取书签路径
    $filename = trim($_POST['filename']);
    $fid = intval($_POST['fid']);
    $property = intval(@$_POST['property']);
    $all =intval(@$_POST['all']);
    $api->imp_link($token,$filename,$fid,$property,$all);
}