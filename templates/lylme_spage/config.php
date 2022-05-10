<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Writeconfig($config.'backgroundURL',$_POST['backgroundURL']);
    Writeconfig($config.'changewidth', intval($_POST['changewidth']));
    
    msg(0,"修改成功");
}
?>
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>主题DIY</title>
  <link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.6.8/css/layui.css'>
  <style>
    .layui-form-item {
        margin-bottom: 10px;
        height: 38px;
    }
  </style>
</head>
<body>
<div>
<!-- 内容主体区域 -->
<div class="layui-row" style = "margin-top:18px;">
	<div class="layui-container">
    <div class="layui-col-lg6 layui-col-md-offset3">
    <form class="layui-form">

    <div class="layui-form-item">
    <label class="layui-form-label">背景图URL</label>
    <div class="layui-input-inline" style="width: 73%;">
    <input type="url" id = "backgroundURL" name="backgroundURL" value = "<?php echo getconfig($config.'backgroundURL','https://api.isoyu.com/bing_images.php');?>" placeholder="请输入图片URL" autocomplete="off" class="layui-input">
    </div>
    </div>
    
    <div class="layui-form-item">
    <label class="layui-form-label">侧栏宽度</label>
    <div class="layui-input-inline" style="width: 73%;">
    <input type="number" id = "changewidth" name="changewidth" value = "<?php echo getconfig($config.'changewidth','130');?>" placeholder="侧栏宽度,默认130" autocomplete="off" class="layui-input">
    </div>
    </div>
    
  <div class="layui-form-item">
    <div class="layui-input-block">
      <button class="layui-btn" lay-submit lay-filter="edit_homepage">保存</button>
    </div>
  </div>
</form>

    </div>
<!-- 内容主题区域END -->
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>

<script>
var u = '<?php echo $u?>';
var t = '<?php echo $theme;?>';
var s = '<?php echo $_GET['source'];?>';


layui.use(['form','dropdown'], function(){
    var form = layui.form;
    var dropdown = layui.dropdown;
//背景图下拉菜单 
  dropdown.render({
    elem: '#backgroundURL'
    ,data: [{
      title: '博天(自适应/动漫)'
      ,url: 'https://api.btstu.cn/sjbz/api.php?lx=dongman&method=zsy'
      ,author:'https://api.btstu.cn/doc/sjbz.php'
    },{
      title: '博天(自适应/妹子)'
      ,url: 'https://api.btstu.cn/sjbz/api.php?lx=meizi&method=zsy'
      ,author:'https://api.btstu.cn/doc/sjbz.php'
    },{
      title: '博天(自适应/风景)'
      ,url: 'https://api.btstu.cn/sjbz/api.php?lx=fengjing&method=zsy'
      ,author:'https://api.btstu.cn/doc/sjbz.php'
    },{
      title: '博天(自适应/随机)'
      ,url: 'https://api.btstu.cn/sjbz/api.php?lx=suiji&method=zsy'
      ,author:'https://api.btstu.cn/doc/sjbz.php'
    },{ 
      title: '姬长信(PC/每日必应)'
      ,url: 'https://api.isoyu.com/bing_images.php'
      ,author:'https://api.isoyu.com'
      ,n:'姬长信'
    },{
      title: '樱花(PC/动漫)'
      ,url: 'https://www.dmoe.cc/random.php'
      ,author:'https://www.dmoe.cc'
    },{
      title: '梁炯灿(PC/动漫)'
      ,url: 'https://tuapi.eees.cc/api.php?category=dongman&type=302'
      ,author:'https://tuapi.eees.cc'
    },{
      title: '梁炯灿(PC/风景)'
      ,url: 'https://tuapi.eees.cc/api.php?category=fengjing&type=302'
      ,author:'https://tuapi.eees.cc'
    },{
      title: '梁炯灿(PC/必应)'
      ,url: 'https://tuapi.eees.cc/api.php?category=biying&type=302'
      ,author:'https://tuapi.eees.cc'
    },{
      title: '梁炯灿(PC/美女)'
      ,url: 'https://tuapi.eees.cc/api.php?category=meinv&type=302'
      ,author:'https://tuapi.eees.cc'
    },{
      title: '苏晓晴(PC/动漫)'
      ,url: 'https://acg.toubiec.cn/random.php'
      ,author:'https://acg.toubiec.cn'
    },{
      title: '墨天逸(PC/动漫)'
      ,url: 'https://api.mtyqx.cn/api/random.php'
      ,author:'https://api.mtyqx.cn/'
    },{
      title: '小歪(PC/动漫)'
      ,url: 'https://api.ixiaowai.cn/api/api.php'
      ,author:'https://api.ixiaowai.cn'
    },{
      title: '小歪(PC/MC酱)'
      ,url: 'https://api.ixiaowai.cn/mcapi/mcapi.php'
      ,author:'https://api.ixiaowai.cn'
    },{
      title: '小歪(PC/风景)'
      ,url: 'https://api.ixiaowai.cn/gqapi/gqapi.php'
      ,author:'https://api.ixiaowai.cn' 
    },{
      title: '保罗(PC/动漫)'
      ,url: 'https://api.paugram.com/wallpaper/?source=sina'
      ,author:'https://api.paugram.com/help/wallpaper'
      ,n:'保罗'
    },{
      title: '樱道(PC/动漫)'
      ,url: 'https://api.r10086.com/img-api.php?type=动漫综合1'
      ,author:'https://img.r10086.com/'
      ,n:'樱道'
    }]
    ,click: function(obj){
        if (obj.n == '樱道'){
            layeropen('官方还有很多分类哦<br />感兴趣的自己去看<br />访问速度比较慢<br />友链有个镜像接口比较快','https://img.r10086.com/');
        }else if (obj.n == '保罗'){
            layeropen('官方还有其他接口<br />感兴趣的自己去看<br />有缓存','https://api.paugram.com/help/wallpaper');
        }else if (obj.n == '姬长信'){
            layeropen('官方还有其他接口<br />感兴趣的自己去看<br />慢且不稳','https://api.isoyu.com/#/壁纸模块');
        }
      this.elem.val(obj.url);
    }
    ,style: 'width: 235px;'
  });
  

//保存设置
form.on('submit(edit_homepage)', function(data){
    $.post('./index.php?c=admin&page=config&u='+u+'&Theme='+t,data.field,function(data,status){
      //如果添加成功
      if(data.code == 0) {
        if (s == 'admin'){
            layer.msg(data.msg, {icon: 1});
            return false;
        }else{
            parent.location.reload(); //刷新页面
        }
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    console.log(data.field);
    return false; 
});
});
</script>
</body>
</html>