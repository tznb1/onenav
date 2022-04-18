<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Writeconfig($config.'fonts',intval($_POST['fonts']));
    Writeconfig($config.'Descr',intval($_POST['Descr']));
    Writeconfig($config.'LeftColumnWidth',intval($_POST['LeftColumnWidth']));
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
    <input id="fonts-input" type="hidden" value="<?php echo getconfig($config.'fonts','0');?>">
    <label class="layui-form-label">Logo字体</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="fonts" name="fonts" lay-search>
        <option value="0">普通</option>
        <option value="1">个性</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">普通加载快,个性更好看</div>
  </div>
  <div class="layui-form-item">
    <input id="Descr-input" type="hidden" value="<?php echo getconfig($config.'Descr','0');?>">
    <label class="layui-form-label">链接描述</label>
    <div class="layui-input-inline">
      <select lay-verify="required"  id="Descr" name="Descr" lay-search>
        <option value="0">隐藏</option>
        <option value="1">显示</option>
      </select>
    </div>
    <div class="layui-form-mid layui-word-aux">是否显示链接描述内容</div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">导航宽度</label>
    <div class="layui-input-inline">
      <input type="number" id = "LeftColumnWidth" name="LeftColumnWidth" value = "<?php echo getconfig($config.'LeftColumnWidth','260');?>" placeholder="默认260" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">左侧栏的宽度,默认260</div>
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
$('#Descr').val(document.getElementById('Descr-input').value); 
$('#fonts').val(document.getElementById('fonts-input').value); 


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