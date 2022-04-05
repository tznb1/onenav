<?php 
include_once('header.php'); 
include_once('left.php');
$HOST = getindexurl();
$NewVer = $udb->get("config","Value",["Name"=>'NewVer']); //缓存的版本号
$NewVer = $NewVer =='' ? $version : $NewVer ;  //如果没有记录就使用当前版本!
$NewVerGetTime = $udb->get("config","Value",["Name"=>'NewVerGetTime']); //上次从Git获取版本号的时间

//如果缓存时间大于1800秒(30分钟),则重新从Git获取!
if( time() - intval($NewVerT) >= 1800 ) {
    //设置参数Get请求,3秒超时!
    $opts = array( 'http'=>array( 'method'=>"GET", 'timeout'=>3, )); 
    //获取git上面的版本号
    $NewVer = file_get_contents('https://gitee.com/tznb/OneNav/raw/master/initial/version.txt', false, stream_context_create($opts)); 
    if(preg_match('/^v.+-(\d{8})$/i',$NewVer,$matches)){
        $NewVerGetTime = time();
        Writeconfigd($udb,'config','NewVer',$NewVer);
        Writeconfigd($udb,'config','NewVerGetTime',$NewVerGetTime);
    }else{
        //读取失败
        $NewVer = $version;
    }
}
preg_match('/^v.+-(\d{8})$/i',$NewVer,$matches);
$NewVerTime = $matches[1];
preg_match('/^v.+-(\d{8})$/i',$version,$matches);
$VerTime = $matches[1];
?>

<style type="text/css">
.layui-layout-admin .layui-body {top: 40px;}
</style>
<div class="layui-tab layui-tab-brief layui-body " lay-filter="index">
<ul class="layui-tab-title">
 <li lay-id="1" class="layui-this">相关信息</li>
 <li lay-id="2" >开发文档</li>
 <li lay-id="3" >日志输出</li>
</ul>
<div class="layui-tab-content">
<div class="layui-tab-item layui-show layui-form layui-form-pane"><!--相关信息--> 
<?php 
 if($password === md5(md5('admin').$RegTime)){
     ?>
      <div class="layui-form-item" style="color:#FF0000">
      <label class="layui-form-label">风险提示</label>
      <div class="layui-input-block" >
        <input value='系统检测到您使用的默认密码，请尽快修改！'disabled class="layui-input" style="color:#FF0000">
      </div>
    </div>
 <?php }
?>
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">当前版本</label>
        <div class="layui-input-inline">
        <input value='<?php echo $version; ?>'disabled class="layui-input">
      </div> 
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">最新版本</label>
        <div class="layui-input-inline">
        <input value='<?php echo $NewVer; ?>'disabled class="layui-input" <?php if ($NewVerTime > $VerTime ) {echo 'style="color:#FF0000"';}?>>
      </div> 
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">数据库</label>
        <div class="layui-input-inline">
        <input value='<?php echo $userdb['SQLite3'];?>'disabled class="layui-input">
      </div> 
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">账号</label>
        <div class="layui-input-inline">
        <input value='<?php echo $userdb['User'];?>'disabled class="layui-input">
      </div> 
    </div>    
    <div class="layui-inline">
      <label class="layui-form-label">注册时间</label>
      <div class="layui-input-inline">
        <input value='<?php echo date("Y-m-d H:i:s",$RegTime);;?>'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">注册IP</label>
      <div class="layui-input-inline">
        <input value='<?php echo $userdb['RegIP'];?>'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">当前IP</label>
      <div class="layui-input-inline">
        <input value='<?php echo getIP();?>'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">浏览器UA</label>
      <div class="layui-input-block">
        <input value='<?php echo $_SERVER['HTTP_USER_AGENT'];?>'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">登录入口</label>
      <div class="layui-input-block">
        <input value='<?php 
        echo $HOST.'?c='.$Elogin.'&u='.$u;?>         注:请保存好您的专属入口,避免特定情况造成无法登陆!'disabled class="layui-input">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">我的首页</label>
      <div class="layui-input-block">
        <input value='<?php 
        echo $HOST.'?u='.$u;?>'disabled class="layui-input">
      </div>
    </div>
  </div><!--表单End-->
<ul class="layui-timeline">
    <?php include_once('uplog.php'); ?>
</ul>     
</div><!--相关信息End-->
<div class="layui-tab-item"><!--开发文档-->


<blockquote class="layui-elem-quote layui-text">此版项目地址：<a href="https://gitee.com/tznb/OneNav" target="_blank">Gitee 码云 (唯一地址)  </a>&ensp; QQ: 271152681&ensp; PS:各位大哥,如果可以的话麻烦大家帮忙宣传一下..目前用户数太少了,根本没动力更新!</blockquote>
<blockquote class="layui-elem-quote layui-text">原著项目地址 ：<a href="https://github.com/helloxz/onenav" target="_blank">github</a>&ensp; QQ群1：147687134 &ensp;QQ群2：932795364&ensp;QQ:337003006</blockquote>
<blockquote class="layui-elem-quote layui-text">帮助文档 ：<a href="https://doc.xiaoz.me/books/onenav-extend" target="_blank">藏经阁</a></blockquote>
<blockquote class="layui-elem-quote layui-text">原著博客 ：<a href="https://www.xiaoz.me" target="_blank">www.xiaoz.me</a></blockquote>
<blockquote class="layui-elem-quote layui-text">社区支持 ：<a href="https://dwz.ovh/vd0bw" target="_blank" >dwz.ovh/vd0bw</a></blockquote>
<blockquote class="layui-elem-quote layui-text">分类图标 ：<a href="http://www.fontawesome.com.cn/faicons" target="_blank">fontawesome</a></blockquote>
<blockquote class="layui-elem-quote layui-text">Layui文档：
<a href="https://gitee.com/sentsin/layui"  target="_blank">gitee码云 </a>&ensp;
<a href="https://www.layui.site/doc/index.htm"  target="_blank">Layui文档 </a>&ensp;
<a href="https://www.layui.site/demo/form.html" target="_blank">Layui示例 </a>&ensp;
<a href="https://soultable.yelog.org/#/zh-CN/component/basic/cache" target="_blank">Layui示例2 </a>
<a href="https://gitee.com/saodiyang/layui-soul-table" target="_blank">Layui示例2的插件 </a>
<a href="https://www.layuion.com/demo/table.html" target="_blank">数据表格</a>
<a href="https://layer.itze.cn/" target="_blank">弹出层组件</a>
</blockquote>
<blockquote class="layui-elem-quote layui-text">Medoo文档：<a href="https://medoo.lvtao.net/1.2/doc.php" title="数据库框架" target="_blank">中文帮助手册</a>
<a href="https://medoo.lvtao.net/doc.where.php" title="数据库框架" target="_blank">中文帮助手册</a>
</blockquote>
<blockquote class="layui-elem-quote layui-text">XIcon项目：<a href="https://gitee.com/imlzw/xicon/tree/master" title="分类图标选择器" target="_blank">gitee</a></blockquote>
<blockquote class="layui-elem-quote layui-text">MDUI 文档：<a href="https://www.mdui.org/docs" title="前台用的框架" target="_blank">官方文档</a></blockquote>
<blockquote class="layui-elem-quote layui-text">百素主题：<a href="https://gitee.com/baisucode/onenav-theme/tree/master/templates/baisu" target="_blank">gitee</a></blockquote>
<blockquote class="layui-elem-quote layui-text">Webstack：<a href="https://github.com/WebStackPage/WebStackPage.github.io" target="_blank">github</a>&ensp;
<a href="https://webstack.cc/cn/index.html" target="_blank">官方</a></blockquote>
<blockquote class="layui-elem-quote layui-text">SimpleWeb主题：<a href="https://github.com/KrunkZhou/SimpleWebNavigation" target="_blank">github</a></blockquote>
<blockquote class="layui-elem-quote layui-text">hotkeys快捷键：<a href="https://github.com/jaywcjlove/hotkeys/" target="_blank">github </a>&ensp;
<a href="https://wangchujiang.com/hotkeys/" target="_blank">官方</a></blockquote>
<blockquote class="layui-elem-quote layui-text">getFavicon：<a href="https://github.com/owen0o0/getFavicon" target="_blank">官方文档</a></blockquote>

</div><!--开发文档End-->
<div class="layui-tab-item" ><!--日志输出--> 
    <div class="layui-col-lg12">
      <p><h3 style = "padding-bottom:1em;">日志输出：</h3></p>
       <textarea id = "console_log" name="desc" rows="20" placeholder="日志输出控制台" class="layui-textarea" readonly="readonly"></textarea>
    </div>
</div><!--日志输出-->
</div>
</div>

<?php include_once('footer.php'); ?> 
<script>
layui.use(["element", "layer"], function(){
    var element = layui.element;
    var layer = layui.layer;
    //element.tabChange('index', '3'); 
    <?php if($udb->get("user","Level",["User"=>$u]) === '999'){ 
    ?>check_db_down();
    function check_db_down(){
        $.ajax({
            type:"HEAD",
            url:"./data/<?php echo $u;?>.db3",
            statusCode: {
                200: function() {
                    $("#console_log").append("安全检测:数据库可被下载(非常危险)，请尽快参考弹窗信息加固安全设置！\n\n");
                    var a = '#安全设置<br />location ~* ^/(class|controller|initial|data|templates)/.*.(db3|php|php5|sql)$ {<br />    return 403;<br />}<br />location ~* ^/(data)/(upload)/.*.(html)$ {<br />        deny all;<br />}<br />location /initial {<br />        deny all;<br />}<br /><br />#伪静态<br />rewrite ^/click/(.*) /index.php?c=click&id=$1 break;<br />rewrite ^/api/(.*)?(.*) /index.php?c=api&method=$1&$2 break;<br />rewrite /login /index.php?c=login break;<br />#伪静态-插件支持<br />location ~* ^/(?![favicon]) {<br />rewrite ^/(.*)/index.php /index.php?u=$1 break;<br />}';
                    var html = '<div style="padding: 15px; color:#01AAED;" ><h3 style="color:#DC143C;">检测到您的服务器未做安全配置,数据库可能被下载(非常危险),请尽快配置!</h3><h4>如果您使用得Nginx，请务必将以下规则添加到站点配置中:</h4><pre class="layui-code">' + a + '</pre><h4>如果使用得Apache则无需设置，已内置.htaccess进行屏蔽。</h4></div>';
                    layer.open({type: 1,maxmin: false,shadeClose: false,resize: false,title: '高危风险提示！',area: ['auto', 'auto'],content: html});
                    element.tabChange('index', '3'); 
                },
                403:function() {
                    //$("#console_log").append("安全检测:您的数据库看起来是安全的！\n\n");
                }
            }
        });
    } 
    <?php } ?> 
    
get_sql_update_list();
//获取待更新数据库列表
function get_sql_update_list() {
  $("#console_log").append("更新检测:正在检查数据库更新...\n");
  $.get("./index.php?c=api&method=get_sql_update_list&u=<?php echo $u;?>",function(data,status){
    if ( data.code == 0 ) {
      //如果没有可用更新，直接结束
      if ( data.data.length == 0 ) {
        $("#console_log").append("更新检测:当前无可用更新！\n");
        return false;
      }else{
        $("#console_log").append("更新检测:检查到可更新SQL列表：\n");
        $("#console_log").append("更新检测:正在准备更新...\n");
        for(i in data.data) {
          sqlname = data.data[i];
          //$("#console_log").append(data.data[i] + "\n");
          exe_sql(sqlname);
        }
      }
    }
  });
}
//更新SQL函数
function exe_sql(sqlname) {
    element.tabChange('index', '3'); 
    $.ajax({ url: "./index.php?c=api&method=exe_sql&name=" + sqlname+'&u=<?php echo $u;?>', async:false, success: function(data,status){
        if( data.code == 0 ){
            $("#console_log").append(sqlname + "更新完毕！请刷新页面直到提示:当前无可用更新！\n");
            element.tabChange('index', '3'); 
        }else{
            $("#console_log").append(sqlname + "更新失败！\n");
        }
    }});
}

});
</script>
