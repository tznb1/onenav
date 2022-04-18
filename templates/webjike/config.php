<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Writeconfig($config.'Notice',intval($_POST['Notice']));
    Writeconfig($config.'NoticeC',$_POST['NoticeC']);
    Writeconfig($config.'location',$_POST['location']);
    
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
    <input id="location-input" type="hidden" value="<?php echo getconfig($config.'location','2');?>">
    <label class="layui-form-label">登录入口</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="location" name="location" lay-search>
        <option value="0">不显示</option>
        <option value="1">搜索下方</option>
        <option value="2">页脚上方</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">手机端入口位置</div>
  </div>
  <div class="layui-form-item">
    <input id="Notice-input" type="hidden" value="<?php echo getconfig($config.'Notice','1');?>">
    <label class="layui-form-label">公告</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="Notice" name="Notice" lay-search>
        <option value="0">不显示</option>
        <option value="1">显示站点描述</option>
        <option value="2">显示公告内容</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">公告</div>
  </div>
  <div class="layui-form-text">
    <label class="layui-form-label">公告内容</label>
    <div class="layui-input-block">
        <textarea id = "NoticeC" name="NoticeC"  rows = "2" class="layui-textarea"><?php echo getconfig($config.'NoticeC','');?></textarea>
    </div>
  </div>
  <div class="layui-form-item" style="padding-top: 10px;">
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
$('#Notice').val(document.getElementById('Notice-input').value); 
$('#location').val(document.getElementById('location-input').value); 


layui.use(['form'], function(){
    var form = layui.form;


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