<?php 
include_once('header.php'); 
include_once('left.php');
$HOST = getindexurl();
$NewVer = $udb->get("config","Value",["Name"=>'NewVer']); //缓存的版本号
$NewVer = $NewVer =='' ? $version : $NewVer ;  //如果没有记录就使用当前版本!

//公告
function Notice(){
    global $udb;
    //数据库读取缓存数据
    $Notice = $udb->get("config","Value",["Name"=>'Notice']);
    if(!empty($Notice)){
        $data = json_decode($Notice, true);
        if( time() - intval( $data["time"] ) < 60 * 0 ){ //缓存时间
            if(time() < $data["et"] ){
            foreach($data["data"] as $key){
                echo "<blockquote class=\"layui-elem-quote layui-text\" style=\"border-left: 5px solid #1e9fff;\"><a href=\"{$key['url']}\" target=\"_blank\" >{$key['title']}</a></blockquote>\n";
            }}
            return;
        }
    }
    if ( $offline ){ return; } //离线模式
    $urls = [ //2个公告获取的地址
         "https://update.lm21.top/OneNav/Notice.json",
         "https://gitee.com/tznb/OneNav/raw/data/Notice.json"
        ];
    foreach($urls as $url){
        $curl  =  curl_init ( $url ) ; //初始化
        curl_setopt($curl, CURLOPT_TIMEOUT, 3 ); //超时
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $Res = curl_exec   ( $curl ) ;
        curl_close  ( $curl ) ;
        $data = json_decode($Res, true);
        if(time() < $data["et"]){
        foreach($data["data"] as $key){
            echo "<blockquote class=\"layui-elem-quote layui-text\"><a href=\"{$key['url']}\" target=\"_blank\" >{$key['title']}</a></blockquote>\n";
        }}
        if($data["code"] == 200 ){ //如果获取成功
            $data["time"] = time(); //记录当前时间
            Writeconfigd($udb,'config','Notice',json_encode($data)); //写入数据库缓存!
            break; //跳出循环.
        } 
    }
}

?>

<style type="text/css">
.layui-layout-admin .layui-body {top: 40px;}
</style>
<div class="layui-tab layui-tab-brief layui-body " lay-filter="index">
<ul class="layui-tab-title">
 <li lay-id="1" >相关信息</li>
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
        <input value='<?php echo $version; ?>'disabled class="layui-input" id="version">
      </div> 
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">最新版本</label>
        <div class="layui-input-inline">
        <input value='<?php if($udb->get("user","Level",["User"=>$u]) == '999'){ echo "获取中..."; } else{ echo $NewVer; } ?>'disabled class="layui-input" id="NewVer">
      </div> 
      <button class="layui-btn layui-btn-sm layui-btn-danger" onclick="System_Upgrade()" style = "display:none;" id='sysup'>一键更新</button>
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
    <?php Notice();?>
  </div><!--表单End-->
<ul class="layui-timeline">
    <?php include_once('uplog.php'); ?>
</ul>     
</div><!--相关信息End-->
<div class="layui-tab-item"><!--开发文档-->


<blockquote class="layui-elem-quote layui-text">项目地址：<a href="https://gitee.com/tznb/OneNav" target="_blank">Gitee    </a>&ensp;<a href="https://github.com/tznb1/onenav" target="_blank">Github   </a> </blockquote>
<blockquote class="layui-elem-quote layui-text">技术支持 ：QQ 271152681&ensp; PS:各位大哥,如果觉得这个程序还不错到话帮忙推荐给朋友或者发到论坛博客哈!项目的更新离不开大家的支持!</blockquote>
<blockquote class="layui-elem-quote layui-text">帮助文档 ：<a href="https://gitee.com/tznb/OneNav/wikis/" target="_blank">Gitee</a></blockquote>
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
<blockquote class="layui-elem-quote layui-text">getFavicon：<a href="https://github.com/owen0o0/getFavicon" target="_blank">官方文档</a></blockquote>
<blockquote class="layui-elem-quote layui-text"> 
关于: OneNav Extend (扩展版) 是基于<a href="https://github.com/helloxz/onenav" target="_blank">小z</a>开发OneNav(原版)的基础上升级而来!各资源和原版不通用!扩展版功能更多更全,支持多用户使用等等,具体可自行探索!
</blockquote>
</div><!--开发文档End-->
<div class="layui-tab-item" ><!--日志输出--> 
    <div class="layui-col-lg12">
      <p><h3 style = "padding-bottom:1em;">日志输出：&ensp;&ensp;<a href="javascript:;" class="layui-btn layui-btn-sm" onclick="Onecheck()">一键诊断</a></h3></p>
      
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
    <?php if($udb->get("user","Level",["User"=>$u]) == '999'){ 
    ?>check_db_down();
    function check_db_down(){
        $.ajax({
            type:"HEAD",
            url:"./data/<?php echo $u;?>.db3",
            statusCode: {
                200: function() {
                    $("#console_log").append("安全检测:数据库可被下载(非常危险)，请尽快参考弹窗信息加固安全设置！\n\n");
                    var a = '#安全设置<br />location ~* ^/(class|controller|initial|data|templates)/.*.(db3|php|php5|sql)$ {<br />    return 403;<br />}<br />location ~* ^/data/upload/.*.html$ {<br />        deny all;<br />}<br /><br />#伪静态<br />rewrite ^/click/(.*) /index.php?c=click&id=$1 break;<br />rewrite ^/api/(.*)?(.*) /index.php?c=api&method=$1&$2 break;<br />rewrite /login /index.php?c=login break;<br />rewrite ^/(.*)/index.php /index.php?u=$1 break;';
                    var html = '<div style="padding: 15px; color:#01AAED;" ><h3 style="color:#DC143C;">检测到您的服务器未做安全配置,数据库可能被下载(非常危险),请尽快配置!</h3><h4>如果您使用得Nginx，请务必将以下规则添加到站点配置中(伪静态):</h4><pre class="layui-code">' + a + '</pre><h4>Apache已内置.htaccess进行屏蔽。但如果您看到此提示说明.htaccess未生效!请自行检查!</h4></div>';
                    layer.open({type: 1,maxmin: false,shadeClose: false,resize: false,title: '高危风险提示！',area: ['auto', 'auto'],content: html});
                    element.tabChange('index', '3'); 
                },
                403:function() {
                    //$("#console_log").append("安全检测:您的数据库看起来是安全的！\n\n");
                }
            }
        });
    }
    get_latest_version();
    
    //获取最新版本
function get_latest_version(){
    $.post("./index.php?c=api&method=get_latest_version<?php echo $_GET['cache'] === 'no'? "&cache=no":"" ?>&u=<?php echo $u;?>",function(data,status){
        //console.log(data.data);
        $("#getting").hide();
        
        //获取最新版本
        let latest_version = data.data;
        $("#NewVer").val(latest_version);

        //获取当前版本
        let current_version = document.getElementById("version").value;
        //console.log(current_version);
        
        let pattern = /\d{8}/;
        current_version = pattern.exec(current_version)[0];
        latest_version = pattern.exec(latest_version)[0];

        //如果当前版本小于最新版本，则提示更新
        if( current_version < latest_version ) {
            $("#NewVer").attr("style","color:#FF0000");//字体红色
            $("#sysup").show();//显示一键更新按钮
            layer.msg(' 检测到新版本,请尽快更新 ', {offset: 'b',anim: 6,time: 60*1000});
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

// 一键检测
function Onecheck(){
    $.post("./index.php?c=api&method=Onecheck&u=<?php echo $u;?>",function(data,status){
        $("#console_log").append("浏览器UA：" + navigator.userAgent +"\n");
        $("#console_log").append("客户端时间：" +  timestampToTime(Math.round(new Date() / 1000) ) +"\n");
        $("#console_log").append(data.msg +"\n");
    });
}

<?php if($udb->get("user","Level",["User"=>$u]) == '999'){ ?>
function System_Upgrade(){
    layer.open({
        title:"温馨提示"
        ,content: "1.更新有风险请备份后再更新<br />2.更新后检查主题是否可更新<br />3.更新时请勿有其他操作<br />4.更新时请勿刷新或关闭页面<br />5.建议更新前访问控制设为禁止<br />6.确保所有文件(夹)是可写权限"
        ,btn: ['确定更新', '更新内容', '取消']
        ,yes: function(index, layero){
            layer.msg('系统更新中,请勿操作.', {offset: 'b',anim: 0,time: 600*1000});
            layer.load(1, {shade:[0.1,'#fff']});//加载层
            $.post("./index.php?c=api&method=System_Upgrade&u=<?php echo $u;?>&cache=no",function(data,status){
                layer.closeAll();//关闭所有层
                if(data.code == 0) {
                    layer.msg(data.msg, {icon: 1});
                    setTimeout(() => {
                        location.reload();
                    }, 700);
                }else{
                    layer.msg(data.msg, {icon: 5});
                }
            });
        },btn2: function(index, layero){
            window.open("https://gitee.com/tznb/OneNav/releases");
        },btn3: function(index, layero){
            return true;
        },cancel: function(){ 
            return true;
        }
    });
}
<?php } ?> 

//时间戳格式化
function  timestampToTime(timestamp) {
    var  date =  new  Date(timestamp * 1000);
    var y = date.getFullYear();
    var m = date.getMonth() + 1;
    m = m < 10 ? ('0' + m) : m;
    var d = date.getDate();
    d = d < 10 ? ('0' + d) : d;
    var h = date.getHours();
    h = h < 10 ? ('0' + h) : h;
    var minute = date.getMinutes();
    var second = date.getSeconds();
    minute = minute < 10 ? ('0' + minute) : minute;
    second = second < 10 ? ('0' + second) : second;
    return y + '-' + m + '-' + d+' '+h+':'+minute+':'+second;
}
</script>
