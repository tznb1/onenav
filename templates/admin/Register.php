<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="utf-8" />
	<title>OneNav注册账号</title>
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
<div class="login-logo"><h1>OneNav注册账号</h1></div>
<div class="layui-col-lg4 layui-col-md-offset4" style ="margin-top:4em;">
<form class="layui-form layui-form-pane" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">账号</i></label>
    <div class="layui-input-block">
      <input type="text" name="user" required value="<?php echo time();?>" lay-verify="required" placeholder="请输入账号" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">密码</label>
    <div class="layui-input-block">
      <input type="password" name="pass" required  value="adminadmin" lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
    </div>
  </div>
<div class="layui-form-item">
    <label class="layui-form-label">确认密码</label>
    <div class="layui-input-block">
      <input type="password" name="pass2" required value="adminadmin"  lay-verify="required" placeholder="请再次输入密码" autocomplete="off" class="layui-input">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">Email</label>
    <div class="layui-input-block">
      <input type="text" name="Email" required value="<?php echo time();?>@qq.com"  lay-verify="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
    </div>
  </div>
 <button class="layui-btn" lay-submit lay-filter="register" style = "width:100%;">注册</button>
</form>
<p style="width: 85%"><a href="?c=login" style="color: #fffbfb;" class="fl">已有账号？立即登录</a></p>
</div>
</div>
</div>
<script src = '<?php echo $libs?>/jquery/jquery-3.6.0.min.js'></script>
<script src = '<?php echo $libs?>/jquery/jquery.md5.js'></script>
<script src = '<?php echo $libs?>/Layui/v2.5.4/layui.js'></script>
<script>
layui.use(['form'], function(){
var form = layui.form;
form.on('submit(register)', function(data){2
    if(!/^[A-Za-z0-9]{4,13}$/.test(data.field.user)){layer.msg('账号只能是4到13位的数字和字母!', {icon: 5});return false;}
    else if(data.field.pass != data.field.pass2){layer.msg('两次输入的密码不一致', {icon: 5});return false;}
    else if(data.field.pass.length<8){layer.msg('密码长度不能小于8个字符!', {icon: 5});return false;}
    data.field.pass = $.md5(data.field.pass);
    delete  data.field.pass2;
    $.post('./index.php?c='+_GET("c"),data.field,function(data,status){
      if(data.code == 0) {
        window.location.href = './?u='+data.user;
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