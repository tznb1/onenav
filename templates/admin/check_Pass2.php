<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<title>二级密码验证</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.6.8/css/layui.css'>
	<link rel='stylesheet' href='./templates/admin/static/style.css?v=<?php echo $version;?>'>
	<style>
	body{
		/* background:url(templates/admin/static/bg.jpg); */
		background-color:rgba(0, 0, 51, 0.8);
		}
	</style>
</head>
<body>
<div class="layui-container">
<div class="layui-row">
<div class="login-logo">
<h1>二级密码验证</h1>
</div>
<div class="layui-col-lg4 layui-col-md-offset4" style ="margin-top:4em;">
<form class="layui-form layui-form-pane" action="">
  <div class="layui-form-item">
    <label class="layui-form-label"><i class="layui-icon layui-icon-password"></i></label>
    <div class="layui-input-block">
      <input type="password" name="Pass2" required  lay-verify="required" placeholder="请输入二级密码" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <button class="layui-btn" lay-submit lay-filter="login" style = "width:100%;">验证</button>
  </div>
</form>
</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.6.8/layui.js'></script>
<script>
//登陆
layui.use(['form'], function(){
    var form = layui.form;
    form.on('submit(login)', function(data){
        $.post('./index.php?c=admin&page=Pass2&u=<?php echo $u; ?>',data.field,function(data,status){
            if(data.code == 0) {
                window.location.href = './index.php?c=admin&u=<?php echo $u; ?>';
            }else{
                layer.msg(data.msg, {icon: 5});
            }
        });
    return false; 
    });
//layui END
});
</script>
</body>
</html>