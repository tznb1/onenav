<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<title>OneNav登录</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel='stylesheet' href='<?php echo $libs?>/Layui/v2.5.4/css/layui.css'>
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
		<h1>OneNav登录</h1>
		</div>
		<div class="layui-col-lg4 layui-col-md-offset4" style ="margin-top:4em;">
		<form class="layui-form layui-form-pane" action="">
  <div class="layui-form-item">
    <label class="layui-form-label"><i class="layui-icon layui-icon-username"></i></label>
    <div class="layui-input-block">
      <input type="text" name="user" required value="<?php echo $u; ?>" lay-verify="required" placeholder="请输入用户名" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label"><i class="layui-icon layui-icon-password"></i></label>
    <div class="layui-input-block">
      <input type="password" name="pass" required  lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
    </div>
  </div>
  
  <div class="layui-form-item">
    <button class="layui-btn" lay-submit lay-filter="login" style = "width:100%;">登录</button>
  </div>
</form>
<p style="width: 85%"><a href="?c=Register" style="color: #fffbfb;" class="fl">没有账号？立即注册</a></p>
</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.5.4/layui.js'></script>
<script>
layui.use(['form'], function(){
var form = layui.form;
form.on('submit(login)', function(data){
    data.field.pass = $.md5(data.field.pass);
    $.post('./index.php?c='+_GET("c"),data.field,function(data,status){
      if(data.code == 0) {
        window.location.href = './index.php?c=admin&u='+data.u;
      }
      else{
        layer.msg(data.msg, {icon: 5});
      }
    });
    return false; 
});

//取Get参数
function _GET(variable){
   var query = window.location.search.substring(1);
   var vars = query.split("&");
       for (var i=0;i<vars.length;i++) {
               var pair = vars[i].split("=");
               if(pair[0] == variable){return pair[1];}
       }
       return(false);
}
});
</script>
</body>
</html>