<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Writeconfig($config.'link_description',$_POST['link_description']);
    Writeconfig($config.'full_width_mode',$_POST['full_width_mode']);
    
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
    <input id="full_width_mode-input" type="hidden" value="<?php echo getconfig($config.'full_width_mode','off');?>">
    <label class="layui-form-label">全宽模式</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="full_width_mode" name="full_width_mode" lay-search>
        <option value="off">关闭</option>
        <option value="on">开启</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">全宽模式</div>
  </div>
  <div class="layui-form-item">
    <input id="link_description-input" type="hidden" value="<?php echo getconfig($config.'link_description','show');?>">
    <label class="layui-form-label">链接描述</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="link_description" name="link_description" lay-search>
        <option value="hide">隐藏</option>
        <option value="show">显示</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">是否显示链接描述</div>
  </div>
  <label class="layui-form-label">离线图标</label><div class="layui-form-mid layui-word-aux">OneNav Extend版由站长在网站管理中设置,全部主题通用!</div>
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
$('#link_description').val(document.getElementById('link_description-input').value); 
$('#full_width_mode').val(document.getElementById('full_width_mode-input').value); 


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