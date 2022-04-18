<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    Writeconfig($config.'Descr',intval($_POST['Descr']));
    Writeconfig($config.'Logo',$_POST['Logo']);
    Writeconfig($config.'SBimg',$_POST['SBimg']);
    
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
    <label class="layui-form-label">Logo</label>
    <div class="layui-input-inline">
      <input type="txt" id = "Logo" name="Logo" value = "<?php echo getconfig($config.'Logo');?>" placeholder="留空则使用默认" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">LogoURL 965 x 156</div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">搜索框背景</label>
    <div class="layui-input-inline">
      <input type="txt" id = "SBimg" name="SBimg" value = "<?php echo getconfig($config.'SBimg');?>" placeholder="留空则使用默认" autocomplete="off" class="layui-input">
    </div>
    <div class="layui-form-mid layui-word-aux">搜索框背景图URL 1251 × 180</div>
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