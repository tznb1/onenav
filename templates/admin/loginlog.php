<?php 
include_once('header.php');
include_once('left.php'); 
if($udb->get("user","Level",["User"=>$u]) != 999){
    include_once('footer.php');
    exit;
}

?>

<style type="text/css">
.layui-layout-admin .layui-body {top: 40px;}
</style>
<div class="layui-tab layui-tab-brief layui-body layui-row content-body" lay-filter="root" style="padding-bottom: 0px;">
<ul class="layui-tab-title">
 <li class="layui-this">登录日志</li>
</ul>
<div class="layui-tab-content" >

<div class="layui-tab-item layui-show layui-form layui-form-pane" ><!--用户管理-->
<div class="layui-row content-body" style="margin-top: 0px;margin-left: 0px;margin-right: 0px;">
    <div class="layui-col-lg12">

        <div class="layui-inline" >
        <input class="layui-input" name="keyword" id="user_keyword" placeholder='请输入用户名,登录IP,值' value=''autocomplete="off" >
        </div>
        <div class="layui-btn-group ">
        <button class="layui-btn layui-btn " data-type="search">搜索</button>
        </div>
        <table id="loginlog_list" lay-filter="loginlog_list"></table>
        <script type="text/html" id="user_tool">
            <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="Del">删除选中</button>
        </div>
        </script>
        <!-- 开启表格头部工具栏END -->
    </div>
</div>

</div><!--用户管理End-->
</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script>
layui.use(['element','table','layer','form','util','dropdown'], function(){
    var element = layui.element;
    var table = layui.table;
    var util = layui.util;
    var form = layui.form;
    var dropdown = layui.dropdown;
    layer = layui.layer;
    
    var limit = String(getCookie('lm_limit'));
    if (limit < 10 || limit > 90){
        limit = 20 ;
    }
    //console.log(limit);

var loginlog_cols=[[ //表头
      {field:'name',title:'用户名',minWidth:120,sort:true}
      ,{field:'ip',title:'登录IP',minWidth:90,sort:true,templet:function(d){
          return '<a style="color:#3c78d8" title="查询归属地" target="_blank" href="//ip.cn/?ip='+d.ip+'">'+d.ip+'</a>'
      }}
      ,{field:'date',title:'登录时间',minWidth:150,sort:true,templet:function(d){
          if(d.date == null){return '';}
          else{return timestampToTime(d.date);}}} 
      ,{field:'value',title:'值',minWidth:170,sort:true}
    ]]
//用户表渲染
table.render({
    elem: '#loginlog_list'
    ,height: 'full-200' //自适应高度
    ,url: './index.php?c=api&method=loginlog_list&u=<?php echo $u;?>' //数据接口
    ,page: true //开启分页
    ,limit:limit  //默认每页显示行数
    ,even:true //隔行背景色
    ,loading:true //加载条
    //,toolbar: '#user_tool'
    ,id:'loginlog_list'
    ,cols: loginlog_cols
});


//表头工具
table.on('toolbar(loginlog_list)', function(obj){
    var checkStatus = table.checkStatus(obj.config.id),id='';
    var data = checkStatus.data;
    switch(obj.event){
      case 'Del':
          layer.msg('暂不支持删除日志！', {icon: 0});
      break;
    }
});
//回车和按钮事件
$('#user_keyword').keydown(function (e){if(e.keyCode === 13){search();}}); 
$('.layui-btn').on('click', function(){
   var type = $(this).data('type');
   active[type] ? active[type].call(this) : '';
});


//用户搜索
function search(){
var keyword = document.getElementById("user_keyword").value;//获取输入内容
table.reload('loginlog_list', {
  url: './index.php?c=api&method=loginlog_list&u=<?php echo $u;?>'
  ,method: 'post'
  ,request: {
   pageName: 'page'
   ,limitName: 'limit'
  }
  ,where: {
   query : keyword
  }
  ,page: {
   curr: 1
  }
});
}
var active = {
search:function(){search();},

};

//时间戳格式化
function  timestampToTime(timestamp) {
    var  date =  new  Date(timestamp * 1000); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
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
//取随机数字
function randomnum(length) {
  var str = '0123456789';
  var result = '';
  for (var i = length; i > 0; --i) 
    result += str[Math.floor(Math.random() * str.length)];
  return result;
}
// 取Cookie
function getCookie(cname){
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = ca[i].trim();
		if (c.indexOf(name)==0) { return c.substring(name.length,c.length); }
	}
	return "";
}
});
</script>
</body>
</html>