<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Writeconfig($config.'Live2D',intval($_POST['Live2D']));
    Writeconfig($config.'hitokoto',intval($_POST['hitokoto']));
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
    <input id="Live2D-input" type="hidden" value="<?php echo getconfig($config.'Live2D','0');?>">
    <label class="layui-form-label">Live2D</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="Live2D" name="Live2D" lay-search>
        <option value="0">不显示</option>
        <option value="1">显示</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">Live2D(看板娘) 在线API</div>
  </div>
  <div class="layui-form-item">
    <input id="hitokoto-input" type="hidden" value="<?php echo getconfig($config.'hitokoto','0');?>">
    <label class="layui-form-label">一言</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="hitokoto" name="hitokoto" lay-search>
        <option value="0">不显示</option>
        <option value="1">显示</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">一言在线API</div>
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
$('#Live2D').val(document.getElementById('Live2D-input').value); 
$('#hitokoto').val(document.getElementById('hitokoto-input').value); 


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